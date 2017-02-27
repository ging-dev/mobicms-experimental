<?php

namespace Mobicms\Routing;

use Psr\Container\ContainerInterface;

class RouterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Router();
    }
}
