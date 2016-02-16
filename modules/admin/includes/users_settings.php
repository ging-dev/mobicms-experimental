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

$regConfig = $app->config()->get('reg');
$usrConfig = $app->config()->get('usr');

$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form
    ->title(_s('Registration'))
    ->element('checkbox', 'allow',
        [
            'label_inline' => _m('Allow registration'),
            'checked'      => $regConfig['allow'],
        ]
    )
    ->element('checkbox', 'approveByAdmin',
        [
            'label_inline' => _m('Confirmation by the Administrator'),
            'checked'      => $regConfig['approveByAdmin'],
            'description'  => _m('Regardless of other settings, the User will be activated only after confirmation by the Administrator'),
        ]
    )
    ->element('checkbox', 'useQuarantine',
        [
            'label_inline' => _m('Enable Quarantine') . ' <a href="quarantine/" class="btn btn-link btn-xs">[ ' . _s('Settings') . ' ]</a>',
            'checked'      => $regConfig['useQuarantine'],
            'description'  => _m('Quarantine allows you to temporarily restrict user activity'),
        ]
    )
    ->title(_m('Welcome Letter'))
    ->element('radio', 'letterMode',
        [
            'checked' => $regConfig['letterMode'],
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
                'checked'      => $usrConfig['allowChangeSex'],
            ]
        )
        ->element('checkbox', 'allowChangeStatus',
            [
                'label_inline' => _m('Change Status'),
                'checked'      => $usrConfig['allowChangeStatus'],
            ]
        )
        ->element('checkbox', 'allowUploadAvatars',
            [
                'label_inline' => _m('Upload Avatars'),
                'checked'      => $usrConfig['allowUploadAvatars'],
            ]
        )
        ->element('checkbox', 'allowUseGravatar',
            [
                'label_inline' => _m('Allow Gravatar'),
                'checked'      => $usrConfig['allowUseGravatar'],
            ]
        )
        ->element('checkbox', 'allowNicknamesOfDigits',
            [
                'label_inline' => _m('Allow Nicknames, consisting of digits'),
                'checked'      => $usrConfig['allowNicknamesOfDigits'],
            ]
        )
        ->element('checkbox', 'allowToChangeNickname',
            [
                'label_inline' => _m('Allow to change Nickname'),
                'checked'      => $usrConfig['allowToChangeNickname'],
            ]
        )
        ->element('text', 'changeNicknamePeriod',
            [
                'label_inline' => _m('After how many days?') . ' <span class="note">(0-99)</span>',
                'value'        => $usrConfig['changeNicknamePeriod'],
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
                'checked'      => $usrConfig['allowGuestsOnlineLists'],
            ]
        )
        ->element('checkbox', 'allowGuestsToUserLists',
            [
                'label_inline' => _m('List of Users'),
                'checked'      => $usrConfig['allowGuestsToUserLists'],
            ]
        )
        ->element('checkbox', 'allowGuestsToViewProfiles',
            [
                'label_inline' => _m('View Profiles'),
                'checked'      => $usrConfig['allowGuestsToViewProfiles'],
            ]
        )
        ->title(_m('Antiflood'))
        ->element('radio', 'antifloodMode',
            [
                'checked' => $usrConfig['antifloodMode'],
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
                'value'        => $usrConfig['antifloodDayDelay'],
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
                'value'        => $usrConfig['antifloodNightDelay'],
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
    $out['reg'] =
        [
            'allow'          => (bool)$form->output['allow'],
            'approveByAdmin' => (bool)$form->output['approveByAdmin'],
            'letterMode'     => (int)$form->output['letterMode'],
            'useQuarantine'  => (bool)$form->output['useQuarantine'],
        ];
    $out['usr'] =
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

    $app->config()->merge(new Zend\Config\Config($out, true));
    (new Zend\Config\Writer\PhpArray)->toFile(CONFIG_FILE_SYS, $app->config());

    // Clear opcode cache
    if (function_exists('opcache_invalidate')) {
        opcache_invalidate(CONFIG_FILE_SYS);
    }

    $app->redirect($app->uri() . '?saved');
}

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
