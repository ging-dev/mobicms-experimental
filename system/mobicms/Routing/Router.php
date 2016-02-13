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

namespace Mobicms\Routing;

use Config\Routes;
use Zend\Http\PhpEnvironment\Request;

/**
 * Class Router
 *
 * @package Mobicms\Routing
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.2.0.0 2015-08-07
 */
class Router
{
    private $path = [];
    private $pathQuery = [];
    private $config = [];
    private $module = null;
    private $protectedPath = [
        'assets',
        'classes',
        'includes',
        'locale',
        'templates',
    ];

    public $dir = '';

    public function __construct(Request $request)
    {
        $uri = substr($request->getRequestUri(), strlen($request->getBaseUrl()));

        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $this->path = array_filter(explode('/', trim($uri, '/')));

        $this->config = (new Routes)->routesMap;
        $this->module = $this->getModule();
        $this->dir = $this->config[$this->module];
    }

    public function dispatch()
    {
        $file = 'index.php';
        $dir = MODULE_PATH . $this->dir . DS;
        $path = array_slice($this->path, 1);
        $i = 0;

        foreach ($path as $val) {
            if (in_array($val, $this->protectedPath)) {
                break;
            }

            if (is_dir($dir . $val)) {
                // Если существует директория
                $dir .= $val . DS;
            } else {
                if (pathinfo($val, PATHINFO_EXTENSION) == 'php' && is_file($dir . $val)) {
                    // Если вызван PHP файл
                    $file = $val;
                    ++$i;
                }

                break;
            }

            ++$i;
        }

        // Разделяем URI на Path и Query
        $this->path = array_slice($this->path, 0, $i + 1);
        $this->pathQuery = array_slice($path, $i);

        $this->includeFile($dir . $file);
    }

    /**
     * @return string
     */
    public function getCurrentModule()
    {
        return $this->module;
    }

    /**
     * @param null|string $key
     * @return array|bool
     */
    public function getQuery($key = null)
    {
        if ($key === null) {
            return $this->pathQuery;
        } else {
            return isset($this->pathQuery[$key]) ? $this->pathQuery[$key] : false;
        }
    }

    /**
     * @return string
     */
    private function getModule()
    {
        $module = !empty($this->path) ? $this->path[0] : 'home';

        if (!isset($this->config[$module]) || !$this->checkModule($module)) {
            $module = '404';
        }

        return $module;
    }

    /**
     * @param string $module
     * @return bool
     */
    private function checkModule($module)
    {
        if (is_dir(MODULE_PATH . $this->config[$module])
            && is_file(MODULE_PATH . $this->config[$module] . DS . 'index.php')
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string $file
     */
    private function includeFile($file)
    {
        include_once $file;
    }
}
