FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    libonig-dev \
    curl \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
CMD bash -c "cd /var/www/html && /usr/bin/composer install -o --prefer-dist && php-fpm"
