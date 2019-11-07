# using the oficial PHP image
FROM php:7.2-apache

# Getting Composer from the oficial PHP image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \ 
    git \
    zip \
    unzip \
    libpq-dev

# https://github.com/docker-library/php/issues/221
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql

# Apache configuration
RUN echo "ServerName laravel-app.local" >> /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

RUN a2enmod rewrite headers

# copying files
COPY . /var/www/html
WORKDIR /var/www/html

# setting permisions for the created files
RUN chown -R www-data.www-data /var/www/html

# running composer
RUN composer install

# lumen config
RUN cp .env.example .env
RUN php artisan key:generate

# port config
EXPOSE 80

CMD apachectl -D FOREGROUND