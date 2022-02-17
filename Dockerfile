FROM php:7

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo \
"\n\
[xdebug] \n\
xdebug.mode=develop,debug \n\
xdebug.start_with_request=yes \n\
xdebug.remote_host=localhost \n\
xdebug.client_port=9009 \n\
xdebug.remote_enable=1 \n\
xdebug.remote_autostart=1 \n\
xdebug.start_with_request = yes \n\
xdebug.idekey = VSCODE \n\
xdebug.discover_client_host=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 9003