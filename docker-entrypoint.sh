#!/bin/sh
set -e

echo "Installation des dépendances Composer..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Migration de la base de données..."
php artisan migrate --force

echo "Création du lien de stockage public..."
php artisan storage:link || true

echo "Remplissage des données de démo (seeders)..."
php artisan db:seed --force

exec /start.sh
