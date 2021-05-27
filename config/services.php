<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
    ],

    'facebook' => [
        'client_id' => '921405838600122',
        'client_secret' => 'd87ea0f675ff527a8998eaac641be5d0',
        'redirect' => 'http://localhost:8000/oauth/facebook/callback',
    ],

    'microsoft' => [

        'AZURE_AD_CLIENT_ID' => '450d3a8a-53cd-4f6c-8b75-2c83bb3afc6e',
        'AZURE_AD_CLIENT_SECRET' => 'eY8iFPF.p5J6MMjGH8b1X~e3_Zm9Ws5bI~',
        'redirect' => 'http://localhost:8000/oauth/facebook/callback',
    ],


];
