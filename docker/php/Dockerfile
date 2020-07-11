FROM php:7.4-fpm-alpine

COPY php.ini $PHP_INI_DIR/php.ini

# Composer Dependencies
RUN apk --update add \
    bzip2-dev \
    autoconf \
    libzip-dev\
    oniguruma-dev \
    build-base \
    curl \
    git \
    subversion \
    freetype-dev \
    libjpeg-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libbz2 \
    libstdc++ \
    libxslt-dev \
    openldap-dev \
    postgresql-dev \
    make \
    unzip \
    wget && \
    pecl install xdebug-2.8.1 && \
    touch /var/log/xdebug.log && \
    chmod 0777 /var/log/xdebug.log && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-install zip bz2 mbstring pcntl xsl && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
    docker-php-ext-install gd && \
    docker-php-ext-configure ldap --with-libdir=lib/ && \
    docker-php-ext-install ldap pdo pdo_pgsql && \
    apk del build-base && \
    rm -rf /var/cache/apk/*



# Register the COMPOSER_HOME environment variable
ENV COMPOSER_HOME /composer

# Add global binary directory to PATH and make sure to re-export it
ENV PATH /composer/vendor/bin:$PATH

# Allow Composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Setup the Composer installer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer \
  && rm -rf /tmp/composer-setup.php

# Set up the working directory
WORKDIR /var/www/app
