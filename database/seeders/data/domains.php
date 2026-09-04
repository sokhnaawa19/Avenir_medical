<?php

/**
 * Contenu de départ des domaines d'intervention.
 *
 * Chaque domaine peut définir :
 *  - icon             : un emoji affiché sur les cartes et en tête de page
 *  - subtitle         : trois ou quatre mots de résumé
 *  - intro            : l'accroche affichée en gros
 *  - description      : le paragraphe de présentation
 *  - equipments       : les équipements types [titre, précision]
 *  - targets          : les structures concernées
 *  - benefits         : nos engagements [titre, explication]
 *  - faq              : les questions fréquentes [question, réponse]
 *  - meta_description : le texte affiché dans les résultats Google
 *
 * Tous ces textes sont modifiables depuis Administration → Domaines.
 */

return [

    [
        'title' => 'Ambulances médicalisées',
        'icon' => '🚑',
        'subtitle' => 'Transport sanitaire équipé',
        'in_gallery' => true,
        'intro' => "Des ambulances prêtes à intervenir, aménagées et équipées selon le niveau de soins que vous devez assurer pendant le transport.",
        'description' => "Une ambulance n'est pas seulement un véhicule : c'est une unité de soins mobile. Nous livrons des ambulances de type A, B et C, aménagées en cellule sanitaire complète, avec l'oxygénothérapie, le brancardage et le monitorage embarqués.\n\nNous partons de vos besoins réels : distance des évacuations, état des routes, type de patients transportés, budget disponible. Nous vous proposons ensuite la configuration adaptée, du transport simple à l'ambulance de réanimation. Le véhicule est livré équipé, testé, et vos équipes sont formées à l'utilisation du matériel embarqué.",
        'equipments' => [
            ['title' => 'Brancard principal à chargement', 'text' => "Structure aluminium, dossier réglable, sangles de sécurité et ancrage au plancher"],
            ['title' => 'Chaise portoir', 'text' => 'Pour les évacuations en étage, dans les escaliers et les couloirs étroits'],
            ['title' => "Installation d'oxygène", 'text' => "Bouteilles fixes et portables, détendeurs, débitmètres et prises murales dans la cellule"],
            ['title' => 'Aspirateur de mucosités', 'text' => 'Fixe sur 12 V et modèle portable sur batterie'],
            ['title' => 'Moniteur multiparamétrique de transport', 'text' => 'ECG, SpO2, tension, capnographie, avec autonomie sur batterie'],
            ['title' => 'Défibrillateur semi-automatique', 'text' => 'Avec électrodes adultes et pédiatriques'],
            ['title' => 'Respirateur de transport', 'text' => 'Pour les ambulances de réanimation de type C'],
            ['title' => "Matériel d'immobilisation", 'text' => 'Plan dur, matelas à dépression, colliers cervicaux et attelles'],
            ['title' => 'Aménagement de la cellule', 'text' => "Banquette, rangements, éclairage, ventilation, gyrophares et sirène"],
        ],
    ],

    [
        'title' => 'Bloc opératoire, réanimation & néonatalogie',
        'icon' => '🫀',
        'subtitle' => 'Chirurgie, réanimation et néonatalogie',
        'in_gallery' => true,
        'intro' => "L'équipement complet des services les plus critiques de votre structure, de la table d'opération à la couveuse néonatale.",
        'description' => "Le bloc opératoire, la réanimation et la néonatalogie ne tolèrent aucune approximation. Le matériel doit fonctionner en permanence, être compatible entre les différentes marques et pouvoir être réparé rapidement.\n\nNous équipons ces trois services en solution complète : conception du plateau technique, fourniture des équipements, installation, mise en service et formation. Nos ingénieurs biomédicaux vérifient les contraintes techniques — alimentation électrique, fluides médicaux, ventilation, circulation dans le service — avant même de vous proposer un devis.",
        'equipments' => [
            ['title' => "Tables d'opération", 'text' => "Hydrauliques ou électriques, plateau radio-transparent, positions multiples et accessoires"],
            ['title' => 'Éclairages scialytiques', 'text' => "LED sans ombre portée, intensité réglable, sur plafonnier ou sur pied"],
            ['title' => "Respirateurs d'anesthésie", 'text' => "Avec évaporateurs, monitorage des gaz et ventilation contrôlée adulte et enfant"],
            ['title' => 'Respirateurs de réanimation', 'text' => "Ventilation invasive et non invasive, écran de courbes, alarmes paramétrables"],
            ['title' => 'Bistouris électriques', 'text' => 'Coupe et coagulation, mono et bipolaire, avec plaques patient et accessoires'],
            ['title' => 'Moniteurs multiparamétriques', 'text' => 'ECG, SpO2, PNI, température, capnographie, avec centrale de surveillance possible'],
            ['title' => 'Couveuses néonatales', 'text' => "Régulation de température et d'humidité, contrôle de l'oxygène, accès latéraux"],
            ['title' => 'Tables de réanimation du nouveau-né', 'text' => 'Chauffage radiant, aspiration, arrivée oxygène et minuteur Apgar'],
            ['title' => 'Lampes de photothérapie', 'text' => 'LED bleues pour le traitement de la jaunisse du nouveau-né'],
            ['title' => 'Pompes et pousse-seringues', 'text' => "Administration précise des traitements en continu"],
            ['title' => 'Autoclaves et stérilisation', 'text' => "Stérilisateurs à vapeur, bacs de décontamination et matériel de conditionnement"],
            ['title' => 'Mobilier de bloc', 'text' => 'Tables instrumentiste, guéridons, tabourets chirurgiens, chariots et paravents'],
        ],
    ],

    [
        'title' => 'Imagerie médicale',
        'icon' => '🩻',
        'subtitle' => 'Radiologie, échographie, monitoring',
        'in_gallery' => true,
        'intro' => "Du poste de radiologie numérique à l'échographe de cabinet : des solutions d'imagerie installées, calibrées et maintenues sur place.",
        'description' => "L'imagerie conditionne la qualité du diagnostic. Un appareil mal choisi ou mal installé, c'est un service qui tourne au ralenti et des patients renvoyés ailleurs.\n\nNous vous accompagnons sur les trois points qui comptent vraiment : le choix de l'appareil au regard de votre volume d'examens, l'aménagement technique de la salle — protection plombée, alimentation électrique, climatisation — et la maintenance dans la durée. Nos ingénieurs assurent l'installation, le calibrage et la formation des manipulateurs.",
        'equipments' => [
            ['title' => 'Tables de radiologie numérique', 'text' => "Capteur plan, console d'acquisition, table et statif, avec traitement d'image"],
            ['title' => 'Appareils de radiologie mobile', 'text' => 'Pour les examens au lit du patient, en réanimation et au bloc'],
            ['title' => 'Échographes', 'text' => "Du modèle portable de cabinet au haut de gamme multi-sondes avec Doppler couleur"],
            ['title' => 'Sondes échographiques', 'text' => 'Convexe, linéaire, endocavitaire, cardiaque, adaptées à chaque spécialité'],
            ['title' => 'Mammographes', 'text' => 'Numériques, pour le dépistage et le diagnostic sénologique'],
            ['title' => 'Radiologie dentaire', 'text' => 'Panoramiques dentaires et appareils rétro-alvéolaires'],
            ['title' => 'Négatoscopes et reprographie', 'text' => "Négatoscopes LED, imprimantes médicales et archivage des images"],
            ['title' => 'Radioprotection', 'text' => 'Tabliers plombés, protège-thyroïde, lunettes, paravents et vitrages plombés'],
            ['title' => 'Moniteurs de surveillance', 'text' => "Suivi des paramètres vitaux pendant les examens et les gestes interventionnels"],
        ],
    ],

    [
        'title' => "Laboratoire d'analyses médicales",
        'icon' => '🔬',
        'subtitle' => 'Diagnostic et analyses médicales',
        'in_gallery' => true,
        'intro' => "L'équipement complet d'un laboratoire, de la paillasse de prélèvement à l'automate de biochimie, réactifs compris.",
        'description' => "Un laboratoire fiable repose sur trois choses : des automates adaptés à votre volume d'analyses, un approvisionnement régulier en réactifs, et une maintenance qui évite les arrêts.\n\nNous équipons les laboratoires d'hôpitaux, de cliniques et les laboratoires privés, en biochimie, hématologie, immunologie, bactériologie et sérologie. Nous vous aidons à dimensionner correctement votre plateau technique et à sécuriser la chaîne d'approvisionnement en réactifs, pour que votre laboratoire ne s'arrête jamais faute de consommables.",
        'equipments' => [
            ['title' => 'Automates de biochimie', 'text' => "Analyseurs semi-automatiques et automatiques, avec calibration et contrôles qualité"],
            ['title' => "Automates d'hématologie", 'text' => 'Numération formule sanguine, 3 ou 5 populations selon votre volume'],
            ['title' => 'Analyseurs de coagulation', 'text' => 'TP, TCA, fibrinogène et bilans complets'],
            ['title' => 'Microscopes de laboratoire', 'text' => "Binoculaires et trinoculaires, fond clair, contraste de phase ou fluorescence"],
            ['title' => 'Centrifugeuses', 'text' => 'De paillasse, à angle fixe ou rotor libre, pour tubes et hématocrite'],
            ['title' => 'Étuves, incubateurs et bains-marie', 'text' => 'Culture bactérienne et incubation à température contrôlée'],
            ['title' => 'Sérologie et immunologie', 'text' => "Lecteurs ELISA, laveurs de microplaques et analyseurs d'immunologie"],
            ['title' => 'Postes de sécurité microbiologique', 'text' => "Hottes à flux laminaire pour la manipulation des échantillons sensibles"],
            ['title' => 'Réactifs et consommables', 'text' => "Réactifs, calibrateurs, contrôles, tubes, cônes, lames et colorants"],
            ['title' => 'Mobilier de laboratoire', 'text' => 'Paillasses résistantes, éviers, rangements et tabourets réglables'],
        ],
    ],

    [
        'title' => 'Centrale à oxygène',
        'icon' => '🫁',
        'subtitle' => "Production et distribution d'oxygène",
        'intro' => "Produisez votre propre oxygène médical sur site et distribuez-le jusqu'au lit du patient, sans dépendre des livraisons de bouteilles.",
        'description' => "Dépendre uniquement des bouteilles d'oxygène, c'est subir les ruptures, les coûts de transport et les délais. Une centrale à oxygène produit l'oxygène médical directement dans votre établissement, en continu.\n\nNous concevons et installons l'ensemble de la chaîne : la production par concentrateur ou générateur PSA, le stockage, le réseau de canalisations jusqu'aux chambres, les prises murales et les systèmes d'alarme. Nous dimensionnons l'installation selon votre nombre de lits et vos services critiques, puis nous assurons la maintenance de l'ensemble.",
        'equipments' => [
            ['title' => 'Générateurs PSA', 'text' => "Production d'oxygène médical à partir de l'air ambiant, en fonctionnement continu"],
            ['title' => 'Concentrateurs individuels', 'text' => "Pour les chambres, les cabinets et le domicile, en 5 ou 10 litres par minute"],
            ['title' => 'Compresseurs et sécheurs', 'text' => "Air médical comprimé, filtré et déshumidifié pour les besoins des services"],
            ['title' => 'Cuves et rampes de bouteilles', 'text' => "Stockage tampon et secours automatique en cas d'arrêt de production"],
            ['title' => 'Réseau de canalisations', 'text' => "Tuyauterie cuivre médical, vannes de sectionnement et repérage réglementaire"],
            ['title' => 'Prises murales et bras plafonniers', 'text' => "Oxygène, air médical et vide, aux normes, dans les chambres et les blocs"],
            ['title' => 'Centrales de vide médical', 'text' => "Aspiration centralisée pour le bloc, la réanimation et les chambres"],
            ['title' => "Systèmes d'alarme", 'text' => "Contrôle de la pression, de la pureté et alertes en cas d'anomalie"],
            ['title' => 'Débitmètres et humidificateurs', 'text' => "Délivrance au lit du patient, avec masques et lunettes à oxygène"],
        ],
    ],

    [
        'title' => 'Centre de dialyse',
        'icon' => '💧',
        'subtitle' => 'Équipements de dialyse complets',
        'intro' => "L'équipement complet d'une unité de dialyse : générateurs, traitement d'eau, fauteuils et consommables, installés et suivis par nos ingénieurs.",
        'description' => "Ouvrir ou agrandir une unité de dialyse demande de traiter simultanément trois sujets : les générateurs, la qualité de l'eau et l'approvisionnement en consommables. Une faiblesse sur l'un des trois compromet toute la séance.\n\nNous concevons l'unité dans son ensemble : nombre de postes, station de traitement d'eau dimensionnée en conséquence, réseau de distribution, fauteuils et monitorage. Nous assurons l'installation, la formation des équipes, le suivi de la qualité de l'eau et l'approvisionnement régulier en dialyseurs et solutions.",
        'equipments' => [
            ['title' => 'Générateurs de dialyse', 'text' => "Machines d'hémodialyse avec contrôle de l'ultrafiltration et surveillance des paramètres"],
            ['title' => "Stations de traitement d'eau", 'text' => "Osmose inverse, adoucisseurs, filtration et déchloration pour une eau ultrapure"],
            ['title' => 'Réseau de distribution', 'text' => "Boucle en matériau inerte, avec points de puisage à chaque poste"],
            ['title' => 'Fauteuils de dialyse', 'text' => "Positions réglables, repose-jambes et accoudoirs adaptés à l'abord vasculaire"],
            ['title' => 'Dialyseurs et lignes', 'text' => "Dialyseurs de différentes surfaces, lignes artério-veineuses et aiguilles de fistule"],
            ['title' => 'Concentrés et solutions', 'text' => "Bicarbonate, concentrés acides et solutions de désinfection des circuits"],
            ['title' => 'Balances et monitorage', 'text' => "Pesée précise avant et après séance, tensiomètres et moniteurs"],
            ['title' => "Matériel d'urgence du service", 'text' => "Chariot d'urgence, oxygénothérapie et défibrillateur à proximité des postes"],
        ],
    ],

    [
        'title' => 'Mobiliers hospitaliers',
        'icon' => '🛏️',
        'subtitle' => 'Lits, chariots, aménagement des services',
        'intro' => "Des lits et du mobilier robustes, pensés pour un usage intensif et pour le confort du personnel comme des patients.",
        'description' => "Le mobilier hospitalier est le matériel le plus sollicité de votre structure : il sert tous les jours, toute l'année. Un lit mal conçu se dégrade en quelques mois et pèse sur le dos des soignants.\n\nNous sélectionnons du mobilier robuste, facile à nettoyer et adapté à un usage intensif : lits médicalisés mécaniques ou électriques, chariots, tables d'examen, mobilier de chambre et d'accueil. Nous assurons la livraison, le montage sur place et le remplacement des pièces d'usure — roulettes, vérins, manivelles — pour prolonger la durée de vie du matériel.",
        'equipments' => [
            ['title' => 'Lits médicalisés', 'text' => "1, 2, 3 ou 4 manivelles, ou électriques, avec relève-buste et relève-jambes"],
            ['title' => 'Lits de réanimation', 'text' => 'Position Trendelenburg, barrières intégrales, potence et réglage en hauteur'],
            ['title' => 'Lits et berceaux pédiatriques', 'text' => "Lits d'enfant à barreaux et berceaux de maternité en plexiglas"],
            ['title' => "Tables d'examen et divans", 'text' => "Fixes ou à hauteur variable, avec dérouleur de papier et sellerie lavable"],
            ['title' => 'Tables gynécologiques', 'text' => 'Étriers réglables, position déclive et plan hydraulique ou électrique'],
            ['title' => 'Chariots de soins et de médicaments', 'text' => 'Tiroirs, plateau de travail, poubelle et support collecteur'],
            ['title' => "Chariots d'urgence", 'text' => "Rangement structuré, support bouteille d'oxygène et fermeture à scellé"],
            ['title' => 'Brancards et fauteuils roulants', 'text' => 'Brancards de transfert, fauteuils pliants et fauteuils de transport'],
            ['title' => 'Mobilier de chambre', 'text' => 'Tables de nuit, adaptables de lit, paravents et fauteuils accompagnant'],
            ['title' => 'Armoires et rangements', 'text' => "Armoires à pharmacie, vitrines à instruments et rangements pour consommables"],
            ['title' => 'Pieds à sérum et guéridons', 'text' => 'Potences à roulettes réglables et petits mobiliers de service'],
            ['title' => "Mobilier de salle d'attente", 'text' => 'Chaises et bancs résistants, faciles à nettoyer'],
        ],
    ],

    [
        'title' => 'Consommables biomédicaux',
        'icon' => '🧤',
        'subtitle' => 'Gants, masques, perfusion, sutures',
        'intro' => "Les consommables du quotidien, disponibles en stock à Dakar et réapprovisionnés régulièrement pour que vos services ne s'arrêtent jamais.",
        'description' => "Les consommables ne coûtent pas cher à l'unité, mais leur rupture arrête un service entier. C'est pourquoi nous travaillons sur la régularité de l'approvisionnement autant que sur le prix.\n\nNous fournissons l'ensemble des consommables médicaux : protection, injection et perfusion, pansements, sutures, sondage et drainage, prélèvement. Pour les structures qui consomment des volumes importants, nous mettons en place un réapprovisionnement planifié, calé sur votre consommation réelle, avec des tarifs négociés à l'année.",
        'equipments' => [
            ['title' => 'Gants', 'text' => "Examen en latex ou nitrile, poudrés ou non, et gants chirurgicaux stériles"],
            ['title' => 'Protection du personnel', 'text' => 'Masques chirurgicaux et FFP2, charlottes, surblouses, sur-chaussures et lunettes'],
            ['title' => 'Injection et perfusion', 'text' => 'Seringues, aiguilles, cathéters, tubulures, robinets et prolongateurs'],
            ['title' => 'Pansements et compresses', 'text' => "Compresses stériles et non stériles, bandes, sparadrap et pansements adhésifs"],
            ['title' => 'Sutures', 'text' => 'Fils résorbables et non résorbables, sertis, en différents calibres'],
            ['title' => 'Sondage et drainage', 'text' => "Sondes urinaires, poches de recueil, sondes d'aspiration et drains"],
            ['title' => 'Prélèvement', 'text' => "Tubes sous vide, corps de pompe, garrots, lames et lamelles"],
            ['title' => 'Champs et casaques', 'text' => "Champs opératoires stériles, casaques chirurgicales et sets de soins"],
            ['title' => 'Élimination des déchets', 'text' => "Collecteurs d'aiguilles, sacs DASRI et conteneurs adaptés"],
            ['title' => 'Petit matériel de soins', 'text' => "Abaisse-langue, thermomètres, embouts auriculaires et haricots"],
        ],
    ],

    [
        'title' => 'Hygiène et entretien',
        'icon' => '🧴',
        'subtitle' => 'Désinfection, stérilisation, entretien',
        'intro' => "Tout ce qui permet de maîtriser le risque infectieux : désinfectants, stérilisation, protection et matériel d'entretien des locaux.",
        'description' => "L'hygiène hospitalière n'est pas un poste secondaire : c'est ce qui protège vos patients des infections associées aux soins et vos équipes des contaminations.\n\nNous fournissons la chaîne complète : hygiène des mains, désinfection des surfaces et des instruments, stérilisation, gestion des déchets de soins et matériel d'entretien des locaux. Nous aidons aussi les structures à organiser leurs protocoles, à choisir le bon produit pour chaque usage et à former le personnel d'entretien.",
        'equipments' => [
            ['title' => 'Hygiène des mains', 'text' => "Solutions hydro-alcooliques, savons antiseptiques et distributeurs muraux"],
            ['title' => 'Antiseptiques', 'text' => "Povidone iodée, chlorhexidine, alcool, pour peau saine ou lésée"],
            ['title' => 'Désinfectants de surfaces', 'text' => "Détergents-désinfectants pour sols, surfaces et dispositifs médicaux"],
            ['title' => 'Autoclaves et stérilisateurs', 'text' => 'Stérilisateurs à vapeur de différentes capacités, cycles programmables'],
            ['title' => 'Conditionnement de stérilisation', 'text' => "Sachets et gaines, indicateurs de passage et tests de contrôle"],
            ['title' => 'Bacs de décontamination', 'text' => "Bacs à couvercle et paniers pour le trempage des instruments"],
            ['title' => 'Laveurs et bacs à ultrasons', 'text' => "Nettoyage en profondeur des instruments avant stérilisation"],
            ['title' => 'Matériel de nettoyage des locaux', 'text' => "Chariots de ménage, balais à franges, seaux à deux compartiments"],
            ['title' => 'Gestion des déchets de soins', 'text' => "Poubelles à pédale, sacs et conteneurs DASRI, collecteurs d'objets piquants"],
            ['title' => 'Blanchisserie', 'text' => "Chariots à linge, sacs de collecte et produits de traitement du linge"],
        ],
    ],

    [
        'title' => 'Ophtalmologie',
        'icon' => '👁️',
        'subtitle' => 'Diagnostic et chirurgie de la vue',
        'intro' => "L'équipement d'un cabinet ou d'un service d'ophtalmologie, du réfractomètre au microscope opératoire.",
        'description' => "L'ophtalmologie demande des appareils précis et bien réglés : une erreur de mesure se traduit directement par une correction inadaptée ou un diagnostic manqué.\n\nNous équipons les cabinets, les services hospitaliers et les centres de dépistage : réfraction, examen du segment antérieur, mesure de la tension oculaire, fond d'œil, et matériel de chirurgie de la cataracte. Nous assurons l'installation, le réglage et la formation, ainsi que le suivi et l'étalonnage des appareils dans la durée.",
        'equipments' => [
            ['title' => 'Réfractomètres automatiques', 'text' => "Mesure objective de la réfraction, avec kératométrie intégrée"],
            ['title' => 'Lampes à fente', 'text' => "Examen du segment antérieur, avec tonomètre à aplanation en option"],
            ['title' => 'Tonomètres', 'text' => "Mesure de la pression intraoculaire, pour le dépistage du glaucome"],
            ['title' => 'Rétinographes et ophtalmoscopes', 'text' => "Examen du fond d'œil et dépistage de la rétinopathie diabétique"],
            ['title' => 'Réfracteurs et boîtes de verres', 'text' => "Réfracteurs manuels ou automatiques, montures d'essai et boîtes complètes"],
            ['title' => "Projecteurs de tests d'acuité", 'text' => "Mesure de l'acuité visuelle de loin et de près"],
            ['title' => 'Microscopes opératoires', 'text' => "Pour la chirurgie de la cataracte et du segment antérieur"],
            ['title' => 'Phacoémulsificateurs', 'text' => "Chirurgie moderne de la cataracte par petite incision"],
            ['title' => 'Instruments de microchirurgie', 'text' => "Boîtes d'instruments spécifiques à la chirurgie oculaire"],
            ['title' => 'Unités et fauteuils de consultation', 'text' => "Colonne de consultation, fauteuil patient réglable et tables d'instruments"],
        ],
    ],

    [
        'title' => 'Réfrigérateurs biomédicaux',
        'icon' => '❄️',
        'subtitle' => 'Vaccins et produits sensibles',
        'intro' => "Une chaîne du froid fiable, avec des équipements qui tiennent la température même en cas de coupure de courant.",
        'description' => "Un vaccin ou une poche de sang conservé hors de sa plage de température est perdu, sans qu'on s'en aperçoive toujours. La chaîne du froid médicale demande donc des équipements spécifiques, très différents d'un réfrigérateur domestique : régulation précise, alarmes et autonomie en cas de coupure.\n\nNous fournissons des réfrigérateurs et congélateurs médicaux à température régulée et surveillée, y compris des modèles solaires adaptés aux zones où l'électricité est instable. Nous assurons l'installation, le réglage des seuils d'alarme et la formation à la surveillance quotidienne.",
        'equipments' => [
            ['title' => 'Réfrigérateurs à vaccins', 'text' => "Plage de 2 à 8 °C, régulation précise, alarme de dépassement et enregistrement"],
            ['title' => 'Réfrigérateurs solaires', 'text' => "Fonctionnement autonome sur panneaux, pour les zones sans réseau stable"],
            ['title' => 'Congélateurs médicaux', 'text' => "-20 °C et basses températures pour les produits à congeler"],
            ['title' => 'Armoires à réactifs', 'text' => "Conservation des réactifs de laboratoire à température contrôlée"],
            ['title' => 'Réfrigérateurs à sang', 'text' => "Conservation des poches selon les exigences des banques de sang"],
            ['title' => 'Enregistreurs de température', 'text' => "Traçabilité continue, historique téléchargeable et alertes en cas de dérive"],
            ['title' => 'Porte-vaccins et glacières', 'text' => "Transport des vaccins vers les postes avancés, avec accumulateurs de froid"],
            ['title' => 'Accumulateurs de froid', 'text' => "Maintien de la température pendant les tournées de vaccination"],
        ],
    ],

    [
        'title' => 'Petits matériels et équipements hospitaliers',
        'icon' => '🩺',
        'subtitle' => "L'essentiel du quotidien médical",
        'intro' => "Le matériel de tous les jours : diagnostic, soins courants et petit équipement, disponible rapidement et à prix maîtrisé.",
        'description' => "Ce sont les appareils dont on ne parle jamais, mais sans lesquels aucune consultation ne se fait : le tensiomètre, le stéthoscope, l'oxymètre, la balance, le chariot de soins.\n\nNous fournissons tout ce petit équipement en qualité professionnelle, avec un stock disponible à Dakar. C'est aussi la gamme idéale pour équiper un cabinet qui démarre, un poste de santé ou une infirmerie d'entreprise : nous constituons avec vous une liste complète et cohérente, adaptée à votre budget.",
        'equipments' => [
            ['title' => 'Tensiomètres', 'text' => 'Manuels à brassard, électroniques de bras et modèles sur pied'],
            ['title' => 'Stéthoscopes', 'text' => 'Simple et double pavillon, adulte, pédiatrique et cardiologique'],
            ['title' => 'Oxymètres de pouls', 'text' => 'De doigt et de table, en versions adulte et pédiatrique'],
            ['title' => 'Thermomètres', 'text' => 'Frontaux sans contact, auriculaires et électroniques'],
            ['title' => 'Glucomètres et bandelettes', 'text' => "Autosurveillance et dépistage du diabète, avec lancettes"],
            ['title' => 'Balances et toises', 'text' => 'Balances adulte, pèse-bébé, balances-colonnes et toises murales'],
            ['title' => 'Otoscopes et ophtalmoscopes', 'text' => "Sets de diagnostic ORL et ophtalmologique, éclairage LED"],
            ['title' => 'Marteaux à réflexes et diapasons', 'text' => "Matériel de base pour l'examen neurologique"],
            ['title' => 'Nébuliseurs et aérosols', 'text' => "Aérosolthérapie avec masques adulte et enfant"],
            ['title' => 'Aspirateurs de mucosités', 'text' => 'Portables et de table, pour les cabinets et les services'],
            ['title' => "Lampes d'examen", 'text' => "Éclairage d'examen sur pied ou mural, à LED"],
            ['title' => 'Boîtes et instruments de soins', 'text' => "Boîtes de pansement, pinces, ciseaux, plateaux et haricots en inox"],
        ],
    ],

    [
        'title' => 'Endoscopie et chirurgie mini-invasive',
        'icon' => '🔎',
        'subtitle' => 'Colonnes vidéo et instrumentation',
        'in_gallery' => true,
        'intro' => "Des colonnes d'endoscopie complètes, montées et testées, pour opérer et explorer sans ouvrir.",
        'description' => "La chirurgie mini-invasive raccourcit les hospitalisations et réduit les complications. Elle suppose en revanche une chaîne d'image irréprochable : une optique nette, une source de lumière stable et un écran fidèle.\n\nNous livrons la colonne complète, adaptée à la spécialité concernée, avec l'instrumentation, le circuit de décontamination des optiques et la formation des équipes. Nos techniciens assurent ensuite la maintenance des caméras et des endoscopes, qui sont les pièces les plus fragiles du plateau.",
        'equipments' => [
            ['title' => 'Colonnes de laparoscopie', 'text' => "Caméra HD, source de lumière LED, insufflateur CO2 et écran médical"],
            ['title' => "Colonnes d'arthroscopie", 'text' => 'Pour la chirurgie du genou, de l\'épaule et des articulations'],
            ['title' => 'Colonnes de cystoscopie', 'text' => "Exploration et gestes sur les voies urinaires"],
            ['title' => "Colonnes d'hystéroscopie", 'text' => "Diagnostic et chirurgie de la cavité utérine"],
            ['title' => 'Colonnes de gastroscopie et coloscopie', 'text' => "Vidéo-endoscopes souples et processeur d'image"],
            ['title' => 'Optiques et endoscopes rigides', 'text' => "Différents diamètres et angles de vision selon la spécialité"],
            ['title' => 'Instruments de cœlioscopie', 'text' => 'Trocarts, pinces, ciseaux, porte-aiguilles et crochets'],
            ['title' => 'Bistouris électriques', 'text' => "Générateurs mono et bipolaires, avec accessoires dédiés"],
            ['title' => 'Décontamination des endoscopes', 'text' => "Bacs de trempage, laveurs et produits enzymatiques"],
            ['title' => 'Armoires de séchage et de stockage', 'text' => "Conservation des endoscopes souples entre deux examens"],
        ],
    ],

];
