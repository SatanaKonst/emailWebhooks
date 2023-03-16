FROM php:8.2.3-apache

RUN apt-get update && \
  echo "Europe/Moscow" > /etc/timezone && \
  rm -rf /etc/localtime && \
  ln -s /usr/share/zoneinfo/Europe/Moscow /etc/localtime && \
  apt-get install -y \
             apt-utils \
             man \
             curl \
             git \
             bash \
             vim \
             zip unzip \
             acl \
             iproute2 \
             dnsutils \
             fonts-freefont-ttf \
             fontconfig \
             dbus \
             openssh-client \
             sendmail \
             libfreetype6-dev \
             libjpeg62-turbo-dev \
             icu-devtools \
             libicu-dev \
             libmcrypt4 \
             libmcrypt-dev \
             libpng-dev \
             zlib1g-dev \
             libxml2-dev \
             libzip-dev \
             libonig-dev \
             graphviz \
             libcurl4-openssl-dev \
             pkg-config \
             libldap2-dev \
             libpq-dev \
             cron \
             nano

RUN docker-php-ext-configure intl --enable-intl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd && \
    docker-php-ext-install pdo \
        pgsql pdo_pgsql \
        mysqli pdo_mysql \
        intl iconv mbstring \
        zip pcntl \
        exif opcache \
    && docker-php-source delete

RUN apt-get install -y libc-client-dev libkrb5-dev && \
    rm -r /var/lib/apt/lists/* && \
    docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
    docker-php-ext-install imap

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite && a2enmod headers

RUN crontab -l | { cat; echo "* * * * * /usr/local/bin/php /var/www/html/artisan schedule:run >> /dev/null 2>&1"; } | crontab -
CMD ["cron", "-f"]