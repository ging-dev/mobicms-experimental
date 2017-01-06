<?php

use Registration\WelcomeLetter;

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$config = $app->config()->get('reg');
$user = $app->user()->get();

if ($user->id) {
    if (!$user->activated) {
        // Проверка на злоупотребление активацией (многократная отправка почты)
        //$stmtCount = $app->db()->prepare('SELECT COUNT(*) FROM `users_activations` WHERE `userId` = ? AND `type` = 0');
        //$stmtCount->execute([$user->id]);

        //if ($stmtCount->fetchColumn() > 1) {
        // Если число попыток повторной активации больше 10, удаляем пользователя и чистим активации.
        //    $stmtDel = $app->db()->prepare('DELETE FROM `users_activations` WHERE `userId` = ?');
        //    $stmtDel->execute([$user->id]);
        //}

        // Если пользователь еще не активировался по ссылке, выводим сообщение с инструкциями по активации
        $message = '<h3>' . _m('Your account is not activated yet.') . '</h3>';
        $message .= '<p>' . _m('Check your mailbox, it should have letter with an activation instructions. Follow these instructions. If you can not find, check your spam folder, maybe a letter by mistake got there.') . '</p>';
        $message .= '<p>' . _m('What to do if there is no letter?') . '</p>';
        $message .= '<ol>';
        $message .= '<li>' . _m('If you sure that entered the correct Email, try to send the letter again.') . '</li>';
        $message .= '<li>' . _m('If the message still does not come, try to change Email and send the letter again.') . '</li>';
        $message .= '<li>' . _m('If Email was specified in error, correct it and send the letter again.') . '</li>';
        $message .= '</ol>';
        $view->info[] = $message; //TODO: Разобраться

        // Показываем форму отправки повторного письма
        $form = new Mobicms\Form\Form(['action' => $app->uri()]);
        $form
            //->title(_m('Send again'))
            ->element('text', 'nickname',
                [
                    'label'    => _s('Your Nickname'),
                    'value'    => $user->nickname,
                    'readonly' => true,
                ]
            )
            ->element('text', 'email',
                [
                    'label'       => _s('Your Email'),
                    'value'       => $user->email,
                    'description' => _s('Please correctly specify your email address. This address will be sent a confirmation code to your registration.'),
                ]
            )
            ->divider(8)
            ->captcha()
            ->element('text', 'captcha',
                [
                    'label_inline' => _s('Verification code'),
                    'class'        => 'small',
                    'maxlenght'    => 5,
                    'reset_value'  => '',
                ]
            )
            ->divider()
            ->element('submit', 'submit',
                [
                    'value' => _s('Send'),
                    'class' => 'btn btn-primary',
                ]
            )
            ->element('submit', 'cancel',
                [
                    'value' => _s('Cancel'),
                    'class' => 'btn btn-link',
                ]
            )
            ->validate('captcha', 'captcha');

        if (isset($form->input['cancel'])) {
            // Если пользоватиель нажал "Отмена", разлогиниваем его и отправляем на Главную
            $app->user()->logout(true);
            $app->redirect($app->request()->getBaseUrl());
        }

        if ($form->isValid()) {

        }

        $view->buttonText = _m('Send an activation again');
        $view->slider = $form->display();

        if ($form->isValid() && $config['letterMode']) {
            try {
                $message = new WelcomeLetter($app, $user->id, $form->output['nickname'], $form->output['email']);
                $message->send(true);
            } catch (\Exception $e) {
                // Если возникли ошибки, выводим сообщение
                $form->errorMessage = _s('When sending emails the error occurred. Please contact the site administrator.');
                $form->setValid(false);
            }
        }
    }

    if (!$user->activated && !$user->approved) {
        // Выводим сообщение, если пользователь не активирован и не промодерирован
        $view->warning = _m('Your account must be approved by Administrator, but before that need activation.<br>Follow the instructions below.');
    } elseif (!$user->approved) {
        // Выводим сообщение, если пользователь активирован, но еще не промодерирован
        $view->warning = _m('Your account must be approved by Administrator.<br>Please wait...');
    }
} else {
    echo 'Надо залогиниться';
}

$view->setTemplate('message.php', null, false);
