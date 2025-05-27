FROM php:8.4-cli-alpine

RUN apk add --no-cache unzip git \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del --no-network .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# WSL permissions
ENV UMASK=0000
RUN echo "umask $UMASK" >> /etc/profile

WORKDIR /app

CMD ["vendor/bin/phpunit"]
