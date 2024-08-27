FROM php:7.4-apache

# Copiar los archivos de la aplicación al directorio root del servidor web
COPY . /var/www/html/

# Instalar extensiones de PHP si es necesario
RUN docker-php-ext-install mysqli

# Exponer el puerto 80
EXPOSE 80
