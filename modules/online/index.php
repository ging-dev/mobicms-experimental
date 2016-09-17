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
 *
 * @module      Online
 * @author      Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version     v.1.0.0 2015-02-01
 */

defined('JOHNCMS') or die('Error: restricted access');

$admin_actions = [
    'guests' => 'guests.php',
    'ip'     => 'ip.php'
];

$common_actions = [
    'history' => 'history.php'
];

$app = App::getInstance();
$query = $app->router()->getQuery();
$include = __DIR__ . '/includes/';

if (isset($query[0])) {
    if ($app->user()->get()->rights > 0 && isset($admin_actions[$query[0]])) {
        $include .= $admin_actions[$query[0]];
    } elseif (isset($common_actions[$query[0]])) {
        $include .= $common_actions[$query[0]];
    } else {
        $include = false;
    }
} else {
    $include .= 'index.php';
}

if ($include && is_file($include)) {
    require_once $include;
} else {
    $app->redirect($app->request()->getBaseUrl() . '/404');
}
