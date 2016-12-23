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

defined('JOHNCMS') or die('Error: restricted access');

use Mobicms\Checkpoint\User\AddUser;
use Mobicms\Validator\Email;
use Mobicms\Validator\Nickname;

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

$app = App::getInstance();
$config = $app->config()->get('reg');
$userId = 0;

if ($config['allow']) {
    $form = new Mobicms\Form\Form(['action' => $app->uri()]);
    $form
        ->html('<div class="alert alert-warning">'
            . sprintf(
                _s('By registering on the site, you acknowledge that you have read <a href="%s">Terms of Use</a> and agree with them.'),
                $app->homeurl() . '/help/rules/'
            )
            . '</div>')
        ->element('text', 'nickname',
            [
                'label'       => _s('Choose Nickname'),
                'description' => _s('Min. 2, Max. 20 Characters.<br>Allowed letters are Cyrillic and Latin alphabet, numbers, spaces and punctuation - = @ ! ? ~ . _ ( ) [ ] *'),
                'required'    => true,
            ]
        )
        ->element('text', 'email',
            [
                'label'       => _s('Your Email'),
                'description' => _s('Please correctly specify your email address. This address will be sent a confirmation code to your registration.'),
                'required'    => $config['letterMode'],
            ]
        )
        ->element('password', 'newpass',
            [
                'label'    => _s('Password'),
                'required' => true,
            ]
        )
        ->element('password', 'newconf',
            [
                'label'       => _s('Repeat password'),
                'description' => _s('The password length min. 3 characters'),
                'required'    => true,
            ]
        )
        ->element('radio', 'sex',
            [
                'label'   => _s('Gender'),
                'checked' => 'm',
                'items'   =>
                    [
                        'm' => '<i class="male lg fw"></i>' . _s('Male'),
                        'w' => '<i class="female lg fw"></i>' . _s('Female'),
                    ],
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
                'value' => _s('Sign Up'),
                'class' => 'btn btn-primary',
            ]
        )
        ->html('<a class="btn btn-link" href="' . $app->request()->getBaseUrl() . '/login/">' . _s('Cancel') . '</a>')
        ->validate('captcha', 'captcha');

    /**
     * Form validation
     */
    if ($form->isValid()) {
        ////////////////////////////////////////////////////////////
        // Nickname validation                                    //
        ////////////////////////////////////////////////////////////
        $checkNickname = new Nickname;
        $checkNickname->setMessages(
            [
                Nickname::LENGTH    => _s('Nickname must be at least 2 and no more than 20 characters in length'),
                Nickname::SYMBOLS   => _s('Nickname contains illegal characters'),
                Nickname::CHARSET   => _s('Nickname contains characters from different languages'),
                Nickname::DIGITS    => _s('Nicknames consisting only of digits are prohibited'),
                Nickname::RECURRING => _s('Nickname contains recurring characters'),
                Nickname::EMAIL     => _s('Email cannot be used as the Nickname'),
                Nickname::EXISTS    => _s('This Nickname is already taken'),
            ]
        );

        if (!$checkNickname->isValid($form->output['nickname'])) {
            $form->setError('nickname', implode('<br>', $checkNickname->getMessages()));
        }

        ////////////////////////////////////////////////////////////
        // Password validation                                    //
        ////////////////////////////////////////////////////////////
        if (mb_strlen($form->output['newpass']) < 3) {
            $form->setError('newpass', _s('Password must be at least 3 characters in length'));
        }

        if ($form->output['newpass'] != $form->output['newconf']) {
            $form->setError('newconf', _s('Passwords don\'t coincide'));
        }

        ////////////////////////////////////////////////////////////
        // Email validation                                       //
        ////////////////////////////////////////////////////////////
        if ($config['letterMode'] >= 1 || !empty($form->output['email'])) {
            $checkEmail = new Email;

            if (!$checkEmail->isValid($form->output['email'])) {
                $form->setError('email',
                    _m('The email address you entered is already in use or invalid. Please enter another email address.'));
            }
        }
    }

    /**
     * Try to add to the database
     */
    if ($form->isValid()) {
        try {
            /** @var Mobicms\Environment\Network $network */
            $network = $container->get(Mobicms\Environment\Network::class);

            // Инициализируем класс Регистрации
            $user = new AddUser([]);

            // Вводим данные пользователя
            $user->email = $form->output['email'];
            $user->nickname = $form->output['nickname'];
            $user->setPassword($form->output['newpass']);
            $user->sex = $form->output['sex'];
            $user->joinDate = time();
            $user->ip = $network->getClientIp();
            $user->userAgent = $network->getUserAgent();

            // Устанавливаем статус активации, подтверждения и карантина
            $user->activated = $config['letterMode'] < 2 ? true : false;
            $user->approved = $config['approveByAdmin'] ? false : true;
            $user->quarantine = $config['useQuarantine'];

            // Добавляем пользователя в базу данных
            $user->save();
            $userId = $user->getInsertId();

            // Если не требуется активация и модерация, сразу впускаем пользователя на сайт
            if ($config['letterMode'] < 2 && !$config['approveByAdmin']) {
                $app->user()->login($form->output['nickname'], $form->output['newpass'], true);
            }
        } catch (Exception $e) {
            $form->errorMessage = _s('When saving the form there errors occurred, try once again. If problem repeat, contact the Site Administrator');
            $form->setValid(false);
        }
    }

    /**
     * Try to send Email
     */
    if ($form->isValid() && $config['letterMode']) {
        try {
            $message = new Registration\WelcomeLetter($app, $userId, $form->output['nickname'], $form->output['email']);
            $message->send();
        } catch (\Exception $e) {
            // Если возникли ошибки, выводим сообщение
            $form->errorMessage = _s('When sending emails the error occurred. Please contact the site administrator.');
            $form->setValid(false);
        }
    }

    /**
     * Заключительные действия
     */
    if ($form->isValid()) {
        if ($app->user()->isValid()) {
            $app->redirect($app->homeurl());
        } else {
            $app->redirect($app->uri() . 'confirmation/');
        }
    } else {
        $app->view()->form = $form->display();
    }

    $app->view()->setTemplate('index.php');
}
