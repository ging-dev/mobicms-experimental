<?php

namespace Mobicms\Config;

use Psr\Container\ContainerInterface;

class ConfigFactory
{
    public function __invoke(ContainerInterface $container){
        return new ConfigContainer($container->get('config')['mobicms']);
    }
}
