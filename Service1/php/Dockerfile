# Use PHP 8.2.11 FPM as the base image
FROM php:8.2.11-fpm

# Display messages in color
ENV TERM xterm-256color

# Update package lists and install dependencies in a single RUN command to reduce layers
RUN apt-get update && apt-get install -y --no-install-recommends \
    apt-utils \
    nano \
    wget \
    dialog \
    vim \
    git \
    curl \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    libbz2-dev \
    locales \
    libmcrypt-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


WORKDIR /var/www
