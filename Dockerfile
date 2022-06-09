FROM php:8.0-fpm-alpine3.16

RUN apk add --no-cache openssl bash mysql-client nodejs npm
RUN docker-php-ext-install pdo pdo_mysql bcmath

WORKDIR /var/www

RUN rm -rf /var/www/html
RUN ln -s public html

RUN curl https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 9000

ENTRYPOINT ["php-fpm"]

