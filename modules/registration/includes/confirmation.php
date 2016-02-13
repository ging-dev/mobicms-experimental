<?php

defined('MOBICMS') or die('Error: restricted access');

use Config\Registration as Config;

$app = App::getInstance();

if (Config::$letterMode == 2 && Config::$approveByAdmin) {
    // Если включена активация по ссылке и модерация
    $app->view()->warning = _m('Your account is created and must be verified before you can use it.<ul><li>You sent an email with verification instructions</li><li>Open the email and follow the instructions</li><li>After verification an administrator will be notified to activate your account</li><li>You\'ll receive a confirmation when it\'s done</li><li>Once that account has been activated you may login using the username and password you entered during registration</li></ul>');
} elseif (Config::$letterMode == 2 && !Config::$approveByAdmin) {
    // Если включена активация по ссылке
    $app->view()->warning = _m('Your account is created and must be activated before you can use it.<ul><li>You sent an email with activation instructions</li><li>Open the email and follow the instructions</li><li>Once that account has been activated you may login using the username and password you entered during registration</li></ul>');
} elseif (Config::$letterMode < 2 && Config::$approveByAdmin) {
    // Eсли включена модерация
    $app->view()->warning = _m('Your account has been created, but must be approved by the Administrator.<br>Once that account has been approved you may login using the username and password you entered during registration.');
}

$app->view()->title = _s('Registration');
$app->view()->setTemplate('message.php', null, false);
