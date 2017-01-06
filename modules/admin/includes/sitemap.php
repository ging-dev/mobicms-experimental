<?php

defined('JOHNCMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form
    ->title(_m('Where to turn?'))
    ->element('checkbox', 'sitemapForum',
        [
            'label_inline' => _s('Forum'),
            'checked'      => Config::$sitemapForum
        ]
    )
    ->element('checkbox', 'sitemapLibrary',
        [
            'label_inline' => _s('Library'),
            'checked'      => Config::$sitemapLibrary
        ]
    )
    ->title(_m('To whom to show?'))
    ->element('radio', 'sitemapUsers',
        [
            'checked' => Config::$sitemapUsers,
            'items'   =>
                [
                    '1' => _m('All'),
                    '0' => _m('Guests only')
                ]
        ]
    )
    ->divider()
    ->element('submit', 'submit',
        [
            'value' => _s('Save'),
            'class' => 'btn btn-primary'
        ]
    )
    ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>');

if ($form->isValid()) {
    // Записываем настройки
    (new Mobicms\Config\WriteHandler())->write('System', $form->output); //TODO: Исправить!!!
    $view->save = true;
}

$view->form = $form->display();
$view->setTemplate('edit_form.php');
