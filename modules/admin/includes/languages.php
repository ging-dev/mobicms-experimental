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
$uri = $app->uri();
$form = new Mobicms\Form\Form(['action' => $uri]);
$form
    ->title(_m('Clear Cache'))
    ->html('<span class="description">' . _m('The Cache clearing is required after installing a new language or upgrade existing ones.') . '</span>')
    ->element('submit', 'update',
        [
            'value' => _m('Clear Cache'),
            'class' => 'btn btn-primary btn-xs'
        ]
    )
    ->title(_m('Default Language'))
    ->element('radio', 'lng',
        [
            'checked'     => Config::$lng,
            'description' => _m('If the choice is prohibited, the language will be forced to set for all visitors. If the choice is allowed, it will be applied only in the case, if requested by the client language is not in the system.'),
            'items'       => $app->lng()->getLocalesList()
        ]
    )
    ->element('checkbox', 'lngSwitch',
        [
            'checked'      => Config::$lngSwitch,
            'label_inline' => _m('Allow to choose'),
            'description'  => _m('Allow visitors specify the desired language from the list of available in the system. Including activated auto select languages by signatures of the browser.')
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
    if (isset($form->input['update'])) {
        // Обновляем кэш
        $app->lng()->clearCache();
        $app->redirect($uri . '?cache');
    } else {
        // Записываем настройки
        $app->session()->offsetUnset('lng');
        (new Mobicms\Config\WriteHandler())->write('System', $form->output);
        $app->redirect($uri . '?saved');
    }
}

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
