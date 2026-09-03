<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShortLinkRequest extends FormRequest
{
    /**
     * Reserved words that cannot be used as custom alias.
     */
    protected $reservedWords = [
        'login', 'register', 'admin', 'dashboard',
        'logout', 'short-links', 'profile',
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'original_url' => [
                'required',
                'url',
                'max:2048',
                function ($attribute, $value, $fail) {
                    $parsed = parse_url($value);
                    if (!isset($parsed['scheme']) || !in_array($parsed['scheme'], ['http', 'https'])) {
                        $fail('URL hanya boleh menggunakan http atau https.');
                    }
                },
            ],
            'custom_alias' => [
                'nullable',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9\-]+$/',
                Rule::unique('short_links', 'short_code'),
                function ($attribute, $value, $fail) {
                    if (in_array(strtolower($value), $this->reservedWords)) {
                        $fail('Alias "' . $value . '" tidak boleh digunakan karena merupakan reserved word.');
                    }
                },
            ],
            'expires_at' => [
                'nullable',
                'date',
                'after:now',
            ],
            'generate_qr' => [
                'nullable',
                'boolean',
            ],
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where('user_id', auth()->id()),
            ],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'original_url.required' => 'URL wajib diisi.',
            'original_url.url' => 'Format URL tidak valid.',
            'original_url.max' => 'URL maksimal 2048 karakter.',
            'custom_alias.min' => 'Custom alias minimal 3 karakter.',
            'custom_alias.max' => 'Custom alias maksimal 30 karakter.',
            'custom_alias.regex' => 'Custom alias hanya boleh berisi huruf, angka, dan tanda hubung (-).',
            'custom_alias.unique' => 'Custom alias sudah digunakan.',
            'expires_at.date' => 'Format tanggal tidak valid.',
            'expires_at.after' => 'Tanggal expired harus di masa depan.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
        ];
    }
}
