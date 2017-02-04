<?php

defined('MOBICMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var PDO $db */
$db = $container->get(PDO::class);

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$view->total = $db->query("SELECT COUNT(*) FROM `sessions` WHERE `userId` = 0 AND `timestamp`  > " . (time() - 300))->fetchColumn();

if ($view->total) {
    $view->list = $db->query("
        SELECT
            `userId` AS `id`,
            `timestamp` AS `lastVisit`,
            `ip`,
            `userAgent`,
            `place`,
            `views`,
            `movings`
        FROM
            `sessions`
        WHERE
            `userId` = 0 AND `timestamp`  > " . (time() - 300) . "
        ORDER BY
            `views` DESC LIMIT " . $app->vars()->start . ',' . $app->user()->get()->getConfig()->pageSize
    )->fetchAll();
}

$view->setTemplate('guests.php');
