# Usar la imagen base de PHP 8.1 con FPM (FastCGI Process Manager)
FROM php:8.1-fpm

# Instalar dependencias del sistema y extensiones de PHP requeridas por Laravel y Composer
RUN apt-get update && apt-get install -y \
    nginx \
    libssl-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip && \
    docker-php-ext-install pdo_mysql mbstring zip

# Instalar extensión de OpenSSL para PHP
RUN apt-get install -y openssl

# Instalar extensión de MongoDB para PHP
RUN pecl install mongodb && \
    docker-php-ext-enable mongodb

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copiar los archivos de la aplicación al contenedor
COPY . .

# Copiar el archivo .env.example como .env
RUN cp .env.example .env

# Generar la clave de la aplicación
RUN php artisan key:generate || true

# Establecer permisos adecuados
RUN chown -R www-data:www-data storage bootstrap/cache

# Instalar dependencias de Composer
RUN composer install --no-interaction --no-scripts --no-plugins --prefer-dist --ignore-platform-reqs --optimize-autoloader

# Copiar el archivo de configuración de Nginx
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# Exponer el puerto 80 del contenedor
EXPOSE 80

# Configurar ENTRYPOINT y CMD para ejecutar ambos servicios
ENTRYPOINT ["/bin/bash", "-c"]
CMD ["php-fpm && nginx -g 'daemon off;'"]
