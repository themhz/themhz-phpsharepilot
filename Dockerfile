# Use an official PHP image with Apache included
FROM php:8.3.0-apache

# Install additional PHP extensions if needed
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install cron
RUN apt-get update && apt-get install -y cron


# Copy your PHP application into the container
COPY SharePilot /var/www/html/

# Copy custom Apache virtual host configuration
COPY my-000-default.conf /etc/apache2/sites-available/000-default.conf

# Remove the default index.html provided by Apache
#RUN rm /var/www/html/index.html
RUN if [ -f /var/www/html/index.html ]; then rm /var/www/html/index.html; fi


# Set correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# Set permissions for Apache and install folder
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    find /var/www/html/install -type f -exec chmod 644 {} \;

# Add your cron job (replace '/path/to/your-script.sh' with your actual script path)
# This is a simple example of a cron job running every minute
RUN echo "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin" >> /etc/cron.d/sharepilot-cron
RUN echo "* * * * * root php /var/www/html/cron/worker.php >> /var/log/cron.log 2>&1" >> /etc/cron.d/sharepilot-cron
RUN chmod 0644 /etc/cron.d/sharepilot-cron

#Run the cron service
RUN service cron start

# Expose port 80 for Apache
EXPOSE 80
