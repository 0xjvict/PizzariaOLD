FROM serversideup/php:8.2-fpm-nginx

MAINTAINER João Melo <jvmelo.dev@gmail.com>

RUN apt-get update \
    && apt-get install nano -y \
    && apt-get install git -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
