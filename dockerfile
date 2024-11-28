# Use the official WordPress image as the base image
FROM wordpress:6.6.1-php8.2

# Use the official Ubuntu 20.04 as the base image
# FROM ubuntu:20.04

# # Set environment variables to avoid interactive prompts during package installation
# ENV DEBIAN_FRONTEND=noninteractive

# # Update the package list and install necessary packages
# RUN apt-get update && \
#     apt-get install -y apache2 \
#                        php7.4 \
#                        php7.4-mysql \
#                        libapache2-mod-php7.4 \
#                        wget \
#                        unzip \
#                        curl \
#                        openssl && \
#     apt-get clean


# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install Xdebug
# RUN pecl install xdebug-3.1.0 \
#     && docker-php-ext-enable xdebug

# Copy custom php.ini settings (if any)
# COPY php.ini /usr/local/etc/php/

# Copy files from the local wordpress directory to the container
COPY wordpress/ /var/www/html/

# Set working directory and copy source
# WORKDIR /var/www/html
# COPY . .

# Configure Apache
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
RUN sed -E -i -e 's/#ServerName www.example.com/ServerName localhost/' /etc/apache2/sites-enabled/000-default.conf

# Copy custom Apache configuration
COPY ./docker/apache-custom.conf /etc/apache2/conf-available/
# Enable the custom Apache configuration
# RUN a2enconf apache-custom

# Debug settings
COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./docker/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini


# Enable mod rewrite and restart apache
RUN a2enconf apache-custom && a2enmod rewrite && a2enmod socache_shmcb && service apache2 restart 
# RUN a2enmod rewrite && a2enmod ssl && a2ensite default-ssl && a2enmod socache_shmcb && service apache2 restart
