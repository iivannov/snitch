<?php

return [

    'default' => [
        'enabled' => env('SNITCH_DEFAULT_ENABLED', true),
        'trace' => env('SNITCH_DEFAULT_TRACE', true),
        'ignore' => [
            //ExceptionYouWantToIgnore::class,
        ],
        'accept' => [
            //ExceptionYouWantToReport::class,
        ]
    ],

    'sentry' => [
        'enabled' => env('SNITCH_SENTRY_ENABLED', false),
        'dsn' => env('SNITCH_SENTRY_DSN'),
        'ignore' => [
            //ExceptionYouWantToIgnore::class,
        ],
        'accept' => [
            //ExceptionYouWantToReport::class,
        ]
    ],


    'rotating' => [
        'api' => [
            'enabled' => true,
            'trace' => false,
            'ignore' => [
            ],
            'accept' => [
            ]
        ],
    ],
];