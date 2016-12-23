<?php

return [
    'dependencies' => [
        'invokables' => [
            Mobicms\Environment\Network::class => Mobicms\Environment\Network::class,
        ],

        'factories' => [
            PDO::class => Mobicms\Database\PdoFactory::class,
        ],

        'aliases' => [

        ],
    ],
];
