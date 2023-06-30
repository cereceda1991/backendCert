# Establecer la imagen base
FROM php:8.1-apache

# Directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Instalar y habilitar la extensión mongodb
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html

# Configurar el entorno
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# Instalar dependencias de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install 

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Puerto expuesto por Apache
EXPOSE 80

# Comando de inicio del contenedor
CMD ["apache2-foreground"]
