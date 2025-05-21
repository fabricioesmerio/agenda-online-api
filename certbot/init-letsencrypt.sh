#!/bin/bash

domains=(bugzilla.online)
email="fabricio1esmeriol@gmail.com" # Troque pelo seu e-mail
staging=0 # mude para 1 se quiser testar sem gerar certificado válido

mkdir -p "./certbot/www"
mkdir -p "./certbot/conf"

args="--webroot -w /var/www/public \"
for domain in "${domains[@]}"; do
  args="$args -d $domain"
done

email_arg="--email $email"
if [ $staging != "0" ]; then staging_arg="--staging"; fi

docker compose run --rm --entrypoint "\
  certbot certonly $args \
  $email_arg \
  $staging_arg \
  --rsa-key-size 4096 \
  --agree-tos \
  --force-renewal" certbot
