<?php

/**
 * Les marques représentées par Avenir Médical, et ce que chacune fournit.
 *
 * Contenu repris de la présentation commerciale « L'expertise médicale à votre
 * service ». Chaque marque peut définir :
 *  - name        : le nom affiché
 *  - country     : le pays du fabricant (laissé vide si à confirmer)
 *  - website     : le site officiel
 *  - description : deux ou trois lignes affichées sur la page Partenaires
 *  - is_featured : la marque apparaît aussi sur la page d'accueil
 *  - domains     : ['Titre exact du domaine' => "une gamme par ligne"]
 *
 * Format d'une ligne de gamme :  Nom du matériel | précision facultative
 *
 * Tout est ensuite modifiable depuis Administration → Partenaires.
 */

return [

    [
        'name' => 'COMEN',
        'country' => 'Chine',
        'website' => 'https://www.comen.com',
        'description' => "Réanimation, anesthésie, néonatalogie et monitorage. Une gamme complète qui permet d'équiper un bloc entier avec un seul interlocuteur technique.",
        'is_featured' => true,
        'domains' => [
            'Bloc opératoire, réanimation & néonatalogie' => <<<'TXT'
            Salle d'opération intégrée | Bras et poutres plafonniers, éclairage opératoire, table d'opération
            Station d'anesthésie | Avec ventilation contrôlée et monitorage des gaz
            Respirateurs de réanimation | Ventilation invasive et non invasive
            Défibrillateurs | Avec électrodes adultes et pédiatriques
            Couveuses néonatales | Régulation de la température, de l'humidité et de l'oxygène
            Tables de réanimation du nouveau-né | Chauffage radiant et aspiration intégrée
            Pousse-seringues et pompes | Administration continue des traitements
            TXT,
            'Imagerie médicale' => <<<'TXT'
            NC5 | Moniteur de chevet compact, écran tactile
            NC19 | Moniteur grand écran, montage sur bras
            C30 | Moniteur de transport portable
            C90 | Moniteur modulaire de réanimation
            Star 8000F | Moniteur multiparamétrique de service
            Star 5000E | Monitorage fœtal avec tracé imprimé
            TXT,
        ],
    ],

    [
        'name' => 'CANON',
        'country' => 'Japon',
        'website' => 'https://global.medical.canon',
        'description' => "Imagerie lourde : scanners et IRM installés, calibrés et maintenus sur place par nos ingénieurs biomédicaux.",
        'is_featured' => true,
        'domains' => [
            'Imagerie médicale' => <<<'TXT'
            Aquilion Start | Scanner d'entrée de gamme, faible dose
            Aquilion Lightning | Scanner polyvalent pour l'activité courante
            Aquilion Serve | Scanner haut débit avec assistance automatisée
            Vantage Elan NX Edition | IRM compacte, faible encombrement
            Vantage Orian | IRM 1,5 T pour l'exploration avancée
            TXT,
        ],
    ],

    [
        'name' => 'EDAN',
        'country' => 'Chine',
        'website' => 'https://www.edan.com',
        'description' => "Échographie de cabinet et de service, du modèle portable au chariot multi-sondes avec Doppler couleur.",
        'is_featured' => true,
        'domains' => [
            'Imagerie médicale' => <<<'TXT'
            LX 3 | Échographe sur chariot, usage polyvalent
            AX 3 | Échographe portable pour les consultations et le lit du patient
            LX 9 | Échographe haut de gamme, imagerie avancée
            TXT,
        ],
    ],

    [
        'name' => 'RANDOX',
        'country' => 'Royaume-Uni',
        'website' => 'https://www.randox.com',
        'description' => "Biochimie clinique : automates, réactifs, calibrateurs et contrôles qualité d'un même fabricant.",
        'is_featured' => true,
        'domains' => [
            "Laboratoire d'analyses médicales" => <<<'TXT'
            Rx Daytona+ | Automate de biochimie pour les laboratoires à fort volume
            Rx Monaco | Automate de biochimie compact
            Réactifs et contrôles Randox | Calibrateurs et contrôles qualité associés
            TXT,
        ],
    ],

    [
        'name' => 'TOSOH',
        'country' => 'Japon',
        'website' => 'https://www.tosohbioscience.com',
        'description' => "Immuno-analyse : dosages hormonaux, marqueurs tumoraux et sérologie sur cartouches prêtes à l'emploi.",
        'is_featured' => true,
        'domains' => [
            "Laboratoire d'analyses médicales" => <<<'TXT'
            AIA-360 | Analyseur d'immuno-analyse de paillasse
            TXT,
        ],
    ],

    [
        'name' => 'BOULE',
        'country' => 'Suède',
        'website' => 'https://boule.com',
        'description' => "Hématologie : numération formule sanguine fiable, adaptée aux volumes des laboratoires et des cliniques.",
        'is_featured' => true,
        'domains' => [
            "Laboratoire d'analyses médicales" => <<<'TXT'
            Swelab Alfa Plus | Automate d'hématologie, avec passeur d'échantillons en option
            TXT,
        ],
    ],

    [
        'name' => 'ZENITHLAB',
        'country' => 'Chine',
        'website' => null,
        'description' => "Équipements généraux de laboratoire : centrifugation, incubation et préparation des échantillons.",
        'is_featured' => false,
        'domains' => [
            "Laboratoire d'analyses médicales" => <<<'TXT'
            LC-06F | Centrifugeuse réfrigérée de grande capacité
            TXT,
        ],
    ],

    [
        'name' => 'ENDOMED SYSTEMS',
        'country' => null,
        'website' => null,
        'description' => "Colonnes d'endoscopie complètes : caméra, source de lumière, insufflateur et écran médical, montés sur chariot.",
        'is_featured' => true,
        'domains' => [
            'Endoscopie et chirurgie mini-invasive' => <<<'TXT'
            Colonne de laparoscopie | Caméra HD, insufflateur CO2 et source de lumière LED
            Colonne d'arthroscopie | Pour la chirurgie articulaire
            Colonne de cystoscopie | Exploration des voies urinaires
            Colonne d'hystéroscopie | Exploration et chirurgie utérine
            Colonne de gastroscopie et coloscopie | Vidéo-endoscopes souples et processeur
            TXT,
        ],
    ],

    [
        'name' => 'SUMER',
        'country' => null,
        'website' => null,
        'description' => "Stérilisation à la vapeur : autoclaves de service et de centrale, en simple ou double porte.",
        'is_featured' => false,
        'domains' => [
            'Hygiène et entretien' => <<<'TXT'
            Autoclaves à vapeur | Différentes capacités, cycles programmables
            Autoclaves double porte | Pour les centrales de stérilisation
            TXT,
        ],
    ],

    [
        'name' => 'ULTRA CONTROLO',
        'country' => 'Portugal',
        'website' => null,
        'description' => "Fluides médicaux : production d'oxygène, air médical et vide, du générateur jusqu'à la prise murale.",
        'is_featured' => true,
        'domains' => [
            'Centrale à oxygène' => <<<'TXT'
            Centrale d'oxygène médical | Production sur site, cuves tampon et rampes de bouteilles
            ULTRAAR | Système d'air médical totalement exempt d'huile
            ULTRAVAC | Système de vide médical centralisé
            Compresseurs médicaux | Avec sécheurs et filtration
            TXT,
        ],
    ],

    [
        'name' => 'SAIKANG MEDICAL',
        'country' => 'Chine',
        'website' => null,
        'description' => "Mobilier hospitalier : lits, tables et chariots pour l'hospitalisation, la maternité et les urgences.",
        'is_featured' => true,
        'domains' => [
            'Mobiliers hospitaliers' => <<<'TXT'
            Lit d'accouchement | Position d'accouchement intégrée, étriers et barrières
            Lit d'hospitalisation électrique | Réglages au vérin, barrières rabattables
            Table gynécologique | Plan électrique, étriers réglables
            Chariot brancard | Hauteur variable, potence et barrières
            TXT,
        ],
    ],

    [
        'name' => 'NITROCARE',
        'country' => 'Turquie',
        'website' => null,
        'description' => "Mobilier hospitalier haut de gamme, du berceau néonatal au brancard d'urgence.",
        'is_featured' => true,
        'domains' => [
            'Mobiliers hospitaliers' => <<<'TXT'
            Lit néonatal | Berceau transparent, hauteur réglable
            Lit d'hospitalisation électrique | Commandes au lit et au pied
            Table gynécologique | Dossier et assise motorisés
            Chariot brancard | Structure renforcée, freinage centralisé
            TXT,
        ],
    ],

    [
        'name' => 'DIALIFE',
        'country' => null,
        'website' => null,
        'description' => "Hémodialyse et hémodiafiltration : générateurs, fauteuils et consommables du poste de dialyse.",
        'is_featured' => true,
        'domains' => [
            'Centre de dialyse' => <<<'TXT'
            DIAADVANCE | Générateur d'hémodialyse avec hémodiafiltration
            DIANOVA | Générateur d'hémodialyse avec hémodiafiltration
            Fauteuil d'hémodialyse | Positions réglables, adapté à l'abord vasculaire
            TXT,
        ],
    ],

    [
        'name' => 'ABRONN',
        'country' => null,
        'website' => null,
        'description' => "Aménagement d'ambulances : cellule sanitaire complète, oxygénothérapie, brancardage et signalisation.",
        'is_featured' => true,
        'domains' => [
            'Ambulances médicalisées' => <<<'TXT'
            Ambulance médicalisée | Cellule sanitaire équipée, livrée testée
            Aménagement de la cellule | Brancard, oxygène, rangements, gyrophares et sirène
            TXT,
        ],
    ],

    [
        'name' => 'FAGOR',
        'country' => 'Espagne',
        'website' => 'https://www.fagorindustrial.com',
        'description' => "Blanchisserie hospitalière : le circuit complet du linge, du lavage au repassage.",
        'is_featured' => false,
        'domains' => [
            'Hygiène et entretien' => <<<'TXT'
            Lave-linge professionnel | Machines à laver essoreuses de blanchisserie
            Sèche-linge professionnel | Séchage rapide de grande capacité
            Machine à repasser | Calandre repasseuse pour le linge plat
            TXT,
        ],
    ],

    [
        'name' => 'SANISWISS',
        'country' => 'Suisse',
        'website' => 'https://www.saniswiss.com',
        'description' => "Désinfection des mains, des surfaces et des instruments, avec des solutions de diffusion automatisée.",
        'is_featured' => true,
        'domains' => [
            'Hygiène et entretien' => <<<'TXT'
            Gel thixotropique | Désinfection des mains sans coulure
            Sanitizer Surfaces | Désinfectant de surfaces en pulvérisateur
            Sanicleaner Surfaces | Détergent-désinfectant des surfaces
            Sanitizer Surfaces en lingettes | Désinfection rapide au lit du patient
            Sanitizer Instruments | Pré-désinfection enzymatique des instruments
            Sanitizer Automate | Solution pour diffusion automatisée
            Smart Automate | Appareil de désinfection automatisée des locaux
            TXT,
        ],
    ],

    [
        'name' => 'DISE',
        'country' => 'Sénégal',
        'website' => null,
        'description' => "La marque de consommables et de dispositifs d'Avenir Médical, tournée vers l'industrialisation locale et la souveraineté pharmaceutique.",
        'is_featured' => true,
        'domains' => [
            'Consommables biomédicaux' => <<<'TXT'
            Papier ECG | Rouleaux pour électrocardiographes
            Seringues et aiguilles | Usage unique, différents volumes
            Cathéters veineux périphériques | Avec et sans ailettes
            Gants d'examen et chirurgicaux | Latex et nitrile, stériles ou non
            Compresses et bandes | Stériles et non stériles
            Tubes de prélèvement | Tubes sous vide, différents additifs
            Champs opératoires et casaques | Sets stériles à usage unique
            Kits de soins | Sets de pansement prêts à l'emploi
            Solutés de perfusion | G5, G10, Ringer Lactate et NaCl 0,9 %
            TXT,
            'Petits matériels et équipements hospitaliers' => <<<'TXT'
            Électrocardiographes | ECG de consultation et de service
            Oxymètres de pouls | De doigt et de table
            Moniteurs de surveillance | Suivi des paramètres vitaux
            Nébuliseurs | Aérosolthérapie adulte et enfant
            Injecteurs et pousse-seringues | Administration contrôlée
            Abaisse-langue | Bois, usage unique
            TXT,
            'Mobiliers hospitaliers' => <<<'TXT'
            Mobilier de service | Chariots, guéridons et rangements
            Ascenseurs médicaux | Équipements techniques du bâtiment hospitalier
            TXT,
        ],
    ],

    [
        'name' => 'GENERTEC INTERNATIONAL',
        'country' => 'Chine',
        'website' => null,
        'description' => "Partenaire pour les projets clé en main : ingénierie, réalisation et financement des plateaux techniques.",
        'is_featured' => false,
        'domains' => [],
    ],

    [
        'name' => 'HOLTEX',
        'country' => 'France',
        'website' => null,
        'description' => "Distribution de matériel médical et de consommables pour les cabinets et les structures de proximité.",
        'is_featured' => false,
        'domains' => [],
    ],

];
