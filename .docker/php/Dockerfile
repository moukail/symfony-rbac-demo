FROM node:20.7-alpine3.18 AS node
FROM php:8.2-fpm-alpine3.18

ENV TIMEZONE 'Europe/Amsterdam'

RUN apk --update --no-cache add bash wget gcc g++ make autoconf linux-headers rabbitmq-c-dev rabbitmq-c libsodium-dev openssh git rsync
RUN docker-php-ext-install -j$(nproc) pdo_mysql

RUN pecl install amqp redis libsodium && docker-php-ext-enable amqp redis opcache

# For Xdebug >=3.2 support PHP 8.0
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Xdebug =<3.1 support PHP 7.4
#RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug

# For Xdebug 3
RUN echo $'zend_extension=xdebug.so \n\
xdebug.mode=debug \n\
xdebug.start_with_request=trigger \n\
xdebug.client_host=host.docker.internal'\
> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# For Xdebug 2
#RUN pecl install xdebug-2.9.8 && docker-php-ext-enable xdebug
#RUN echo $'zend_extension=xdebug.so \n\
#xdebug.default_enable=1 \n\
#xdebug.remote_enable=1 \n\
#xdebug.remote_host=host.docker.internal '\
#> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i "s|;date.timezone =.*|date.timezone = ${TIMEZONE}|" /usr/local/etc/php/php.ini \
    && sed -i "s|memory_limit =.*|memory_limit = -1|" /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony5/bin/symfony /usr/bin/symfony

### Codeception
RUN wget http://codeception.com/codecept.phar -O /usr/bin/codecept && chmod a+x /usr/bin/codecept
#source <(codecept _completion --generate-hook --program codecept)

# mkcert
RUN wget https://dl.filippo.io/mkcert/v1.4.4?for=linux/amd64 -O mkcert && chmod a+x ./mkcert && mv ./mkcert /usr/bin/mkcert

# Basic smoke test
COPY --from=node /usr/local /usr/local
COPY --from=node /opt /opt

RUN addgroup _www && adduser -S -H --ingroup _www _www

RUN echo | npm install -g npm@10.1.0

WORKDIR /var/www

ADD install.sh /home/
RUN chmod +x /home/install.sh

ADD docker-init.sh /home/
RUN chmod +x /home/docker-init.sh
CMD bash /home/docker-init.sh
