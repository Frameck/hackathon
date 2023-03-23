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
                'visit_site' => 'Visit site',
                'company_settings' => 'Settings',
            ],
        ],
    ],
    'notifications' => [
        'login' => [
            'warning-notification' => [
                'title' => 'Default user not found',
                'body' => 'Default user for login not found. Have you ran migrations and seeders?',
            ],
            'success-notification' => [
                'title' => config('app.name') . ' Admin Panel',
                'body' => 'Welcome to the admin panel',
            ],
        ],
        'database' => [
            'bell_icon' => 'Notifications :count unread',
        ],
        'validation' => [
            'title' => 'Validation Error',
        ],
    ],
    'buttons' => [
        'save_and_close' => 'Save & close',
    ],
    'excel' => [
        'bulk-action' => [
            'label' => 'Export selected',
        ],
        'header-action' => [
            'label' => 'Export',
            'form' => [
                'date_on' => 'Date on',
                'date_off' => 'Date off',
            ],
        ],
        'import' => [
            'label' => 'Import',
        ],
    ],
    'settings' => [
        'menu_group' => 'Settings',
        'pages' => [
            'company' => [
                'navigation-label' => 'Company Settings',
                'heading' => 'Settings',
                'subheading' => 'Settings and configurations regarding the company',
                'tabs' => [
                    'company_registry' => 'Company Registry',
                    'email' => 'Emails',
                    'events_emails' => 'Manage emails',
                    'social' => 'Social',
                    'analitycs' => 'Analitycs',
                    'color' => 'Colors',
                    'positioning' => 'Positioning',
                    'text' => 'Text',
                ],
                'repeater' => [
                    'email' => 'Add email',
                    'events_emails' => [
                        'placeholder' => [
                            'title' => 'Events Emails Associations',
                            'content' => 'Below you can define on what emails the system should send the communications based on the event that you select',
                        ],
                        'add_button' => 'Add association event-email',
                        'select_all' => 'all',
                        'headers' => [
                            'Event',
                            'Email Group',
                            'Email',
                        ],
                    ],
                    'text' => 'Add text block',
                ],
            ],
        ],
    ],
];
