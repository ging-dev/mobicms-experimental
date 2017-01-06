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
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form->infoMessages = false;
$form
    ->title(_m('Update cache'))
    ->divider()
    ->element('submit', 'submit',
        [
            'value' => _s('Run'),
            'class' => 'btn btn-primary'
        ]
    )
    ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>');

if ($form->isValid()) {
    $cache = [];
    $smilies = glob(ROOT_PATH . 'assets' . DS . 'smilies' . DS . '*' . DS . '*.{gif,jpg,png}', GLOB_BRACE);
    foreach ($smilies as $val) {
        $file = basename($val);
        $name = explode(".", $file);
        $parent = basename(dirname($val));
        $image = '<img src="' . $app->request()->getBaseUrl() . 'assets/smilies/' . $parent . '/' . $file . '" alt="" />';
        if ($parent == '_admin') {
            $cache['adm_s'][] = '/:' . preg_quote($name[0]) . ':/';
            $cache['adm_r'][] = $image;
            $cache['adm_s'][] = '/:' . preg_quote(Includes\Functions::translit($name[0])) . ':/';
            $cache['adm_r'][] = $image;
        } elseif ($parent == '_simply') {
            $cache['usr_s'][] = '/:' . preg_quote($name[0]) . '/';
            $cache['usr_r'][] = $image;
        } else {
            $cache['usr_s'][] = '/:' . preg_quote($name[0]) . ':/';
            $cache['usr_r'][] = $image;
            $cache['usr_s'][] = '/:' . preg_quote(Includes\Functions::translit($name[0])) . ':/';
            $cache['usr_r'][] = $image;
        }
    }

    if (file_put_contents(CACHE_PATH . 'smilies.cache', serialize($cache))) {
        $view->save = _m('The cache is updated');
    } else {
        $view->error = _m('When updating a cache there was a error');
    }
}

$view->form = $form->display();
$view->setTemplate('smilies.php');
