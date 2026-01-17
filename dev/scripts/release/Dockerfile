# this should match the minimum php version specified in composer.json
FROM php:8.2-fpm-bookworm

RUN apt-get update

RUN apt-get install -y python3-minimal npm zip

RUN  --mount=type=bind,from=ghcr.io/mlocati/php-extension-installer:latest,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
    install-php-extensions @composer exif

COPY entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
