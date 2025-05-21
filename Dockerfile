FROM php:8.3-fpm

# Instala dependências
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
    git unzip curl \
    && docker-php-ext-install \
        bz2 intl iconv bcmath opcache calendar mbstring pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia tudo (opcional — pode mover para docker-compose bind)
# COPY . .

EXPOSE 9000

CMD ["php-fpm"]
