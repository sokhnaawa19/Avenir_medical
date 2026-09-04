# Mise en production

Ce document réunit trois choses : les corrections d'affichage sur téléphone, la
simplification du paiement, et ce qu'il faut faire pour mettre le site en ligne.

---

## 1. Les corrections sur téléphone

### La page de commande était illisible

La grille du panier et de la page de commande portait ses colonnes en style
écrit directement dans le HTML : `style="grid-template-columns:1.5fr 1fr"`.
Un style écrit ainsi l'emporte toujours sur les règles responsives du fichier
CSS. Résultat : sur un écran de 430 pixels, le formulaire et le récapitulatif
restaient côte à côte, chacun dans une colonne d'environ 150 pixels — d'où le
« Total2 250 FCFA » et le « LivraisonÀ convenir » de votre capture.

La grille est maintenant une vraie classe, `shop-layout`, qui passe à une seule
colonne sous 900 pixels. Le récapitulatif remonte alors **au-dessus** du
formulaire : le client voit ce qu'il paie avant de saisir ses coordonnées.

### Les autres réglages appliqués sous 600 pixels

| Correction | Effet |
|---|---|
| Marges latérales ramenées de 44 à 16 pixels | ~50 pixels de largeur utile regagnés |
| Bande illustrée des cartes de domaine : 220 → 150 pixels | Moins de vide à faire défiler sur la page « Nos domaines » |
| Boutons d'appel à l'action en pleine largeur | Cibles plus faciles à toucher |
| Libellé et montant séparés dans le récapitulatif | Plus de textes collés |
| Choix du paiement et sélecteur de quantité en pleine largeur | Saisie plus confortable au pouce |
| `img, video, iframe { max-width:100% }` | Plus aucun débordement horizontal possible |

Sur votre première capture, le grand vide sous l'en-tête venait de cette bande
illustrée : 220 pixels de dégradé pour une simple icône, sur chaque carte de
domaine. Si vous ajoutez une photo à chaque domaine depuis l'administration,
cette bande reprend tout son sens.

---

## 2. Le paiement

Wave, Orange Money et le virement bancaire ont été retirés : **seul le paiement
à la livraison** est proposé. La modification porte sur trois points.

- La valeur par défaut dans `config/settings.php`.
- Une migration qui met à jour le réglage déjà enregistré en base.
- La page de commande, qui n'affiche plus une liste de choix quand il n'y a
  qu'un seul moyen de paiement : elle annonce simplement « Paiement à la
  livraison » et transmet la valeur.

Pour en rajouter un jour : **Administration → Réglages → Boutique**, un moyen de
paiement par ligne. La page de commande repasse alors toute seule en liste de
choix.

---

## 3. Ce qui a été allégé

### Les fichiers envoyés : 103 Mo → 25 Mo

| Fichier | Avant | Après |
|---|---:|---:|
| Trois vidéos (le même film, en trois copies) | 33 Mo chacune | 7,2 Mo chacune |
| Trois photos de fond en JPEG 7952 × 5304 | 1,6 à 1,9 Mo | 111 à 143 Ko |

Les vidéos ont été réencodées en 1280 pixels de large avec l'index placé en
tête de fichier, ce qui permet au navigateur de démarrer la lecture sans
télécharger tout le fichier. Les photos ont été ramenées à 1600 pixels de large.
**Les noms de fichiers n'ont pas changé** : rien à modifier en base.

Sur une connexion mobile sénégalaise, la page qui portait la vidéo demandait
33 Mo au visiteur. Elle en demande 7.

> Le même film est stocké trois fois, sous trois noms, parce qu'il a été envoyé
> depuis trois écrans d'administration différents. Ces 14 Mo en trop pourraient
> être récupérés, mais il faudrait modifier les trois réglages qui pointent
> dessus : à faire à la main depuis l'administration si vous le souhaitez.

### Les fichiers supprimés

- `storage/framework/{cache,sessions,views}` : caches et **sessions de
  visiteurs** compilés sur votre poste, sans intérêt sur le serveur.
- `storage/logs/*.log` et `.phpunit.result.cache`.
- `database/seeders/ProductSeeder.php` et `CategorySeeder.php` : ils
  recréaient les douze produits de démonstration au prochain `db:seed`.
- Les trois notes de mise à jour précédentes sont rangées dans `docs/`.

### Ce qui n'a volontairement pas été touché

Le dossier `vendor` contient encore les outils de développement (PHPUnit,
Faker, Mockery). Les retirer à la main casse le chargement automatique des
classes ; c'est `composer install --no-dev` qui doit le faire, sur le serveur —
c'est la première ligne de `deploy.sh`.

Les anciennes photos de produits supprimés sont encore dans
`storage/app/public/produits/`. Pour les nettoyer :
`php artisan boutique:vider --avec-images` avant de réimporter le catalogue.

---

## 4. Mettre en ligne

```bash
cp .env.production.example .env      # puis renseignez la base et le SMTP
php artisan key:generate
bash deploy.sh
```

`deploy.sh` installe les dépendances sans les outils de développement, applique
les migrations, crée le lien vers les fichiers envoyés, met en cache la
configuration, les routes et les vues, et ajuste les droits d'écriture.

### Les trois points qui comptent vraiment

**La racine du domaine doit pointer sur `public/`**, jamais sur la racine du
projet — sinon `.env`, avec ses mots de passe, devient téléchargeable.

**`APP_DEBUG=false`.** En cas d'erreur, `true` affiche le code source, les
requêtes SQL et le contenu de `.env` au visiteur.

