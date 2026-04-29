FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    nano \
    python3 \
    py3-pip \
    ffmpeg \
    yt-dlp \
    nodejs \
    npm

RUN docker-php-ext-install pdo_pgsql gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /opt/TutaTeam

COPY . .

RUN chown -R www-data:www-data /opt/TutaTeam/storage /opt/TutaTeam/bootstrap/cache

LABEL authors="MaskiCH"

CMD ["php-fpm"]
