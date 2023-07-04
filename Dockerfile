# Usar la imagen base de PHP 8.1
FROM php:8.1

# Establecer el directorio de trabajo en /var/www
WORKDIR /var/www

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libonig-dev \
    libzip-dev \
    zip \
    unzip

# Instalar extensiones de PHP requeridas por Laravel
RUN docker-php-ext-install pdo_mysql mbstring zip

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
RUN composer install --no-interaction --no-scripts --no-plugins --prefer-dist --optimize-autoloader

# Exponer el puerto 80 del contenedor
EXPOSE 80

# Iniciar el servidor web de PHP
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
