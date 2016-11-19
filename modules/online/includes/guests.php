<?php

defined('JOHNCMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var PDO $db */
$db = $container->get(PDO::class);

$app = App::getInstance();
$app->view()->total = $db->query("SELECT COUNT(*) FROM `sessions` WHERE `userId` = 0 AND `timestamp`  > " . (time() - 300))->fetchColumn();

if ($app->view()->total) {
    $app->view()->list = $db->query("
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

$app->view()->setTemplate('guests.php');
