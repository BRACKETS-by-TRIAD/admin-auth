<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    |
    | This option controls the url for redirect after login
    |
    */

    'login_redirect' => '/admin',

    /*
    |
    | This option controls if Login should check also forbidden key
    |
    */

    'check_forbidden' => env('FORBIDDEN_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    |
    | This option controls the url for redirect after logout
    |
    */

    'logout_redirect' => '/admin/login',

    /*
    |--------------------------------------------------------------------------
    | Password reset
    |--------------------------------------------------------------------------
    |
    | This option controls the url for redirect after password reset
    |
    */

    'password_reset_redirect' => '/admin/login',

    /*
    |--------------------------------------------------------------------------
    | Activations
    |--------------------------------------------------------------------------
    |
    | This options controls if activation is required or not
    | And the activation redirect controls where to redirect after activation
    |
    */

    'activations' => [
        'enabled' => env('ACTIVATION_ENABLED', false),

        'self_activation_form_enabled' => true,

        'redirect' => '/admin/login',

        /*
        |
        | You may specify multiple activation configurations if you have more
        | than one user table or model in the application and you want to have
        | separate activation settings based on the specific user types.
        |
        | The expire time is the number of minutes that the reset token should be
        | considered valid. This security feature keeps tokens short-lived so
        | they have less time to be guessed. You may change this as needed.
        |
        */
        'configuration' => [
            'users' => [
                'provider' => 'users',
                'table' => 'activations',
                'expire' => 60 * 24,
            ],
        ],
    ],

    'defaults' => [
        'activations' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | This option controls if package routes are used or not
    |
    */

    'use_routes' => true,
];
