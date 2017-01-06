<?php

namespace Mobicms\Editors\Adapters;

use Mobicms\Api\ViewInterface;

/**
 * Class CodeMirror
 *
 * @package Mobicms\Editors\Adapters
 * @author  Oleg Kasyanov <dev@mobicms.net>
 */
class CodeMirror implements AdapterInterface
{
    /**
     * @var ViewInterface
     */
    private $view;

    public function __construct()
    {
        /** @var ViewInterface $view */
        $view = \App::getContainer()->get(ViewInterface::class);
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
        $this->view->embedJs('<script type="text/javascript">var editor = CodeMirror.fromTextArea(document.getElementById("editor"),{lineNumbers: true, mode: "text/html", matchBrackets: true, extraKeys: {"Ctrl-Space": "autocomplete"}});</script>');
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
