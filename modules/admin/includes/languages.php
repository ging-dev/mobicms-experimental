<?php

defined('MOBICMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ConfigInterface $config */
$config = $container->get(Mobicms\Api\ConfigInterface::class);

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$uri = $app->uri();

$form = new Mobicms\Form\Form(['action' => $uri]);
$form
    ->title(_m('Clear Cache'))
    ->html('<span class="description">' . _m('The Cache clearing is required after installing a new language or upgrade existing ones.') . '</span>')
    ->element('submit', 'update',
        [
            'value' => _m('Clear Cache'),
            'class' => 'btn btn-primary btn-xs',
        ]
    )
    ->title(_m('Default Language'))
    ->element('radio', 'lng',
        [
            'checked'     => $config->lng,
            'description' => _m('If the choice is prohibited, the language will be forced to set for all visitors. If the choice is allowed, it will be applied only in the case, if requested by the client language is not in the system.'),
            'items'       => $app->lng()->getLocalesList(),
        ]
    )
    ->element('checkbox', 'lngSwitch',
        [
            'checked'      => $config->lngSwitch,
            'label_inline' => _m('Allow to choose'),
            'description'  => _m('Allow visitors specify the desired language from the list of available in the system. Including activated auto select languages by signatures of the browser.'),
        ]
    )
    ->divider()
    ->element('submit', 'submit',
        [
            'value' => _s('Save'),
            'class' => 'btn btn-primary',
        ]
    )
    ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>');

if ($form->isValid()) {
    if (isset($form->input['update'])) {
        // Обновляем кэш
        $app->lng()->clearCache();
        $app->redirect($uri . '?cache');
    } else {
        //TODO: запилить запись настроек!!!
        /*
        $out =
            [
                'lng' =>
                    [
                        'lng'       => $form->output['lng'],
                        'lngSwitch' => (bool)$form->output['lngSwitch'],
                    ],
            ];

        $app->config()->merge(new Zend\Config\Config($out, true));
        (new Zend\Config\Writer\PhpArray)->toFile(CONFIG_FILE_SYS, $app->config());
        $app->session()->offsetUnset('lng');

        // Clear opcode cache
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate(CONFIG_FILE_SYS);
        }
        */
        $app->redirect($uri . '?saved');
    }
}

$view->form = $form->display();
$view->setTemplate('edit_form.php');
