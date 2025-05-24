FROM php:8.2-fpm

# Instala dependências básicas
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /app

# Copia tudo
COPY . /app

# Ajusta permissões
RUN chown -R www-data:www-data /app
