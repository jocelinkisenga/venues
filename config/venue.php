<?php

return [
    'default_currency' => env('VENUE_CURRENCY', 'XOF'),

    // Devises courantes en Afrique (XOF/XAF = franc CFA, sans décimales)
    'currencies' => [
        'XOF' => ['label' => 'Franc CFA (UEMOA)', 'decimals' => 0],
        'XAF' => ['label' => 'Franc CFA (CEMAC)', 'decimals' => 0],
        'GNF' => ['label' => 'Franc guinéen', 'decimals' => 0],
        'CDF' => ['label' => 'Franc congolais', 'decimals' => 0],
        'NGN' => ['label' => 'Naira', 'decimals' => 0],
        'KES' => ['label' => 'Shilling kényan', 'decimals' => 0],
        'GHS' => ['label' => 'Cedi', 'decimals' => 2],
        'MAD' => ['label' => 'Dirham', 'decimals' => 2],
        'USD' => ['label' => 'Dollar US', 'decimals' => 2],
        'EUR' => ['label' => 'Euro', 'decimals' => 2],
    ],

   'payment_methods' => [
    'mobile_money' => 'Mobile Money (M-Pesa, Orange Money, Airtel Money)',
    'card' => 'Carte bancaire',
    'bank_transfer' => 'Virement bancaire',
    'cash' => 'Espèces',
],

    // TVA par défaut (ex : CI 18 %, SN 18 %, CM 19,25 %) — modifiable par salle
    'default_tax_rate' => env('VENUE_TAX_RATE', 0),
    'deposit_percent' => 50,
    'option_hold_hours' => 48,
    'cleaning_buffer_hours' => 3,
    'event_types' => [
        'Mariage', 'Dot / Cérémonie coutumière', 'Baptême', 'Anniversaire',
        'Séminaire', 'Conférence', 'Gala', 'Soirée d\'entreprise', 'Autre',
    ],

    'checklists' => [
        'entry' => [
            'État des lieux d\'entrée : murs, sols, plafonds',
            'Inventaire des chaises et tables',
            'Vérification de la sonorisation et des micros',
            'Vérification de l\'éclairage et de la climatisation',
            'Vérification des sanitaires et de l\'eau',
        ],
        'exit' => [
            'État des lieux de sortie : dégâts éventuels',
            'Inventaire du matériel restitué (chaises, tables, sono)',
            'Décorations et matériel partenaires retirés',
            'Nettoyage des espaces et sanitaires',
            'Coupure des équipements et fermeture des accès',
        ],
    ],
    'cancellation_policy' => [
    ['days' => 30, 'refund' => 100], // annulation 30 jours ou plus avant : remboursement total
    ['days' => 15, 'refund' => 50],  // entre 15 et 29 jours : 50 %
    ['days' => 0,  'refund' => 0],   // moins de 15 jours : aucun remboursement
],
'online_min_notice_hours' => 48,   // délai minimum entre la demande et l'événement
'online_max_photos' => 12,

'amenities' => [
    'climatisation' => 'Climatisation',
    'groupe_electrogene' => 'Groupe électrogène',
    'parking' => 'Parking',
    'sono' => 'Sono incluse',
    'cuisine' => 'Cuisine équipée',
    'wifi' => 'Wi-Fi',
    'securite' => 'Sécurité 24 h',
    'acces_pmr' => 'Accès PMR',
    'eau' => 'Eau courante',
    'vestiaires' => 'Vestiaires',
],
'seating' => [
    'categories' => [
        'standard' => 'Standard',
        'honneur' => 'Table d\'honneur',
        'famille' => 'Famille',
        'vip' => 'VIP',
        'enfants' => 'Enfants',
    ],
    'shapes' => [
        'round' => 'Ronde',
        'square' => 'Carrée',
        'rectangle' => 'Rectangulaire',
    ],
    'layouts' => [
        'banquet' => 'Banquet (tables rondes)',
        'u' => 'Disposition en U',
        'theatre' => 'Style théâtre',
        'cocktail' => 'Cocktail debout',
    ],
],
];
