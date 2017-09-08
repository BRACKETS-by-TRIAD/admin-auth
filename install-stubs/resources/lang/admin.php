<?php

return [
    'auth_global' => [
        'email' => 'Your e-mail',
        'password' => 'Password',
        'password_confirm' => 'Password Confirmation',
    ],

    'login' => [
        'title' => 'Login',
        'sign_in_text' => 'Sign In to your account',
        'button' => 'Login',
        'forgot_password' => 'Forgot password?',
    ],

    'password_reset' => [
        'title' => 'Reset Password',
        'note' => 'Reset your forgotten password',
        'button' => 'Reset Password',
    ],

    'forgot_password' => [
        'title' => 'Reset Password',
        'note' => 'Send password reset e-mail',
        'button' => 'Send Password Reset Link',
    ],

    'activation_form' => [
        'title' => 'Activate account',
        'note' => 'Send activation link to e-mail',
        'button' => 'Send Activation Link',
    ],

    'activations' => [
        'sent' => 'We have e-mailed your activation link!',
        'activated' => 'Your account has been activated!',
        'invalid_request' => 'The request failed.',
        'disabled' => "Activation is disabled.",
    ],

    'passwords' => [
        'sent' => 'We have e-mailed your password reset link!',
        'reset' => 'Your password has been reset!',
        'invalid_token' => 'This password reset token is invalid.',
        'invalid_user' => "We can't find a user with that e-mail address.",
        'invalid_password' => 'Passwords must be at least six characters and match the confirmation.',
    ]
];
