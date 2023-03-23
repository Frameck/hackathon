<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration file for default users
    |--------------------------------------------------------------------------
    */

    'local' => [
        'super_admin' => [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'local.superadmin@test.com',
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => str()->random(20),
        ],
        'admin' => [
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'local.admin@test.com',
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => str()->random(20),
        ],
    ],
    'staging' => [
        'root' => [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => env('ROOT_USER_STAGING_EMAIL', null),
            'email_verified_at' => now(),
            'password' => env('ROOT_USER_STAGING_PASSWORD', null),
            'remember_token' => str()->random(20),
        ],
    ],
    'production' => [
        'root' => [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => env('ROOT_USER_PRODUCTION_EMAIL', null),
            'email_verified_at' => now(),
            'password' => env('ROOT_USER_PRODUCTION_PASSWORD', null),
            'remember_token' => str()->random(20),
        ],
    ],
];
