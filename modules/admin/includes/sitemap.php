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
    ->title(_m('Where to turn?'))
    ->element('checkbox', 'sitemapForum',
        [
            'label_inline' => _s('Forum'),
            'checked'      => Config::$sitemapForum
        ]
    )
    ->element('checkbox', 'sitemapLibrary',
        [
            'label_inline' => _s('Library'),
            'checked'      => Config::$sitemapLibrary
        ]
    )
    ->title(_m('To whom to show?'))
    ->element('radio', 'sitemapUsers',
        [
            'checked' => Config::$sitemapUsers,
            'items'   =>
                [
                    '1' => _m('All'),
                    '0' => _m('Guests only')
                ]
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
    $app->view()->save = true;
}

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
