<?php
return [
    'mobicms'    => [

    ],

    // Старый конфиг, убрать
    'sys'        => [
        'timeshift'           => 4,
        'filesize'            => 2100,
        'profilingGeneration' => true,
        'profilingMemory'     => true,
        'siteName'            => 'mobiCMS DEV',
        'email'               => 'user@example.com',
        'copyright'           => 'Powered by mobiCMS',
        'homeTitle'           => 'mobiCMS!',
        'metaKey'             => 'mobicms',
        'metaDesc'            => 'mobiCMS mobile content management system http://mobicms.net',
    ],
    'reg'        => [
        'allow'          => true,
        'approveByAdmin' => false,
        'letterMode'     => 2,
        'useQuarantine'  => false,
    ],
    'usr'        => [
        'allowChangeSex'            => false,
        'allowChangeStatus'         => true,
        'allowUploadAvatars'        => true,
        'allowUseGravatar'          => true,
        'allowNicknamesOfDigits'    => false,
        'allowToChangeNickname'     => true,
        'changeNicknamePeriod'      => 30,
        'allowGuestsOnlineLists'    => true,
        'allowGuestsToUserLists'    => true,
        'allowGuestsToViewProfiles' => true,
        'antifloodMode'             => 1,
        'antifloodDayDelay'         => 10,
        'antifloodNightDelay'       => 40,
    ],
    'lng'        => [
        'lng'       => 'ru',
        'lngSwitch' => true,
    ],
    'quarantine' => [
        'period'         => 24,
        'mailSent'       => 5,
        'mailRecipients' => 3,
        'comments'       => 5,
        'uploadImages'   => 3,
        'reputation'     => false,
        'album'          => 3,
    ],

    // Пример конфига от JohnCMS
    'johncms'    =>
        [
            'active'        => 1,
            'antiflood'     =>
                [
                    'mode'    => 2,
                    'day'     => 10,
                    'night'   => 30,
                    'dayfrom' => 10,
                    'dayto'   => 22,
                ],
            'clean_time'    => 0,
            'copyright'     => 'Powered by JohnCMS',
            'email'         => 'user@example.com',
            'flsz'          => '16000',
            'gzip'          => 1,
            'homeurl'       => 'http://johncms/johncms-next',
            'karma'         =>
                [
                    'karma_points' => 5,
                    'karma_time'   => 86400,
                    'forum'        => 20,
                    'time'         => 0,
                    'on'           => 1,
                    'adm'          => 0,
                ],
            'lng'           => 'ru',
            'lng_list'      =>
                [
                    'ar' => 'Arabic',
                    'en' => 'English',
                    'id' => 'Indonesia',
                    'lt' => 'Lietuvos',
                    'pl' => 'Polski',
                    'ro' => 'Romana',
                    'ru' => 'Русский',
                    'uk' => 'Український',
                    'vi' => 'Việt Nam',
                ],
            'mod_reg'       => 2,
            'mod_forum'     => 2,
            'mod_guest'     => 2,
            'mod_lib'       => 2,
            'mod_lib_comm'  => 1,
            'mod_down'      => 2,
            'mod_down_comm' => 1,
            'meta_key'      => 'johncms',
            'meta_desc'     => 'Powered by JohnCMS http://johncms.com',
            'news'          =>
                [
                    'view'     => 1,
                    'size'     => 200,
                    'quantity' => 3,
                    'days'     => 7,
                    'breaks'   => true,
                    'smileys'  => false,
                    'tags'     => true,
                    'kom'      => true,
                ],
            'skindef'       => 'default',
            'timeshift'     => 0,
        ],
];
