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

/** @var Mobicms\Api\ConfigInterface $config */
$config = $container->get(Mobicms\Api\ConfigInterface::class);

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$uri = $app->uri();

$form = new Mobicms\Form\Form(['action' => $uri]);
$form
    ->title(_m('System Time'))
    ->element('text', 'timeshift',
        [
            'value'        => $config->timeshift,
            'class'        => 'small',
            'label_inline' => '<span class="badge badge-green">' . date("H:i", time() + $config->timeshift * 3600) . '</span> ' . _m('Time Shift') . ' <span class="note">(+ - 12)</span>',
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => -12,
                    'max'  => 13,
                ],
        ]
    )
    ->title(_m('Upload Files'))
    ->element('text', 'filesize',
        [
            'value'        => $config->filesize,
            'label_inline' => _m('Max. File size') . ' kB <span class="note">(100-50000)</span>',
            'description'  => _m('Note that the maximum size of uploaded file may be limited by your PHP settings. Most often the default 2000kb.'),
            'class'        => 'small',
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => 100,
                    'max'  => 50000,
                ],
        ]
    )
    ->title(_m('Profiling'))
    ->element('checkbox', 'profilingGeneration',
        [
            'checked'      => $config->profilingGeneration,
            'label_inline' => _m('Show generation time'),
        ]
    )
    ->element('checkbox', 'profilingMemory',
        [
            'checked'      => $config->profilingMemory,
            'label_inline' => _m('Show used memory'),
        ]
    )
    ->title(_m('Site Requisites'))
    ->element('text', 'email',
        [
            'value' => $config->email,
            'label' => _m('Site Email'),
        ]
    )
    ->element('textarea', 'copyright',
        [
            'value' => $config->copyright,
            'label' => _m('Copyright'),
        ]
    )
    ->title(_m('SEO Attributes'))
    ->element('text', 'homeTitle',
        [
            'value'       => $config->homeTitle,
            'style'       => 'max-width: none',
            'label'       => _m('Homepage title'),
            'description' => _m('In the search results for keywords, search engines use the page title for the reference to the document. Well written title containing keywords will attract many visitors and increase chances are that the site is visited by many people.'),
        ]
    )
    ->element('textarea', 'metaKey',
        [
            'value'       => $config->metaKey,
            'label'       => 'META Keywords',
            'description' => _m('Keywords (or phrases) separated by commas. This meta tag search engines use to determine the relevance of links. In forming this tag should be used only the words that are contained in the document. The use of words that are not on the page, is not recommended. The recommended number of words in this tag - no more than ten. In addition,revealed that the breakdown of the tag on a few lines affect the estimate of links by search engines.<br>Max. 250 characters.'),
            'limit'       =>
                [
                    'type' => 'str',
                    'max'  => 250,
                ],
        ]
    )
    ->element('textarea', 'metaDesc',
        [
            'value'       => $config->metaDesc,
            'label'       => 'META Description',
            'description' => _m('This tag is used to create a short description of the page, use the search engines to index, as well as to create annotations the extradition request. In the absence of a tag search system gives the first line in the summary of the document or a passage containing the keywords. Displayed after the link in the search pages in search engine. Different search engines charge different rates for the length of the tag. Try to write a small description of 150 characters.<br>Max. 250 characters.'),
            'limit'       =>
                [
                    'type' => 'str',
                    'max'  => 250,
                ],
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
    //TODO: Запилить запись настроек
    /*
    $out['sys'] =
        [
            'timeshift'           => (int)$form->output['timeshift'],
            'filesize'            => (int)$form->output['filesize'],
            'profilingGeneration' => (bool)$form->output['profilingGeneration'],
            'profilingMemory'     => (bool)$form->output['profilingMemory'],
            'email'               => $form->output['email'],
            'copyright'           => $form->output['copyright'],
            'homeTitle'           => $form->output['homeTitle'],
            'metaKey'             => $form->output['metaKey'],
            'metaDesc'            => $form->output['metaDesc'],
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
