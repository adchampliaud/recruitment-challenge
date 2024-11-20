FROM bitnami/php-fpm:8.3.14

RUN apt-get update && apt-get install supervisor -y

RUN install_packages autoconf g++ make

# Install extensions
RUN pecl install pcov
RUN apt-get update
RUN apt-get -yq install librabbitmq-dev
RUN pecl install amqp

# Add configurations to PHP.ini
RUN echo "extension=pcov" >> /opt/bitnami/php/etc/php.ini
RUN echo "extension=amqp" >> /opt/bitnami/php/etc/php.ini

CMD ["/usr/bin/supervisord"]
