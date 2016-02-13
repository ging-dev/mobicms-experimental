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
 * @module      Help System
 * @author      Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version     v.1.0.0 2015-02-01
 */

defined('MOBICMS') or die('Error: restricted access');

$app = App::getInstance();
$query = $app->router()->getQuery();
$app->lng()->setModule('help');

if (isset($query[0])) {
    switch ($query[0]) {
        case 'avatars':
            require_once __DIR__ . '/includes/avatars.php';
            break;

        case 'rules':
            $app->view()->setTemplate('rules.php');
            break;

        default:
            $app->redirect($app->request()->getBaseUrl() . '/404');
    }
} else {
    $app->view()->setTemplate('index.php');
}
