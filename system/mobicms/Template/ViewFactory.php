<?php

namespace Mobicms\Template;

use Psr\Container\ContainerInterface;

class ViewFactory
{
    public function __invoke(ContainerInterface $container)
    {
        //TODO: переделать на получение объекта Роутера из контейнера
        return new View(\App::getInstance()->router());
    }
}
