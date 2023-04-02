FROM php:8.1.17-fpm-alpine3.17

WORKDIR /var/www/html/

# Essentials
RUN echo "UTC" > /etc/timezone

# install necessary alpine packages
RUN apk add zip unzip curl

# Installing bash
RUN apk add bash
RUN sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd

# Installing PHP
RUN apk add --no-cache php81 \
    php81-common \
    php81-gd \
    php81-fpm \
    php81-pdo \
    php81-opcache \
    php81-zip \
    php81-phar \
    php81-iconv \
    php81-cli \
    php81-curl \
    php81-openssl \
    php81-mbstring \
    php81-tokenizer \
    php81-fileinfo \
    php81-json \
    php81-xml \
    php81-xmlwriter \
    php81-simplexml \
    php81-dom \
    php81-pdo_mysql \
    php81-tokenizer


# install driver pdo mysql since it not being instaled
# when install with apk add command
RUN docker-php-ext-install pdo pdo_mysql

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer --version=2.4.2
RUN rm -rf composer-setup.php


COPY . .
# RUN composer install --no-dev
RUN composer install


RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
