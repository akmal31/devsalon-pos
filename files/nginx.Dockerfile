FROM nginx:alpine3.18

COPY --from=app /usr/local/etc/php /usr/local/etc/php
COPY --from=app /var/www/html /var/www/html
COPY --from=app /var/www/html/files/nginx.conf /etc/nginx/conf.d/default.conf
EXPOSE 80