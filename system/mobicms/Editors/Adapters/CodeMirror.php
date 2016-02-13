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
 * Class CodeMirror
 *
 * @package Mobicms\Editors\Adapters
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-02-12
 */
class CodeMirror implements AdapterInterface
{
    public function __construct()
    {
        $view = \App::getInstance()->view();
        $url = \App::getInstance()->request()->getBaseUrl(); //TODO: Переделать

        $view->setCss('editors/codemirror/theme.min.css');

        $view->embedJs('<script src="' . $url . '/assets/js/codemirror/lib/codemirror.min.js"></script>');

        $view->embedJs('<script src="' . $url . '/assets/js/codemirror/addon/hint/show-hint.min.js"></script>');
        $view->embedJs('<script src="' . $url . '/assets/js/codemirror/addon/hint/xml-hint.min.js"></script>');
        $view->embedJs('<script src="' . $url . '/assets/js/codemirror/addon/hint/html-hint.min.js"></script>');

        $view->embedJs('<script src="' . $url . '/assets/js/codemirror/mode/xml/xml.js"></script>');
        $view->embedJs('<script src="' . $url . '/assets/js/codemirror/mode/javascript/javascript.js"></script>');
        $view->embedJs('<script src="' . $url . '/assets/js/codemirror/mode/css/css.js"></script>');
        $view->embedJs('<script src="' . $url . '/assets/js/codemirror/mode/htmlmixed/htmlmixed.js"></script>');
    }

    public function display()
    {
        //TODO: improve!
        \App::getInstance()->view()->embedJs('<script type="text/javascript">var editor = CodeMirror.fromTextArea(document.getElementById("editor"),{lineNumbers: true, mode: "text/html", matchBrackets: true, extraKeys: {"Ctrl-Space": "autocomplete"}});</script>');
    }

    public function getStyle()
    {

    }

    public function setLanguage($iso)
    {

    }

    public function getHelp()
    {
        return 'Press <strong>ctrl-space</strong> to activate completion.';
    }
}
