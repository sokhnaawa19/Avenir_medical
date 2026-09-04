#!/bin/sh
set -e

echo "Migration de la base SQLite..."
php artisan migrate --force

echo "Remplissage des données de démo (seeders)..."
php artisan db:seed --force

exec /start.sh
