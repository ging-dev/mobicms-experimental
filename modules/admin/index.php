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
 *
 * @module      System Tools
 * @author      Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version     v.1.0.0 2015-02-01
 */

@ini_set("max_execution_time", "600");
defined('MOBICMS') or die('Error: restricted access');

$app = App::getInstance();
$user = $app->user()->get();

if ($user->rights >= 7) {
    $sv_actions = [
        'counters'        => 'counters.php',
        'firewall'        => 'firewall.php',
        'languages'       => 'languages.php',
        'sitemap'         => 'sitemap.php',
        'system_settings' => 'system_settings.php',
    ];

    $admin_actions = [
        'links'                     => 'links.php',
        'users_settings/quarantine' => 'quarantine.php',
        'scanner'                   => 'scanner.php',
        'smilies'                   => 'smilies.php',
        'users_settings'            => 'users_settings.php',
    ];

    $common_actions = [];

    $app->lng()->setModule('admin');
    $query = implode('/', $app->router()->getQuery());
    $include = __DIR__ . '/includes/';

    if (!empty($query)) {
        if ($user->rights == 9 && isset($sv_actions[$query])) {
            $include .= $sv_actions[$query];
        } elseif ($user->rights >= 7 && isset($admin_actions[$query])) {
            $include .= $admin_actions[$query];
        } elseif (isset($common_actions[$query])) {
            $include .= $common_actions[$query];
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
} else {
    echo _s('Access forbidden');
}
