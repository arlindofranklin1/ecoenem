FROM php:8.2-apache

# cURL é utilizado para validar tokens do Supabase Auth e chamar a RPC pelo PHP.
RUN apt-get update && apt-get install -y --no-install-recommends libcurl4-openssl-dev \
    && docker-php-ext-install curl mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
