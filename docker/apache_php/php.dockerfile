FROM php:7.2-apache

ARG DOCKER_GROUP_ID

RUN addgroup -gid $DOCKER_GROUP_ID laravel && adduser -gid $DOCKER_GROUP_ID -shell /bin/sh -disabled-login laravel

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    gnupg2 \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV NODE_VERSION=14.19.2
ENV NVM_DIR=/usr/local/.nvm
RUN mkdir "$NVM_DIR"
RUN apt install -y curl
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="$NVM_DIR/versions/node/v${NODE_VERSION}/bin/:${PATH}"
RUN node --version
RUN npm --version

COPY . /var/www/html

RUN ls -la /var/www/html

RUN a2enmod rewrite && service apache2 restart

COPY ./docker/apache_php/apache_config.prod.conf /etc/apache2/sites-enabled/000-default.conf

COPY ./docker/apache_php/php.ini /usr/local/etc/php/php.ini

COPY ./docker/apache_php/run.sh /var/www/html/run.sh

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

USER laravel