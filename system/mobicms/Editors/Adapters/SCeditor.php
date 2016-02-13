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

namespace Mobicms\Editors\Adapters;

/**
 * Class SCeditor
 *
 * @package Mobicms\Editors\Adapters
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-02-12
 */
class SCeditor implements AdapterInterface
{
    public function __construct()
    {
        $app = \App::getInstance(); //TODO: improve!
        $app->view()->setCss('editors/sceditor/theme.min.css');
        $app->view()->embedJs('<script src="' . $app->request()->getBaseUrl() . '/assets/js/sceditor/jquery.sceditor.xhtml.min.js"></script>');
    }

    public function display()
    {
        // Задаем параметры редактора
        $editorOptions = [
            'plugins: "xhtml"',
            'width: "98%"',
            'height: "100%"',
            'colors: "#FF8484,#FFD57D,#7EE27E,#98ABD8,#B9B9C8|#FF0000,#FFAA00,#00CC00,#154BCA,#8D8DA5|#9B0000,#9B6700,#007C00,#0B328C,#424251"',
            'emoticonsEnabled: false',
//            'toolbar: "bold,italic,underline,strike|size,color|left,center,right,justify|bulletlist,orderedlist,code,quote|link,unlink,youtube,horizontalrule|source"',
            'toolbar: "bold,italic,underline,strike|size,color|bulletlist,orderedlist,code,quote|link,unlink,youtube,horizontalrule|source"',
            'style: "' . \App::getInstance()->view()->getLink('editors/sceditor/editor.min.css') . '"'
        ];
        \App::getInstance()->view()->embedJs('<script>$(function () {$("textarea").sceditor({' . implode(',', $editorOptions) . '});});</script>');
    }

    public function getStyle()
    {
        return 'min-height: 200px; display: none;';
    }

    public function setLanguage($iso)
    {
        if (is_file(ROOT_PATH . 'assets' . DS . 'js' . DS . 'sceditor' . DS . $iso[0] . '.js')) {
            \App::getInstance()->view()->embedJs('<script src="' . \App::getInstance()->request()->getBaseUrl() . '/assets/js/sceditor/' . $iso[0] . '.js" type="text/javascript"></script>');
        }
    }

    public function getHelp()
    {

    }
}
