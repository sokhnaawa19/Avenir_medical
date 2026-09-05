#!/bin/sh
#
# Demarrage du conteneur sur Render.
#
# Les dependances Composer sont desormais installees pendant la construction
# de l'image (voir le Dockerfile), pas ici : le reveil du service apres une
# mise en veille passe ainsi de plus d'une minute a quelques secondes.
#
set -e

echo "→ Migrations"
php artisan migrate --force

echo "→ Lien vers les fichiers envoyes depuis l'administration"
php artisan storage:link || true

# ---------------------------------------------------------------------------
# Filet de securite.
#
# Le contenu du site vit desormais dans la base Postgres, qui est persistante :
# les seeders n'ont plus a se relancer a chaque demarrage. On les garde
# uniquement pour le cas ou la base serait vide (base recreee, premier
# deploiement), afin que le site ne sorte jamais completement nu.
# ---------------------------------------------------------------------------

BASE_VIDE=$(php artisan tinker --execute="echo DB::table('settings')->count() > 0 ? 'non' : 'oui';" 2>/dev/null | tail -1)

case "$BASE_VIDE" in
    *oui*)
        echo "→ Base vide : remplissage initial"
        php artisan db:seed --force
        ;;
    *)
        echo "→ Base deja remplie : seeders ignores"
        ;;
esac

echo "→ Mise en cache de la configuration, des routes et des vues"
php artisan optimize

exec /start.sh
