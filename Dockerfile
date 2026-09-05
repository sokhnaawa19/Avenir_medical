FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Configuration de l'image
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# Config Laravel
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Base de données PostgreSQL persistante (fournie par Render)
ENV DB_CONNECTION=pgsql

# Les dépendances sont installées ICI, pendant la construction de l'image,
# et non plus à chaque démarrage. Le réveil du service après une mise en
# veille passe ainsi de plus d'une minute à quelques secondes — ce qui
# compte quand un client teste le site sur plusieurs jours.
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Migrations au démarrage ; les seeders ne se relancent que si la base est vide.
RUN chmod +x docker-entrypoint.sh
CMD ["./docker-entrypoint.sh"]
