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

namespace Mobicms\Template;

use App;
use Mobicms\Routing\Router;
use Mobicms\Template\Traits\HelpersTrait;
use Mobicms\Template\Traits\PathTrait;

/**
 * Class View
 *
 * @package Mobicms\Template
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.2.2.0 2015-08-29
 */
class View extends \ArrayObject
{
    use HelpersTrait;
    use PathTrait;

    private $layout = null;
    private $template = null;
    private $rawContent;
    private $css = [];
    private $headerJs = [];
    private $footerJs = [];

    private $router;

    public function __construct(Router $router)
    {
        $this->setFlags(parent::ARRAY_AS_PROPS);
        $this->router = $router;
    }

    /**
     * Returns the value at the specified index
     *
     * @param mixed $key
     * @return bool|mixed
     */
    public function offsetGet($key)
    {
        return $this->offsetExists($key) === true ? parent::offsetGet($key) : false;
    }

    /**
     * Sanitixe and sets the value at the specified index
     *
     * @param string $key
     * @param mixed  $val
     */
    public function setSanitize($key, $val)
    {
        if (is_array($val)) {
            $val = $this->sanitizeArray($val);
        } else {
            $val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8', true);
        }

        $this->offsetSet($key, $val);
    }

    /**
     * @param string     $file
     * @param bool|false $module
     */
    public function setLayout($file, $module = false)
    {
        if ($file === false) {
            $this->layout = false;
        } else {
            $this->layout = $this->getPath($file, ['module' => $module]);
        }
    }

    /**
     * Set template
     *
     * @param string $template
     * @param null   $key
     * @param bool   $module
     */
    public function setTemplate($template, $key = null, $module = true)
    {
        if ($key === null) {
            $key = 'content';
        }

        if ($module === true) {
            $module = $this->router->dir;
        }

        $this->template[$key] = ['template' => $template, 'module' => $module];
    }

    /**
     * Preparing CSS
     *
     * @param string $file
     * @param string $media
     */
    public function setCss($file, $media = '')
    {
        $media = empty($media) ? ' media="' . $media . '"' : '';
        $css = '    <link rel="stylesheet" href="' . $this->getLink($file) . '"' . $media . '>';
        $this->css[] = $css;
    }

    public function embedCss($css = '')
    {
        $this->css[] = $css;
    }

    /**
     * Preparing JS
     *
     * @param string $file
     * @param array  $args
     */
    public function setJs($file, array $args = [])
    {
        $js = '<script src="' . $this->getLink($file) . '" type="text/javascript"></script>';

        if (isset($args['header']) && $args['header']) {
            $this->headerJs[] = $js;
        } else {
            $this->footerJs[] = $js;
        }
    }

    /**
     * Embed a custom JS
     *
     * @param string $js
     * @param bool   $footer
     */
    public function embedJs($js = '', $footer = true)
    {
        if ($footer) {
            $this->footerJs[] = $js;
        } else {
            $this->headerJs[] = $js;
        }
    }

    /**
     * Rendering layout
     */
    public function render()
    {
        if ($this->layout !== false) {
            // Obtain the old modules output contents
            $this->rawContent = ob_get_clean();

            if ($this->layout === null) {
                $this->layout = $this->getPath('layout.default.php');
            }

            ob_start('ob_gzhandler');
            include_once $this->layout;

            return ob_get_clean();
        }

        return '';
    }

    protected function loadHeader()
    {
        return '<meta name="Generator" content="mobiCMS, http://mobicms.net"/>' . PHP_EOL .
        implode(PHP_EOL, array_merge($this->css, $this->headerJs)) . PHP_EOL;
    }

    protected function loadFooter()
    {
        return implode(PHP_EOL, $this->footerJs) . PHP_EOL;
    }

    protected function loadTemplate($key = null)
    {
        if ($key === null) {
            $key = 'content';
        }

        if (isset($this->template[$key])) {
            return include_once $this->getPath($this->template[$key]['template'],
                ['module' => $this->template[$key]['module']]);
        }

        return false;
    }

    protected function loadRawContent($force = false)
    {
        if ($this->template === null || $force) {
            echo $this->rawContent;
        }
    }
}
