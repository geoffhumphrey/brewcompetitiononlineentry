FROM php:7.3-apache

# Enable mod_rewrite, mod_headers, and other necessary modules
RUN a2enmod rewrite headers cgi include

# Install necessary PHP extensions, including mysqli
RUN docker-php-ext-install pdo_mysql mysqli

# Copy the source code
COPY ./ /var/www/html

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html

# Set access permissions for the .htaccess file
RUN chmod -R 755 /var/www/html/.htaccess

# Restart Apache to apply changes
CMD ["apache2-foreground"]
