<?php

defined('MOBICMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\RouterInterface $router */
$router = $container->get(Mobicms\Api\RouterInterface::class);

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$query = $router->getQuery();
$app->lng()->setModule('help');

if (isset($query[0])) {
    switch ($query[0]) {
        case 'avatars':
            require_once __DIR__ . '/includes/avatars.php';
            break;

        case 'rules':
            $view->setTemplate('rules.php');
            break;

        default:
            $app->redirect($app->request()->getBaseUrl() . '/404');
    }
} else {
    $view->setTemplate('index.php');
}
