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

function getThemesList()
{
    global $app;

    $tpl_list = [];
    $dirs = glob(THEMES_PATH . '*', GLOB_ONLYDIR);

    foreach ($dirs as $val) {
        if (is_file($val . DS . 'theme.ini')) {
            $options = parse_ini_file($val . DS . 'theme.ini');

            if (isset($options['name'], $options['author'], $options['author_url'], $options['author_email'], $options['description'])
                && is_file($val . DS . 'theme.png')
            ) {
                $dir = basename($val);
                $options['thumbinal'] = $app->request()->getBaseUrl() . '/themes/' . $dir . '/theme.png';
                $tpl_list[$dir] = $options;
            }
        }
    }

    ksort($tpl_list);

    return $tpl_list;
}

$themes = getThemesList();
$act = filter_input(INPUT_GET, 'act', FILTER_SANITIZE_STRING);
$mod = filter_input(INPUT_GET, 'mod', FILTER_SANITIZE_STRING);

if ($act == 'set' && isset($themes[$mod])) {
    $theme = $themes[$mod];
    $description = '<br/><dl class="description">' .
        '<dt class="wide"><img src="' . $themes[$mod]['thumbinal'] . '" alt=""/></dt>' .
        '<dd>' .
        '<div class="header">' . $theme['name'] . '</div>' .
        (!empty($theme['author']) ? '<strong>' . _m('Author') . '</strong>: ' . htmlspecialchars($theme['author']) : '') .
        (!empty($theme['author_url']) ? '<br/><strong>' . _m('Site') . '</strong>: ' . htmlspecialchars($theme['author_url']) : '') .
        (!empty($theme['author_email']) ? '<br/><strong>Email</strong>: ' . htmlspecialchars($theme['author_email']) : '') .
        (!empty($theme['description']) ? '<br/><strong>' . _m('Description') . '</strong>: ' . htmlspecialchars($theme['description']) : '') .
        '</dd></dl>';

    $form = new Mobicms\Form\Form(['action' => $app->uri()]);
    $form
        ->title(_m('Choose Skin'))
        ->html($description)
        ->divider()
        ->element('submit', 'submit',
            [
                'value' => _m('Choose'),
                'class' => 'btn btn-primary'
            ]
        )
        ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>');

    if ($form->isValid()) {
//        $stmt = App::db()->prepare("UPDATE `" . TP . "usr__users` SET `avatar` = ? WHERE `id` = " . App::user()->id);
//        $stmt->execute([$image]);
//        $stmt = null;
//
//        @unlink(FILES_PATH . 'users' . DS . 'avatar' . DS . Users::$data['id'] . '.jpg');
//        @unlink(FILES_PATH . 'users' . DS . 'avatar' . DS . Users::$data['id'] . '.gif');
//
//        $form->continueLink = App::cfg()->sys->homeurl . 'profile/' . App::user()->id . '/option/avatar/';
//        $form->successMessage = _d('avatar_applied');
//        $form->confirmation = true;
//        App::view()->hideuser = true;
    }

    $app->view()->form = $form->display();
    $app->view()->setTemplate('option_theme_set.php');
} else {
    $app->view()->tpl_list = $themes;
    $app->view()->setTemplate('option_theme.php');
}
