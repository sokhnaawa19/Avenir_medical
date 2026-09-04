FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Configuration de l'image (build/installation)
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

# Migrations + seeders relancés à chaque démarrage du conteneur
# (idempotent grâce à firstOrCreate — ne duplique rien si déjà peuplé)
RUN chmod +x docker-entrypoint.sh
CMD ["./docker-entrypoint.sh"]
