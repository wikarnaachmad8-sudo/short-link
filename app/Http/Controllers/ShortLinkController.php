<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortLinkRequest;
use App\Models\LinkClick;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class ShortLinkController extends Controller
{
    /**
     * Display list of user's short links.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $categoryId = $request->input('category_id');

        $totalLinks = auth()->user()->shortLinks()->count();
        $categories = auth()->user()->categories()->latest()->get();

        $shortLinks = auth()->user()->shortLinks()
            ->with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('short_code', 'like', "%{$search}%")
                      ->orWhere('original_url', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('short-links.index', compact('shortLinks', 'search', 'totalLinks', 'categories', 'categoryId'));
    }

    /**
     * Show form to create new short link.
     */
    public function create()
    {
        $categories = auth()->user()->categories()->latest()->get();
        return view('short-links.create', compact('categories'));
    }

    /**
     * Store a new short link.
     */
    public function store(StoreShortLinkRequest $request)
    {
        $shortCode = $request->custom_alias ?: $this->generateShortCode();
        $generateQr = $request->boolean('generate_qr');

        $shortLink = auth()->user()->shortLinks()->create([
            'category_id' => $request->category_id,
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
            'expires_at' => $request->expires_at,
            'qr_generated' => $generateQr,
        ]);

        // Buat dan simpan QR Code jika user memilih fitur Generate QR Code
        if ($generateQr) {
            $this->generateAndSaveQrCode($shortLink);
        }

        return redirect()->route('short-links.show', $shortLink)
            ->with('success', 'Short link berhasil dibuat!');
    }

    /**
     * Display detail of a short link.
     */
    public function show(ShortLink $shortLink)
    {
        // Authorization: user can only view their own links
        if ($shortLink->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke short link ini.');
        }

        $shortLink->load(['category', 'clicks' => function ($query) {
            $query->latest('clicked_at')->take(10);
        }]);

        $lastClick = $shortLink->clicks()->latest('clicked_at')->first();

        return view('short-links.show', compact('shortLink', 'lastClick'));
    }

    /**
     * Delete a short link and its stored QR code file.
     */
    public function destroy(ShortLink $shortLink)
    {
        // Authorization: user can only delete their own links
        if ($shortLink->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke short link ini.');
        }

        // Hapus file QR Code dari storage jika ada
        if ($shortLink->qr_code_path && Storage::disk('public')->exists($shortLink->qr_code_path)) {
            Storage::disk('public')->delete($shortLink->qr_code_path);
        }

        $shortLink->delete();

        return redirect()->route('short-links.index')
            ->with('success', 'Short link berhasil dihapus.');
    }

    /**
     * Redirect short code to original URL.
     */
    public function redirect($shortCode)
    {
        $shortLink = ShortLink::where('short_code', $shortCode)->first();

        if (!$shortLink) {
            abort(404);
        }

        // Check if link is inactive (disabled by admin)
        if (!$shortLink->is_active) {
            return response()->view('link.inactive', compact('shortLink'), 410);
        }

        // Check if expired
        if ($shortLink->isExpired()) {
            return response()->view('link.expired', compact('shortLink'), 410);
        }

        // Increment click count
        $shortLink->increment('click_count');

        // Record click details
        LinkClick::create([
            'short_link_id' => $shortLink->id,
            'clicked_at'    => now(),
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
            'referer'       => request()->header('referer'),
        ]);

        return redirect()->away($shortLink->original_url);
    }

    /**
     * Generate a random unique short code (6-7 characters).
     */
    private function generateShortCode(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $maxAttempts = 10;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $length = rand(6, 7);
            $shortCode = '';

            for ($i = 0; $i < $length; $i++) {
                $shortCode .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Check if unique
            if (!ShortLink::where('short_code', $shortCode)->exists()) {
                return $shortCode;
            }
        }

        // Fallback: use Str::random which is very unlikely to collide
        return Str::random(8);
    }

    /**
     * Return the stored QR Code image for a short link.
     * Mengambil file yang sudah tersimpan jika qr_generated aktif.
     */
    public function qrCode(ShortLink $shortLink)
    {
        // Authorization: user can only view QR for their own links
        if ($shortLink->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke short link ini.');
        }

        // QR Code hanya tersedia jika user mengaktifkan generate QR saat pembuatan link
        if (!$shortLink->qr_generated) {
            abort(404, 'QR Code tidak tersedia untuk short link ini.');
        }

        $qrPath = $shortLink->qr_code_path;

        // Jika file belum ada di storage tetapi qr_generated aktif, buat dan simpan
        if (!$qrPath || !Storage::disk('public')->exists($qrPath)) {
            $qrPath = $this->generateAndSaveQrCode($shortLink);
        }

        // Ambil file QR Code yang sudah tersimpan (tidak generate ulang)
        $fileContent = Storage::disk('public')->get($qrPath);

        return response($fileContent, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'inline; filename="qr-' . $shortLink->short_code . '.png"')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Generate QR Code with original_url and save it to storage.
     */
    private function generateAndSaveQrCode(ShortLink $shortLink): string
    {
        // QR Code selalu berisi Original URL langsung
        $qrCode = QrCode::create($shortLink->original_url)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setSize(300)
            ->setMargin(15)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $path = 'qrcodes/qr_' . $shortLink->short_code . '.png';
        Storage::disk('public')->put($path, $result->getString());

        $shortLink->update([
            'qr_code_path' => $path,
            'qr_generated' => true,
        ]);

        return $path;
    }
}

