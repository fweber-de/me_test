FROM ubuntu:20.04

ENV TZ=Europe/Berlin
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get install -y software-properties-common && LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php

RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    git \
    unzip \
    apache2 \
    php8.1 \
    php8.1-cli \
    libapache2-mod-php8.1 \
    php8.1-gd \
    php8.1-ldap \
    php8.1-curl \
    php8.1-xml \
    php8.1-xsl \
    php8.1-mbstring \
    php8.1-zip \
    php8.1-imap \
    php-pear \
    php8.1-xdebug \
    php8.1-dev \
    php8.1-mysql

RUN a2enmod rewrite
RUN a2enmod headers

RUN usermod -u 1000 www-data

#node
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install -y nodejs

# Set up composer variables
ENV COMPOSER_BINARY=/usr/local/bin/composer \
    COMPOSER_HOME=/usr/local/composer
ENV PATH $PATH:$COMPOSER_HOME

ENV PATH $PATH:/usr/local/composer/vendor/bin

# Install composer system-wide
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar $COMPOSER_BINARY && \
    chmod +x $COMPOSER_BINARY

RUN apt-get install -y locales && \
    echo "de_DE.UTF-8 UTF-8" > /etc/locale.gen && \
    locale-gen

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list \
    && apt update \
    && apt install symfony-cli

#app
COPY docker/apache_dev /etc/apache2/sites-available/000-default.conf
COPY docker/run_dev /usr/local/bin/run
RUN chmod +x /usr/local/bin/run

VOLUME /var/www/html/app
EXPOSE 80
CMD ["/usr/local/bin/run"]
