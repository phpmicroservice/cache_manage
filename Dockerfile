#
# PHP-APACHE 7.2 Dockerfile
#
# https://github.com/phalcon/dockerfiles
#

FROM php:7.2-fpm

MAINTAINER Dongasai 1514582970@qq.com


ENV REFRESH_DATE=2020年7月3日14:25:09
RUN echo "deb http://mirrors.163.com/debian/ buster main non-free contrib" > /etc/apt/sources.list && \
    echo "deb http://mirrors.163.com/debian/ buster-updates main non-free contrib " >> /etc/apt/sources.list  && \
    echo "deb http://mirrors.163.com/debian/ buster-backports main non-free contrib " >> /etc/apt/sources.list && \
    echo "deb-src http://mirrors.163.com/debian/ buster main non-free contrib " >> /etc/apt/sources.list && \
    echo "deb-src http://mirrors.163.com/debian/ buster-updates main non-free contrib " >> /etc/apt/sources.list && \
    echo "deb-src http://mirrors.163.com/debian/ buster-backports main non-free contrib " >> /etc/apt/sources.list  && \
    echo "deb http://mirrors.163.com/debian-security/ buster/updates main non-free contrib  " >> /etc/apt/sources.list  && \
    echo "deb-src http://mirrors.163.com/debian-security/ buster/updates main non-free contrib " >> /etc/apt/sources.list
RUN apt-get update && apt-get install -y vim wget zip zlib1g-dev git

RUN docker-php-ext-install bcmath mbstring pdo pdo_mysql zip;docker-php-ext-enable pdo pdo_mysql;
RUN pecl install redis \
    && docker-php-ext-enable redis
RUN apt-get install -y libmemcached-dev zlib1g-dev \
    && pecl install memcached-3.0.4\
    && docker-php-ext-enable memcached


RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd
RUN apt install -y imagemagick libmagick++-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# mongodb
RUN apt install -y libcurl4-openssl-dev pkg-config libssl-dev
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
# 安装composer
RUN wget  https://mirrors.aliyun.com/composer/composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ \
    && composer global require hirak/prestissimo \
    && composer global require slince/composer-registry-manager ^2.0
COPY default.conf /etc/nginx/conf.d/





