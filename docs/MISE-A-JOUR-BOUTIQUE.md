# Mise à jour — La boutique en ligne

## Ce qui change

La boutique contenait douze produits de démonstration (tensiomètre, lit
médicalisé…) avec des prix inventés et des marques tirées au hasard. Elle
contient désormais le **catalogue revendeur DISE 2026** : 62 articles, leurs
prix réels en francs CFA, leur conditionnement, leur fiche descriptive et,
pour la moitié d'entre eux, une photo découpée dans le catalogue.

---

## Installation

Copiez les fichiers dans le projet, puis lancez :

```bash
php artisan boutique:vider          # supprime les anciens produits
php artisan db:seed --class=DiseCatalogueSeeder
php artisan cache:clear
```

`boutique:vider` demande confirmation et annonce le nombre de produits
concernés. Options disponibles :

| Option | Effet |
|---|---|
| `--force` | Ne demande pas de confirmation (utile en déploiement automatique) |
| `--avec-images` | Supprime aussi les fichiers photo des produits effacés |

> **Les commandes déjà passées ne sont pas touchées.** Chaque ligne de commande
> conserve le nom et le prix de l'article au moment de l'achat ; seul le lien
> vers la fiche produit devient vide.

Vérifiez que le lien vers les fichiers publics existe, sinon les photos ne
s'afficheront pas :

```bash
php artisan storage:link
```

### Vérifié en conditions réelles

Migration, vidage, import, réimport et navigation ont été exécutés sur une base
réelle : 62 produits dans 10 rayons, 30 photos servies en HTTP 200, aucune image
manquante. La recherche par nom (« foley » → 8 résultats) et par référence
(`DISE-CAT-18G` → 1 résultat) fonctionne, comme le filtre par rayon, la
pagination, la fiche produit et l'ajout au panier. Le seeder relancé deux fois
de suite ne crée aucun doublon.

---

## Le catalogue importé

| Rayon | Articles | Fourchette de prix |
|---|---:|---|
| Seringues & perfusion | 4 | 1 950 – 15 000 FCFA |
| Cathéters & accès vasculaire | 5 | 60 – 130 FCFA |
| Aiguilles & lames | 8 | 300 – 3 100 FCFA |
| Sondes urinaires | 8 | 375 FCFA |
| Protection & chirurgie | 5 | 1 300 – 5 300 FCFA |
| Pansements & prélèvements | 9 | 200 – 5 500 FCFA |
| Voies aériennes & aspiration | 10 | 175 – 95 000 FCFA |
| Trachéotomie & intubation | 2 | 450 – 1 300 FCFA |
| Gants & divers | 7 | 850 – 20 000 FCFA |
| Laboratoire & imagerie | 4 | 600 – 5 000 FCFA |

Chaque article porte :

- une **référence** du type `DISE-SER-10`, sur laquelle la recherche fonctionne ;
- un **prix** correspondant au conditionnement du catalogue ;
- une **accroche** au format « Paquet de 100 · 3 parties, aiguille 21G × 1,5 » ;
- une **fiche** de deux à trois phrases ;
- le rattachement au domaine « Consommables biomédicaux » et à la marque DISE ;
- une **photo** ou, à défaut, une vignette emoji.

Onze articles sont marqués « en avant » et remontent sur la page d'accueil.

---

## Les photos

Le catalogue PDF contient sept photos de groupe. Elles ont été découpées article
par article : les quatre cathéters ont été isolés de la photo d'ensemble, les
sept sondes de Foley séparées ligne par ligne, les seringues extraites de la
photo d'assortiment, et ainsi de suite. Résultat : **30 photos** en WebP, larges
de 1 000 pixels, dans `storage/app/public/produits/`.

Les 32 articles restants n'apparaissent sur aucune photo du catalogue : masques,
bonnets, gants, canules de Guedel, lames de scalpel, aiguilles spinales,
aspirateurs, nébuliseur, abaisse-langue. Ils affichent une vignette emoji en
attendant. Pour ajouter une photo : **Administration → Produits → Image**.

Deux articles méritent une photo dédiée quand vous en aurez :

- la **sonde de Foley CH 22** utilise pour l'instant la photo d'ensemble de la
  gamme, faute d'une ligne CH 22 dans le catalogue ;
- le **tube de trachéotomie** et la **sonde endotrachéale** sont vendus en
  plusieurs diamètres avec une seule photo commune.

---

## Fichiers

### Nouveaux

| Fichier | Rôle |
|---|---|
| `database/seeders/data/dise-catalogue.php` | Les 62 articles : prix, spécifications, fiches |
| `database/seeders/DiseCatalogueSeeder.php` | Crée les rayons et importe les articles |
| `app/Console/Commands/ClearShop.php` | La commande `boutique:vider` |
| `storage/app/public/produits/dise-*.webp` | Les 30 photos découpées du catalogue |

### Modifiés

| Fichier | Rôle |
|---|---|
| `database/seeders/DatabaseSeeder.php` | Appelle `DiseCatalogueSeeder` à la place des seeders de démonstration |

### Supprimés

`database/seeders/ProductSeeder.php` et `database/seeders/CategorySeeder.php`
créaient les douze produits et les six rayons de démonstration. Les conserver
aurait fait réapparaître ce contenu au prochain `php artisan db:seed`.

---

## Points restants

**Les anciens rayons.** « Petits matériels », « Consommables », « Mobiliers
hospitaliers », « Laboratoire », « Oxygène & respiratoire » et « Réfrigérateurs
biomédicaux » sont désormais vides. Le seeder les masque automatiquement pour
qu'ils n'apparaissent pas avec « (0) » dans les filtres. Ils restent
réactivables depuis **Administration → Catégories**.

**Les anciennes photos.** Les images des produits supprimés restent sur le
disque dans `storage/app/public/produits/`. Utilisez `boutique:vider
--avec-images` si vous refaites l'opération, ou supprimez-les à la main.

**Les stocks.** Aucun stock n'est renseigné : la boutique n'affiche donc pas de
quantité disponible. Si vous voulez le faire apparaître, saisissez le stock
produit par produit en administration et activez l'option correspondante dans
les réglages.

**Le masque KN95** figure deux fois dans le catalogue PDF, dans « Protection &
chirurgie » et dans « Nébuliseur, gants & divers », au même prix. Il n'a été
créé qu'une fois, dans « Protection & chirurgie ».

**Les prix** sont ceux du catalogue revendeur 2026. Ils se modifient un par un
en administration, ou en une fois dans `dise-catalogue.php` avant de relancer le
seeder — celui-ci retrouve chaque article par sa référence et met à jour la
fiche sans créer de doublon.
