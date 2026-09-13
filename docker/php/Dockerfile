# 1. Объявляем аргументы ДО команды FROM, чтобы они работали в самой FROM
ARG PHP_VERSION=8.4
ARG GITHUB_ACCESS_TOKEN

# 2. Используем аргумент в базовом образе
FROM php:${PHP_VERSION}-cli

LABEL authors="Hellmerise@gmail.com"

# 3. ENV объявляем УЖЕ ПОСЛЕ FROM (это правильный синтаксис)
ENV APT_INSTALL_OPTIONS="-y"

RUN apt-get update && apt-get install ${APT_INSTALL_OPTIONS} \
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

# 4. Повторно объявляем ARG после FROM, чтобы он был доступен в команде RUN
ARG GITHUB_ACCESS_TOKEN
RUN if [ -n "$GITHUB_ACCESS_TOKEN" ]; then \
        composer config --global --auth github-oauth.github.com ${GITHUB_ACCESS_TOKEN}; \
    fi

WORKDIR /var/www/src