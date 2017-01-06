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

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();

/**
 * @var $profile Mobicms\Checkpoint\User\AbstractUser
 */
$profile = $app->get('profile');
$profileData = $profile->getUserData('profile');
$profileData->allowModifications(true);

$config = $app->config()->get('usr');
$userRights = $app->user()->get()->rights;
$allowEdit = $userRights = 9 || ($userRights = 7 && $userRights > $profile->rights) ? true : false;

$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form->title(_m('Edit Profile'));

if ($config['allowChangeStatus'] || $allowEdit) {
    $form
        ->html('<div class="form-group">')
        ->element('text', 'status',
            [
                'label'       => _m('Status'),
                'value'       => $profile->status,
                'description' => _m('Min.3, Max. 50 symbols, or blank to remove status'),
                'filter'      => FILTER_SANITIZE_SPECIAL_CHARS,
            ]
        )
        ->html('</div>');
}

$form
    ->element('text', 'realName',
        [
            'label'       => _m('Your Name'),
            'value'       => $profileData->realName,
            'description' => _m('Max. 50 characters'),
            'filter'      => FILTER_SANITIZE_STRING,
        ]
    );

if ($config['allowChangeSex'] || $allowEdit) {
    $form
        ->element('radio', 'sex',
            [
                'label'   => _s('Gender'),
                'checked' => $profile->sex,
                'items'   =>
                    [
                        'm' => _s('Male'),
                        'w' => _s('Female'),
                    ],
            ]
        );
}

$form
    ->element('text', 'day',
        [
            'label'    => _m('Birthday'),
            //'value'  => date("d", strtotime($profile->birth)),
            'readonly' => true,
            'class'    => 'mini',
            'filter'   => FILTER_SANITIZE_NUMBER_INT,
        ]
    )
    ->element('text', 'month',
        [
            //'value'  => date("m", strtotime($profile->birth)),
            'readonly' => true,
            'class'    => 'mini',
            'filter'   => FILTER_SANITIZE_NUMBER_INT,
        ]
    )
    ->element('text', 'year',
        [
            //'value'       => date("Y", strtotime($profile->birth)),
            'readonly'    => true,
            'class'       => 'small',
            'description' => _m('Day, month, year'),
            'filter'      => FILTER_SANITIZE_NUMBER_INT,
        ]
    )
    ->element('text', 'live',
        [
            'label'       => _m('Accommodation'),
            'value'       => $profileData->live,
            'description' => _m('Specify the country of residence, your city.<br/>Max. 100 characters.'),
            'filter'      => FILTER_SANITIZE_STRING,
        ]
    )
    ->element('textarea', 'about',
        [
            'label'       => _m('About yourself'),
            'value'       => $profileData->about,
            'editor'      => false,
            'description' => _m('Max. 5000 characters'),
        ]
    )
    ->element('text', 'tel',
        [
            'label'       => _m('Phone Number'),
            'value'       => $profileData->tel,
            'description' => _m('Max. 100 characters'),
            'filter'      => FILTER_SANITIZE_STRING,
        ]
    )
    ->element('text', 'siteurl',
        [
            'label'       => _m('Site'),
            'value'       => $profileData->siteurl,
            'description' => _m('You can enter multiple URL, separated by spaces.<br/>Max. 100 characters'),
            'filter'      => FILTER_SANITIZE_STRING,
        ]
    );

if (!empty($profile->email)) {
    $form
        ->element('text', 'email',
            [
                'label'    => 'E-mail',
                'value'    => $profile->email,
                'readonly' => true,
                'filter'   => FILTER_SANITIZE_EMAIL,
            ]
        )
        ->element('checkbox', 'showEmail',
            [
                'label_inline' => _m('Your Email visible to everyone'),
                'checked'      => $profile->showEmail,
            ]
        );
}

$form
    ->element('text', 'skype',
        [
            'label'       => 'Skype',
            'value'       => $profileData->skype,
            'description' => _m('Max. 50 characters'),
            'filter'      => FILTER_SANITIZE_STRING,
        ]
    )
    ->element('text', 'icq',
        [
            'label'       => 'ICQ',
            'value'       => $profileData->icq,
            'description' => _m('Enter your UIN number'),
            'filter'      => FILTER_SANITIZE_NUMBER_INT,
        ]
    )
    ->divider()
    ->element('submit', 'submit',
        [
            'value' => _s('Save'),
            'class' => 'btn btn-primary',
        ]
    )
    ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>')
    ->validate('status', 'lenght', ['min' => 3, 'max' => 50, 'empty' => true])
    ->validate('realName', 'lenght', ['max' => 50])
    ->validate('live', 'lenght', ['max' => 100])
    ->validate('about', 'lenght', ['max' => 5000])
    ->validate('tel', 'lenght', ['max' => 100])
    ->validate('siteurl', 'lenght', ['max' => 100])
    ->validate('skype', 'lenght', ['max' => 50])
    ->validate('icq', 'numeric', ['min' => 10000, 'empty' => true]);

if ($form->isValid()) {
    $profile->status = $form->output['status'];
    $profile->sex = $form->output['sex'];
    $profileData->realName = $form->output['realName'];
    $profileData->live = $form->output['live'];
    $profileData->about = trim($app->purify($form->output['about']));
    $profileData->tel = $form->output['tel'];
    $profileData->siteurl = $form->output['siteurl'];
    $profile->showEmail = isset($form->output['showEmail']) ? 1 : 0;
    $profileData->icq = $form->output['icq'];
    $profileData->skype = $form->output['skype'];

    //TODO: Добавить валидацию даты
//    if (empty($form->output['day'])
//        && empty($form->output['month'])
//        && empty($form->output['year'])
//    ) {
//        $profile->birth = '00-00-0000';
//    } else {
//        $profile->birth = intval($form->output['year']) . '-' . intval($form->output['month']) . '-' . intval($form->output['day']);
//    }

    $profile->save();
    $profileData->save();
}

$view->form = $form->display();
$view->setTemplate('edit_form.php');
