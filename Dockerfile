# Imagen base de PHP
FROM php:8.1-fpm

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . /var/www/html

# Instalar dependencias de Composer
RUN composer install --no-interaction --no-scripts --no-plugins --prefer-dist --optimize-autoloader

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto 9000 para PHP-FPM
EXPOSE 9000

# Comando de inicio para PHP-FPM
CMD ["php-fpm"]
