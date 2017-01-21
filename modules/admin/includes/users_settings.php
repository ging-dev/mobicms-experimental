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

/** @var Mobicms\Api\ConfigInterface $config */
$config = $container->get(Mobicms\Api\ConfigInterface::class);

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();

$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form
    ->title(_s('Registration'))
    ->element('checkbox', 'registrationAllow',
        [
            'label_inline' => _m('Allow registration'),
            'checked'      => $config->registrationAllow,
        ]
    )
    ->element('checkbox', 'registrationApproveByAdmin',
        [
            'label_inline' => _m('Confirmation by the Administrator'),
            'checked'      => $config->registrationApproveByAdmin,
            'description'  => _m('Regardless of other settings, the User will be activated only after confirmation by the Administrator'),
        ]
    )
    ->element('checkbox', 'registrationQuarantine',
        [
            'label_inline' => _m('Enable Quarantine') . ' <a href="quarantine/" class="btn btn-default btn-xs">' . _s('Settings') . '</a>',
            'checked'      => $config->registrationQuarantine,
            'description'  => _m('Quarantine allows you to temporarily restrict user activity'),
        ]
    )
    ->title(_m('Welcome Letter'))
    ->element('radio', 'registrationLetterMode',
        [
            'checked' => $config->registrationLetterMode,
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
        ->element('checkbox', 'userAllowChangeSex',
            [
                'label_inline' => _m('Change Sex'),
                'checked'      => $config->userAllowChangeSex,
            ]
        )
        ->element('checkbox', 'userAllowChangeStatus',
            [
                'label_inline' => _m('Change Status'),
                'checked'      => $config->userAllowChangeStatus,
            ]
        )
        ->element('checkbox', 'userAllowUploadAvatars',
            [
                'label_inline' => _m('Upload Avatars'),
                'checked'      => $config->userAllowUploadAvatars,
            ]
        )
        ->element('checkbox', 'userAllowUseGravatar',
            [
                'label_inline' => _m('Allow Gravatar'),
                'checked'      => $config->userAllowUseGravatar,
            ]
        )
        ->element('checkbox', 'userAllowNicknamesOfDigits',
            [
                'label_inline' => _m('Allow Nicknames, consisting of digits'),
                'checked'      => $config->userAllowNicknamesOfDigits,
            ]
        )
        ->element('checkbox', 'userAllowChangeNickname',
            [
                'label_inline' => _m('Allow to change Nickname'),
                'checked'      => $config->userAllowChangeNickname,
            ]
        )
        ->element('text', 'userChangeNicknamePeriod',
            [
                'label_inline' => _m('After how many days?') . ' <span class="note">(0-99)</span>',
                'value'        => $config->userChangeNicknamePeriod,
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
        ->element('checkbox', 'guestsAllowOnlineLists',
            [
                'label_inline' => _m('Online Lists'),
                'checked'      => $config->guestsAllowOnlineLists,
            ]
        )
        ->element('checkbox', 'guestsAllowUserLists',
            [
                'label_inline' => _m('List of Users'),
                'checked'      => $config->guestsAllowUserLists,
            ]
        )
        ->element('checkbox', 'guestsAllowViewProfiles',
            [
                'label_inline' => _m('View Profiles'),
                'checked'      => $config->guestsAllowViewProfiles,
            ]
        )
        ->title(_m('Antiflood'))
        ->element('radio', 'antifloodMode',
            [
                'checked' => $config->antifloodMode,
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
                'value'        => $config->antifloodDayDelay,
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
                'value'        => $config->antifloodNightDelay,
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
    //TODO: запилить запись настроек!!!
    /*
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
    */
    $app->redirect($app->uri() . '?saved');
}

$view->form = $form->display();
$view->setTemplate('edit_form.php');
