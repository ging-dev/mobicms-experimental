<?php

namespace Mobicms\Template;

use Mobicms\Api\RouterInterface;
use Psr\Container\ContainerInterface;

class ViewFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new View($container->get(RouterInterface::class));
    }
}
