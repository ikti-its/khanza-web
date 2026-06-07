FROM dunglas/frankenphp:php8.5

RUN install-php-extensions \
    intl \
    mbstring \
    gd \
    pgsql \
    zip \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

COPY . .

RUN chown -R www-data:www-data writable

ENV APP_ENV=production

EXPOSE 80

CMD ["frankenphp", "run", "--config", "/app/.config/Caddyfile"]