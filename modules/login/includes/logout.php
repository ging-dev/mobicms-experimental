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
$homeUrl = $app->request()->getBaseUrl();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form
    ->title(_s('Leave the site?'))
    ->element('checkbox', 'clear',
        [
            'label_inline' => _s('Remove authorization from all devices')
        ]
    )
    ->divider(12)
    ->element('submit', 'submit',
        [
            'value' => '   ' . _s('Exit') . '   ',
            'class' => 'btn btn-primary btn-lg btn-block'
        ]
    )
    ->html('<br/><a class="btn btn-default btn-lg btn-block" href="' . $homeUrl . '/profile/' . $app->user()->get()->id . '/">' . _s('Back') . '</a>');

if ($form->isValid()) {
    $app->user()->logout($form->output['clear']);
    $app->redirect($homeUrl);
}

$view->form = $form->display();
$view->setTemplate('login.php');
