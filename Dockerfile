FROM php:7.3.27-zts

RUN apt-get update && apt-get upgrade -y && apt-get install -y git

RUN git clone https://github.com/krakjoe/pthreads -b master /tmp/pthreads
RUN docker-php-ext-configure /tmp/pthreads --enable-pthreads
RUN docker-php-ext-install /tmp/pthreads

RUN git clone https://github.com/kostikpenzin/thread_php_docker.git
RUN cd thread_php_docker

WORKDIR /var/www/thread_php_docker
RUN echo | php index.php
                                 