# AVENIR MEDICAL — site internet

Site vitrine + boutique en ligne + blog + espace d'administration.
Développé avec **Laravel 12** (PHP 8.2 minimum).

---

## 1. Ce que contient le site

### Côté visiteurs

| Page | Adresse |
|---|---|
| Accueil | `/` |
| Qui sommes-nous | `/entreprise` |
| Nos domaines | `/domaines` |
| Nos services | `/services` |
| Boutique | `/boutique` |
| Fiche produit | `/boutique/nom-du-produit` |
| Panier | `/panier` |
| Commande | `/commande` |
| Blog | `/blog` |
| Article | `/blog/titre-de-larticle` |
| Contact | `/contact` |
| Mon compte | `/compte` |
| Connexion / Inscription | `/connexion` · `/inscription` |

### Côté administration — `/admin`

* **Tableau de bord** : commandes, chiffre d'affaires, messages, stocks faibles
* **Commandes** : liste, détail, changement d'état (en attente → confirmée → livrée…)
* **Produits** et **Catégories** : ajout, modification, photo, prix, stock, mise en avant
* **Articles du blog** : rédaction, photo, publication ou brouillon
* **Domaines**, **Services**, **Valeurs** : le contenu des pages de présentation
* **Messages** reçus par le formulaire de contact
* **Comptes** : clients et administrateurs
* **Réglages du site** : logo, couleurs, textes, photos, coordonnées, réseaux sociaux,
  frais de livraison, moyens de paiement, référencement… **tout est modifiable sans toucher au code.**

---

## 2. Installation

### Ce qu'il faut sur le serveur

* PHP **8.2** ou plus, avec les extensions habituelles de Laravel
  (`pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd`)
