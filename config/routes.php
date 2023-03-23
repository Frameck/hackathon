<?php

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration file for routes
    | by default all models are registered in api routes using Route::apiResource() method,
    | you can manually exclude certain routes inside this config file
    |
    | excludes all routes for User model
    | 'exclude' => [
    |      User::class
    | ]
    |
    | excludes only destroy route for User model
    | 'exclude' => [
    |      User::class => [
    |          'destroy',
    |      ]
    | ]
    |
    | possible values are 'index', 'store', 'show', 'update', 'destroy'
    |
    |--------------------------------------------------------------------------
    */

    'api' => [
        'exclude' => [
            User::class => [
                'destroy',
            ],
        ],
    ],
];
