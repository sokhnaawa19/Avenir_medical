# Mise à jour — Les marques et leurs gammes

## Ce qui change

La présentation commerciale d'Avenir Médical liste, domaine par domaine, les
marques représentées et le matériel fourni par chacune. Le site n'affichait
rien de tout cela : les marques vivaient sur la page « Partenaires », les
domaines sur la page « Nos domaines », sans lien entre les deux.

Cette mise à jour relie les deux. Une marque est désormais rattachée aux
domaines qu'elle équipe, avec la liste des gammes fournies dans chacun d'eux.

---

## Installation

Copiez les fichiers dans le projet, puis lancez :

```bash
php artisan migrate
php artisan db:seed --class=DomainSeeder
php artisan db:seed --class=PartnerSeeder
php artisan cache:clear
php artisan view:clear
```

Le `DomainSeeder` ajoute le domaine « Endoscopie et chirurgie mini-invasive »,
et le `PartnerSeeder` a besoin que les domaines existent : **lancez-les dans
cet ordre**.

> **Les seeders n'écrasent rien.** Ils ne remplissent que les champs restés
> vides et n'ajoutent que les liens marque ↔ domaine manquants. Vous pouvez les
> relancer sans perdre ce qui a été saisi en administration.

Les CSS minifiés sont déjà régénérés. Si vous retouchez `style.css` ou
`admin.css` par la suite, relancez `php artisan site:minify`.

### Vérifié en conditions réelles

La migration, les seeders et les pages ont été exécutés sur une base réelle :
13 domaines, 19 marques, 20 liens marque ↔ domaine et 78 gammes enregistrés.
Les pages `/`, `/domaines`, `/partenaires`, `/entreprise`, `/boutique`,
`/services` et `/contact` répondent toutes en 200. Le formulaire
d'administration a été testé de bout en bout : connexion, cases préremplies,
ajout d'une gamme, apparition immédiate sur la page publique.

---

## Contenu livré

| | |
|---|---|
| Marques | 19 |
| Liens marque ↔ domaine | 20 |
| Gammes d'équipement | 78 |
| Nouveau domaine | Endoscopie et chirurgie mini-invasive (10 équipements) |

Les marques et leur périmètre :

| Marque | Domaines équipés |
|---|---|
| COMEN | Bloc opératoire, réanimation & néonatalogie · Imagerie médicale (monitorage) |
| CANON | Imagerie médicale |
| EDAN | Imagerie médicale |
| RANDOX | Laboratoire d'analyses médicales |
| TOSOH | Laboratoire d'analyses médicales |
| BOULE | Laboratoire d'analyses médicales |
| ZENITHLAB | Laboratoire d'analyses médicales |
| ENDOMED SYSTEMS | Endoscopie et chirurgie mini-invasive |
| SUMER | Hygiène et entretien |
| ULTRA CONTROLO | Centrale à oxygène |
| SAIKANG MEDICAL | Mobiliers hospitaliers |
| NITROCARE | Mobiliers hospitaliers |
| DIALIFE | Centre de dialyse |
| ABRONN | Ambulances médicalisées |
| FAGOR | Hygiène et entretien (blanchisserie) |
| SANISWISS | Hygiène et entretien |
| DISE | Consommables biomédicaux · Petits matériels · Mobiliers hospitaliers |
| GENERTEC INTERNATIONAL | — |
| HOLTEX | — |

Genertec et Holtex figurent sur la page « Nos partenaires » de la présentation
sans matériel associé : ils sont créés comme partenaires, sans domaine. Vous
pouvez leur en cocher depuis l'administration.

---

## Ce que voit le visiteur

**Sur `/domaines`**, chaque carte gagne un bloc « Marques référencées » : le nom
de la marque en pastille, suivi des gammes fournies. Sur la carte Imagerie
médicale, par exemple :

> **CANON** Aquilion Start · Aquilion Lightning · Aquilion Serve · Vantage Elan
> NX Edition · Vantage Orian
> **EDAN** LX 3 · AX 3 · LX 9
> **COMEN** NC5 · NC19 · C30 · C90 · Star 8000F · Star 5000E

**Sur `/partenaires`**, chaque marque affiche les domaines qu'elle couvre — des
étiquettes cliquables qui renvoient vers la carte du domaine — puis la liste de
ses gammes.

---

## Ce que peut faire l'équipe

Dans **Administration → Partenaires**, un bloc repliable « Ce que cette marque
fournit » liste tous les domaines. Pour chacun : une case à cocher et un champ
de gammes.

Une ligne par gamme, avec une précision facultative après une barre verticale :

```
Aquilion Lightning | Scanner polyvalent pour l'activité courante
Vantage Orian | IRM 1,5 T pour l'exploration avancée
```

Seul le nom avant la barre s'affiche sur la carte du domaine ; la précision
reste disponible pour un usage ultérieur. Décocher un domaine retire le lien,
le recocher le rétablit — les gammes saisies sont conservées tant que le champ
n'est pas vidé.

---

## Fichiers

### Nouveaux

| Fichier | Rôle |
|---|---|
| `database/migrations/2026_01_06_000100_create_domain_partner_table.php` | La table de liaison marque ↔ domaine |
| `database/seeders/data/brands.php` | Le contenu rédigé des 19 marques |

### Modifiés

| Fichier | Rôle |
|---|---|
| `database/seeders/PartnerSeeder.php` | Lit le nouveau fichier de données et crée les liens |
| `database/seeders/data/domains.php` | Ajout du domaine Endoscopie |
| `app/Models/Domain.php` | Relation `partners()` et raccourci `brandList()` |
| `app/Models/Partner.php` | Relation `domains()` |
| `app/Http/Controllers/PageController.php` | Chargement anticipé des relations |
| `app/Http/Controllers/Admin/PartnerController.php` | Enregistrement des domaines cochés |
| `app/Http/Requests/Admin/PartnerRequest.php` | Validation des domaines et des gammes |
| `resources/views/public/domains.blade.php` | Bloc « Marques référencées » |
| `resources/views/public/partners.blade.php` | Bloc « Domaines couverts » |
| `resources/views/admin/partners/form.blade.php` | Bloc repliable des domaines et gammes |
| `public/assets/css/style.css` + `.min.css` | Styles des deux nouveaux blocs |
| `public/assets/css/admin.css` + `.min.css` | Style du bloc de saisie |

---

## Points restants

**Les anciennes marques de démonstration.** L'ancien `PartnerSeeder` avait créé
six marques fictives — Mindray, Dräger, GE HealthCare, Fresenius, Nihon Kohden,
B. Braun. Elles sont toujours en base et s'affichent sur la page Partenaires.
Supprimez-les depuis **Administration → Partenaires** ; le seeder n'y touche
pas, pour ne rien effacer sans votre accord.

**Quatre pays à confirmer.** Le pays de SUMER, ENDOMED SYSTEMS, ABRONN et
DIALIFE est laissé vide : mieux vaut un champ vide qu'une information fausse
sur le site. À compléter en administration.

**Les logos.** Les marques s'affichent avec leurs initiales tant qu'aucun logo
n'est envoyé. Les logos figurent sur la page « Nos partenaires » de la
présentation : format PNG à fond transparent, via **Administration →
Partenaires → Logo**.

**Les tests.** Trois tests de `tests/Feature/DomainPageTest.php` échouent :
ils appellent la route `/domaines/{slug}`, supprimée lors de la mise à jour
précédente. Cet échec est antérieur à la présente mise à jour (vérifié sur le
projet d'origine) et n'a pas été corrigé ici pour ne pas mélanger deux sujets.
