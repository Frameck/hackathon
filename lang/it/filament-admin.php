<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Admin Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during custom translations
    | inside the admin panel.
    |
    */

    'widgets' => [
        'dashboard' => [
            'brand' => [
                'visit_site' => 'Visita il sito',
                'company_settings' => 'Impostazioni',
            ],
        ],
    ],
    'notifications' => [
        'login' => [
            'warning-notification' => [
                'title' => 'Default user non trovato',
                'body' => 'Non è stato trovato l\'utente default per il login. Hai lanciato le migration e i seeder?',
            ],
            'success-notification' => [
                'title' => config('app.name') . ' Admin Panel',
                'body' => 'Benvenuto nel pannello amministratore',
            ],
        ],
        'database' => [
            'bell_icon' => ':count notifiche non lette',
        ],
        'validation' => [
            'title' => 'Errore Validazione',
        ],
    ],
    'buttons' => [
        'save_and_close' => 'Salva & chiudi',
    ],
    'excel' => [
        'bulk-action' => [
            'label' => 'Esporta selezionati',
        ],
        'header-action' => [
            'label' => 'Esporta',
            'form' => [
                'date_on' => 'Data inizio',
                'date_off' => 'Data fine',
            ],
        ],
        'import' => [
            'label' => 'Importa',
        ],
    ],
    'settings' => [
        'menu_group' => 'Impostazioni',
        'pages' => [
            'company' => [
                'navigation-label' => 'Impostazioni Azienda',
                'heading' => 'Impostazioni',
                'subheading' => 'Impostazioni e configurazioni riguardanti l\'azienda',
                'tabs' => [
                    'company_registry' => 'Anagrafica Azienda',
                    'email' => 'Email',
                    'events_emails' => 'Gestisci email',
                    'social' => 'Social',
                    'analitycs' => 'Analitycs',
                    'color' => 'Colori',
                    'positioning' => 'Posizionamento',
                    'text' => 'Testi',
                ],
                'repeater' => [
                    'email' => 'Aggiungi email',
                    'events_emails' => [
                        'placeholder' => [
                            'title' => 'Associazione Eventi Email',
                            'content' => 'Sotto puoi configurare a quali email il sistema deve inviare le comunicazioni in base agli eventi selezionati',
                        ],
                        'add_button' => 'Aggiungi associazione evento-email',
                        'select_all' => 'tutti',
                        'headers' => [
                            'Evento',
                            'Gruppo Email',
                            'Email',
                        ],
                    ],
                    'text' => 'Aggiungi blocco di testo',
                ],
            ],
        ],
    ],
];
