# THIS FILE IS FOR DEVELOPMENT. For production, see https://github.com/NamelessMC/Nameless-Docker

FROM namelessmc/php

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN userdel -f www-data
RUN if getent group www-data ; then groupdel www-data; fi
RUN groupadd -g ${GROUP_ID} www-data || true
RUN useradd -l -u ${USER_ID} -g ${GROUP_ID} www-data

ENTRYPOINT [ "php-fpm" ]
