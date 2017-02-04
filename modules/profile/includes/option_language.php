<?php

defined('MOBICMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$uri = $app->uri();
$config = $app->profile()->getConfig();
$items['#'] = _m('Select automatically');
$items = array_merge($items, $app->lng()->getLocalesList());
$form = new Mobicms\Form\Form(['action' => $uri]);
$form
    ->title(_m('Select Language'))
    ->element('radio', 'lng',
        [
            'checked'     => $config->lng,
            'items'       => $items,
            'description' => _m('If you turn on automatic mode, the system language is set depending on the settings of your browser.')
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
    $config->lng = $form->output['lng'];
    $config->save();
    $app->session()->offsetUnset('lng');
    $app->redirect($uri . '?saved');
}

$view->form = $form->display();
$view->setTemplate('edit_form.php');
