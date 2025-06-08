FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy php.ini configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Configure PHP
RUN echo "memory_limit = 256M" >> $PHP_INI_DIR/conf.d/custom.ini \
    && echo "upload_max_filesize = 20M" >> $PHP_INI_DIR/conf.d/custom.ini \
    && echo "post_max_size = 20M" >> $PHP_INI_DIR/conf.d/custom.ini \
    && echo "max_execution_time = 300" >> $PHP_INI_DIR/conf.d/custom.ini \
    && echo "date.timezone = Europe/Kiev" >> $PHP_INI_DIR/conf.d/custom.ini

# Configure OPcache
RUN echo "opcache.enable=1" >> $PHP_INI_DIR/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> $PHP_INI_DIR/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> $PHP_INI_DIR/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=4000" >> $PHP_INI_DIR/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=2" >> $PHP_INI_DIR/conf.d/opcache.ini

# Create a non-root user
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# Change ownership
RUN chown -R www:www /var/www

USER www
