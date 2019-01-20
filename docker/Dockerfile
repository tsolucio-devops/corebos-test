FROM alpine:3.7

RUN apk update && apk upgrade && apk nginx add curl bash git
RUN adduser -D -g 'www' www

RUN mkdir /www
RUN chown -R www:www /www

COPY docker/nginx.conf /etc/nginx/

RUN mkdir -p /run/php
RUN mkdir -p /run/nginx

RUN echo "daemon off;" >> /etc/nginx/nginx.conf



RUN apk add php7-fpm php7-mcrypt php7-soap php7-openssl php7-gmp php7-pdo_odbc php7-json \
    php7-dom php7-pdo php7-zip php7-mysqli php7-sqlite3 php7-apcu php7-pdo_pgsql php7-bcmath \
    php7-gd php7-odbc php7-pdo_mysql php7-pdo_sqlite php7-gettext php7-xmlreader php7-xmlrpc \
    php7-bz2 php7-iconv php7-pdo_dblib php7-curl php7-ctype php7-cli php7-session \
    php7-phar php7-dom php7-tokenizer php7-mbstring php7-xmlwriter php7-xml php7-simplexml php7-fileinfo

COPY docker/php7/php.ini /etc/php7/
COPY docker/php7/php-fpm.conf /etc/php7/
COPY docker/php7/php-fpm.d/www.conf /etc/php7/php-fpm.d/

RUN apk add tzdata
RUN ls /usr/share/zoneinfo
RUN cp /usr/share/zoneinfo/Europe/Madrid /etc/localtime
RUN echo "Europe/Madrid" > /etc/timezone

RUN chown -R www:www /var/lib/nginx
RUN chown -R www:www /var/tmp/nginx


RUN git clone https://github.com/tsolucio/corebos

RUN mv corebos/* /www

RUN rm -rf corebos

RUN chown www:www -R /www

COPY --chown=www:www . /www/build/coreBOSTests
COPY --chown=www:www docker/config.inc.php /www/

COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisord.conf


WORKDIR /www

COPY --chown=www:www /docker/start.sh /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]