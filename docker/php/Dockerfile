FROM php:7.2.3-fpm

RUN apt-get update && apt-get upgrade -y \
    && docker-php-ext-install mysqli  \
    && pecl install xdebug && docker-php-ext-enable xdebug \
    && apt-get autoremove -y --purge \
    && apt-get clean \
    && rm -Rf /tmp/*
