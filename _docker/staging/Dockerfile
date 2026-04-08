FROM --platform=linux/amd64 ubuntu:24.04
RUN apt-get update

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get -y --allow-downgrades --allow-remove-essential --allow-change-held-packages install apache2 libapache2-mod-php8.3 && \
	echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get -y --allow-downgrades --allow-remove-essential --allow-change-held-packages install \
	php8.3 php8.3-common php8.3-opcache php8.3-cli php8.3-gd php8.3-curl php8.3-mysql && \
	apt-get -y --allow-downgrades --allow-remove-essential --allow-change-held-packages install \
	php8.3-pspell php8.3-tidy php8.3-mbstring php8.3-soap php8.3-zip php8.3-xsl php8.3-xml php8.3-intl zip mysql-client

RUN cd /var/www/html && rm -rf /var/www/*

ADD ./_docker/staging/000-default.conf /etc/apache2/sites-available/000-default.conf
ADD ./_docker/staging/ports.conf /etc/apache2/ports.conf
ADD ./_docker/staging/apache2.conf /etc/apache2/apache2.conf
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ADD ./ /var/www/

RUN apt-get install -y libapache2-mod-auth-mellon wget nano openssl && \
    a2enmod auth_mellon && \
    mkdir -p /etc/apache2/mellon/ && cd /etc/apache2/mellon/

ADD ./_docker/staging/mellon/mellon_create_metadata_fixed.sh /etc/apache2/mellon/mellon_create_metadata_fixed.sh

RUN chmod +x /etc/apache2/mellon/mellon_create_metadata_fixed.sh && \
    /bin/bash /etc/apache2/mellon/mellon_create_metadata_fixed.sh he-faq-edit-staging-0242ac120002 http://storefront.hosteurope.de/typo3/secret/endpoint

ADD ./_docker/staging/mellon/idp.xml /etc/apache2/mellon/idp.xml

RUN	a2enmod php8.3 && a2enmod rewrite && a2enmod lbmethod_byrequests

RUN rm -rf /var/www/public/typo3temp && \
    mkdir -p /var/www/public/typo3temp && \
    chmod -R a+rwx /var/www/public/typo3temp && \
    rm -rf /var/www/public/_assets && \
    mkdir -p /var/www/public/_assets && \
    chmod -R a+rwx /var/www/public/_assets && \
    mkdir -p /var/www/public/fileadmin && \
    mkdir -p /var/www/var && \
    chmod -R a+rwx /var/www && \
    chown -R www-data:www-data /var/www/public && \
    chown -R www-data:www-data /etc/apache2/mellon && \
    chmod -R a+rwx /etc/apache2/mellon && \
    chmod -R a+rwx /var/run/apache2 && \
    chmod -R a+rwx /var/log/apache2

RUN apt-get update && \
apt-get install -y locales locales-all

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/apache2/access.log \
    && ln -sf /dev/stderr /var/log/apache2/error.log

ENV LC_ALL en_US.UTF-8
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US.UTF-8

ENV HOST_CUSTOM_PREFIX=${HOST_CUSTOM_PREFIX} \
    DOCKER_DEVELOP_BOX=${DOCKER_DEVELOP_BOX} \
    DATABASE_NAME=${DATABASE_NAME} \
    DATABASE_HOST=${DATABASE_HOST} \
    DATABASE_USERNAME=${DATABASE_USERNAME} \
    DATABASE_PASSWORD=${DATABASE_PASSWORD} \
    CD_CLIENT_ID=${CD_CLIENT_ID} \
    CD_CLIENT_SECRET=${CD_CLIENT_SECRET} \
    PPE_CLIENT_ID=${PPE_CLIENT_ID} \
    PPE_CLIENT_SECRET=${PPE_CLIENT_SECRET} \
    PPE_USERNAME=${PPE_USERNAME} \
    PPE_PASSWORD=${PPE_PASSWORD}

WORKDIR /var/www
RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080

USER www-data

CMD apache2ctl -D FOREGROUND