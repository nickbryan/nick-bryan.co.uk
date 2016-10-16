<?php

return [
    'settings' => [
        // Slim settings.
        'displayErrorDetails' => true,

        // View settings.
        'view' => [
            'template_path' => __DIR__ . '/views',
            'twig' => [
                'cache' => __DIR__ . '/../storage/cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ]
        ]
    ]
];
