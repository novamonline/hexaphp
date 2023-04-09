FROM php:latest as stage


FROM nginx
copy --from=stage /usr/local/bin/php /usr/local/bin/php


EXPOSE 80