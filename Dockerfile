# Menggunakan PHP 8.3 versi CLI
FROM php:8.3-cli

# Menentukan folder kerja di dalam container
WORKDIR /app

# Install dependensi sistem yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Install extension PHP (Termasuk pdo_mysql untuk database MySQL)
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    gd

# Copy composer dari image resminya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy seluruh file project Laravel-mu ke dalam /app
COPY . .

# Install dependensi PHP tanpa interaksi
RUN composer install --no-interaction --optimize-autoloader

# Atur perizinan folder agar Laravel bisa menulis file (log, cache, dll)
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Buka port 8096
EXPOSE 8096

# Jalankan aplikasinya
CMD php artisan serve --host=0.0.0.0 --port=8096