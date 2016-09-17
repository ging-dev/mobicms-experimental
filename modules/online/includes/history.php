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

defined('JOHNCMS') or die('Error: restricted access');

$app = App::getInstance();
$app->view()->total = $app->db()->query("SELECT COUNT(*) FROM `users` WHERE `lastVisit` < " . (time() - 300))->fetchColumn();

if ($app->view()->total) {
    $app->view()->list = $app->db()->query("
        SELECT * FROM `users`
        WHERE `lastVisit` < " . (time() - 300) . "
        ORDER BY `nickname`" . $app->db()->pagination()
    )->fetchAll();
}

$app->view()->setTemplate('index.php');
