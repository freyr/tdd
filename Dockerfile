FROM php:8.3-cli

# Install system dependencies
RUN apt-get update \
    && apt-get install -y unzip git \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD ["vendor/bin/phpunit"]
