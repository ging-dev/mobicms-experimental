<?php
return array(
    'sys' => array(
        'timeshift' => 4,
        'filesize' => 2100,
        'profilingGeneration' => true,
        'profilingMemory' => true,
        'email' => 'user@example.com',
        'copyright' => 'Powered by mobiCMS',
        'homeTitle' => 'mobiCMS!',
        'metaKey' => 'mobicms',
        'metaDesc' => 'mobiCMS mobile content management system http://mobicms.net',
    ),
    'reg' => array(
        'allow' => true,
        'approveByAdmin' => false,
        'letterMode' => 2,
        'useQuarantine' => false,
    ),
    'usr' => array(
        'allowChangeSex' => false,
        'allowChangeStatus' => true,
        'allowUploadAvatars' => true,
        'allowUseGravatar' => true,
        'allowNicknamesOfDigits' => false,
        'allowToChangeNickname' => true,
        'changeNicknamePeriod' => 30,
        'allowGuestsOnlineLists' => true,
        'allowGuestsToUserLists' => true,
        'allowGuestsToViewProfiles' => true,
        'antifloodMode' => 1,
        'antifloodDayDelay' => 10,
        'antifloodNightDelay' => 40,
    ),
    'lng' => array(
        'lng' => 'ru',
        'lngSwitch' => true,
    ),
    'quarantine' => array(
        'period' => 24,
        'mailSent' => 5,
        'mailRecipients' => 3,
        'comments' => 5,
        'uploadImages' => 3,
        'reputation' => false,
        'album' => 3,
    ),
);