**Le SMTP doit être renseigné.** Avec `MAIL_MAILER=log`, les confirmations de
commande sont écrites dans un fichier au lieu d'être envoyées : le client ne
reçoit rien.

### À chaque mise à jour du site

Relancez `bash deploy.sh`. Si vous modifiez seulement du CSS ou du JavaScript,
`php artisan site:minify` suffit — puis videz le cache des vues avec
`php artisan view:clear`.

---

## 5. Ce qui était déjà en place

L'audit a montré que l'essentiel des bonnes pratiques était déjà appliqué, et
n'appelait aucune correction :

- `public/.htaccess` : compression Brotli et gzip, mise en cache d'un an des
  fichiers statiques, en-têtes de sécurité ;
- images avec `loading="lazy"`, `decoding="async"`, largeur et hauteur
  déclarées — ce qui évite les sauts de mise en page pendant le chargement ;
- image principale de l'accueil préchargée en `fetchpriority="high"` ;
- polices chargées sans bloquer l'affichage du texte ;
- JavaScript en `defer` ;
- vidéos YouTube et Vimeo chargées seulement au clic.

Côté serveur, les pages restent entre 2 et 5 requêtes SQL, l'accueil monte à 14,
et le HTML pèse de 13 à 69 Ko. Aucune requête en cascade n'a été trouvée.

---

## 6. Les vidéos envoyées après la mise en ligne

Les images envoyées depuis l'administration étaient déjà redimensionnées et
converties en WebP automatiquement. **Les vidéos, non** : elles étaient
enregistrées telles quelles. Une nouvelle vidéo de 40 Mo aurait donc annulé
tout le travail d'allègement.

C'est corrigé. Une vidéo de plus de 3 Mo est maintenant réencodée à l'envoi :
1280 pixels de large, index placé en tête de fichier pour que la lecture
démarre sans attendre. Mesuré sur un fichier de test de 71,5 Mo : **5,5 Mo en
sortie, 35 secondes de traitement**.

Trois garde-fous :

- une vidéo déjà légère n'est pas retouchée — inutile de dégrader l'image pour
  quelques kilo-octets ;
- si le réencodage produit un fichier plus lourd que l'original, l'original est
  conservé ;
- **si ffmpeg n'est pas installé sur le serveur, l'envoi fonctionne quand
  même** : la vidéo est simplement enregistrée telle quelle et une ligne est
  écrite dans le journal.

### Vérifier que ffmpeg est présent

```bash
ffmpeg -version
```

Si la commande répond, il n'y a rien à faire. Si elle ne répond pas, la
compression automatique ne s'appliquera pas : demandez son installation à votre
hébergeur, ou indiquez son chemin dans `.env` :

```
FFMPEG_PATH=/usr/local/bin/ffmpeg
FFPROBE_PATH=/usr/local/bin/ffprobe
```

### Rattraper des vidéos déjà en ligne

```bash
php artisan videos:compresser              # montre ce qui serait fait
php artisan videos:compresser --appliquer  # compresse pour de vrai
```

La commande parcourt les fichiers envoyés, ignore ceux qui sont déjà légers et
**garde les noms de fichiers** : rien à modifier en base. Utile si ffmpeg a été
installé après coup, ou si une vidéo est passée pendant une indisponibilité.

Sur un hébergement mutualisé sans ffmpeg, la solution de repli reste le lien
YouTube : le champ vidéo l'accepte, et la vidéo n'est alors chargée qu'au clic.

---

## Fichiers

### Nouveaux

| Fichier | Rôle |
|---|---|
| `.env.production.example` | Le modèle de configuration pour le serveur |
| `deploy.sh` | Installation et mise à jour en une commande |
| `database/migrations/2026_01_07_000100_keep_cash_on_delivery_only.php` | Ne garde que le paiement à la livraison |
| `docs/` | Les notes des mises à jour précédentes |
| `app/Services/VideoOptimizer.php` | Allège les vidéos à l'envoi |
| `app/Console/Commands/CompressVideos.php` | La commande `videos:compresser` |
| `config/media.php` | Chemin de ffmpeg et ffprobe |

### Modifiés

| Fichier | Rôle |
|---|---|
| `public/assets/css/style.css` + `.min.css` | Correctifs téléphone, classe `shop-layout`, paiement unique |
| `resources/views/shop/checkout.blade.php` | Grille responsive, paiement unique |
| `resources/views/shop/cart.blade.php` | Grille responsive |
| `config/settings.php` | Paiement à la livraison par défaut |
| `app/Http/Controllers/Concerns/HandlesMediaUploads.php` | Passe les vidéos par le nouveau service |
| `resources/views/admin/partials/video-field.blade.php` | Annonce la compression automatique |
| `storage/app/public/**` | Vidéos et photos recompressées, mêmes noms |

---

## Vérifié

Migration, seeders, puis parcours complet sur une base réelle : les huit pages
publiques répondent en 200, le tunnel de commande va jusqu'au numéro
`AM-2026-0001`, la valeur transmise est bien « Paiement à la livraison », et il
ne reste aucune trace de Wave ou d'Orange Money dans la page. La suite de tests
donne 27 passés et 3 échecs — les mêmes trois qu'avant cette mise à jour, dus à
la route `/domaines/{slug}` supprimée précédemment.

La compression des vidéos a été mesurée dans les deux cas de figure : avec
ffmpeg (71,5 Mo → 5,5 Mo, lecture immédiate, 1280 × 720) et sans ffmpeg
(l'envoi passe, le fichier est conservé intact). Une vidéo déjà allégée
renvoyée une seconde fois n'est pas réencodée.
