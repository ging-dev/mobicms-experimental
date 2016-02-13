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
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form
    ->title(_m('Delete Avatar'))
    ->html('<p>' . _m('You really want to delete avatar?') . '</p>')
    ->divider()
    ->element('submit', 'submit',
        [
            'value' => _m('Delete'),
            'class' => 'btn btn-primary'
        ]
    )
    ->html('<a class="btn btn-link" href="../">' . _s('Cancel') . '</a>');

if ($form->isValid()) {
    $app->profile()->avatar = '';
    $app->profile()->save();

    $ext = ['.jpg', '.gif', '.png'];
    $file = FILES_PATH . 'users' . DS . 'avatar' . DS . $app->profile()->id;

    foreach ($ext as $val) {
        if (is_file($file . $val)) {
            unlink($file . $val);
        }
    }

    $form->continueLink = '../';
    $form->successMessage = _m('Avatar is deleted');
    $form->confirmation = true;
    $app->view()->hideuser = true;
}

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
