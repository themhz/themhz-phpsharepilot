# Use the latest Ubuntu as a parent image
FROM ubuntu:latest

# Prevents prompting from apt-get
ENV DEBIAN_FRONTEND=noninteractive

# Update Ubuntu Software repository and install software
RUN apt-get update && \
    apt-get install -y software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y apache2 \
                       php \
                       libapache2-mod-php \
                       mysql-server \
                       php-mysql \                                                            
                       && apt-get clean \
                       && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN apt-get update && apt-get install nano

# Copy your PHP application directory into the container
COPY SharePilot /var/www/html/

# Remove the default index.html provided by Apache
RUN rm /var/www/html/index.html

# Set correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# Expose ports for Apache and MySQL
EXPOSE 80 3306

# Set up environment variables to specify the MySQL credentials
ENV MYSQL_ROOT_PASSWORD=526996
ENV MYSQL_DATABASE=sharepilot
ENV MYSQL_USER=root
ENV MYSQL_PASSWORD=526996

# Update the MySQL bind-address configuration
RUN sed -i 's/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

# Copy the database init script
COPY init_db.sh /usr/local/bin/init_db.sh
RUN chmod +x /usr/local/bin/init_db.sh

# Set the entrypoint script to initialize the database and start Apache
ENTRYPOINT ["/usr/local/bin/init_db.sh"]

# Copy custom Apache virtual host file
#COPY my-000-default.conf /etc/apache2/sites-available/000-default.conf

# Set the apache server to the foreground
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

