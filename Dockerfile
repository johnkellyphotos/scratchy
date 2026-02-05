FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y \
        libicu-dev \
        libzip-dev \
        libonig-dev \
        pkg-config \
        zip \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        intl \
        zip \
    && rm -rf /var/lib/apt/lists/*
