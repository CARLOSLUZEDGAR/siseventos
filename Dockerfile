# =========================
# 1. Imagen base (Laravel 7 compatible)
# =========================
FROM php:7.4-fpm

# Evita prompts interactivos
ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1

# =========================
# 2. Dependencias del sistema
# =========================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# =========================
# 3. Extensiones PHP necesarias
# =========================
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        mbstring \
        zip \
        gd \
        exif \
        bcmath

# =========================
# 4. Instalar Composer
# =========================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =========================
# 5. Directorio de trabajo
# =========================
WORKDIR /var/www

# =========================
# 6. Copiar proyecto
# =========================
COPY . .

# =========================
# 7. Instalar dependencias PHP
# =========================
RUN composer install --no-dev --optimize-autoloader

# =========================
# 8. Permisos Laravel
# =========================
RUN chmod -R 775 storage bootstrap/cache

# =========================
# 9. Cache optimización (opcional pero recomendado)
# =========================
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

# =========================
# 10. Puerto (Render lo usa dinámicamente, pero no afecta)
# =========================
EXPOSE 8000

# =========================
# 11. Iniciar Laravel
# =========================
CMD php artisan optimize:clear && php artisan serve --host=0.0.0.0 --port=8000