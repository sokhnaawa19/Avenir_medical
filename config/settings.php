<?php

/*
|--------------------------------------------------------------------------
| Parametres du site
|--------------------------------------------------------------------------
|
| Ce fichier decrit TOUS les reglages modifiables depuis l'administration
| (Reglages du site). Chaque champ est enregistre dans la table "settings".
|
| Pour ajouter un nouveau reglage : ajoutez une ligne dans le bon groupe.
| Il apparaitra automatiquement dans le formulaire de l'administration et
| sera utilisable dans les vues avec setting('ma_cle').
|
| Types disponibles : text, textarea, richtext, email, tel, url, number,
|                     color, image, boolean, select
|
*/

return [

    'identite' => [
        'label' => 'Identité du site',
        'icon' => '🏷️',
        'description' => "Nom et slogan de l'entreprise.",
        'fields' => [
            'site_name' => ['label' => 'Nom du site', 'type' => 'text', 'default' => 'AVENIR MEDICAL', 'rules' => 'required|string|max:120'],
            'site_baseline' => ['label' => 'Slogan', 'type' => 'text', 'default' => 'Équipements médicaux à Dakar, Sénégal'],
        ],
    ],

    'contact' => [
        'label' => 'Coordonnées',
        'icon' => '📞',
        'description' => 'Téléphones, email, adresse et horaires.',
        'fields' => [
            'phone_1' => ['label' => 'Téléphone principal', 'type' => 'tel', 'default' => '+221 33 827 20 36'],
            'phone_2' => ['label' => 'Téléphone 2', 'type' => 'tel', 'default' => '+221 77 471 07 09'],
            'phone_3' => ['label' => 'Téléphone 3', 'type' => 'tel', 'default' => '+221 77 765 82 34'],
            'whatsapp' => ['label' => 'WhatsApp — numéro principal', 'type' => 'tel', 'default' => '+221 77 471 07 09', 'help' => 'Laissez vide pour masquer le bouton WhatsApp.'],
            'whatsapp_1_label' => ['label' => 'WhatsApp 1 — intitulé', 'type' => 'text', 'default' => 'Laboratoires & équipements', 'help' => "Si vous renseignez plusieurs numéros, le bouton propose de choisir le service."],
            'whatsapp_1_number' => ['label' => 'WhatsApp 1 — numéro', 'type' => 'tel', 'default' => '+221 77 733 73 44'],
            'whatsapp_2_label' => ['label' => 'WhatsApp 2 — intitulé', 'type' => 'text', 'default' => 'Bloc, néonatalogie & imagerie'],
            'whatsapp_2_number' => ['label' => 'WhatsApp 2 — numéro', 'type' => 'tel', 'default' => '+221 77 733 73 09'],
            'whatsapp_3_label' => ['label' => 'WhatsApp 3 — intitulé', 'type' => 'text', 'default' => ''],
            'whatsapp_3_number' => ['label' => 'WhatsApp 3 — numéro', 'type' => 'tel', 'default' => ''],
            'email' => ['label' => 'Email principal', 'type' => 'email', 'default' => 'contact@avenir-medic.com', 'rules' => 'nullable|email|max:190'],
            'email_orders' => ['label' => 'Email de réception des commandes', 'type' => 'email', 'default' => 'contact@avenir-medic.com', 'rules' => 'nullable|email|max:190'],
            'address' => ['label' => 'Adresse', 'type' => 'textarea', 'default' => 'Dakar, Sénégal'],
            'opening_hours' => ['label' => 'Horaires d’ouverture', 'type' => 'text', 'default' => 'Du lundi au samedi, 8h – 18h'],
            'map_embed' => ['label' => 'Carte Google Maps', 'type' => 'textarea', 'help' => 'Collez ici le code d’intégration (iframe) fourni par Google Maps.'],
        ],
    ],

    'reseaux' => [
        'label' => 'Réseaux sociaux',
        'icon' => '🌍',
        'description' => 'Liens affichés dans le pied de page.',
        'fields' => [
            'facebook' => ['label' => 'Facebook', 'type' => 'url', 'rules' => 'nullable|url|max:190'],
            'instagram' => ['label' => 'Instagram', 'type' => 'url', 'rules' => 'nullable|url|max:190'],
            'linkedin' => ['label' => 'LinkedIn', 'type' => 'url', 'rules' => 'nullable|url|max:190'],
            'youtube' => ['label' => 'YouTube', 'type' => 'url', 'rules' => 'nullable|url|max:190'],
        ],
    ],

    'accueil' => [
        'label' => "Page d'accueil",
        'icon' => '🏠',
        'description' => 'Grande image, titres et boutons de la première page.',
        'fields' => [
            'hero_eyebrow' => ['label' => 'Petit texte au-dessus du titre', 'type' => 'text', 'default' => 'Solutions médicales · Sénégal & Afrique de l’Ouest'],
            'hero_title' => ['label' => 'Grand titre', 'type' => 'text', 'default' => "Équiper les établissements de santé. Accompagner ceux qui les font vivre."],
            'hero_highlight' => ['label' => 'Mots à mettre en couleur', 'type' => 'text', 'default' => "d'aujourd'hui", 'help' => 'Ces mots seront colorés dans le titre.'],
            'hero_text' => ['label' => 'Texte d’introduction', 'type' => 'textarea', 'default' => "Des équipements médicaux fiables, associés à l'installation, la maintenance et la formation de vos équipes."],
            'hero_image' => ['label' => 'Grande photo d’accueil', 'type' => 'image', 'help' => 'Format paysage, 1600 px de large minimum.'],
            'hero_btn1_label' => ['label' => 'Bouton 1 — texte', 'type' => 'text', 'default' => "Découvrir nos solutions"],
            'hero_btn1_url' => ['label' => 'Bouton 1 — lien', 'type' => 'text', 'default' => '/entreprise'],
            'hero_btn2_label' => ['label' => 'Bouton 2 — texte', 'type' => 'text', 'default' => 'Parler à un expert'],
            'hero_btn2_url' => ['label' => 'Bouton 2 — lien', 'type' => 'text', 'default' => '/contact'],
            'hero_badge_1' => ['label' => 'Point fort 1', 'type' => 'text', 'default' => '✅ Normes internationales'],
            'hero_badge_2' => ['label' => 'Point fort 2', 'type' => 'text', 'default' => '🛠️ SAV & maintenance'],
            'hero_badge_3' => ['label' => 'Point fort 3', 'type' => 'text', 'default' => '👨‍🔧 Ingénieurs biomédicaux'],
        ],
    ],

    'entreprise' => [
        'label' => "Présentation de l'entreprise",
        'icon' => '🏢',
        'description' => 'Textes et photos de la partie « Qui sommes-nous ».',
        'fields' => [
            'about_eyebrow' => ['label' => 'Petit texte au-dessus du titre', 'type' => 'text', 'default' => 'Qui sommes-nous ?'],
            'about_title' => ['label' => 'Titre', 'type' => 'text', 'default' => 'Un partenaire de confiance pour vos structures sanitaires'],
            'about_text' => ['label' => 'Texte principal', 'type' => 'textarea', 'default' => "AVENIR MEDICAL intègre des idées innovantes dans la gestion du parc technique des structures de santé. Une gamme large et variée, un service après-vente performant, des équipes d'ingénieurs et de techniciens biomédicaux à votre service."],
            'about_text_2' => ['label' => 'Texte complémentaire', 'type' => 'textarea', 'default' => "Notre équipe est composée d'ingénieurs et de techniciens biomédicaux, appuyés par un service commercial et financier dynamique. Nous étudions chaque projet dans ses moindres détails."],
            'about_image_1' => ['label' => 'Photo 1', 'type' => 'image'],
            'about_image_2' => ['label' => 'Photo 2', 'type' => 'image'],
            'about_image_3' => ['label' => 'Photo 3', 'type' => 'image'],
            'stat_1_value' => ['label' => 'Chiffre 1 — valeur', 'type' => 'text', 'default' => '12+'],
            'stat_1_label' => ['label' => 'Chiffre 1 — légende', 'type' => 'text', 'default' => 'Gammes de produits'],
            'stat_2_value' => ['label' => 'Chiffre 2 — valeur', 'type' => 'text', 'default' => '3'],
            'stat_2_label' => ['label' => 'Chiffre 2 — légende', 'type' => 'text', 'default' => 'Services experts'],
            'stat_3_value' => ['label' => 'Chiffre 3 — valeur', 'type' => 'text', 'default' => '100%'],
            'stat_3_label' => ['label' => 'Chiffre 3 — légende', 'type' => 'text', 'default' => 'Matériel neuf certifié'],
            'stat_4_value' => ['label' => 'Chiffre 4 — valeur', 'type' => 'text', 'default' => '', 'help' => 'Laissez vide pour n’afficher que 3 chiffres. N’indiquez que des chiffres réels.'],
            'stat_4_label' => ['label' => 'Chiffre 4 — légende', 'type' => 'text', 'default' => ''],
        ],
    ],

    'sections' => [
        'label' => 'Titres des sections',
        'icon' => '📝',
        'description' => 'Les titres affichés au-dessus de chaque partie du site.',
        'fields' => [
            'domains_eyebrow' => ['label' => 'Domaines — petit texte', 'type' => 'text', 'default' => "Nos domaines d'expertise"],
            'domains_title' => ['label' => 'Domaines — titre', 'type' => 'text', 'default' => 'Une solution pour chaque environnement de soins.'],
            'domains_text' => ['label' => 'Domaines — texte', 'type' => 'textarea', 'default' => "Du bloc opératoire au laboratoire, de l'imagerie à la réanimation : nous réunissons les équipements et l'expertise nécessaires à chaque service de votre établissement."],
            'services_page_title' => ['label' => 'Page Services — titre', 'type' => 'text', 'default' => 'Services & expertise'],
            'services_page_text' => ['label' => 'Page Services — texte', 'type' => 'textarea', 'default' => "Bien plus qu'un fournisseur : un service après-vente, des techniciens formés chez les fabricants et des partenariats exclusifs."],
            'services_eyebrow' => ['label' => 'Services — petit texte', 'type' => 'text', 'default' => 'Nos services'],
            'services_title' => ['label' => 'Services — titre', 'type' => 'text', 'default' => "Bien plus qu'un fournisseur"],
            'services_text' => ['label' => 'Services — texte', 'type' => 'textarea', 'default' => 'Nous vous accompagnons avant, pendant et après votre achat.'],
            'services_image' => ['label' => 'Services — photo de fond', 'type' => 'image'],
            'gallery_title' => ['label' => 'Galerie — titre', 'type' => 'text', 'default' => 'Nos équipements'],
            'values_title' => ['label' => 'Valeurs — titre', 'type' => 'text', 'default' => 'Ce qui nous guide au quotidien'],
            'shop_title' => ['label' => 'Boutique — titre', 'type' => 'text', 'default' => 'Commandez vos équipements'],
            'shop_text' => ['label' => 'Boutique — texte', 'type' => 'textarea', 'default' => 'Prix affichés pour les particuliers, livraison partout au Sénégal.'],
            'shop_image' => ['label' => 'Boutique — photo', 'type' => 'image'],
            'shop_bg' => ['label' => 'Boutique — photo de fond de la section', 'type' => 'image', 'help' => 'Grande photo affichée derrière la section « Nos produits ». Format paysage.'],
            'blog_title' => ['label' => 'Blog — titre', 'type' => 'text', 'default' => 'Nos partenariats et dernières nouvelles'],
            'blog_text' => ['label' => 'Blog — texte', 'type' => 'textarea', 'default' => "Suivez les partenariats signés et l'actualité d'AVENIR MEDICAL."],
            'blog_image' => ['label' => 'Blog — photo', 'type' => 'image'],
            'blog_bg' => ['label' => 'Blog — photo de fond de la section', 'type' => 'image', 'help' => 'Grande photo affichée derrière la section « Actualités ». Format paysage.'],
            'partners_bg' => ['label' => 'Partenaires — photo de fond', 'type' => 'image', 'help' => 'Facultatif : donne du relief à la bande des partenaires.'],
            'cta_title' => ['label' => 'Bandeau de contact — titre', 'type' => 'text', 'default' => "Parlons de votre projet d'équipement"],
            'cta_text' => ['label' => 'Bandeau de contact — texte', 'type' => 'textarea', 'default' => 'Nos équipes réalisent une étude précise de votre projet, dans les moindres détails techniques.'],
        ],
    ],

    'videos' => [
        'label' => 'Vidéos',
        'icon' => '🎬',
        'description' => 'Une vidéo de présentation sur la page d’accueil.',
        'fields' => [
            'home_video_enabled' => ['label' => 'Afficher la vidéo sur l’accueil', 'type' => 'boolean', 'default' => '0'],
            'home_video_title' => ['label' => 'Titre de la section', 'type' => 'text', 'default' => 'Découvrez AVENIR MEDICAL en vidéo'],
            'home_video_text' => ['label' => 'Texte de présentation', 'type' => 'textarea', 'default' => 'Quelques minutes pour découvrir nos équipements, nos équipes et notre façon de travailler.'],
            'home_video_url' => [
                'label' => 'Lien de la vidéo',
                'type' => 'text',
                'help' => 'Collez ici un lien YouTube ou Vimeo. Exemple : https://www.youtube.com/watch?v=XXXXXXXX',
            ],
            'home_video_file' => [
                'label' => 'Ou envoyez un fichier vidéo',
                'type' => 'video',
                'help' => 'Fichier MP4 ou WEBM, 50 Mo maximum. Utilisé seulement si aucun lien n’est renseigné. Pour un site rapide, YouTube est conseillé.',
            ],
            'home_video_poster' => [
                'label' => 'Image d’aperçu',
                'type' => 'image',
                'help' => 'Image affichée avant la lecture. Format paysage, 1280 × 720 px.',
            ],
        ],
    ],

    'langues' => [
        'label' => 'Langues du site',
        'icon' => '🌍',
        'description' => 'Le site existe en français et en anglais (/en).',
        'fields' => [
            'auto_locale' => [
                'label' => 'Détecter automatiquement la langue du visiteur',
                'type' => 'boolean',
                'default' => '0',
                'help' => "À la toute première visite, un visiteur dont le navigateur est en anglais arrive directement sur la version anglaise. Son choix est ensuite mémorisé pendant un an. Les robots de Google ne sont jamais redirigés.",
            ],
        ],
    ],

    'partenaires' => [
        'label' => 'Partenaires',
        'icon' => '🤝',
        'description' => 'Titre de la section où apparaissent les logos des partenaires.',
        'fields' => [
            'partners_title' => ['label' => 'Titre', 'type' => 'text', 'default' => 'Ils nous font confiance'],
            'partners_text' => ['label' => 'Texte', 'type' => 'textarea', 'default' => 'Nous travaillons avec des fabricants reconnus pour vous garantir du matériel fiable et suivi.'],
        ],
    ],

    'histoire' => [
        'label' => "Histoire de l'entreprise",
        'icon' => '📜',
        'description' => 'Titre de la frise affichée sur la page « Qui sommes-nous ».',
        'fields' => [
            'history_title' => ['label' => 'Titre', 'type' => 'text', 'default' => 'Les grandes étapes de notre parcours'],
            'history_text' => ['label' => 'Texte d’introduction', 'type' => 'textarea', 'default' => "D'une petite structure à Dakar au partenaire de référence des établissements de santé."],
        ],
    ],

    'chatbot' => [
        'label' => 'Assistant de discussion',
        'icon' => '💬',
        'description' => 'La petite bulle « Puis-je vous aider ? » en bas de page.',
        'fields' => [
            'chat_enabled' => ['label' => 'Activer l’assistant', 'type' => 'boolean', 'default' => '1'],
            'chat_delay' => ['label' => 'Délai avant ouverture (secondes)', 'type' => 'number', 'default' => '4', 'help' => 'Mettez 0 pour que la bulle reste fermée tant que le visiteur ne clique pas.'],
            'chat_title' => ['label' => 'Titre de la fenêtre', 'type' => 'text', 'default' => 'Bonjour 👋'],
            'chat_welcome' => ['label' => 'Message d’accueil', 'type' => 'textarea', 'default' => 'Bienvenue chez AVENIR MEDICAL ! Puis-je vous aider ?'],
            'chat_q1' => ['label' => 'Question rapide 1', 'type' => 'text', 'default' => 'Je cherche un équipement'],
            'chat_a1' => ['label' => 'Réponse 1', 'type' => 'textarea', 'default' => 'Très bien ! Notre boutique en ligne présente nos produits avec leurs prix. Vous pouvez aussi nous appeler pour un conseil personnalisé.'],
            'chat_q2' => ['label' => 'Question rapide 2', 'type' => 'text', 'default' => 'Je veux un devis'],
            'chat_a2' => ['label' => 'Réponse 2', 'type' => 'textarea', 'default' => 'Avec plaisir. Remplissez le formulaire de contact ou appelez-nous : nous étudions votre projet et revenons vers vous rapidement.'],
            'chat_q3' => ['label' => 'Question rapide 3', 'type' => 'text', 'default' => 'Besoin d’une réparation'],
            'chat_a3' => ['label' => 'Réponse 3', 'type' => 'textarea', 'default' => 'Notre service après-vente intervient sur le matériel que nous fournissons. Appelez-nous, un technicien vous rappellera.'],
            'chat_q4' => ['label' => 'Question rapide 4', 'type' => 'text', 'default' => 'Parler à quelqu’un'],
            'chat_a4' => ['label' => 'Réponse 4', 'type' => 'textarea', 'default' => 'Bien sûr ! Appelez-nous ou écrivez-nous sur WhatsApp, nous répondons du lundi au samedi de 8h à 18h.'],
        ],
    ],

    'references' => [
        'label' => 'Références & savoir-faire',
        'icon' => '🏥',
        'description' => 'Les établissements équipés, les partenariats exclusifs et les formations.',
        'fields' => [
            'references_title' => ['label' => 'Références — titre de la page', 'type' => 'text', 'default' => 'Ils nous font confiance'],
            'references_text' => ['label' => 'Références — texte', 'type' => 'textarea', 'default' => 'Hôpitaux, cliniques et centres de santé : découvrez les établissements que nous accompagnons au quotidien.'],
            'references_home_title' => ['label' => 'Accueil — titre de la section références', 'type' => 'text', 'default' => 'Des établissements de référence nous font confiance'],
            'references_home_text' => ['label' => 'Accueil — texte', 'type' => 'textarea', 'default' => 'Des structures publiques et privées équipées et suivies par nos équipes.'],
            'expertise_title' => ['label' => 'Savoir-faire — titre de la page', 'type' => 'text', 'default' => 'Notre savoir-faire'],
            'expertise_text' => ['label' => 'Savoir-faire — texte', 'type' => 'textarea', 'default' => "Des partenariats exclusifs et des techniciens formés chez les fabricants, en Afrique comme à l'international."],
            'exclusive_title' => ['label' => 'Exclusivités — titre', 'type' => 'text', 'default' => 'Nos partenariats exclusifs'],
            'exclusive_text' => ['label' => 'Exclusivités — texte', 'type' => 'textarea', 'default' => "Nous sommes le représentant exclusif de ces fabricants : matériel d'origine, pièces garanties et service assuré directement par leurs équipes."],
            'training_title' => ['label' => 'Formations — titre', 'type' => 'text', 'default' => 'Des techniciens formés chez les fabricants'],
            'training_text' => ['label' => 'Formations — texte', 'type' => 'textarea', 'default' => "Nos ingénieurs et techniciens partent régulièrement en formation à l'étranger, directement chez les constructeurs, pour maîtriser chaque équipement que nous installons."],
        ],
    ],

    'developpement' => [
        'label' => 'Développement & groupe',
        'icon' => '🌍',
        'description' => "Les agences, l'expansion et les entreprises du groupe.",
        'fields' => [
            'expansion_title' => ['label' => 'Développement — titre', 'type' => 'text', 'default' => 'Au plus près de chaque structure de santé'],
            'ambition_title' => ['label' => 'Ambition — titre', 'type' => 'text', 'default' => 'Une agence dans chaque grande région'],
            'ambition_text' => ['label' => 'Ambition — texte', 'type' => 'textarea', 'default' => "Notre feuille de route : couvrir progressivement les grandes régions du Sénégal, puis les principaux pays de la sous-région, pour réduire les délais d'intervention auprès de nos partenaires."],
            'expansion_text' => ['label' => 'Développement — texte', 'type' => 'textarea', 'default' => "Notre ambition : une agence dans chaque grande région du Sénégal, puis dans les principaux pays de la sous-région, pour intervenir plus vite auprès de nos partenaires."],
            'group_title' => ['label' => 'Groupe — titre de la page', 'type' => 'text', 'default' => 'Le groupe AVENIR MEDICAL'],
            'group_text' => ['label' => 'Groupe — texte', 'type' => 'textarea', 'default' => 'Un ensemble d’entreprises spécialisées, réunies autour d’une même exigence : la santé.'],
            'group_parent_text' => ['label' => 'Groupe — présentation de la maison mère', 'type' => 'textarea', 'default' => "Maison mère du groupe, AVENIR MEDICAL équipe les structures de santé et fédère des entreprises complémentaires, chacune spécialisée dans son domaine."],
            'gallery_page_title' => ['label' => 'Galerie — titre de la page', 'type' => 'text', 'default' => 'La vie de l’entreprise en images'],
            'gallery_page_text' => ['label' => 'Galerie — texte', 'type' => 'textarea', 'default' => 'Installations, formations, salons et moments forts partagés avec nos partenaires.'],
        ],
    ],

    'parcours' => [
        'label' => "Parcours d'accompagnement",
        'icon' => '🧭',
        'description' => 'La section qui explique comment un besoin devient une solution.',
        'fields' => [
            'process_eyebrow' => ['label' => 'Petit texte', 'type' => 'text', 'default' => 'Notre approche'],
            'process_title' => ['label' => 'Titre', 'type' => 'text', 'default' => 'De votre besoin à la mise en service, nous sommes à vos côtés.'],
            'process_text' => ['label' => 'Texte', 'type' => 'textarea', 'default' => "Parce qu'un équipement médical ne se résume pas à sa livraison, nous vous accompagnons à chaque étape de votre projet."],
            'process_conclusion' => [
                'label' => 'Phrase de conclusion',
                'type' => 'textarea',
                'default' => "Un équipement livré est un équipement. Un équipement installé, maîtrisé et maintenu devient une véritable solution.",
                'help' => 'Mise en valeur sous les étapes. Laissez vide pour la masquer.',
            ],
        ],
    ],

    'boutique' => [
        'label' => 'Boutique & livraison',
        'icon' => '🛒',
        'description' => 'Monnaie, frais de livraison et moyens de paiement.',
        'fields' => [
            'shop_enabled' => ['label' => 'Activer la boutique en ligne', 'type' => 'boolean', 'default' => '1'],
            'currency' => ['label' => 'Monnaie', 'type' => 'text', 'default' => 'FCFA'],
            'delivery_fee' => ['label' => 'Frais de livraison', 'type' => 'number', 'default' => '0', 'help' => 'Mettez 0 pour « livraison à convenir ».', 'rules' => 'nullable|integer|min:0'],
            'delivery_note' => ['label' => 'Note de livraison', 'type' => 'text', 'default' => 'Livraison à Dakar et partout au Sénégal'],
            'payment_methods' => [
                'label' => 'Moyens de paiement',
                'type' => 'textarea',
                'default' => 'Paiement à la livraison',
                'help' => 'Un moyen de paiement par ligne.',
            ],
            'order_prefix' => ['label' => 'Préfixe des numéros de commande', 'type' => 'text', 'default' => 'AM'],
            'show_stock' => ['label' => 'Afficher le stock disponible', 'type' => 'boolean', 'default' => '0'],
            'products_per_page' => ['label' => 'Produits par page', 'type' => 'number', 'default' => '12', 'rules' => 'nullable|integer|min:4|max:60'],
        ],
    ],

    'seo' => [
        'label' => 'Référencement (SEO)',
        'icon' => '🔎',
        'description' => 'Ce qui apparaît sur Google et les réseaux sociaux.',
        'fields' => [
            'meta_title' => ['label' => 'Titre par défaut', 'type' => 'text', 'default' => 'AVENIR MEDICAL — Équipements médicaux à Dakar'],
            'meta_description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Vente de matériels médicaux neufs aux normes internationales, service après-vente et accompagnement de projets de santé au Sénégal.'],
            'og_image' => ['label' => 'Image de partage', 'type' => 'image', 'help' => 'Format 1200 × 630 px.'],
            'analytics_code' => ['label' => 'Code de statistiques', 'type' => 'textarea', 'help' => 'Collez ici le code Google Analytics ou Meta Pixel.'],
        ],
    ],

    'legal' => [
        'label' => 'Pied de page & mentions',
        'icon' => '⚖️',
        'description' => 'Textes légaux et bas de page.',
        'fields' => [
            'footer_text' => ['label' => 'Texte du pied de page', 'type' => 'textarea', 'default' => 'AVENIR MEDICAL — vente de matériels médicaux neufs, service après-vente et accompagnement de vos projets de santé.'],
            'copyright' => ['label' => 'Mention de copyright', 'type' => 'text', 'default' => '© :year AVENIR MEDICAL. Tous les droits sont réservés.', 'help' => 'Écrivez :year pour afficher l’année en cours.'],
            'legal_notice' => ['label' => 'Mentions légales', 'type' => 'textarea'],
            'terms' => ['label' => 'Conditions générales de vente', 'type' => 'textarea'],
        ],
    ],

];
