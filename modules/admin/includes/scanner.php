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
define('ROOT_DIR', '.');

$app = App::getInstance();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form->infoMessages = false;
$form
    ->title(_m('Anti-Spyware'))
    ->element('radio', 'mode',
        [
            'checked' => 1,
            'items'   =>
                [
                    '1' => _m('Scan to the appropriate distribution'),
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
        case 1:
            // Сканируем на соответствие дистрибутиву
            $scanner->scan();
            if (count($scanner->modifiedFiles) || count($scanner->missingFiles) || count($scanner->newFiles)) {
                $app->view()->modifiedFiles = $scanner->modifiedFiles;
                $app->view()->missingFiles = $scanner->missingFiles;
                $app->view()->extraFiles = $scanner->newFiles;
                $app->view()->errormsg = _m('Distributive inconsistency!');
            } else {
                $app->view()->ok = _m('List of files corresponds to the distributive');
            }
            break;

        case 2:
            // Сканируем на соответствие ранее созданному снимку
            $scanner->scan(true);
            if (count($scanner->whiteList) == 0) {
                $app->view()->errormsg = _m('Snapshot image is not created');
            } else {
                if (count($scanner->modifiedFiles) || count($scanner->missingFiles) || count($scanner->newFiles)) {
                    $app->view()->modifiedFiles = $scanner->modifiedFiles;
                    $app->view()->missingFiles = $scanner->missingFiles;
                    $app->view()->extraFiles = $scanner->newFiles;
                    $app->view()->errormsg = _m('Snapshot inconsistency');
                } else {
                    $app->view()->ok = _m('All files are consistent with previously made image');
                }
            }
            break;

        case 3:
            // Создаем снимок файлов
            $scanner->snap();
            $app->view()->ok = _m('Snapshot successfully created');
            break;
    }
}

$app->view()->form = $form->display();
$app->view()->setTemplate('scanner.php');
