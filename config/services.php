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

        'region' => env('SES_REGION', 'us-east-1'),

    ],



    'sparkpost' => [

        'secret' => env('SPARKPOST_SECRET'),

    ],



    'stripe' => [

        'model' => App\User::class,

        'key' => env('STRIPE_KEY'),

        'secret' => env('STRIPE_SECRET'),

    ],

    'google' => [

        'client_id'     => '983483790507-n88s7e7fo5ufq10hstjmu9f33fslrgfh.apps.googleusercontent.com',

        'client_secret' => 'JefAR_gN5HKzz6RI9xO_8t7J',

        'redirect'      => 'https://sneaksdeal.com/auth/google/callback'

    ],

    'facebook' => [

        'client_id'     => '2169837056672281',

        'client_secret' => '0931d8464604dffece6a36b981094753',

        'redirect'      => 'https://sneaksdeal.com/auth/facebook/callback'

    ],





];