* MySQL 8 / MariaDB 10.4 (ou SQLite pour un essai rapide)
* [Composer](https://getcomposer.org)

### Étapes

```bash
# 1. Installer les librairies
composer install --optimize-autoloader --no-dev

# 2. Créer le fichier de configuration
cp .env.example .env
php artisan key:generate

# 3. Renseigner la base de données dans le fichier .env
#    DB_DATABASE=avenir_medical
#    DB_USERNAME=...
#    DB_PASSWORD=...

# 4. Créer les tables et les données de départ
php artisan migrate --seed

# 5. Activer le dossier des images envoyées depuis l'administration
php artisan storage:link
```

Le site est prêt. En local :

```bash
php artisan serve
```

puis ouvrez http://localhost:8000

### Compte administrateur créé automatiquement

* **Adresse** : http://votre-site/admin
* **Email** : `admin@avenir-medic.com`
* **Mot de passe** : `AvenirMedical2026`

> ⚠️ **À faire dès la première connexion** : allez dans *Comptes* → modifiez ce compte
> et changez l'email et le mot de passe.

---

## 3. Mise en ligne (hébergement)

1. Envoyez tous les fichiers sur le serveur.
2. Faites pointer le domaine vers le dossier **`public/`** (et non la racine du projet).
3. Dans `.env` :
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://avenir-medic.com
   ```
4. Donnez les droits d'écriture aux dossiers `storage/` et `bootstrap/cache/`.
5. Optimisez le site :
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

Pour l'envoi des emails (commandes et messages), renseignez les lignes `MAIL_…` du fichier
`.env` avec les informations SMTP de l'hébergeur.

---

## 4. Organisation du code

```
app/
├── Http/
│   ├── Controllers/        Le cerveau de chaque page
│   │   ├── Admin/          L'administration
│   │   ├── Auth/           Connexion, inscription, mot de passe oublié
│   │   └── Account/        Espace client
│   ├── Middleware/         Contrôle d'accès à l'administration
│   └── Requests/           Vérification des formulaires
├── Models/                 Les tables : Produit, Commande, Article…
├── Services/               Panier et réglages du site
└── Support/helpers.php     Fonctions pratiques : setting(), money(), cart()

config/settings.php         ⭐ La liste de TOUS les réglages modifiables
database/migrations/        La structure de la base de données
database/seeders/           Les données de départ
resources/views/            Les pages (Blade)
public/assets/              CSS, JavaScript et images du thème
lang/fr/                    Messages d'erreur en français
tests/Feature/              Tests automatisés
```

---

## 5. Ajouter un nouveau réglage

Tout se passe dans **`config/settings.php`**. Exemple, pour ajouter un numéro de fax :

```php
'contact' => [
    'fields' => [
        // …
        'fax' => ['label' => 'Numéro de fax', 'type' => 'tel'],
    ],
],
```

Le champ apparaît immédiatement dans l'administration, et s'utilise dans les pages avec :

```blade
{{ setting('fax') }}
```

Types disponibles : `text`, `textarea`, `email`, `tel`, `url`, `number`, `color`,
`image`, `boolean`.

---

## 6. Tests automatisés

```bash
php artisan test
```

Les tests vérifient que les pages publiques répondent, qu'une commande passe
correctement de bout en bout, et que l'administration est bien protégée.

---

## 7. Bon à savoir

* Les images envoyées depuis l'administration sont enregistrées dans
  `storage/app/public/` et rendues visibles par `php artisan storage:link`.
* Les réglages sont gardés en mémoire (cache) : ils se rafraîchissent tout seuls
  à chaque enregistrement. En cas de doute : `php artisan cache:clear`.
* Le paiement en ligne (Wave, Orange Money) n'est pas encore branché : les commandes
  arrivent dans l'administration et sont confirmées par téléphone. L'ajout d'une
  passerelle de paiement (PayDunya, PayTech…) pourra se faire dans un second temps.


---

## 8. Petits problèmes courants

### Le site s'affiche sans design (texte brut, liens bleus soulignés)

Le CSS n'a pas pu être chargé. Vérifiez la ligne `APP_URL` du fichier `.env` :
elle doit correspondre **exactement** à l'adresse que vous utilisez dans le navigateur.

* En local : `APP_URL=http://localhost:8000`
* En ligne : `APP_URL=https://avenir-medic.com`

Puis videz le cache :

```bash
php artisan config:clear
php artisan cache:clear
```

### Les photos envoyées depuis l'administration ne s'affichent pas

Le raccourci vers le dossier des images n'a pas été créé :

```bash
php artisan storage:link
```

### Une page blanche s'affiche

Mettez temporairement `APP_DEBUG=true` dans `.env`, rechargez la page :
le message d'erreur exact s'affichera. Les erreurs sont aussi enregistrées dans
`storage/logs/laravel.log`.


---

## 9. Les vidéos

Le site accepte les vidéos **YouTube**, **Vimeo** ou un **fichier MP4**.

| Où | Comment faire |
|---|---|
| Page d'accueil | *Réglages du site* → onglet **🎬 Vidéos** : cochez « Afficher la vidéo », collez le lien YouTube, ajoutez une image d'aperçu |
| Un article de blog | *Articles* → dans le formulaire, champ **Lien vidéo** |
| Une fiche produit | *Produits* → champ **Lien vidéo** (une démonstration de l'équipement, par exemple) |
| Un domaine | *Domaines* → champ **Lien vidéo** |

Dans chaque formulaire, deux possibilités : **coller un lien** ou **envoyer un fichier MP4**
depuis votre ordinateur. Les deux fonctionnent.

### Si vous envoyez vos propres fichiers vidéo

1. **Compressez la vidéo avant l'envoi.** Un logiciel gratuit comme
   [HandBrake](https://handbrake.fr) fait cela en deux clics :
   choisissez le réglage *Fast 1080p30* (ou *720p30* pour un fichier encore plus léger).
   Une vidéo de 500 Mo descend souvent à 20–40 Mo sans perte visible.
2. **Autorisez les gros fichiers sur le serveur.** Par défaut, PHP limite les envois à 2 Mo.
   Dans le fichier `php.ini` (ou dans l'onglet « PHP » de votre hébergeur) :

   ```ini
   upload_max_filesize = 128M
   post_max_size = 128M
   max_execution_time = 300
   ```

   La limite réelle de votre serveur est affichée directement sous le champ d'envoi.
3. **Ajoutez toujours une photo au contenu** : elle sert d'image d'aperçu avant la lecture.

La vidéo n'est **jamais téléchargée tant que le visiteur ne clique pas sur lecture**
(`preload="none"`), donc la vitesse du site n'est pas pénalisée.

### Et si vous voulez éviter d'héberger les vidéos

Créer une chaîne YouTube est gratuit et prend 2 minutes avec une adresse Gmail. Vous pouvez
publier les vidéos en **« non répertoriée »** : elles ne sont visibles que sur votre site,
pas dans les recherches YouTube. C'est la solution la plus légère pour l'hébergement,
mais l'envoi de fichiers reste parfaitement utilisable.

---

## 10. Performance du site

Le site est préparé pour obtenir de bons scores sur PageSpeed Insights, GTmetrix
et WebPageTest. Ce qui est déjà en place :

* **Images allégées automatiquement** : toute photo envoyée depuis l'administration
  est redimensionnée (1600 px maximum) et convertie en **WebP**. Une photo de 5 Mo
  devient un fichier d'environ 100 Ko.
* **Chargement différé** : les images en bas de page ne se chargent que lorsque le
  visiteur descend (`loading="lazy"`), et leurs dimensions sont indiquées pour éviter
  les sauts d'affichage (bon score CLS).
* **Image principale prioritaire** : la grande photo d'accueil est préchargée
  (bon score LCP).
* **Vidéos non bloquantes** : YouTube n'est chargé qu'au clic.
* **Polices non bloquantes** : le texte s'affiche immédiatement.
* **CSS et JavaScript compressés** et mis en cache un an dans le navigateur.
* **Contenus de l'accueil mis en cache** côté serveur (vidés automatiquement dès
  qu'un contenu est modifié).
* **Compression gzip / brotli** activée dans le fichier `public/.htaccess`.

### À faire lors de la mise en ligne

```bash
php artisan optimize        # config, routes et vues en cache
php artisan site:minify     # recompresse le CSS et le JavaScript
```

> Relancez `php artisan site:minify` **à chaque fois que vous modifiez**
> `public/assets/css/style.css` ou `public/assets/js/script.js`.

### Les derniers points dépendent de l'hébergeur

* Activez **HTTPS** et **HTTP/2** (souvent une simple case à cocher).
* Choisissez un hébergeur avec **PHP 8.3+** et **OPcache** activé.
* Pour un site consulté depuis plusieurs pays, un **CDN** (Cloudflare, gratuit)
  améliore encore les temps de chargement.


---

## 11. Nouveautés (mise à jour)

### 💬 L'assistant de discussion

Une bulle « Puis-je vous aider ? » apparaît en bas à droite après quelques secondes.
Le visiteur clique sur une question, une réponse s'affiche, et il peut appeler,
écrire sur WhatsApp ou aller sur le formulaire de contact.

Tout se règle dans **Réglages du site → 💬 Assistant de discussion** :
activer/désactiver, délai d'ouverture, message d'accueil, et les 4 questions/réponses.

> Il s'agit d'un assistant à réponses préparées, pas d'une intelligence artificielle :
> aucun abonnement, aucune donnée envoyée à l'extérieur, et il fonctionne instantanément.

### 🤝 Les partenaires

Nouvelle rubrique **Partenaires** dans l'administration : nom, logo, pays, site internet.

Les logos apparaissent automatiquement sur :

* la page d'accueil (uniquement ceux cochés « Afficher sur l'accueil ») ;
* la page **Services** ;
* la page **Qui sommes-nous** ;
* une page dédiée : `/partenaires`.

Sans logo, les initiales du partenaire s'affichent dans un carré coloré.
Le titre de la section se change dans *Réglages → 🤝 Partenaires*.

### 📜 L'histoire de l'entreprise

Nouvelle rubrique **Historique** : chaque étape a une année, un titre, un récit
et une photo facultative. Elles s'affichent sous forme de frise chronologique
sur la page « Qui sommes-nous ».

### 🖼️ Photos de fond des sections

Dans *Réglages → 📝 Titres des sections*, trois nouveaux champs :

* **Boutique — photo de fond de la section**
* **Blog — photo de fond de la section**
* **Partenaires — photo de fond**

La photo est assombrie automatiquement et les textes passent en blanc :
les sections de l'accueil deviennent beaucoup plus vivantes.

### 🎬 Vidéos — ce qui a été corrigé

1. **La lecture ne démarrait pas (cause principale).** Le serveur de test de Laravel
   (`php artisan serve`) ne sait pas envoyer un fichier « par morceaux » : le navigateur
   devait télécharger les 32 Mo avant de pouvoir lancer la lecture, et l'avance rapide
   ne fonctionnait pas. Les vidéos passent maintenant par une adresse dédiée
   (`/media/video/…`) qui gère cela correctement.
   *Une fois le site en ligne sur un vrai serveur (Apache ou Nginx), le fichier est
   envoyé directement, sans passer par PHP : c'est automatique.*
2. Les fichiers **.mov** (vidéos filmées avec un iPhone), **.m4v** et **.ogv**
   sont désormais acceptés, en plus de .mp4 et .webm.
3. Le formulaire affiche maintenant **la taille maximale réellement acceptée par
   votre serveur**, et vous prévient si elle est trop basse.
4. Si un lien vidéo n'est pas reconnu, un message d'avertissement s'affiche
   (visible par les administrateurs seulement) au lieu d'un cadre vide.

> ⚠️ **Important** : la commande `php artisan site:minify` comportait un défaut
> qui rendait le fichier JavaScript inutilisable — ce qui bloquait les vidéos,
> le menu mobile et l'assistant. C'est corrigé. Relancez la commande une fois.

### Limite d'envoi des vidéos

Par défaut, PHP n'accepte que **2 Mo** par fichier : c'est trop peu pour une vidéo.
Dans le fichier `php.ini` (ou l'onglet PHP de votre hébergeur) :

```ini
upload_max_filesize = 128M
post_max_size = 128M
max_execution_time = 300
memory_limit = 256M
```

Redémarrez ensuite le serveur. La limite réelle s'affiche sous le champ d'envoi
dans l'administration.

Et **compressez toujours vos vidéos avant l'envoi** avec [HandBrake](https://handbrake.fr)
(réglage *Fast 720p30*) : une vidéo de 500 Mo tombe à 20–40 Mo sans perte visible.


---

## 12. Les indicateurs de chargement (administration)

Pour savoir en permanence si le site est en train de travailler, l'administration
affiche désormais quatre retours visuels :

| Quand | Ce qui s'affiche |
|---|---|
| Clic sur un lien du menu | Une fine barre bleue progresse en haut de l'écran |
| Clic sur « Enregistrer » | Le bouton affiche une roue qui tourne et le texte « Enregistrement… » |
| Envoi d'une photo ou d'une vidéo | Un voile avec « Envoi du fichier en cours… », la taille du fichier et une barre animée |
| Choix d'un fichier | Aperçu immédiat de l'image, avec son nom et son poids |

Deux avantages en plus :

* **Le double clic est impossible** pendant un enregistrement : plus de risque de créer
  deux fois le même produit.
* **Le message vert de confirmation s'efface tout seul** au bout de 6 secondes.

Ces indicateurs sont dans `public/assets/js/admin.js` et la fin de
`public/assets/css/admin.css`. Ils ne sont **pas** chargés sur le site public :
la vitesse pour les visiteurs reste inchangée.

> Après toute modification de ces fichiers, relancez `php artisan site:minify`.


---

## 13. Les domaines d'intervention (nouvelle version)

Les domaines ne sont plus de simples images : chaque domaine a désormais **sa propre page**,
avec les produits et les marques qui lui correspondent.

### Comment rattacher un produit à un domaine

Dans *Produits → Modifier un produit*, deux nouvelles listes déroulantes :

* **Domaine d'intervention** — le produit apparaîtra automatiquement sur la page de ce domaine ;
* **Marque / fabricant** — la liste vient de la rubrique **Partenaires**.

> Pour qu'une marque apparaisse dans la liste, créez-la d'abord dans *Partenaires*
> (avec son logo). Elle servira à la fois pour la bande des partenaires et pour
> identifier les fabricants des produits.

### Ce que voient les visiteurs

**Page « Nos domaines »** — chaque domaine est présenté sur une large carte avec :
sa photo, son texte, le **nombre de produits disponibles**, et un **aperçu de 4 produits**
avec le nom de leur fabricant.

**Page d'un domaine** (`/domaines/imagerie-medicale`) :

1. présentation du domaine, avec sa vidéo si vous en avez ajouté une ;
2. deux chiffres clés : nombre de produits et nombre de marques ;
3. les **logos des fabricants** présents dans ce domaine ;
4. **tous les produits** du domaine, avec prix et bouton d'ajout au panier ;
5. un lien vers les autres domaines.

Les cartes produits et les fiches produits affichent aussi la marque,
et la fiche produit renvoie vers son domaine.


---

## 14. Confortable au quotidien (administration)

### La fenêtre de confirmation avant suppression

Un clic sur « Supprimer » ouvre désormais une **vraie fenêtre de confirmation**
aux couleurs du site, qui rappelle **le nom de l'élément concerné** :

> 🗑️ **Confirmer la suppression**
> « Tensiomètre électronique »
> Cette action est définitive et ne pourra pas être annulée.
> [ Annuler ] [ Oui, supprimer ]

La touche **Échap** et un clic à côté de la fenêtre annulent l'opération.
Rien n'est supprimé tant que « Oui, supprimer » n'a pas été cliqué.

### Le retour sur la bonne page

Avant, après avoir modifié ou supprimé un produit depuis la page 4,
on revenait à la page 1 — très pénible avec un catalogue important.

Désormais, le site **mémorise la page de liste consultée**, avec sa
pagination **et ses filtres**. Après un enregistrement ou une suppression,
on revient exactement où l'on était : page 4, filtre « Consommables », recherche en cours.

Cela fonctionne pour toutes les rubriques : produits, catégories, articles,
domaines, services, valeurs, partenaires, historique, commandes, messages et comptes.

> Techniquement : le middleware `RememberAdminList` retient la dernière page de
> liste visitée, et la fonction `admin_back()` l'utilise pour la redirection.

Si la page consultée se retrouve vide (dernier élément supprimé), un message
propose de revenir à la première page.


---

## 15. Références, savoir-faire et groupe

Cinq nouvelles rubriques ont été ajoutées dans l'administration.

### 🏥 Références — les établissements équipés

*Administration → Références.* Pour chaque hôpital, clinique ou centre de santé :
nom, type, ville, année, logo, photo, description et **liste des équipements installés**
(un par ligne).

Deux cases changent l'affichage :

* **Réalisation phare** — l'établissement a été équipé intégralement. Il est alors
  présenté en grand, tout en haut de la page, avec la liste de ses équipements.
* **Afficher sur la page d'accueil** — son logo rejoint la bande « Ils nous font confiance ».

> Le mot « client » n'apparaît jamais sur le site : on parle de **références**,
> d'**établissements accompagnés** et de structures « qui nous font confiance ».

### ⭐ Partenariats exclusifs

*Administration → Partenaires.* Deux nouveaux champs sur chaque partenaire :

* la case **Partenariat exclusif** ;
* la **portée de l'exclusivité** (« Afrique de l'Ouest », « Sénégal »…).

Les partenaires exclusifs reçoivent alors un bandeau doré **⭐ Exclusivité Afrique de l'Ouest**,
et apparaissent dans une section dédiée sur l'accueil et sur la page Savoir-faire.

### 🎓 Formations — les techniciens formés à l'étranger

*Administration → Formations.* Intitulé, organisme formateur, pays, ville, année,
nombre de techniciens formés, photo et description.
Visibles sur la page **Savoir-faire** et sur la page **Qui sommes-nous**.

### 📷 Galerie photos

*Administration → Galerie photos.* Chaque photo a un titre, un **album**
(Événements, Installations, Formations, Salons…), une légende et une date.
Les albums deviennent automatiquement des filtres sur la page `/galerie`.
Un clic sur une photo l'agrandit ; les flèches du clavier font défiler.

### 📍 Agences — le développement

*Administration → Agences.* Chaque agence a une **situation** :
*Agence ouverte*, *Ouverture en cours* ou *Projet à venir*, avec l'année prévue.

Les agences du Sénégal et celles de la sous-région sont présentées séparément,
avec des compteurs. C'est ce qui met en valeur l'ambition d'expansion.

### 🏢 Le groupe

*Administration → Le groupe.* AVENIR MEDICAL est présentée comme **maison mère**,
et chaque entreprise du groupe a son nom, sa signature, son activité, son logo,
sa couleur et son site internet.

Page dédiée : `/le-groupe`, avec un aperçu sur l'accueil et sur la page Qui sommes-nous.

### Les textes de ces sections

Tous les titres sont modifiables dans *Réglages du site*, onglets
**🏥 Références & savoir-faire** et **🌍 Développement & groupe**.

### Nouvelles pages du site

| Page | Adresse |
|---|---|
| Références | `/references` |
| Savoir-faire (exclusivités + formations) | `/expertise` |
| Galerie photos | `/galerie` |
| Le groupe | `/le-groupe` |

« Références » est dans le menu principal ; les trois autres sont accessibles
depuis le pied de page et des boutons placés sur les pages concernées.


---

## 16. Organisation du site (réorganisation)

Le site comptait des contenus répétés d'une page à l'autre. Tout a été regroupé :
**chaque information n'apparaît plus qu'à un seul endroit**, avec des renvois entre les pages.

### Les 8 pages du menu

| Page | Ce qu'elle contient |
|---|---|
| **Accueil** | Un aperçu de chaque sujet, une seule fois, avec un bouton vers la page complète |
| **L'entreprise** | Présentation · valeurs · histoire · **le groupe** · **notre développement** |
| **Nos domaines** | Les 12 domaines, leurs produits et leurs marques |
| **Services & expertise** | Les 3 services · **partenariats exclusifs** · **formations des techniciens** · tous les partenaires |
| **Références** | Établissements équipés · aperçu de la galerie |
| **Boutique** · **Blog** · **Contact** | Inchangés |

La **galerie photos** garde sa page complète (`/galerie`), accessible depuis l'accueil,
la page Références et le pied de page.

### Pages fusionnées

| Ancienne page | Est devenue |
|---|---|
| `/partenaires` | une section de **Services & expertise** |
| `/expertise` | fusionnée dans **Services & expertise** |
| `/le-groupe` | une section de **L'entreprise** |

> Les anciennes adresses redirigent automatiquement (redirection permanente) :
> aucun lien partagé ou référencé sur Google n'est cassé.

### Ce qui a été dédoublonné

* Les **valeurs** n'apparaissent plus que sur *L'entreprise*.
* La **liste des partenaires** n'apparaît plus que sur *Services & expertise*
  (l'accueil ne montre que les partenariats **exclusifs**).
* Les **formations** ne sont plus répétées sur *L'entreprise*.
* Le **groupe** est détaillé sur *L'entreprise* et seulement évoqué ailleurs.

### ⚠️ Ambition régionale : ne jamais annoncer ce qui n'existe pas

La section « Notre développement » sépare désormais strictement :

* **Aujourd'hui** — uniquement les agences réellement ouvertes ou en cours d'ouverture ;
* **Notre ambition** — les projets, dans un encadré en pointillés, avec la mention
  *« Ces implantations sont des projets de développement, à des stades d'avancement différents. »*

Les compteurs ne totalisent plus jamais les projets avec les agences existantes.
Sur l'accueil, on lit par exemple « **1 agence ouverte** » et « **+5 implantations prévues
d'ici 2029** » — deux chiffres distincts, impossible à confondre.

> C'est ce qui a fait disparaître la mention trompeuse des « 8 pôles régionaux ».


---

## 17. Harmonie visuelle et fil conducteur

### Les boutons : un système unique

Les styles de boutons étaient définis à trois endroits différents du fichier CSS,
ce qui créait des tailles et des couleurs incohérentes. Tout est désormais regroupé
**à la fin de `style.css`**, dans une section unique qui fait autorité.

Il n'existe plus que **deux boutons** à utiliser dans le code :

| Classe | Rôle |
|---|---|
| `btn btn-primary` | L'action principale — une seule par section |
| `btn btn-line` | L'action secondaire |

Sur les sections foncées, **l'inversion est automatique** : le bouton principal
devient blanc, le secondaire prend un contour clair. Plus besoin d'y penser en écrivant
le code, et l'apparence reste cohérente partout.

Tous les boutons ont la même hauteur (50 px), le même arrondi et le même comportement au survol.
Pour aligner deux boutons, utilisez `<div class="btn-row">` (ils passent l'un sous l'autre
sur téléphone).

### L'ordre des sections de l'accueil

Les sections s'enchaînent dans un ordre logique, sans numérotation :
chaque affirmation est suivie de ce qui la justifie.

| Section | Ce qu'elle dit au visiteur |
|---|---|
| Qui sommes-nous | Voici qui nous sommes |
| Nos domaines | Voici ce que nous équipons |
| Nos services | Voici ce que nous faisons |
| Représentation exclusive | Voici ce qui nous distingue |
| Formation continue | Voici comment nous le garantissons |
| Ils nous font confiance | Voici la preuve |
| En images | Voici les coulisses |
| Boutique | Voici ce que vous pouvez commander |
| Le groupe | Voici l'ensemble |
| Notre ambition | Voici où nous allons |
| Blog | Voici notre actualité |

### Le rythme des fonds

Deux sections foncées ne se suivent plus jamais. Les fonds alternent :
foncé → gris clair → crème → blanc → foncé…

En particulier, la section **Partenariats exclusifs** est en **gris clair** et non
en bleu foncé : elle suit le bandeau des services, qui est déjà foncé.
La mise en valeur vient des cartes blanches en relief et du ruban ⭐, pas d'une
couleur de fond particulière — le site garde ainsi une seule palette.

Classes disponibles pour composer : `band-white`, `band-soft`, `band-tint`, `band-dark`.

### 📷 Photos des formations

*Administration → Formations.* Chaque formation accepte désormais **jusqu'à 12 photos**
(les techniciens sur place, les manipulations, la remise des certificats…).

* Sélectionnez plusieurs photos d'un coup avec le champ « Photos de la formation ».
* Les photos déjà en ligne s'affichent en vignettes, avec un bouton **×** pour les retirer.
* Sur le site, les 4 premières apparaissent sous la formation ; les suivantes sont
  comptées (« +3 »). Un clic les agrandit, les flèches du clavier font défiler.


---

## 18. Refonte éditoriale de la page d'accueil

### 🧭 Le parcours d'accompagnement (nouveau)

*Administration → Parcours client.* Cinq étapes modifiables, qui expliquent
comment un besoin devient une solution :

**Comprendre → Conseiller → Installer → Former → Maintenir**

Chaque étape a un nom, une icône, une phrase courte et un détail.
C'est cette section qui relie entre eux les services, les formations et le SAV :
sans elle, ces trois sujets semblaient indépendants.

Les titres et la phrase de conclusion se modifient dans
*Réglages → 🧭 Parcours d'accompagnement*. La phrase mise en valeur sous les étapes
peut être vidée pour la masquer.

### L'ordre des sections

| Section | Ce qu'elle répond |
|---|---|
| Accueil (hero) | Qui peut répondre à mon besoin ? |
| Qui sommes-nous | Qui est AVENIR MEDICAL ? |
| Nos domaines | Interviennent-ils dans mon domaine ? |
| **Notre approche** | Comment transforment-ils mon besoin en solution ? |
| Vidéo | À quoi ressemble leur travail ? |
| Représentation exclusive | Qu'ont-ils que les autres n'ont pas ? |
| Formation continue | Maîtrisent-ils vraiment ces équipements ? |
| Ils nous font confiance | Qui d'autre leur fait confiance ? |
| **Une réalisation** | À quoi ressemble un projet mené par eux ? |
| En images | Sont-ils réellement sur le terrain ? |
| Notre ambition · Le groupe | Jusqu'où peuvent-ils m'accompagner ? |
| Boutique | Que puis-je commander tout de suite ? |
| Actualités | Sont-ils actifs ? |
| Contact | Comment les joindre ? |

> La section « Nos services » a quitté la page d'accueil : le parcours
> d'accompagnement dit la même chose de façon plus concrète. Les trois services
> restent détaillés sur la page **Services & expertise**.

### Les mises en page alternent

Toutes les sections ne se ressemblent plus. On alterne désormais :

* **image / texte** pour la présentation (classe `split`, et `split is-reversed` pour inverser) ;
* **grille de cartes** pour les domaines ;
* **frise horizontale** pour le parcours ;
* **bandeau pleine largeur** pour la vidéo ;
* **grande photo + fiche projet** pour la réalisation mise en avant (classe `showcase`).

C'est ce qui supprime l'impression que chaque section est une page indépendante.

### Une réalisation mise en avant

La première référence cochée **« Réalisation phare »** est présentée en grand sur
l'accueil : photo, type d'établissement, ville, année, équipements installés et
accompagnement fourni. Aucune saisie supplémentaire — ce sont les informations
déjà présentes dans *Références*.

### ⚠️ Les chiffres clés

Un **quatrième chiffre** est disponible dans *Réglages → Présentation*, laissé vide
par défaut. N'y mettez que des chiffres réels et vérifiables : c'est le même principe
que pour les agences, on n'annonce jamais ce qui n'existe pas.


---

## 19. La section « Nos domaines » sur l'accueil

### Des cartes qui parlent

Une carte ne montrait que le nom du domaine et une phrase courte. Elle annonce
maintenant **ce que le domaine contient réellement** : au survol, les trois premiers
équipements saisis dans *Domaines → Équipements* apparaissent sous forme d'étiquettes.

Sur téléphone, où il n'y a pas de survol, les étiquettes sont affichées en permanence.

S'ajoutent aussi : l'icône du domaine, le nombre d'équipements disponibles à la vente,
et un lien « Découvrir → » qui s'anime au survol.

> Plus vous remplissez la liste des équipements d'un domaine, plus la carte devient
> convaincante. C'est le champ qui a le plus d'impact sur cette section.

### Les textes

| Réglage | Contenu |
|---|---|
| Domaines — petit texte | Nos domaines d'expertise |
| Domaines — titre | Une solution pour chaque environnement de soins |
| Domaines — texte | Du bloc opératoire au laboratoire, de l'imagerie à la réanimation… |

L'ancien titre (« Du bloc opératoire au laboratoire ») décrivait deux domaines sur douze.
Le nouveau annonce une promesse valable pour tout l'établissement, et l'ancienne
formule est réutilisée dans le texte d'introduction.

> Ces textes restent modifiables dans *Réglages → 📝 Titres des sections*.
> La mise à jour automatique **ne remplace pas** un texte que vous auriez déjà
> personnalisé : elle n'agit que si l'ancien texte par défaut est encore en place.


---

## 20. Le site en français et en anglais

### Comment ça marche

Le français est la langue de référence. L'anglais s'ajoute par-dessus,
sans jamais dupliquer la saisie :

| Adresse | Langue |
|---|---|
| `avenir-medic.com/boutique` | Français |
| `avenir-medic.com/en/boutique` | Anglais |

Le sélecteur **FR | EN** en haut à droite conserve la page en cours.
Les balises `hreflang` indiquent à Google que les deux versions existent :
sans elles, la version anglaise ne serait jamais indexée.

**Règle de sécurité :** si une traduction manque, le texte français s'affiche.
Le site n'est donc jamais vide, même en cours de traduction.

### Traduire le contenu automatiquement

Le contenu saisi dans l'administration (produits, domaines, articles…) se
traduit en une commande :

```bash
php artisan site:traduire --essai   # montre ce qui serait traduit
php artisan site:traduire           # traduit pour de bon
```

Il faut d'abord une clé, à ajouter dans le fichier `.env` :

```ini
TRANSLATE_DRIVER=deepl
TRANSLATE_KEY=votre-cle
```

Trois services au choix :

| Service | `TRANSLATE_DRIVER` | Remarque |
|---|---|---|
| **DeepL** | `deepl` | Meilleure qualité pour le français. Gratuit jusqu'à 500 000 caractères par mois. |
| Google Translate | `google` | Environ 20 $ par million de caractères. |
| Claude | `claude` | Plus cher, meilleur sur le vocabulaire médical. |

**Les noms de marques ne sont jamais traduits.** COMEN, RANDOX, CANON, Dakar,
Sénégal… sont protégés avant l'envoi puis remis en place. La liste se complète
dans `config/services.php`, section `translate.protected`.

### Relire et corriger

*Administration → 🌍 Traductions.* Le français et l'anglais s'affichent côte à
côte, rubrique par rubrique. Chaque traduction porte une étiquette :

* **automatique** — issue de la traduction machine ;
* **corrigé** — modifiée à la main ; la commande ne l'écrasera plus ;
* **à traduire** — le français s'affiche en attendant.

En pratique, seules une trentaine de textes méritent une relecture :
le titre d'accueil, la présentation, les noms de domaines. Les descriptions
produits peuvent rester telles quelles.

### Les textes du site lui-même

Les mots de l'interface (menus, panier, boutons) ne passent pas par la base :
ils sont dans `lang/fr/site.php` et `lang/en/site.php`, et sont déjà traduits.

### Pour les développeurs

* Un modèle devient traduisible avec le trait `HasTranslations` et la propriété
  `$translatable`.
* La lecture est transparente : `$produit->name` renvoie l'anglais en anglais.
  Pour obtenir le français d'origine, utiliser `$produit->raw('name')`.
* Les traductions sont chargées automatiquement avec les contenus : afficher
  30 produits en anglais ne déclenche pas 30 requêtes supplémentaires
  (un test le vérifie).
* Les liens publics s'écrivent avec `lroute()` et non `route()`, pour rester
  dans la langue affichée.


---

## 21. Galerie photo sur les contenus

Les formations, les services et les références acceptent désormais **plusieurs photos**
(et non plus une seule). Le champ est le même partout :

*Administration → Services → Modifier → 📷 Photos du service.*

Sélectionnez plusieurs fichiers d'un coup. Les photos déjà en ligne s'affichent
en vignettes, avec un bouton **×** pour les retirer.

Sur le site, les 4 premières apparaissent sous le contenu, les suivantes sont
comptées (« +5 »). Un clic les agrandit, les flèches du clavier font défiler.

> Techniquement : une seule table `content_photos` sert à tous les contenus,
> via le trait `HasPhotos`. Les photos de formation déjà enregistrées ont été
> reprises automatiquement.

### Le projet clé en main

Les 9 photos du chantier sont rattachées au service **Projets clé en main**,
avec une légende chacune, dans un ordre qui raconte le projet : équipements
installés, mise en service, formation avec le fabricant, prise en main par
les équipes soignantes.

### Stérilisation

SHINVA est rattachée au domaine **Hygiène et entretien**, qui couvre la
stérilisation. La marque apparaît sur la carte du domaine.


---

## 22. Détection automatique de la langue

*Réglages du site → 🌍 Langues → « Détecter automatiquement la langue du visiteur ».*

**Désactivée par défaut.** Une fois cochée, un visiteur dont le navigateur est en
anglais arrive directement sur la version anglaise, dès sa première visite.

### Ce qui se passe exactement

| Situation | Résultat |
|---|---|
| Navigateur en français | Reste en français |
| Navigateur en anglais, 1re visite | Redirigé vers `/en/…` |
| Navigateur en anglais, mais a cliqué sur « FR » | Reste en français, définitivement |
| Navigateur en espagnol ou autre | Reste en français |
| Googlebot et autres robots | Jamais redirigé |
| Administration, connexion, panier | Jamais redirigé |

### Pourquoi ces précautions

**Les robots ne sont jamais redirigés.** Si Google était renvoyé vers l'anglais,
il n'indexerait jamais les pages françaises : le site disparaîtrait des recherches
en français.

**Le choix du visiteur prime toujours.** Dès qu'il clique sur FR ou EN, un cookie
mémorise sa décision pendant un an. Il n'est plus jamais redirigé, même si son
navigateur est dans l'autre langue.

**La redirection ne va que du français vers l'anglais**, jamais l'inverse, et la
page `/langue/…` en est exclue : aucune boucle de redirection n'est possible.

### En cas de problème

La fonction se désactive depuis l'administration, qui reste toujours accessible
(elle n'est jamais redirigée). Les visiteurs déjà redirigés peuvent revenir au
français avec le sélecteur FR | EN.


---

## 23. Le nom de l'entreprise n'est jamais traduit

« AVENIR MEDICAL » est un nom propre : il doit rester identique dans toutes
les langues. Trois protections s'appliquent.

### 1. Contre le traducteur du navigateur

Chrome, Edge et Safari proposent de traduire automatiquement une page.
Sans précaution, ils transforment « AVENIR MEDICAL » en « MEDICAL FUTURE ».

Le logo, le nom du site et les noms de marques portent désormais l'attribut
`translate="no"`, reconnu par tous les navigateurs : ils sont laissés tels quels,
même quand le visiteur fait traduire la page.

> Si vous voyez le site en anglais alors que le sélecteur affiche **FR** et que
> l'adresse ne contient pas `/en`, c'est votre navigateur qui traduit, pas le site.
> Une icône de traduction apparaît alors dans la barre d'adresse.

### 2. Contre notre propre traduction

La commande `php artisan site:traduire` ignore désormais explicitement le nom
du site, la signature, l'adresse et les numéros de téléphone. De plus, un texte
qui se réduit à un nom de marque protégé est renvoyé sans modification.

### 3. Nettoyage des traductions existantes

Si une traduction du nom a déjà été enregistrée, la migration
`2026_01_15_000100` la supprime. Le nom d'origine réapparaît partout.

### Ajouter une marque à protéger

Dans `config/services.php`, section `translate.protected`. Les marques y sont
déjà : COMEN, SHINVA, CANON, RANDOX, SUNBIO, les villes du Sénégal…


---

## 24. Boutons sur les images de fond

Un bouton au contour clair devient illisible par-dessus une photo. Sur la grande
image d'accueil, les bandeaux de page et la section vidéo, **les deux boutons ont
désormais un fond plein** :

* le bouton principal est **blanc**, texte bleu ;
* le bouton secondaire est **blanc légèrement voilé**, texte bleu foncé.

Ils restent distincts l'un de l'autre, et lisibles quelle que soit la photo placée
derrière.

> Ces règles se trouvent en fin de `public/assets/css/style.css`.
> Après toute modification du CSS, relancez `php artisan site:minify`.


---

## 25. Correction importante — « Enregistrer » supprimait le contenu

### Le défaut

Sur les formulaires comportant une galerie (formations, services), chaque photo
avait son propre petit formulaire de suppression, écrit **à l'intérieur** du
formulaire d'édition.

Or un `<form>` imbriqué dans un autre est invalide en HTML. Le navigateur
fusionne les deux : le champ caché `_method = DELETE` de la suppression se
retrouve dans le formulaire d'édition. Laravel lit alors la dernière valeur —
`DELETE` — et **le bouton « Enregistrer » supprimait la formation** au lieu de
l'enregistrer.

### La correction

Les formulaires de suppression sont désormais rendus **en fin de page**, hors de
tout autre formulaire, grâce à `@push('formulaires-hors-page')` et au `@stack`
correspondant dans le gabarit de l'administration.

Les boutons « × » restent visuellement sur les photos, mais leur sont reliés par
l'attribut HTML `form="…"`, prévu exactement pour cela.

Résultat :

* « Enregistrer » envoie bien un **PUT** vers la formation ;
* chaque « × » envoie un **DELETE** vers sa propre photo.

### Pour ne pas le refaire

Trois tests interdisent le retour du défaut (`AdminFormIntegrityTest`) :
ils vérifient qu'aucun formulaire n'est imbriqué sur les écrans concernés, et
qu'enregistrer une formation ne la supprime pas.

> **Règle à retenir :** ne jamais écrire un `<form>` à l'intérieur d'un autre.
> Pour un bouton d'action à l'intérieur d'un formulaire d'édition, utiliser
> `@push('formulaires-hors-page')` et l'attribut `form="…"` sur le bouton.


---

## 26. Ajustements de la page d'accueil

### Qui sommes-nous

Le carré bleu décalé derrière la photo a été supprimé : l'image est simplement
posée, sans ornement.

### Nos domaines d'expertise

* **Quatre domaines** affichés au lieu de trois, sur une seule ligne.
* Les **logos des marques** remplacent leurs noms sur les cartes
  (COMEN, SHINVA, CANON, RANDOX…). Sans logo, le nom s'affiche à la place.

> Les marques proviennent de la fiche du domaine : *Domaines → Modifier → Marques*.

### Formation continue

Les cartes reprennent la présentation de la page Services : **photo en haut**,
pays en pastille, puis intitulé, organisme et nombre de techniciens formés.
La photo est celle de la formation ; à défaut, la première de sa galerie.

Trois formations sont affichées.

### Ils nous font confiance

**Quatre références** au lieu de dix, réparties sur toute la largeur, avec des
cartes de hauteur égale.

### Nouveau — la carte en bas de page

Une carte Google occupe désormais toute la largeur en bas de l'accueil, avec un
encart posé dessus : nom, adresse, téléphone, email, horaires et un bouton de
contact.

Elle n'apparaît que si le code d'intégration est renseigné dans
*Réglages → Contact → Carte Google Maps*. Pour l'obtenir : ouvrez Google Maps,
cherchez l'adresse, cliquez sur **Partager → Intégrer une carte**, et copiez le
code `<iframe>` fourni.

### Restauration des formations

La migration `2026_01_16_000100` recrée les trois formations (CANON, FAGOR,
RANDOX) et leurs photos si elles manquent — elles ont pu être supprimées par le
défaut du bouton « Enregistrer » corrigé précédemment. Une formation déjà
présente n'est jamais modifiée.


---

## 27. Cohérence visuelle de l'accueil

### Le bouton invisible de la carte

La règle `.map-card a` colorait tous les liens de l'encart en bleu — y compris
le bouton, dont le fond est déjà bleu. Le texte était donc invisible.
La règle exclut désormais les boutons : `.map-card a:not(.btn)`.

### Les grilles remplissent toujours la ligne

Avec un nombre de colonnes figé, trois entreprises du groupe sur quatre colonnes
laissaient un vide à droite. Les grilles utilisent maintenant `auto-fit` :
elles répartissent l'espace quel que soit le nombre d'éléments.

Concerne : le groupe, les partenariats exclusifs, les références et les domaines.

### L'alternance des fonds

Les sections de l'accueil alternent désormais sans jamais répéter deux fois
le même fond :

| | Section | Fond |
|---|---|---|
| 1 | Accueil (grande image) | foncé |
| 2 | Qui sommes-nous | blanc |
| 3 | Nos domaines | gris |
| 4 | Vidéo | foncé |
| 5 | Partenariats exclusifs | blanc |
| 6 | Formation continue | gris |
| 7 | Une réalisation | blanc |
| 8 | Galerie | gris |
| 9 | Notre ambition | foncé |
| 10 | Le groupe | blanc |
| 11 | Boutique | gris |
| 12 | Blog | blanc |

Les sections Boutique et Blog prennent un fond gris ou blanc tant qu'aucune
photo de fond n'est réglée : elles ne restent plus « transparentes ».

### La règle des boutons

Une seule logique sur tout le site :

* **Sur fond clair** (blanc ou gris) : bouton principal **bleu plein**.
* **Sur fond foncé** (photo ou bandeau bleu) : bouton principal **blanc plein**,
  car un bouton bleu sur fond bleu serait illisible.
* Le bouton secondaire (contour) n'apparaît qu'**à côté** d'un bouton principal.

La section « Formation continue » avait un bouton contour isolé : il est passé
en bouton principal, comme toutes les autres sections.


---

## 28. Icônes vectorielles à la place des émojis

Les émojis dépendaient du système d'exploitation du visiteur : ils s'affichaient
différemment sous Windows, macOS et Android, et donnaient un air d'autocollant.

Le site utilise désormais des **icônes dessinées au trait**, dans le style du
reste de la mise en page. Elles prennent automatiquement la couleur du texte
qui les entoure : elles restent donc lisibles sur fond clair comme sur fond foncé.

**57 émojis ont été remplacés** sur l'ensemble du site public.

### Utiliser une icône

```blade
@include('partials.icon', ['name' => 'phone'])
@include('partials.icon', ['name' => 'graduation', 'size' => 30])
```

Icônes disponibles : `phone`, `mail`, `pin`, `globe`, `clock`, `cart`, `box`,
`truck`, `cash`, `trash`, `hospital`, `wrench`, `graduation`, `user`, `users`,
`handshake`, `building`, `star`, `search`, `camera`, `news`, `menu`, `check`,
`alert`, `lock`, `settings`, `bulb`, `list`, `party`, `wave`, `sparkle`.

### Les icônes saisies dans l'administration

Les domaines, les services et les étapes du parcours ont un champ « icône » où
l'on saisit un émoji. Ces émojis sont **convertis automatiquement** en icône
dessinée quand un équivalent existe (`partials/icon-from`).

Si l'émoji choisi n'a pas d'équivalent, il s'affiche tel quel : rien n'est perdu,
et vous pouvez en choisir un autre parmi ceux reconnus (🏥 🔧 🎓 📦 🔬 💉 🚑 🛡️ …).

> L'administration conserve ses émojis dans le menu latéral : ils y servent de
> repères visuels rapides, et ne sont pas vus par les visiteurs.

---

## 29. Deux corrections d'affichage

**« ACCOMPAGNEMENT » se coupait en deux** dans la fiche de réalisation : la
colonne des libellés était trop étroite pour un mot en majuscules espacées.
Elle est passée de 130 à 170 pixels, et les libellés ne se coupent plus.

**Le bouton de la fiche de réalisation** était un bouton contour isolé.
Il est passé en bouton plein, comme partout ailleurs.


---

## 30. Icônes agrandies et colorées

Les icônes étaient trop discrètes et toutes noires.

* **Taille par défaut : 26 px** au lieu de 18. Toutes les tailles explicites ont
  été multipliées par 1,6 (les grandes icônes des pages d'erreur passent à 70 px).
* **Couleur bleue** par défaut, au lieu du noir hérité du texte.
  Dans les titres, elles prennent le bleu foncé de la marque.
* **Exception** : une icône posée sur une pastille, un bouton ou un fond foncé
  suit la couleur du texte qui l'entoure, pour rester lisible.

La bibliothèque compte désormais **39 icônes** et **60 correspondances** avec les
émojis saisis dans l'administration (💎 🤝 📡 ❤️ 📊 💊 🌡️ …).

### Les icônes des valeurs

Sur la page « Qui sommes-nous », les icônes des valeurs venaient de la base et
restaient des émojis. Elles sont maintenant converties comme les autres.

---

## 31. Mises en page ajustées

| Section | Avant | Après |
|---|---|---|
| Le groupe (page entreprise) | 2 par ligne | **3 par ligne** |
| Services & expertise | 3 par ligne | **4 par ligne** |
| Partenariats exclusifs | 3 par ligne | **4 par ligne** |

Ces grilles se réduisent à 2 colonnes en dessous de 1200 px, puis à 1 sur téléphone.

> La grille `.grid-3` (valeurs, formations) n'a pas été touchée : elle s'adapte
> désormais au nombre d'éléments, sans laisser de vide.

### Section retirée

« Notre développement » a été retirée de la page **Qui sommes-nous**. Elle reste
disponible ailleurs si besoin — le composant `partials/agencies` est conservé.


---

## 32. Ajustements — icônes, domaines et services

### Icônes corrigées

Trois tracés étaient mal construits et s'affichaient déformés : le diamant
(valeur « Qualité »), la poignée de main (« Satisfaction client ») et l'antenne
(« Proximité »). Ils ont été redessinés.

Un contrôle automatique vérifie désormais que **chacun des 39 tracés** est un
SVG valide.

### Page « Nos domaines »

* Les icônes ont été retirées : elles n'apportaient rien à côté des photos.
* **Les logos des marques remplacent leurs noms.** Sans logo, le nom s'affiche
  en majuscules, comme avant.

### Page « Services & expertise »

Les quatre services sur une ligne rendaient les titres illisibles (« Service
après-|vente ») et le texte entassé.

Nouvelle présentation : **deux cartes larges par ligne**, avec la photo à gauche
et le texte à droite. Le titre a de la place, la description respire, et l'icône
du service est posée dans une pastille bleu clair.

Sur écran moyen, les cartes passent l'une sous l'autre ; sur téléphone, la photo
repasse au-dessus du texte.

### Bouton retiré

Le bouton « Notre développement » de l'accueil pointait vers une section qui
n'existe plus depuis qu'elle a été retirée de la page entreprise.


---

## 33. Vente au carton

### Le principe

Le prix enregistré sur un produit reste **le prix unitaire**. On indique en plus
le nombre d'unités contenues dans un carton, et le site calcule le prix du carton
tout seul.

Avantage : si le prix unitaire change, tous les prix carton suivent
automatiquement. Aucun recalcul à faire à la main.

### Saisie groupée — la façon rapide

*Administration → 📐 Conditionnement.*

Un tableau affiche tous les produits, avec le prix unitaire à gauche et une case
à remplir. **Le prix du carton s'affiche en direct** pendant la saisie, avant même
d'enregistrer. Un seul bouton enregistre tout.

Trois filtres facilitent le travail : *Tous*, *À remplir*, *Déjà remplis*, plus
un filtre par catégorie. Les produits sans conditionnement remontent en premier.

### Produit par produit

Le champ existe aussi dans la fiche de chaque produit
(*Produits → Modifier*), avec un second champ pour le nom du conditionnement :
« Carton », « Boîte », « Sachet »… (« Carton » par défaut).

### Ce que voit le visiteur

Sur la carte produit :

> **45 000 FCFA**  ·  *Carton de 50*

Sur la fiche produit :

> **45 000 FCFA**
> **Carton de 50** — soit 900 FCFA l'unité

Le panier, la commande et les totaux utilisent le prix du carton. Un produit sans
conditionnement (ou avec 1 seule unité) reste vendu à l'unité, exactement comme avant.


---

## 34. Deux ajustements

### Espacement des marques (page Nos domaines)

Un vide important séparait les logos des marques. La liste était en **grille**,
à l'intérieur d'une carte dont la hauteur est imposée par la photo du domaine :
les lignes s'étiraient pour remplir l'espace disponible.

Elle est passée en **flex vertical** avec `align-items: flex-start` : chaque
ligne garde sa hauteur naturelle, quelle que soit la hauteur de la photo.

### Titre de la section Services

La section des services n'avait pas de titre, contrairement aux autres.
Elle en a un maintenant, avec les trois textes modifiables dans
*Réglages du site → 📝 Titres des sections* :

| Réglage | Valeur par défaut |
|---|---|
| Services — petit texte | Nos services |
| Services — titre | Bien plus qu'un fournisseur |
| Services — texte | Nous vous accompagnons avant, pendant et après votre achat. |


---

## 35. Audit de l'affichage sur téléphone

Une vérification systématique du CSS a été menée : nombre de colonnes réellement
appliqué à 390 px, largeurs fixes, zones tactiles, titres et formulaires.

### Ce qui allait déjà

* Les **40 grilles** du site repassent à une colonne sur téléphone.
  Seules 7 restent à deux colonnes — vignettes de galerie, logos de partenaires
  et chiffres clés — ce qui est voulu : environ 179 px par vignette, largement
  lisible.
* Les titres utilisent des tailles adaptatives (`clamp`), sauf un qui a été corrigé.
* Aucune largeur fixe ne dépasse de l'écran.
* Les tableaux de l'administration défilent horizontalement.
* Le menu déroulant se déclenche à 1080 px.

### Ce qui a été corrigé

**Le zoom automatique sur iPhone.** Safari agrandit la page dès qu'on saisit dans
un champ dont le texte fait moins de 16 px — et la page reste décalée ensuite.
Tous les champs passent à 16 px sur téléphone, sur le site comme dans
l'administration. C'était le défaut le plus gênant.

**Le titre « AVENIR MEDICAL » de la maison mère** était en taille fixe (2 rem)
et débordait sur les petits écrans. Il est devenu adaptatif.

**Les mots longs** (adresses email, noms d'établissements) passent maintenant à
la ligne au lieu d'élargir la page.

**Le sélecteur de langue** passe à 36 px de haut sur téléphone : en dessous,
la cible tactile est trop petite.

**Les deux boutons flottants** (WhatsApp et assistant) sont espacés pour ne pas
se chevaucher sur les petits écrans.

**Sous 380 px** (iPhone SE et petits Android), les galeries passent à deux
colonnes maximum et le collage de photos à une seule.


---

## 36. Corrections mobiles constatées à l'écran

### Le texte passait sous l'en-tête

Sur téléphone, le logo tient sur deux lignes : l'en-tête est plus haut que les
76 px prévus, et le petit texte du bandeau se retrouvait caché derrière.

La hauteur de l'en-tête devient automatique (72 px minimum), et les bandeaux
laissent désormais **60 à 78 px d'espace libre** en dessous, sur toutes les pages.

Trois règles concurrentes se disputaient l'espacement du bandeau des pages
internes ; la dernière n'en laissait que 104 px. Elles ont été remplacées par
deux règles claires.

### Les cartes de domaine masquaient leur photo

Sur ordinateur, les équipements d'un domaine apparaissent au survol. Faute de
survol sur téléphone, ils étaient affichés en permanence — et le texte,
les étiquettes et les logos remplissaient toute la carte, cachant la photo.

Sous 760 px, la carte passe donc en **présentation empilée** : la photo occupe
une bande de 190 px entièrement visible, et le texte se place en dessous sur
fond bleu. Le voile dégradé disparaît, puisqu'il n'y a plus rien par-dessus
l'image.


---

## 37. Corrections mobiles — deuxième passe

### Les marges latérales avaient disparu

C'était la cause principale. La règle `.hero-inner{padding:140px 0 50px}`
s'appliquait au **même élément** que `.wrap`, dont elle écrasait les marges
gauche et droite (le raccourci `padding` remet les quatre côtés à zéro).

Le texte et les boutons touchaient donc les bords, et les boutons trop larges
paraissaient décentrés.

Ces règles n'agissent plus que sur le haut et le bas (`padding-top` /
`padding-bottom`). Les marges de `.wrap` sont préservées : **24 px** sur
ordinateur, **16 px** sur téléphone.

> Un contrôle vérifie qu'aucune autre règle n'écrase les marges de `.wrap`.

### Trop d'espace en haut

L'espace au-dessus du titre est passé de 140 à **106 px** sur téléphone,
ce qui laisse 34 px sous l'en-tête — suffisant, sans vide inutile.

### En-tête allégé

Sur téléphone, l'en-tête contenait le logo sur deux lignes, le sélecteur FR|EN,
le compte, le panier et le menu : beaucoup trop.

* Le **sélecteur de langue** et le **lien vers le compte** passent dans le
  menu déroulant.
* Le logo tient sur **une seule ligne** (le retour à la ligne est masqué).
* Il ne reste que le logo, le panier et le bouton du menu.

### Boutons

Sur petit écran, un bouton dont le texte est long passe désormais sur deux
lignes plutôt que de déborder, tout en restant centré.


---

## 38. Sélecteur de langue et indicateur de chargement

### Le sélecteur FR|EN dans le menu

Placé dans le menu déroulant, il héritait des styles des liens du menu :
une couleur imposée en `!important`, un affichage en bloc et une bordure
d'élément actif. Résultat : un bouton de travers, avec « FR » illisible.

Ses propres styles sont désormais rétablis : deux zones de 64 × 42 px,
la langue active en bleu plein, la seconde en gris.

### Barre de chargement

Entre deux pages, le visiteur voyait un écran blanc — impression que rien
ne se passe, surtout sur une connexion lente.

Une **fine barre bleue** progresse maintenant en haut de l'écran dès qu'un lien
est cliqué ou qu'un formulaire est envoyé. Elle ralentit à mesure qu'elle avance
et n'atteint 100 % qu'à l'arrivée de la page.

Le contenu apparaît ensuite **en fondu**, plutôt que d'un coup.

Elle ne se déclenche pas pour : les ancres (`#`), les liens externes,
les liens `tel:` et `mailto:`, les téléchargements, l'ouverture dans un nouvel
onglet, ni un clic sur la page déjà affichée.

Le réglage « animations réduites » du téléphone est respecté.

> Ces comportements sont vérifiés par 9 tests dans un navigateur simulé.


---

## 39. Traduction des textes du site

### Pourquoi les boutons restaient en français

La commande `php artisan site:traduire` traduit ce qui est **en base de données** :
produits, domaines, articles, réglages. Mais les boutons, les intitulés de section
et les messages étaient **écrits en dur dans les pages** — donc jamais traduits.

« Explorer tous nos domaines », « Découvrir l'entreprise », « Ils nous font
confiance », « Formation continue »… tous restaient en français.

### Ce qui a été fait

**189 textes ont été extraits** des pages vers les fichiers de langue
`lang/fr/site.php` et `lang/en/site.php`. Les pages appellent désormais
`__('site.explorer_tous_nos_domaines')` au lieu du texte figé.

Les plus visibles (boutons, titres de section, panier, formulaires) sont déjà
traduits à la main.

### Traduire le reste avec votre clé DeepL

```bash
php artisan site:traduire --interface --essai   # montre ce qui serait traduit
php artisan site:traduire --interface           # traduit pour de bon
```

Cette option complète `lang/en/site.php`. Elle ne touche jamais à une valeur
déjà différente du français : **vos corrections manuelles sont conservées**.

Pour tout traduire d'un coup — contenu et interface :

```bash
php artisan site:traduire --interface
```

### Vérification

Un contrôle confirme que les **201 clés** utilisées dans les pages existent bien
dans les deux fichiers de langue. Aucune clé manquante.

---

## 40. Logo agrandi sur téléphone

L'en-tête ne contient plus que le logo, le panier et le bouton du menu :
le logo peut donc respirer.

| Écran | Icône | Texte |
|---|---|---|
| 412 px et plus | 48 px | 1,15 rem |
| 400 à 340 px | 44 px | 1,05 rem |
| moins de 340 px | 38 px | 0,95 rem |

Ces tailles ont été calculées pour que le logo, le panier et le menu tiennent
sur une seule ligne jusqu'à 320 px de large.


---

## 41. Compléments de traduction

### Les vidéos et le référencement n'étaient jamais traduits

La commande excluait les groupes `videos` et `seo`. Le titre et le texte de la
vidéo d'accueil (« SISDAK 2025… ») restaient donc en français, ainsi que les
descriptions vues par Google.

Seuls restent exclus désormais : **le nom de l'entreprise, les coordonnées et
les liens des réseaux sociaux** — qui ne doivent pas être traduits.

### Le bouton « Découvrir »

Il était mêlé à des directives Blade (le décompte des équipements), ce qui l'avait
fait échapper à l'extraction. Il est traduit, ainsi que les mots
« équipement / équipements » du décompte.

### Après cette mise à jour

```bash
php artisan optimize:clear
php artisan site:traduire --interface
```

La commande traduira ce qui manque : les textes de vidéo, les descriptions de
référencement et les 146 textes d'interface encore en français.
Les traductions déjà corrigées à la main ne sont pas touchées.


---

## 42. Pourquoi la plupart des réglages restaient en français

### La cause

Un réglage n'existe en base **que s'il a été enregistré au moins une fois**
depuis l'administration. Tant que ce n'est pas le cas, sa valeur vient de
`config/settings.php`.

La commande de traduction parcourait la table `settings` : elle ne voyait donc
que les réglages déjà enregistrés. Tous les autres — la majorité — lui étaient
invisibles, et le site affichait leur valeur française par défaut.

Sur les **132 réglages** déclarés :

| | Nombre |
|---|---|
| Textes traduisibles | 84 |
| Exclus volontairement (nom, coordonnées, liens) | 21 |
| Non traduisibles (images, cases à cocher, nombres) | 27 |

### La correction

Avant de traduire, la commande **enregistre en base les réglages manquants**
avec leur valeur par défaut — exactement ce que ferait un passage dans
l'administration. Ils deviennent alors visibles et traduisibles.

Rien n'est modifié pour les réglages déjà enregistrés, et les groupes exclus
(nom de l'entreprise, coordonnées, réseaux sociaux) ne sont jamais créés.

```bash
php artisan site:traduire --interface --essai   # annonce ce qui serait fait
php artisan site:traduire --interface           # enregistre puis traduit
```

La simulation indique désormais combien de réglages seraient enregistrés avant
d'être traduits.
