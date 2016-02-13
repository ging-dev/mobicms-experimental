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

$file = ROOT_PATH . 'system/logs/ip-requests.log';
$array = [];

if (is_file($file)) {
    $array = file($file);

    // Убираем заголовок текстового файла
    unset($array[0], $array[1]);

    // Передаем в шаблон 100 самых активных адресов
    $app->view()->list = array_slice($array, 0, 100);
}

$total = count($array);
$app->view()->total = $total > 100 ? '> 100' : $total;
$app->view()->setTemplate('ip.php');
