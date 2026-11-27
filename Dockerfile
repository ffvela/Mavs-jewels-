# Entorno local: Apache + PHP 8.2 con las mismas extensiones que el hosting
FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        libjpeg-dev libpng-dev libwebp-dev libzip-dev unzip git \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql gd \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# La raíz pública del sitio es public/ (el resto del código no queda expuesto)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
