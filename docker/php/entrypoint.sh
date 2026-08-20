#!/bin/sh
set -e

if [ ! -d "/var/www/vendor" ]; then
    echo "📦 Instalando dependências do Composer..."
    composer install
fi

exec "$@"
