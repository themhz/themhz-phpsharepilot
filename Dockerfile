# Use an official PHP image with Apache included
FROM php:8.3.0-apache

# Install necessary packages including OpenSSL, cron, and nano
# Combine apt-get update, install, and cleanup into a single RUN to reduce image layers and size
RUN apt-get update && apt-get install -y \
    openssl \
    cron \
    nano \
    libzip-dev \
    zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite ssl headers \
    && rm -rf /var/lib/apt/lists/*

COPY mycert.crt /etc/ssl/certs/mycert.crt
COPY mycert.key /etc/ssl/private/mycert.key

# Copy custom Apache virtual host configuration for both HTTP and HTTPS
COPY default.conf /etc/apache2/sites-available/default.conf
COPY default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

# Copy the php.ini file
#COPY php.ini-development /usr/local/etc/php/php.ini
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli && docker-php-ext-install pdo_mysql
RUN a2enmod rewrite && a2enmod ssl && a2enmod socache_shmcb
RUN a2ensite default-ssl
RUN apt-get update && apt-get upgrade -y

RUN sed -i '/SSLCertificateFile.*snakeoil\.pem/c\SSLCertificateFile \/etc\/ssl\/certs\/mycert.crt' /etc/apache2/sites-available/default-ssl.conf
RUN sed -i '/SSLCertificateKeyFile.*snakeoil\.key/cSSLCertificateKeyFile /etc/ssl/private/mycert.key\' /etc/apache2/sites-available/default-ssl.conf


# Enable the SSL site
RUN a2ensite default-ssl
RUN a2enmod ssl && a2enmod socache_shmcb

# Copy your PHP application into the container
COPY SharePilot /var/www/html/

# Optionally remove the default index.html provided by Apache if it exists
RUN if [ -f /var/www/html/index.html ]; then rm /var/www/html/index.html; fi

# Set correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && find /var/www/html/install -type f -exec chmod 644 {} \;

# Add your cron job
# This is a simple example of a cron job running every minute
RUN echo "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin" >> /etc/cron.d/sharepilot-cron \
    && echo "* * * * * root php /var/www/html/cron/worker.php >> /var/log/cron.log 2>&1" >> /etc/cron.d/sharepilot-cron \
    && chmod 0644 /etc/cron.d/sharepilot-cron

# Note: The command to start the cron service is removed. Instead, use a custom script or entrypoint to ensure the cron service starts with the container.

# Expose ports 80 and 443 for Apache
EXPOSE 80 443
