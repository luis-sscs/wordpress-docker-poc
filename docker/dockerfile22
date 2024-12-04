# Use the official Ubuntu 20.04 as the base image
FROM ubuntu:20.04

# Set environment variables to avoid interactive prompts during package installation
ENV DEBIAN_FRONTEND=noninteractive

# Update the package list and install necessary packages
RUN apt-get update && \
    apt-get install -y apache2 \
                       php7.4 \
                       php7.4-mysql \
                       libapache2-mod-php7.4 \
                       wget \
                       unzip \
                       curl \
                       openssl && \
    apt-get clean

# Enable Apache modules
RUN a2enmod rewrite ssl

# Generate a self-signed SSL certificate
RUN mkdir -p /etc/apache2/ssl && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/apache2/ssl/apache.key \
    -out /etc/apache2/ssl/apache.crt \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=Department/CN=localhost"

# Download and extract WordPress
RUN wget https://wordpress.org/latest.zip -O /tmp/wordpress.zip && \
    unzip /tmp/wordpress.zip -d /var/www/html/ && \
    mv /var/www/html/wordpress/* /var/www/html/ && \
    rmdir /var/www/html/wordpress && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Copy the default Apache configuration file
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

# Expose ports 80 and 443
EXPOSE 80 443

# Start Apache in the foreground
CMD ["apachectl", "-D", "FOREGROUND"]