ARG PHP_VERSION
FROM php:${PHP_VERSION}-cli

LABEL authors="Hellmerise@outlook.com"

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libpng-dev \
    libonig-dev \
    default-libmysqlclient-dev \
    && docker-php-ext-install zip xml curl mbstring pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG GITHUB_ACCESS_TOKEN
RUN if [ -n "$GITHUB_ACCESS_TOKEN" ]; then \
        composer config --global --auth github-oauth.github.com ${GITHUB_ACCESS_TOKEN}; \
    fi

WORKDIR /var/www/src