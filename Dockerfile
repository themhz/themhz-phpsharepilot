# Use an official PHP image with Apache included
FROM php:8.3.0-apache

# Install necessary packages including OpenSSL, cron, and nano
# Combine apt-get update, install, and cleanup into a single RUN to reduce image layers and size
RUN apt-get update && apt-get install -y \
    openssl \
    cron \
    nano \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite ssl headers \
    && rm -rf /var/lib/apt/lists/*

# Generate SSL certificates
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/localhost.key -out /etc/ssl/certs/localhost.crt \
    -subj "/C=GR/ST=Attica/L=Zografou/O=Personal/CN=localhost"


# Copy custom Apache virtual host configuration for both HTTP and HTTPS
COPY my-000-default.conf /etc/apache2/sites-available/000-default.conf
COPY my-default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

# Enable the SSL site
RUN a2ensite default-ssl

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
