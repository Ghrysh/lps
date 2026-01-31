# Gunakan PHP 8.2 FPM
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring xml gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project jika ingin build, tapi kita pakai volume di docker-compose
# COPY . /var/www/html

# Jalankan composer install otomatis saat container pertama kali jalan
# (opsional: bisa diganti entrypoint script)
CMD ["php-fpm"]
