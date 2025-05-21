#!/bin/bash

cd /var/www/html

# Instala dependências se não existir o autoload
if [ ! -f vendor/autoload.php ]; then
    composer install
fi

# Gera APP_KEY se .env existir e chave não estiver setada
if [ -f .env ] && ! grep -q 'APP_KEY=base64' .env; then
    php artisan key:generate
fi

# Gera chave do JWT se a lib estiver instalada
if grep -q 'tymon/jwt-auth' composer.lock; then
    php artisan jwt:secret --force
fi

# Permissões (ajuste se necessário)
chmod -R 775 storage bootstrap/cache

# Inicia o PHP-FPM (modo padrão do container php-fpm)
php-fpm
