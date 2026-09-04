#!/usr/bin/env bash
#
# Mise en ligne du site Avenir Médical.
#
# À lancer depuis la racine du projet, sur le serveur :
#     bash deploy.sh
#
# Le script s'arrête à la première erreur plutôt que de continuer sur une
# installation à moitié faite.
set -euo pipefail

echo "→ Dépendances (sans les outils de développement)"
composer install --no-dev --optimize-autoloader --no-interaction

echo "→ Base de données"
php artisan migrate --force

echo "→ Lien vers les fichiers envoyés depuis l'administration"
php artisan storage:link || true

echo "→ Mise en cache de la configuration, des routes et des vues"
php artisan optimize

echo "→ Droits d'écriture"
chmod -R ug+rw storage bootstrap/cache

echo
echo "Terminé."
echo "Vérifiez que .env contient APP_ENV=production et APP_DEBUG=false,"
echo "et que la racine web du domaine pointe bien sur le dossier public/."
