FROM php:8.3-fpm

# Instalar dependências do sistema e extensões PHP usadas pelo Laravel
RUN apt-get update && apt-get install -y \
    libbz2-dev \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libsqlite3-dev \
    default-libmysqlclient-dev \
    unzip \
    curl \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd bz2 intl pdo_mysql zip mbstring xml bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar composer.json e composer.lock antes para otimizar build
COPY laravel/composer.json laravel/composer.lock ./

# Instalar dependências PHP com composer
RUN composer install --no-dev --optimize-autoloader

# Copiar todo o código Laravel para dentro do container
COPY laravel/ ./

# Ajustar permissões para storage e cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expor a porta do PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
