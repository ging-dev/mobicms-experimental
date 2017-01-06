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
$user = $app->user()->get();
$profile = $app->profile();

if ($user->rights == 9 || ($user->rights == 7 && $user->rights > $profile->rights)) {
    $items =
        [
            0 => _m('User'),
            3 => _m('Forum Moderator'),
            4 => _m('Downloads Moderator'),
            5 => _m('Library Moderator'),
            6 => _m('Super Modererator')
        ];

    if ($user->rights == 9) {
        $items['7'] = '<i class="exclamation-circle lg"></i> ' . _m('Administrator');
        $items['9'] = '<span class="danger"><i class="exclamation-circle lg"></i> ' . _m('Supervisor') . '</span>';
    }

    $form = new Mobicms\Form\Form(['action' => $app->uri()]);
    $form
        ->title(_m('Rank'))
        ->element('radio', 'rights',
            [
                'checked' => $profile->rights,
                'items'   => $items
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
        ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>');

    if ($form->isValid()) {
        // Проверяем пароль
        if (!$user->checkPassword($form->output['password'])) {
            $form->setError('password', _s('Invalid password'));
        }

        if ($form->isValid()) {
            $profile->offsetSet('rights', intval($form->output['rights']), true);
            $profile->save();

            if ($profile->id == $user->id) {
                $app->redirect('../');
            }
        }
    }

    $view->admin = true;
    $view->form = $form->display();
    $view->setTemplate('edit_form.php');
}
