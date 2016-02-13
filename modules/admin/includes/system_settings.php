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

use Config\System as Config;

$app = App::getInstance();
$uri = $app->uri();
$form = new Mobicms\Form\Form(['action' => $uri]);
$form
    ->title(_m('System Time'))
    ->element('text', 'timeshift',
        [
            'value'        => Config::$timeshift,
            'class'        => 'small',
            'label_inline' => '<span class="badge badge-green">' . date("H:i", time() + (int)Config::$timeshift * 3600) . '</span> ' . _m('Time Shift') . ' <span class="note">(+ - 12)</span>',
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => -12,
                    'max'  => 13
                ]
        ]
    )
    ->title(_m('Upload Files'))
    ->element('text', 'filesize',
        [
            'value'        => Config::$filesize,
            'label_inline' => _m('Max. File size') . ' kB <span class="note">(100-50000)</span>',
            'description'  => _m('Note that the maximum size of uploaded file may be limited by your PHP settings. Most often the default 2000kb.'),
            'class'        => 'small',
            'limit'        =>
                [
                    'type' => 'int',
                    'min'  => 100,
                    'max'  => 50000
                ]
        ]
    )
    ->title(_m('Profiling'))
    ->element('checkbox', 'profilingGeneration',
        [
            'checked'      => Config::$profilingGeneration,
            'label_inline' => _m('Show generation time')
        ]
    )
    ->element('checkbox', 'profilingMemory',
        [
            'checked'      => Config::$profilingMemory,
            'label_inline' => _m('Show used memory')
        ]
    )
    ->title(_m('Site Requisites'))
    ->element('text', 'email',
        [
            'value' => Config::$email,
            'label' => _m('Site Email')
        ]
    )
    ->element('textarea', 'copyright',
        [
            'value' => Config::$copyright,
            'label' => _m('Copyright')
        ]
    )
    ->title(_m('SEO Attributes'))
    ->element('text', 'homeTitle',
        [
            'value'       => Config::$homeTitle,
            'style'       => 'max-width: none',
            'label'       => _m('Homepage title'),
            'description' => _m('In the search results for keywords, search engines use the page title for the reference to the document. Well written title containing keywords will attract many visitors and increase chances are that the site is visited by many people.')
        ]
    )
    ->element('textarea', 'metaKey',
        [
            'value'       => Config::$metaKey,
            'label'       => 'META Keywords',
            'description' => _m('Keywords (or phrases) separated by commas. This meta tag search engines use to determine the relevance of links. In forming this tag should be used only the words that are contained in the document. The use of words that are not on the page, is not recommended. The recommended number of words in this tag - no more than ten. In addition,revealed that the breakdown of the tag on a few lines affect the estimate of links by search engines.<br>Max. 250 characters.'),
            'limit'       =>
                [
                    'type' => 'str',
                    'max'  => 250
                ]
        ]
    )
    ->element('textarea', 'metaDesc',
        [
            'value'       => Config::$metaDesc,
            'label'       => 'META Description',
            'description' => _m('This tag is used to create a short description of the page, use the search engines to index, as well as to create annotations the extradition request. In the absence of a tag search system gives the first line in the summary of the document or a passage containing the keywords. Displayed after the link in the search pages in search engine. Different search engines charge different rates for the length of the tag. Try to write a small description of 150 characters.<br>Max. 250 characters.'),
            'limit'       =>
                [
                    'type' => 'str',
                    'max'  => 250
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
    (new Mobicms\Config\WriteHandler())->write('System', $form->output);
    $app->redirect($uri . '?saved');
}

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
