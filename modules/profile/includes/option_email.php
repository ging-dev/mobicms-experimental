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

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$profile = $app->profile();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form->title(_m('Change E-mail'));

if (!empty($profile->email)) {
    $form->element('text', 'oldemail',
        [
            'label'    => _m('Old E-mail'),
            'value'    => $profile->email,
            'readonly' => true
        ]
    );
}

$form
    ->element('text', 'email',
        [
            'label'     => _m('New E-mail'),
            'maxlength' => 50
        ]
    )
    ->element('text', 'repeatemail',
        [
            'label'       => _m('Repeat E-mail'),
            'maxlength'   => 50,
            'description' => _m('Correctly specify your email address, that it will be sent your password.<br/>Max. 50 characters')
        ]
    )
    ->element('password', 'password',
        [
            'label'    => _m('Your Password'),
            'required' => true
        ]
    )
    ->divider()
    ->element('submit', 'submit',
        [
            'value' => _s('Save'),
            'class' => 'btn btn-primary'
        ]
    )
    ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>')
    ->validate('email', 'lenght', ['min' => 5, 'max' => 50, 'empty' => true])
    ->validate('repeatemail', 'compare', ['compare_field' => 'email']);

if ($form->isValid()) {
    // Проверяем Email
    if (!filter_var($form->output['email'], FILTER_VALIDATE_EMAIL)) {
        $form->setError('email', _s('Invalid Email address'));
    } elseif ($app->user()->validate()->checkEmailExists($form->output['email'])) {
        $form->setError('email', _s('This Email is already taken'));
    }

    // Проверяем пароль
    if (!$user->checkPassword($form->output['password'])) {
        $form->setError('password', _s('Invalid password'));
    }

    if ($form->isValid()) {
        $profile->email = $form->output['email'];
        $profile->save();
        $form->continueLink = '../';
        $form->confirmation = true;
    }
}

$view->form = $form->display();
$view->setTemplate('edit_form.php');

//TODO: Добавить подтверждение по Email
