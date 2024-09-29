FROM php:7.3-apache

# Habilitar mod_rewrite, mod_headers y otros módulos necesarios
RUN a2enmod rewrite headers cgi include

# Instalar extensiones de PHP necesarias, incluyendo mysqli
RUN docker-php-ext-install pdo_mysql mysqli

# Copiar el código fuente
COPY ./html /var/www/html

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# Establecer permisos de acceso al archivo .htaccess
RUN chmod -R 755 /var/www/html/.htaccess

# Reiniciar Apache para aplicar cambios
CMD ["apache2-foreground"]
