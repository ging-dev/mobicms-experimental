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
 *
 * @module      Registration
 * @author      Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version     v.2.0.0 2016-01-20
 */

defined('JOHNCMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$app->lng()->setModule('registration');
$query = $app->router()->getQuery(0);

(new Zend\Loader\StandardAutoloader)->registerNamespace('Registration', __DIR__ . DS . 'classes' . DS)->register();

if ($app->user()->isValid()) {
    // Если регистрацию пытается открыть вошедший на сайт пользователь, показываем уведомление
    $view->title = _s('Registration');
    $view->success = _s('You are already registered');
    $view->setTemplate('message.php', null, false);
} else {
    $include = __DIR__ . '/includes/';
    $actions =
        [
            'activation'   => 'activation.php',
            'confirmation' => 'confirmation.php',
            'notice'       => 'notice.php',
        ];

    if (!empty($query)) {
        if (isset($actions[$query])) {
            $include .= $actions[$query];
        } else {
            $include = false;
        }
    } else {
        $include .= 'index.php';
    }

    if ($include && is_file($include)) {
        require_once $include;
    } else {
        $app->redirect($app->request()->getBaseUrl() . '/404');
    }
}
