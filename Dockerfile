#
# PHP-CLI 7.0 Dockerfile
#

FROM php:7.0-cli

MAINTAINER Dongasai 1514582970@qq.com


ENV REFRESH_DATE=2020年7月3日14:25:09

RUN echo "deb http://mirrors.163.com/debian/ stretch main non-free contrib" > /etc/apt/sources.list && \
    echo "deb http://mirrors.163.com/debian/ stretch-updates main non-free contrib " >> /etc/apt/sources.list  && \
    echo "deb http://mirrors.163.com/debian/ stretch-backports main non-free contrib " >> /etc/apt/sources.list && \
    echo "deb-src http://mirrors.163.com/debian/ stretch main non-free contrib " >> /etc/apt/sources.list && \
    echo "deb-src http://mirrors.163.com/debian/ stretch-updates main non-free contrib " >> /etc/apt/sources.list && \
    echo "deb-src http://mirrors.163.com/debian/ stretch-backports main non-free contrib " >> /etc/apt/sources.list  && \
    echo "deb http://mirrors.163.com/debian-security/ stretch/updates main non-free contrib  " >> /etc/apt/sources.list  && \
    echo "deb-src http://mirrors.163.com/debian-security/ stretch/updates main non-free contrib " >> /etc/apt/sources.list

RUN apt-get update && apt-get install -y vim wget zip zlib1g-dev git libgconf-2-4 libcurl3

RUN docker-php-ext-install bcmath mbstring pdo pdo_mysql zip;docker-php-ext-enable pdo pdo_mysql;
RUN pecl install redis \
    && docker-php-ext-enable redis
RUN apt-get install -y libmemcached-dev zlib1g-dev \
    && pecl install memcached-3.0.4\
    && docker-php-ext-enable memcached

# mongodb
RUN apt install -y libcurl3-openssl-dev libcurl3 pkg-config libssl-dev
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# 安装composer
RUN wget  https://mirrors.aliyun.com/composer/composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ \
    && composer global require slince/composer-registry-manager 
#重置工作目录
WORKDIR /var/www/html
