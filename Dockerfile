FROM php:7.4-apache

RUN apt-get update && apt-get upgrade -y
RUN apt-get install --yes --force-yes cron g++ gettext libicu-dev openssl libc-client-dev libkrb5-dev  libxml2-dev libfreetype6-dev libgd-dev libmcrypt-dev bzip2 libbz2-dev libtidy-dev libcurl4-openssl-dev libz-dev libmemcached-dev libxslt-dev net-tools

# PHP Configuration
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install bz2
RUN docker-php-ext-install calendar
RUN docker-php-ext-install dba
RUN docker-php-ext-install exif
RUN docker-php-ext-install fileinfo
RUN apt-get --yes install libfreetype6-dev \
                          libjpeg62-turbo-dev \
                          libpng-dev \
                          libwebp-dev 

RUN set -e; \
    docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype; \
    docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install gettext
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl
RUN docker-php-ext-install imap
RUN docker-php-ext-install intl
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install soap
RUN docker-php-ext-install tidy
# RUN docker-php-ext-install xmlrpc
# RUN docker-php-ext-install mbstring
# RUN docker-php-ext-install xsl
# RUN docker-php-ext-install zip
RUN docker-php-ext-configure hash --with-mhash

# Apache Configuration
RUN a2enmod rewrite
RUN a2enmod headers

# Imagemagick
RUN apt-get install --yes --force-yes libmagickwand-dev libmagickcore-dev
RUN yes '' | pecl install -f imagick
RUN docker-php-ext-enable imagick
RUN sed -i 's/ServerSignature On/ServerSignature Off/gi' /etc/apache2/conf-enabled/security.conf