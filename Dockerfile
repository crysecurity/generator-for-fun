FROM php:8.2

WORKDIR /app

COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt update  && \
    apt install -y zip unzip && \
    apt clean && \
    rm -rf /var/lib/apt/lists/* && \
    composer i  && \
    vendor/bin/phpstan analyse src tests --level 9 && \
    composer exec phpunit && \
    echo "Thanks for your attention!" && \
    sleep 10