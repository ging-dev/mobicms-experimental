<?php

defined('JOHNCMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$config = $app->config()->get('reg');

if ($config['letterMode'] == 2 && $config['approveByAdmin']) {
    // Если включена активация по ссылке и модерация
    $view->warning = _m('Your account is created and must be verified before you can use it.<ul><li>You sent an email with verification instructions</li><li>Open the email and follow the instructions</li><li>After verification an administrator will be notified to activate your account</li><li>You\'ll receive a confirmation when it\'s done</li><li>Once that account has been activated you may login using the username and password you entered during registration</li></ul>');
} elseif ($config['letterMode'] == 2 && !$config['approveByAdmin']) {
    // Если включена активация по ссылке
    $view->warning = _m('Your account is created and must be activated before you can use it.<ul><li>You sent an email with activation instructions</li><li>Open the email and follow the instructions</li><li>Once that account has been activated you may login using the username and password you entered during registration</li></ul>');
} elseif ($config['letterMode'] < 2 && $config['approveByAdmin']) {
    // Eсли включена модерация
    $view->warning = _m('Your account has been created, but must be approved by the Administrator.<br>Once that account has been approved you may login using the username and password you entered during registration.');
}

$view->title = _s('Registration');
$view->setTemplate('message.php', null, false);
