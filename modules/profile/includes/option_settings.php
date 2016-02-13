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
$profile = $app->profile();
$config = $profile->config();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$editors[0] = _m('Without Editor');
$editors[1] = '<abbr title="SCeditor">' . _m('WYSIWYG Editor') . '</abbr>';

if ($profile->rights == 9) {
    $editors[2] = '<abbr title="CodeMirror">' . _m('HTML Editor') . '</abbr>';
}

$form
    // Set system settings
    ->title(_m('System Settings'))
    ->element('text', 'timeShift',
        [
            'value'        => $config->timeShift,
            'label_inline' => '<span class="badge badge-large">' . date("H:i", time() + (Config::$timeshift + $config->timeShift) * 3600) . '</span> ' . _m('Time settings'),
            'description'  => _m('Time Shift') . ' (+ - 12)',
            'class'        => 'small',
            'maxlength'    => 3,
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => -12,
                    'max'  => 13
                ]
        ]
    )
    ->element('text', 'pageSize',
        [
            'value'        => $config->pageSize,
            'label_inline' => _m('List Size'),
            'description'  => _m('Number of items per page') . ' (5-99)',
            'class'        => 'small',
            'maxlength'    => 2,
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => 5,
                    'max'  => 99
                ]
        ]
    )
    ->element('checkbox', 'directUrl',
        [
            'checked'      => $config->directUrl,
            'label_inline' => _m('Direct URL')
        ]
    )
    // Choose text editor
    ->title(_m('Text Editor'))
    ->element('radio', 'editor',
        [
            'checked' => $config->editor,
            'items'   => $editors
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
    $config->timeShift = $form->output['timeShift'];
    $config->pageSize = $form->output['pageSize'];
    $config->directUrl = $form->output['directUrl'];
    $config->editor = $form->output['editor'];
    $config->save();

    $app->redirect($app->request()->getUri() . '?saved');
}

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
