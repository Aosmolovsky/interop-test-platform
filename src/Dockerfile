FROM node:14.5.0-stretch AS frontend
WORKDIR /usr/src/app
COPY .npm-package/* ./
RUN npm ci
COPY webpack.mix.js webpack.mix.js
COPY resources resources

RUN npm run prod

FROM composer:1.10.8 AS composer
COPY composer* ./

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --no-autoloader

# Add application code
COPY . .
RUN composer dump-autoload --optimize && composer update

FROM php:fpm-alpine3.12
ENV WAIT_VERSION 2.7.2
ENV PHPREDIS_VERSION 5.2.2

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/$WAIT_VERSION/wait /wait

COPY build/docker-bootstrap.sh /usr/local/bin/start
# Download phpredis sources to a dir that docker-php-ext-install will look in and make it aware it's there.
RUN mkdir -p /usr/src/php/ext/redis && \
    curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 && \
    echo 'redis' >>/usr/src/php-available-exts && \
    apk add --no-cache supervisor freetype-dev libjpeg-turbo-dev libzip-dev libpng-dev postgresql-dev nginx libxml2-dev libxslt-dev && \
    rm /etc/nginx/conf.d/default.conf && \
    chmod +x /wait && \
    chmod +x /usr/local/bin/start  && \
    chown -R www-data:www-data /var/www && \
    chown -R www-data:www-data /var/lib/nginx && \
    chown -R www-data:www-data /var/log/ && \
    chown -R www-data:www-data /usr/lib/nginx && \
    chown -R www-data:www-data /run && \
    docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli opcache redis pcntl gd bcmath posix zip xmlrpc sockets soap xsl

ARG APP_ENV=prod

# Install composer
RUN if [ "${APP_ENV}" = "development" ]; then \
        php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer; \
        composer global require hirak/prestissimo; \
    fi;

# Use custom user to ensure correct permissions
USER www-data
WORKDIR /var/www/html
RUN mkdir /var/www/logs

# Configure nginx
COPY build/nginx-server.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY build/php-fpm.conf /etc/php7/php-fpm.d/www.conf
ARG PHP_INI_TEMPLATE
COPY build/php-ini-${PHP_INI_TEMPLATE}.ini $PHP_INI_DIR/php.ini

# Configure supervisord
COPY build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# copy in code and dependencies 
COPY --from=composer --chown=www-data:www-data /app /var/www/html

# Setup IDE helpers 
RUN if [ "${APP_ENV}" = "development" ]; then \
        composer update; \
    fi;


# copy in assets built in node image
COPY --from=frontend --chown=www-data:www-data /usr/src/app/public/assets /var/www/html/public/assets

RUN chmod +x artisan && \
    php artisan dusk:chrome-driver 81 && \
    chmod -R 777 storage bootstrap/cache

# Expose the port nginx is reachable on
EXPOSE 8080

CMD ["/usr/local/bin/start"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/health