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

use Config\System as Config;

$app = App::getInstance();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form
    ->title(_s('Forum'))
    ->element('radio', 'aclForum',
        [
            'checked' => Config::$aclForum,
            'items'   =>
                [
                    '2' => _m('Access is open'),
                    '1' => _m('Only for the authorized'),
                    '3' => _m('Read only'),
                    '0' => _m('Access denied')
                ]
        ]
    )
    ->title(_s('Guestbook'))
    ->element('radio', 'aclGuestbook',
        [
            'checked' => Config::$aclGuestbook,
            'items'   =>
                [
                    '2' => _m('Enable posting for guests'),
                    '1' => _m('Access is open'),
                    '0' => _m('Access denied')
                ]
        ]
    )
    ->title(_s('Library'))
    ->element('radio', 'aclLibrary',
        [
            'checked' => Config::$aclLibrary,
            'items'   =>
                [
                    '2' => _m('Access is open'),
                    '1' => _m('Only for the authorized'),
                    '0' => _m('Access denied')
                ]
        ]
    )
    ->title(_s('Downloads'))
    ->element('radio', 'aclDownloads',
        [
            'checked' => Config::$aclDownloads,
            'items'   =>
                [
                    '2' => _m('Access is open'),
                    '1' => _m('Only for the authorized'),
                    '0' => _m('Access denied')
                ]
        ]
    )
    ->element('checkbox', 'aclDownloadsComm',
        [
            'label_inline' => _s('Comments'),
            'checked'      => Config::$aclDownloadsComm
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
    // Записываем настройки
    (new Mobicms\Config\WriteHandler())->write('System', $form->output);
    //App::cfg()->sys->write($form->output);
    $app->view()->save = true;
}

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
