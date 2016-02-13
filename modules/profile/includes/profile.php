<?php
/*
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

// Построение графика репутации
$reputation = !empty($app->profile()->reputation)
    ? unserialize($app->profile()->reputation)
    : ['a' => 0, 'b' => 0, 'c' => 0, 'd' => 0, 'e' => 0];

$app->view()->reputation = [];
$app->view()->reputation_total = array_sum($reputation);

foreach ($reputation as $key => $val) {
    $app->view()->reputation[$key] = $app->view()->reputation_total
        ? 100 / $app->view()->reputation_total * $val
        : 0;
}

$app->view()->setTemplate('profile.php');
