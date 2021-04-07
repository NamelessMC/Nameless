# THIS FILE IS FOR DEVELOPMENT. For production, see https://github.com/NamelessMC/Nameless-Docker

FROM namelessmc/php:dev

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN echo "root:x:0:0:root:/root:/bin/ash" > /etc/passwd
RUN echo "root:x:0:root" > /etc/group
RUN groupadd -g ${GROUP_ID} www-data
RUN useradd -l -u ${USER_ID} -g www-data www-data

ENTRYPOINT [ "php-fpm" ]
