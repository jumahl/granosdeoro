# Usa una imagen base ligera
FROM php:8.3.7-fpm-alpine

# Actualiza y agrega paquetes necesarios en una sola capa
RUN apk --no-cache add \
    linux-headers \
    bash \
    git \
    sudo \
    openssh \
    libxml2-dev \
    oniguruma-dev \
    autoconf \
    gcc \
    g++ \
    make \
    npm \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    ssmtp \
    icu-dev

# Instala extensiones de PHP en una sola capa
RUN pecl channel-update pecl.php.net && \
    pecl install pcov swoole && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install mbstring xml pcntl gd zip sockets pdo pdo_mysql bcmath soap intl && \
    docker-php-ext-enable mbstring xml gd zip pcov pcntl sockets bcmath pdo pdo_mysql soap swoole

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copia archivos de aplicación y ejecuta composer en una sola capa
COPY . /app
RUN composer install --no-dev --optimize-autoloader

# Copia el archivo de entorno para producción
COPY .env.example .env
RUN mkdir -p /app/storage/logs

# Exponer el puerto
EXPOSE 8000

# Comando para ejecutar la 

CMD ["sh", "-c", "php artisan key:generate && php artisan migrate --force && php artisan make:seeder DatabaseSeeder && php artisan serve --host=0.0.0.0 --port=8000"]

