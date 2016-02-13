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

namespace Mobicms\Editors;

/**
 * Class Editor (Facade)
 *
 * @package Mobicms\Editors
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-02-12
 *
 * @method display()
 * @method getHelp()
 * @method getStyle()
 * @method setLanguage($iso)
 */
class Editor
{
    /**
     * @var Adapters\AdapterInterface
     */
    private $editorInstance = null;
    private $editors = [
        '0' => 'Stub',
        '1' => 'SCeditor',
        '2' => 'CodeMirror'
    ];

    public function __construct($editor)
    {
        if ($this->editorInstance === null && isset($this->editors[$editor])) {
            $class = __NAMESPACE__ . '\Adapters\\' . $this->editors[$editor];
            $this->editorInstance = new $class;

            if (!($this->editorInstance instanceof Adapters\AdapterInterface)) {
                $this->editorInstance = null;
            }
        }
    }

    public function __call($method, $arguments = [])
    {
        if (method_exists($this->editorInstance, $method)) {
            return call_user_func_array([$this->editorInstance, $method], $arguments);
        } else {
            throw new \BadMethodCallException('Invalid method [' . $method . ']');
        }
    }
}
