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

use Config\Registration;
use Config\Users;

$app = App::getInstance();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form
    ->title(_s('Registration'))
    ->element('checkbox', 'allow',
        [
            'label_inline' => _m('Allow registration'),
            'checked'      => Registration::$allow,
        ]
    )
    ->element('checkbox', 'approveByAdmin',
        [
            'label_inline' => _m('Confirmation by the Administrator'),
            'checked'      => Registration::$approveByAdmin,
            'description'  => _m('Regardless of other settings, the User will be activated only after confirmation by the Administrator'),
        ]
    )
    ->element('checkbox', 'useQuarantine',
        [
            'label_inline' => _m('Enable Quarantine') . ' <a href="quarantine/" class="btn btn-link btn-xs">[ ' . _s('Settings') . ' ]</a>',
            'checked'      => Registration::$useQuarantine,
            'description'  => _m('Quarantine allows you to temporarily restrict user activity'),
        ]
    )
    ->title(_m('Welcome Letter'))
    ->element('radio', 'letterMode',
        [
            'checked' => Registration::$letterMode,
            'items'   =>
                [
                    '0' => ['label' => _m('Do not send'), 'description' => _m('The letter is not sent, the Email field is not mandatory')],
                    '1' => ['label' => _m('Send Welcome Letter'), 'description' => _m('On the specified at registration Email address will be sent a Welcome Letter')],
                    '2' => ['label' => _m('Confirmation by Email'), 'description' => _m('On the specified at registration address will be sent an Email with a confirmation link')],
                ],
        ]
    );

if ($app->user()->get()->rights == 9) {
    $form
        ->title(_m('For Users'))
        ->element('checkbox', 'allowChangeSex',
            [
                'label_inline' => _m('Change Sex'),
                'checked'      => Users::$allowChangeSex,
            ]
        )
        ->element('checkbox', 'allowChangeStatus',
            [
                'label_inline' => _m('Change Status'),
                'checked'      => Users::$allowChangeStatus,
            ]
        )
        ->element('checkbox', 'allowUploadAvatars',
            [
                'label_inline' => _m('Upload Avatars'),
                'checked'      => Users::$allowUploadAvatars,
            ]
        )
        ->element('checkbox', 'allowUseGravatar',
            [
                'label_inline' => _m('Allow Gravatar'),
                'checked'      => Users::$allowUseGravatar,
            ]
        )
        ->element('checkbox', 'allowNicknamesOfDigits',
            [
                'label_inline' => _m('Allow Nicknames, consisting of digits'),
                'checked'      => Users::$allowNicknamesOfDigits,
            ]
        )
        ->element('checkbox', 'allowToChangeNickname',
            [
                'label_inline' => _m('Allow to change Nickname'),
                'checked'      => Users::$allowToChangeNickname,
            ]
        )
        ->element('text', 'changeNicknamePeriod',
            [
                'label_inline' => _m('After how many days?') . ' <span class="note">(0-99)</span>',
                'value'        => Users::$changeNicknamePeriod,
                'class'        => 'mini',
                'limit'        =>
                    [
                        'type' => 'int',
                        'min'  => 0,
                        'max'  => 99,
                    ],
            ]
        )
        ->title(_m('For Guests'))
        ->element('checkbox', 'allowGuestsOnlineLists',
            [
                'label_inline' => _m('Online Lists'),
                'checked'      => Users::$allowGuestsOnlineLists,
            ]
        )
        ->element('checkbox', 'allowGuestsToUserLists',
            [
                'label_inline' => _m('List of Users'),
                'checked'      => Users::$allowGuestsToUserLists,
            ]
        )
        ->element('checkbox', 'allowGuestsToViewProfiles',
            [
                'label_inline' => _m('View Profiles'),
                'checked'      => Users::$allowGuestsToViewProfiles,
            ]
        )
        ->title(_m('Antiflood'))
        ->element('radio', 'antifloodMode',
            [
                'checked' => Users::$antifloodMode,
                'items'   =>
                    [
                        '3' => _m('Day mode'),
                        '4' => _m('Night mode'),
                        '2' => ['label' => _m('Autoswitch'), 'description' => _m('From 10:00 till 20:00 the day mode, in the rest of the time the night mode is active')],
                        '1' => ['label' => _m('Adaptive'), 'description' => _m('If on the site there is someone from administration, the day mode, differently the night mode is active')],
                    ],
            ]
        )
        ->element('text', 'antifloodDayDelay',
            [
                'value'        => Users::$antifloodDayDelay,
                'class'        => 'small',
                'label_inline' => _m('Day mode, delay in seconds') . ' <span class="note">(3-300)</span>',
                'limit'        =>
                    [
                        'type' => 'int',
                        'min'  => 3,
                        'max'  => 300,
                    ],
            ]
        )
        ->element('text', 'antifloodNightDelay',
            [
                'value'        => Users::$antifloodNightDelay,
                'class'        => 'small',
                'label_inline' => _m('Night mode, delay in seconds') . ' <span class="note">(3-300)</span>',
                'limit'        =>
                    [
                        'type' => 'int',
                        'min'  => 3,
                        'max'  => 300,
                    ],
            ]
        );
}

$form
    ->divider()
    ->element('submit', 'submit',
        [
            'value' => _s('Save'),
            'class' => 'btn btn-primary',
        ]
    )
    ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>');

if ($form->isValid()) {
    // Записываем настройки регистрации
    $regData =
        [
            'allow'          => (bool)$form->output['allow'],
            'approveByAdmin' => (bool)$form->output['approveByAdmin'],
            'letterMode'     => (int)$form->output['letterMode'],
            'useQuarantine'  => (bool)$form->output['useQuarantine'],
        ];
    (new Mobicms\Config\WriteHandler())->write('Registration', $regData);

    // записываем настройки пользователей
    $userData =
        [
            'allowChangeSex'            => (bool)$form->output['allowChangeSex'],
            'allowChangeStatus'         => (bool)$form->output['allowChangeStatus'],
            'allowUploadAvatars'        => (bool)$form->output['allowUploadAvatars'],
            'allowUseGravatar'          => (bool)$form->output['allowUseGravatar'],
            'allowNicknamesOfDigits'    => (bool)$form->output['allowNicknamesOfDigits'],
            'allowToChangeNickname'     => (bool)$form->output['allowToChangeNickname'],
            'changeNicknamePeriod'      => (int)$form->output['changeNicknamePeriod'],
            'allowGuestsOnlineLists'    => (bool)$form->output['allowGuestsOnlineLists'],
            'allowGuestsToUserLists'    => (bool)$form->output['allowGuestsToUserLists'],
            'allowGuestsToViewProfiles' => (bool)$form->output['allowGuestsToViewProfiles'],
            'antifloodMode'             => (int)$form->output['antifloodMode'],
            'antifloodDayDelay'         => (int)$form->output['antifloodDayDelay'],
            'antifloodNightDelay'       => (int)$form->output['antifloodNightDelay'],
        ];
    (new Mobicms\Config\WriteHandler())->write('Users', $userData);
    $app->redirect($app->uri() . '?saved');
}

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
