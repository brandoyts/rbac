FROM php:8.3.10-fpm

# Arguments for custom UID and GID
ARG UID=1000
ARG GID=1000

# Environment variables
ENV UID=${UID} \
    GID=${GID} \
    COMPOSER_ALLOW_SUPERUSER=1 \
    PATH=/home/laravel/.composer/vendor/bin:$PATH

# Create non-root user and group
RUN groupadd -g ${GID} laravel \
    && useradd -u ${UID} -g laravel -s /bin/bash -m laravel

# Install system dependencies and PHP extensions
RUN --mount=type=cache,target=/var/cache/apt \
    --mount=type=cache,target=/var/lib/apt/lists \
    apt-get update -y && apt-get install -y \
        git \
        unzip \
        zip \
        libzip-dev \
        libpq-dev \
        p7zip-full \
        openssl \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        zip \
    && docker-php-ext-enable zip \
    # Install Composer globally
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    # Install PHP extensions
    && docker-php-ext-install pdo pdo_pgsql \
    && docker-php-ext-install zip \
    && docker-php-ext-enable zip \
    # Check for mbstring module
    && php -m | grep mbstring \
    # Clean up to reduce image size
    && rm -rf /var/lib/apt/lists/* /var/cache/apt/*

# Set working directory
WORKDIR /app

# Copy application code
COPY ./laravel /app

# Ensure Laravel writable directories exist and have correct permissions
RUN mkdir -p storage bootstrap/cache \
    && chown -R laravel:laravel /app \
    && chmod -R 775 storage bootstrap/cache

# Switch to non-root user
USER laravel
