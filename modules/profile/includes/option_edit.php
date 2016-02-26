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
    ->element('text', 'imname',
        [
            'label'       => _m('Your Name'),
            //'value'       => $profile->imname,
            'readonly'    => true,
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
            //'value'       => $profile->live,
            'readonly'    => true,
            'description' => _m('Specify the country of residence, your city.<br/>Max. 100 characters.'),
            'filter'      => FILTER_SANITIZE_STRING,
        ]
    )
    ->element('textarea', 'about',
        [
            'label'       => _m('About yourself'),
            //'value'       => $profile->about,
            'readonly'    => true,
            'editor'      => true,
            'description' => _m('Max. 5000 characters'),
        ]
    )
    ->element('text', 'tel',
        [
            'label'       => _m('Phone Number'),
            //'value'       => $profile->tel,
            'readonly'    => true,
            'description' => _m('Max. 100 characters'),
            'filter'      => FILTER_SANITIZE_STRING,
        ]
    )
    ->element('text', 'siteurl',
        [
            'label'       => _m('Site'),
            //'value'       => $profile->siteurl,
            'readonly'    => true,
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
        ->element('checkbox', 'mailvis',
            [
                'label_inline' => _m('Show in the Profile'),
                //'checked'      => $profile->mailvis,
                'description'  => _m('Correctly specify your email address, that it will be sent your password.<br/>Max. 50 characters') .
                    '<br/><a href="../email/">' . _m('Change E-mail') . '</a>',
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
    ->validate('imname', 'lenght', ['max' => 50])
    ->validate('live', 'lenght', ['max' => 100])
    ->validate('about', 'lenght', ['max' => 5000])
    ->validate('tel', 'lenght', ['max' => 100])
    ->validate('siteurl', 'lenght', ['max' => 100])
    ->validate('skype', 'lenght', ['max' => 50])
    ->validate('icq', 'numeric', ['min' => 10000, 'empty' => true]);

if ($form->isValid()) {
    $profile->status = $form->output['status'];
    $profile->sex = $form->output['sex'];
    //$profile->imname = $form->output['imname'];
    //$profile->live = $form->output['live'];
    //$profile->about = $app->purify($form->output['about']);
    //$profile->tel = $form->output['tel'];
    //$profile->siteurl = $form->output['siteurl'];
    //$profile->mailvis = isset($form->output['mailvis']) ? 1 : 0;
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

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
