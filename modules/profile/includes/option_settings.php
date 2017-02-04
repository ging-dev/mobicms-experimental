<?php

defined('MOBICMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ConfigInterface $config */
$config = $container->get(Mobicms\Api\ConfigInterface::class);

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$profile = $app->profile();
$userConfig = $profile->getConfig();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$editors[0] = _m('Without Editor');
$editors[1] = '<abbr title="SCeditor">' . _m('WYSIWYG Editor') . '</abbr>';

if ($profile->rights == 9) {
    $editors[2] = '<abbr title="CodeMirror">' . _m('HTML Editor') . '</abbr>';
}

$form
    // Set system settings
    ->title(_m('System Settings'))
    ->element('text', 'timeShift',
        [
            'value'        => $userConfig->timeShift,
            'label_inline' => '<span class="badge badge-large">' . date("H:i", time() + ($config->timeshift + $userConfig->timeShift) * 3600) . '</span> ' . _m('Time settings'),
            'description'  => _m('Time Shift') . ' (+ - 12)',
            'class'        => 'small',
            'maxlength'    => 3,
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => -12,
                    'max'  => 13
                ]
        ]
    )
    ->element('text', 'pageSize',
        [
            'value'        => $userConfig->pageSize,
            'label_inline' => _m('List Size'),
            'description'  => _m('Number of items per page') . ' (5-99)',
            'class'        => 'small',
            'maxlength'    => 2,
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => 5,
                    'max'  => 99
                ]
        ]
    )
    ->element('checkbox', 'directUrl',
        [
            'checked'      => $userConfig->directUrl,
            'label_inline' => _m('Direct URL')
        ]
    )
    // Choose text editor
    ->title(_m('Text Editor'))
    ->element('radio', 'editor',
        [
            'checked' => $userConfig->editor,
            'items'   => $editors
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
    $userConfig->timeShift = $form->output['timeShift'];
    $userConfig->pageSize = $form->output['pageSize'];
    $userConfig->directUrl = $form->output['directUrl'];
    $userConfig->editor = $form->output['editor'];
    $userConfig->save();

    $app->redirect($app->request()->getUri() . '?saved');
}

$view->form = $form->display();
$view->setTemplate('edit_form.php');
