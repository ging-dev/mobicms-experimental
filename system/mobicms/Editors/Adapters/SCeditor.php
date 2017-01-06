<?php

namespace Mobicms\Editors\Adapters;

use Mobicms\Api\ViewInterface;

/**
 * Class SCeditor
 *
 * @package Mobicms\Editors\Adapters
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 */
class SCeditor implements AdapterInterface
{
    /**
     * @var ViewInterface
     */
    private $view;

    public function __construct()
    {
        /** @var ViewInterface $view */
        $view = \App::getContainer()->get(ViewInterface::class);

        $app = \App::getInstance(); //TODO: improve!
        $view->setCss('editors/sceditor/theme.min.css');
        $view->embedJs('<script src="' . $app->request()->getBaseUrl() . '/assets/js/sceditor/jquery.sceditor.xhtml.min.js"></script>');
        $this->view = $view;
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
            'style: "' . $this->view->getLink('editors/sceditor/editor.min.css') . '"'
        ];
        $this->view->embedJs('<script>$(function () {$("textarea").sceditor({' . implode(',', $editorOptions) . '});});</script>');
    }

    public function getStyle()
    {
        return 'min-height: 200px; display: none;';
    }

    public function setLanguage($iso)
    {
        if (is_file(ROOT_PATH . 'assets' . DS . 'js' . DS . 'sceditor' . DS . $iso[0] . '.js')) {
            $this->view->embedJs('<script src="' . \App::getInstance()->request()->getBaseUrl() . '/assets/js/sceditor/' . $iso[0] . '.js" type="text/javascript"></script>');
        }
    }

    public function getHelp()
    {

    }
}
