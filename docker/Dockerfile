FROM php:8.1-fpm-buster

RUN apt-get update && apt-get install -y \
    qrencode \
    libcurl4-openssl-dev \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    imagemagick \
    pdftk \
    poppler-utils

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install zip gd mbstring

WORKDIR /var/www

COPY src/ /var/www
RUN composer install

CMD ["php","artisan","serve","--host=0.0.0.0","--port=8080","--no-reload"]
EXPOSE 8080