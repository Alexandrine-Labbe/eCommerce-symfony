FROM node:22 AS build_front

WORKDIR /app

COPY package.json .
COPY package-lock.json .
COPY webpack.config.js .
RUN npm ci
COPY assets assets
COPY public public
RUN mkdir -p public/build
RUN npm run build

##############################################################################################
FROM php:8.3-apache

COPY --from=composer/composer:2-bin /composer /usr/bin/composer

RUN apt-get update && apt-get install -y sudo zip unzip

RUN apt-get -y update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Install SQLite and enable PHP SQLite extension
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev
RUN docker-php-ext-install pdo pdo_sqlite

RUN a2enmod rewrite

COPY init.sh init.sh
COPY assets assets
COPY public public
COPY bin bin
COPY migrations migrations
COPY config config
COPY translations translations
COPY templates templates
COPY src src
COPY composer.json .
COPY composer.lock .
COPY .env .env

COPY --from=build_front /app/public/build /var/www/html/public/build
RUN mkdir -p public/uploads

ENV APP_ENV="prod"
RUN composer install --no-dev --no-interaction --optimize-autoloader
RUN php bin/console cache:clear --no-debug

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY .cloud/php.ini $PHP_INI_DIR/conf.d/app.ini
COPY .cloud/docker.apache.conf /etc/apache2/sites-available/000-default.conf

RUN chmod 755 -R .
RUN chmod -R 777 var
RUN chmod 775 -R public/uploads

RUN chown -R www-data:www-data /var/www/html

USER www-data

