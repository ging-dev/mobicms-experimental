<?php

defined('MOBICMS') or die('Error: restricted access');

use Registration\Activation;

/** @var Mobicms\Api\ViewInterface $view */
$view = App::getContainer()->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$activator = new Activation($app->user());

// Задаем сообщения об ошибках
$failedMsg = _m('<h3>Activation failed</h3>Possible reasons:<ul><li>You\'ve followed a broken activation link</li><li>From the moment of registration passed more than 24 hours.<br>In this case you need to be registered again.</li></ul>');
$activator->setMessages(
    [
        Activation::INVALIDTOKEN  => _m('<h3>Activation error</h3>You\'ve followed a broken activation link'),
        Activation::TOKENNOTFOUND => $failedMsg,
        Activation::TOKENEXPIRED  => $failedMsg,
        Activation::USERNOTFOUND  => $failedMsg,
    ]
);

if ($activator->isValid($app->router()->getQuery(1))) {
    $view->success = _m('<h3>Your Account has been successfully activated</h3>You can now log in using the username and password you chose during the registration.<br>Thank you for registering.');

    if (!$app->user()->get()->id) {
        // Если пользователь не залогинен, показываем ссылку на вход
        $view->message = '<a href="' . $app->request()->getBaseUrl() . '/login/" class="btn btn-primary">' . _s('Login') . '</a>';
    }
} else {
    $view->error = implode('<br>', $activator->getMessages());
}

$view->title = _s('Registration');
$view->setTemplate('message.php', null, false);
