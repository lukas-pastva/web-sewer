FROM php:8.0-apache

RUN a2enmod rewrite && \
    apt-get update -y && \
	apt-get install -y --no-install-recommends \
	apt-transport-https \
	libgd-dev  \
    libfreetype6-dev  \
    libjpeg62-turbo-dev  \
    libpng-dev  \
    libzip-dev && \
	rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli gd && \
	docker-php-ext-configure gd

USER www-data

COPY ./html /var/www/html