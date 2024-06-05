# Use an official PHP image with Apache included
FROM php:8.3.0-apache

# Install necessary packages including OpenSSL, cron, and nano
RUN apt-get update && apt-get install -y \
    openssl \
    cron \
    nano \
    libzip-dev \
    zip \
    p7zip-full \
    && docker-php-ext-install zip \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite ssl headers \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Verify Composer installation
RUN composer --version

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Set the working directory
WORKDIR /var/www/html

# Copy only the composer files to install dependencies
COPY SharePilot/composer.json SharePilot/composer.lock ./

# Install the dependencies
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application code
COPY SharePilot /var/www/html/

# Install the Composer autoloader (optional, depending on your setup)
RUN composer dump-autoload --optimize

# Copy Xdebug configuration
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Sample xdebug.ini content
# [Xdebug]
# zend_extension=xdebug.so
# xdebug.mode=debug
# xdebug.start_with_request=yes
# xdebug.client_host=host.docker.internal
# xdebug.client_port=9003

COPY mycert.crt /etc/ssl/certs/mycert.crt
COPY mycert.key /etc/ssl/private/mycert.key

# Copy custom Apache virtual host configuration for both HTTP and HTTPS
COPY default.conf /etc/apache2/sites-available/default.conf
COPY default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

# Use production php.ini
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Enable necessary PHP extensions and Apache modules
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli && docker-php-ext-install pdo_mysql
RUN a2enmod rewrite && a2enmod ssl && a2enmod socache_shmcb
RUN a2ensite default-ssl
RUN apt-get update && apt-get upgrade -y

# Update SSL certificate paths
RUN sed -i '/SSLCertificateFile.*snakeoil\.pem/c\SSLCertificateFile \/etc\/ssl\/certs\/mycert.crt' /etc/apache2/sites-available/default-ssl.conf
RUN sed -i '/SSLCertificateKeyFile.*snakeoil\.key/c\SSLCertificateKeyFile \/etc\/ssl\/private\/mycert.key' /etc/apache2/sites-available/default-ssl.conf

# Set correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && find /var/www/html/install -type f -exec chmod 644 {} \;

# Add your cron job
RUN echo "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin" >> /etc/cron.d/sharepilot-cron \
    && echo "* * * * * root php /var/www/html/cron/worker.php >> /var/log/cron.log 2>&1" >> /etc/cron.d/sharepilot-cron \
    && chmod 0644 /etc/cron.d/sharepilot-cron

# Expose ports 80 and 443 for Apache
EXPOSE 80 443
