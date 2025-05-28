FROM php:8.3-cli

# Install system dependencies
RUN apt-get update \
    && apt-get install -y unzip git \
    && rm -rf /var/lib/apt/lists/*

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --- Windows 11/WSL2 volume permission friendly ---
# Do not set USER, run as root (default)
# Optionally set a permissive umask for new files
ENV UMASK=0000
RUN echo "umask $UMASK" >> /etc/profile

# Optional: Basic Xdebug development config (uncomment to use)
# COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /app

CMD ["vendor/bin/phpunit"]
