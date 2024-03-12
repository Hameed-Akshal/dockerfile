FROM php:7.4-apache

RUN apt-get update
RUN apt-get install --yes --force-yes cron g++ gettext libicu-dev openssl libc-client-dev libkrb5-dev libxml2-dev libfreetype6-dev libgd-dev libmcrypt-dev bzip2 libbz2-dev libtidy-dev libcurl4-openssl-dev libz-dev libmemcached-dev libxslt-dev

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli 
RUN docker-php-ext-enable mysqli

RUN docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr
RUN docker-php-ext-install gd

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the application code into the container
COPY ./ /var/www/html

RUN chmod -R 775 /var/www/html/
RUN chmod -R 775 /etc/apache2/

# Copy Apache virtual host configuration file
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules and configure virtual host
RUN a2enmod rewrite && a2ensite 000-default

RUN echo "ServerName betaapi.demo.com" >> /etc/apache2/apache2.conf



# Expose port 80 for Apache
EXPOSE 80

# Define the command to run your application
CMD ["apache2-foreground"]
