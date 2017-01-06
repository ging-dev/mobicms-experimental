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
define('ROOT_DIR', '.');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form->infoMessages = false;
$form
    ->title(_m('Anti-Spyware'))
    ->element('radio', 'mode',
        [
            'checked' => 2,
            'items'   =>
                [
                    '2' => _m('Snapshot scan'),
                    '3' => _m('Make snapshot')
                ]
        ]
    )
    ->divider()
    ->element('submit', 'submit',
        [
            'value' => _s('Run'),
            'class' => 'btn btn-primary'
        ]
    )
    ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>');

if ($form->isValid()) {
    require_once dirname(__DIR__) . '/classes/Scanner.php';
    $scanner = new Scanner;

    switch ($form->output['mode']) {
        case 2:
            // Сканируем на соответствие ранее созданному снимку
            $scanner->scan();
            if (count($scanner->whiteList) == 0) {
                $view->errormsg = _m('Snapshot image is not created');
            } else {
                if (count($scanner->modifiedFiles) || count($scanner->missingFiles) || count($scanner->newFiles)) {
                    $view->modifiedFiles = $scanner->modifiedFiles;
                    $view->missingFiles = $scanner->missingFiles;
                    $view->extraFiles = $scanner->newFiles;
                    $view->errormsg = _m('Snapshot inconsistency');
                } else {
                    $view->ok = _m('All files are consistent with previously made image');
                }
            }
            break;

        case 3:
            // Создаем снимок файлов
            $scanner->snap();
            $view->ok = _m('Snapshot successfully created');
            break;
    }
}

$view->form = $form->display();
$view->setTemplate('scanner.php');
