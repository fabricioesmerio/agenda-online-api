#!/bin/bash

domains=(bugzilla.online)
email="fabricio1esmeriol@gmail.com"  # Seu e-mail
staging=0  # 1 para ambiente de testes (sem gerar certificado válido)

mkdir -p ./certbot/www
mkdir -p ./certbot/conf

args=""
for domain in "${domains[@]}"; do
  args="$args -d $domain"
done

email_arg="--email $email"
if [ $staging != "0" ]; then
  staging_arg="--staging"
else
  staging_arg=""
fi

docker compose run --rm --entrypoint "\
  certbot certonly --webroot -w /var/www/public \
  $args \
  $email_arg \
  $staging_arg \
  --rsa-key-size 4096 \
  --agree-tos \
  --force-renewal" certbot
