FROM php:7.4-cli

RUN apt-get update && apt-get install -y libonig-dev wget libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip  \
    &&  wget https://get.symfony.com/cli/installer -O - | bash

COPY backend /app/

WORKDIR /app

RUN wget https://getcomposer.org/composer.phar && php composer.phar install

COPY docker/wait_for_it.sh  /wait_for_it.sh
COPY docker/php/entrypoint.sh /app/entrypoint.sh

ENTRYPOINT ["./entrypoint.sh"]
