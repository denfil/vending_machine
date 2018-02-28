FROM alpine

RUN apk add --no-cache \
        runit \
        nginx \
        php7 \
        php7-dom \
        php7-fpm \
        php7-json \
        php7-mbstring \
        php7-openssl \
        php7-phar \
        php7-pdo_sqlite \
        php7-session \
        php7-tokenizer \
        php7-xdebug \
        php7-xml \
        php7-xmlwriter \
        php7-zlib \
    && wget -qO - https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN echo -e \
    "server {\n" \
    "   listen 8080;\n" \
    "   server_name localhost;\n" \
    "   root /var/www/public;\n" \
    "   index index.php;\n" \
    "   location / {\n" \
    "      try_files \$uri \$uri/ /index.php?\$is_args\$args;\n" \
    "   }\n" \
    "   location ~ \.php$ {\n" \
    "      fastcgi_pass unix:/run/php7-fpm.sock;\n" \
    "      fastcgi_index index.php;\n" \
    "      include fastcgi.conf;\n" \
    "   }\n" \
    "}" > /etc/nginx/conf.d/default.conf \
    && sed -i -r \
        -e "s/(listen =) 127.0.0.1:9000/\1 \/run\/php7-fpm.sock/g" \
        -e "s/;(listen.owner =) nobody/\1 nginx/g" \
        -e "s/;(listen.group =) nobody/\1 nginx/g" \
        -e "s/(user =) nobody/\1 nginx/g" \
        -e "s/(group =) nobody/\1 nginx/g" \
        -e "s/(pm.start_servers =) 2/\1 1/g" \
        /etc/php7/php-fpm.d/www.conf \
    && sed -i -r \
        -e "s/(error_reporting = E_ALL) & ~E_DEPRECATED & ~E_STRICT/\1/g" \
        -e "s/(display_errors =) Off/\1 On/g" \
        -e "s/(display_startup_errors =) Off/\1 On/g" \
        /etc/php7/php.ini \
    && sed -i -r \
        -e "s/;(zend_extension=xdebug.so)/\1/g" \
        /etc/php7/conf.d/xdebug.ini \
    && mkdir -p /run/nginx /etc/service/php-fpm7 /etc/service/nginx \
	&& echo -e "#!/bin/sh\nphp-fpm7 -F" > /etc/service/php-fpm7/run \
    && echo -e "#!/bin/sh\nnginx -g 'daemon off;'" > /etc/service/nginx/run \
	&& chmod -R +x /etc/service/*

VOLUME /var/www

WORKDIR /var/www

EXPOSE 8080

CMD composer install -o --no-dev ; runsvdir -P /etc/service
