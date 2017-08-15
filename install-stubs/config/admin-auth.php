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

    'login-redirect' => '/admin/user',

    /*
    |
    | This option controls if Login should check also forbidden key
    |
    */

    'check-forbidden' => true,

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    |
    | This option controls the url for redirect after logout
    |
    */

    'logout-redirect' => '/admin/login',

    /*
    |--------------------------------------------------------------------------
    | Password reset
    |--------------------------------------------------------------------------
    |
    | This option controls the url for redirect after password reset
    |
    */

    'password-reset-redirect' => '/admin/login',

    /*
    |--------------------------------------------------------------------------
    | Activation
    |--------------------------------------------------------------------------
    |
    | This options controls if activation is required or not
    | And the activation redirect controls where to redirect after activation
    |
    */

    'activation-required' => env('ACTIVATION_REQUIRED', false), //enabled

    'activation-form-visible' => true, //self-activation-form-enabled

    'activation-redirect' => '/admin/login', //redirect

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

    'activations' => [
        'users' => [
            'provider' => 'users',
            'table' => 'activations',
            'expire' => 60 * 24,
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

    'use-routes' => true,
];
