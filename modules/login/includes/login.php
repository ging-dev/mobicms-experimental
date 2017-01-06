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
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form
    ->element('text', 'login',
        [
            'label'    => _s('Nickname or Email'),
            'class'    => 'relative largetext',
            'required' => true,
        ]
    )
    ->element('password', 'password',
        [
            'label'    => _s('Password'),
            'class'    => 'relative largetext',
            'required' => true,
        ]
    )
    ->element('checkbox', 'remember',
        [
            'checked'      => true,
            'label_inline' => _s('Remember'),
        ]
    )
    ->divider(12)
    ->element('submit', 'submit',
        [
            'value' => _s('Login'),
            'class' => 'btn btn-primary btn-lg btn-block',
        ]
    )
    ->html('<br/><a class="btn btn-default" href="#">' . _s('Forgot password?') . '</a>')
    ->validate('login', 'lenght', ['min' => 2, 'max' => 20])
    ->validate('password', 'lenght', ['min' => 3]);

if ($form->isValid()) {
    try {
        $app->user()->login($form->output['login'], $form->output['password'], $form->output['remember']);
        $user = $app->user()->get();

        if (!$user->activated || !$user->approved) {
            $app->redirect($app->request()->getBaseUrl() . '/registration/notice/');
        } else {
            $app->redirect($app->request()->getBaseUrl());
        }
    } catch (\Mobicms\Checkpoint\Exceptions\UserExceptionInterface $e) {
        $form->errorMessage = _s('Login information are incorrect');
        $form->setError('login', '');
        $form->setError('password', '');
    }
}

$view->form = $form->display();
$view->setTemplate('login.php');
