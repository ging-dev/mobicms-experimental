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

$config = $app->config()->get('usr');
$user = $app->user()->get();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);

if ($user->rights >= 7 || $user->nickChanged < time() - ($config['changeNicknamePeriod'] * 86400)) {
    $form
        ->title(_m('Change Nickname'))
        ->element('text', 'nickname',
            [
                'label'     => _m('New Nickname'),
                'maxlength' => 20,
                'required'  => true,
            ]
        )
        ->element('text', 'repeat',
            [
                'label'       => _m('Repeat Nickname'),
                'maxlength'   => 20,
                'description' => _s('Min. 2, Max. 20 Characters.<br>Allowed letters are Cyrillic and Latin alphabet, numbers, spaces and punctuation - = @ ! ? ~ . _ ( ) [ ] *') . '<br/>' .
                    _m('Please note that while changing the nickname is changing your Login on the site.<br>The next change of nickname is allowed through') . ' ' .
                    $config['changeNicknamePeriod'] . ' ' . _sp('Day', 'Days', $config['changeNicknamePeriod']) . '.',
                'required'    => true,
            ]
        )
        ->element('password', 'password',
            [
                'label'    => _m('Your Password'),
                'required' => true,
            ]
        )
        ->divider()
        ->element('submit', 'submit',
            [
                'value' => _s('Save'),
                'class' => 'btn btn-primary',
            ]
        )
        ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>')
        ->validate('nickname', 'lenght', ['min' => 2, 'max' => 20])
        ->validate('repeat', 'compare', ['compare_field' => 'nickname']);
} else {
    $form
        ->html('<div class="alert alert-danger">' .
            '<strong>' . _m('Nickname can not change more than once a') . ' ' . $config['changeNicknamePeriod'] . ' ' . _sp('Day', 'Days', $config['changeNicknamePeriod']) . '</strong><br/><br/>' .
            _m('You have already changed their nickname:') . ' ' . Includes\Functions::displayDate($user->nickChanged) . '<br/>' .
            _m('Next time will be able to change:') . ' ' . Includes\Functions::displayDate($user->nickChanged + ($config['changeNicknamePeriod'] * 86400)) .
            '</div>')
        ->html('<a class="btn btn-primary" href="../">' . _s('Back') . '</a>');
}

if ($form->isValid()) {
    $valid = $app->user()->validate();
    //TODO: Переделать на использование валидатора Nickname
    // Проверяем Ник
    if (!$valid->checkNicknameChars($form->output['nickname'])) {
        // Обнаружены запрещенные символы
        $form->setError('nickname', _s('Invalid characters'));
    } elseif (!$valid->checkNicknameCharsets($form->output['nickname'])) {
        // Обнаружены символы из разных языков
        $form->setError('nickname', _s('It is forbidden to use characters of different languages'));
    } elseif (ctype_digit($form->output['nickname'])) {
        // Ник состоит только из цифр
        $form->setError('nickname', _s('Nicknames consisting only of numbers are prohibited'));
    } elseif (!$valid->checkNicknameRepeatedChars($form->output['nickname'])) {
        // Обнаружены повторяющиеся символыь (более 3-х подряд)
        $form->setError('nickname', _s('Repeated characters'));
    } elseif (filter_var($form->output['nickname'], FILTER_VALIDATE_EMAIL)) {
        // Попытка использовать Email адрес в качестве Ника
        $form->setError('nickname', _s('Email cannot be used as the Nickname'));
    } elseif ($valid->checkNicknameExists($form->output['nickname'])) {
        // Ник уже занят
        $form->setError('nickname', _s('This Nickname is already taken'));
    }

    // Проверяем пароль
    if (!$user->checkPassword($form->output['password'])) {
        $form->setError('password', _s('Invalid password'));
    }

    // Если все проверки пройдены, записываем данные
    if ($form->isValid()) {
        $profile = $app->profile();
        $profile->nickname = $form->output['nickname'];
        $profile->nickChanged = time();
        $profile->save();

        $form->continueLink = '../';
        $form->successMessage = _m('Nickname successfully changed');
        $form->confirmation = true;
    }
}

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
