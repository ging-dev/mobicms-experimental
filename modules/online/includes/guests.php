<?php
/**
 * mobiCMS Content Management System (http://mobicms.net)
 *
 * For copyright and license information, please see the LICENSE.md
 * Installing the system or redistributions of files must retain the above copyright notice.
 *
 * @link        http://mobicms.net mobiCMS Project
 * @copyright   Copyright (C) mobiCMS Community
 * @license     LICENSE.md (see attached file)
 */

defined('MOBICMS') or die('Error: restricted access');

$app = App::getInstance();
$app->view()->total = $app->db()->query("SELECT COUNT(*) FROM `sys__sessions` WHERE `userId` = 0 AND `timestamp`  > " . (time() - 300))->fetchColumn();

if ($app->view()->total) {
    $app->view()->list = $app->db()->query("
        SELECT
            `userId` AS `id`,
            `timestamp` AS `lastVisit`,
            `ip`,
            `ip_via_proxy`,
            `userAgent`,
            `place`,
            `views`,
            `movings`
        FROM
            `sys__sessions`
        WHERE
            `userId` = 0 AND `timestamp`  > " . (time() - 300) . "
        ORDER BY
            `views` DESC" . $app->db()->pagination()
    )->fetchAll();
}

$app->view()->setTemplate('guests.php');
