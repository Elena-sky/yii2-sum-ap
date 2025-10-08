FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libzip-dev libonig-dev \
    curl wget \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" intl mbstring zip

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer global require codeception/codeception --no-interaction

ENV PATH="/root/.composer/vendor/bin:${PATH}"

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --prefer-dist --no-ansi --no-progress

COPY . .

RUN composer install --dev --no-interaction --prefer-dist --no-ansi --no-progress

RUN mkdir -p runtime/cache runtime/logs web/assets \
    && chmod -R 777 runtime web/assets

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "web"]
