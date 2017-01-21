<?php

defined('JOHNCMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$quarantine = $container->get(Mobicms\Api\ConfigInterface::class)->quarantine;

$app = App::getInstance();
$uri = $app->uri();

$form = new Mobicms\Form\Form(['action' => $uri]);
$form
    ->html('<div class="alert alert-warning">' . _m('Keep in mind that this section reflect only the general settings.<br>Individual modules may have their quarantine settings. To set up, use the administrative part of the relevant modules.') . '</div>')
    ->title(_s('Quarantine'))
    ->html('<div class="description">' . _m('Quarantine allows you to temporarily restrict user activity') . '</div>')
    ->element('text', 'period',
        [
            'label_inline' => _m('The Quarantine action period, in hours') . ' <span class="note">(1-99)</span>',
            'value'        => $quarantine['period'],
            'class'        => 'mini',
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => 1,
                    'max'  => 99,
                ],
        ]
    )
    ->title(_s('Restrictions'))
    ->html('<div class="description">' . _m('For the duration of the quarantine period, will be active the following restrictions:') . '</div>')
    ->element('text', 'mailSent',
        [
            'label_inline' => _m('How much mail can be sent?') . ' <span class="note">(0-99)</span>',
            'value'        => $quarantine['mailSent'],
            'class'        => 'mini',
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => 0,
                    'max'  => 99,
                ],
        ]
    )
    ->element('text', 'mailRecipients',
        [
            'label_inline' => _m('How many mail recipients?') . ' <span class="note">(0-9)</span>',
            'value'        => $quarantine['mailRecipients'],
            'class'        => 'mini',
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => 0,
                    'max'  => 9,
                ],
        ]
    )
    ->element('text', 'comments',
        [
            'label_inline' => _m('How many comments are allowed?') . ' <span class="note">(0-99)</span>',
            'value'        => $quarantine['comments'],
            'class'        => 'mini',
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => 0,
                    'max'  => 99,
                ],
        ]
    )
    ->element('text', 'uploadImages',
        [
            'label_inline' => _m('How many images are allowed to upload into the Album?') . ' <span class="note">(0-99)</span>',
            'value'        => $quarantine['uploadImages'],
            'class'        => 'mini',
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => 0,
                    'max'  => 99,
                ],
        ]
    )
    ->element('checkbox', 'reputation',
        [
            'label_inline' => _m('Allow Reputation'),
            'checked'      => $quarantine['reputation'],
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
    //TODO: запилить запись настроек!!!
    /*
    $out['quarantine'] =
        [
            'period'         => (int)$form->output['period'],
            'mailSent'       => (int)$form->output['mailSent'],
            'mailRecipients' => (int)$form->output['mailRecipients'],
            'comments'       => (int)$form->output['comments'],
            'album'          => (int)$form->output['uploadImages'],
            'reputation'     => (bool)$form->output['reputation'],
        ];

    $app->config()->merge(new Zend\Config\Config($out, true));
    (new Zend\Config\Writer\PhpArray)->toFile(CONFIG_FILE_SYS, $app->config());

    // Clear opcode cache
    if (function_exists('opcache_invalidate')) {
        opcache_invalidate(CONFIG_FILE_SYS);
    }
    */
    $app->redirect($uri . '?saved');
}

$view->form = $form->display();
$view->setTemplate('edit_form.php');
