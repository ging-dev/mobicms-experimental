<?php

namespace Mobicms\Routing;

use Psr\Container\ContainerInterface;

class RouterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $routes = isset($config['routes']) ? $config['routes'] : [];

        return new Router($routes);
    }
}
