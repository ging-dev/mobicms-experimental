<?php

return [
    'dependencies' => [
        'factories' => [
            Mobicms\Api\ConfigInterface::class => Mobicms\Config\ConfigFactory::class,
            Mobicms\Api\RouterInterface::class => Mobicms\Routing\Router::class,
            Mobicms\Api\ViewInterface::class   => Mobicms\Template\ViewFactory::class,
            Mobicms\Environment\Network::class => Mobicms\Environment\Network::class,
            PDO::class                         => Mobicms\Database\PdoFactory::class,
        ],
    ],
];
