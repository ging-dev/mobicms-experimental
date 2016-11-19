<?php

defined('JOHNCMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var PDO $db */
$db = $container->get(PDO::class);

$app = App::getInstance();
$app->view()->total = $db->query("SELECT COUNT(*) FROM `users` WHERE `lastVisit` < " . (time() - 300))->fetchColumn();

if ($app->view()->total) {
    $app->view()->list = $db->query("
        SELECT * FROM `users`
        WHERE `lastVisit` < " . (time() - 300) . "
        ORDER BY `nickname` LIMIT " . $app->vars()->start . ',' . $app->user()->get()->getConfig()->pageSize
    )->fetchAll();
}

$app->view()->setTemplate('index.php');
