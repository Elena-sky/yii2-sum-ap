FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libicu-dev \
       libonig-dev \
       git \
       unzip \
       curl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" intl mbstring

WORKDIR /app

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-interaction --prefer-dist --no-ansi --no-progress

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "web", "web/index.php"]
