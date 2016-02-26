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

namespace Mobicms\Template\Traits;

/**
 * Class PathTrait
 *
 * @package Mobicms\Template\Traits
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.2.0 2015-08-29
 */
trait PathTrait
{
    /**
     * Returns a formatted link to the CSS or JS file
     *
     * @param string $file
     * @return string
     */
    public function getLink($file)
    {
        foreach ($this->links($file) as $val) {
            if (is_file($val[0])) {
                return $val[1];
            }
        }

        throw new \InvalidArgumentException($file . '" not found');
    }

    /**
     * Get file path|uri
     *
     * @param string $file
     * @param array $args
     * @return string
     */
    protected function getPath($file, array $args = [])
    {
        $module = isset($args['module']) ? $args['module'] : false;
        $skin = \App::getInstance()->user()->get()->getConfig()->skin; //TODO: replace!

        if ($module === false) {
            // Вызов системного файла
            if (is_file(THEMES_PATH . $skin . DS . 'includes' . DS . $file)) {
                return THEMES_PATH . $skin . DS . 'includes' . DS . $file;
            } elseif (is_file(ASSETS_PATH . 'template' . DS . 'includes' . DS . $file)) {
                return ASSETS_PATH . 'template' . DS . 'includes' . DS . $file;
            }
        } else {
            // Вызов файла модуля
            $module = \App::getInstance()->router()->dir;

            if (is_file(THEMES_PATH . $skin . DS . 'modules' . DS . $module . DS . 'templates' . DS . $file)) {
                return THEMES_PATH . $skin . DS . 'modules' . DS . $module . DS . 'templates' . DS . $file;
            } elseif (is_file(MODULE_PATH . $module . DS . 'templates' . DS . $file)) {
                return MODULE_PATH . $module . DS . 'templates' . DS . $file;
            }
        }

        throw new \InvalidArgumentException($file . '" not found');
    }

    private function links($file)
    {
        $url = \App::getInstance()->request()->getBaseUrl();
        $skin = \App::getInstance()->user()->get()->getConfig()->skin; //TODO: replace!
        $type = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $moduleDir = \App::getInstance()->router()->dir;
        $themeLink = $url . '/themes/';
        $skinLink = $themeLink . $skin . '/';
        $skinPath = THEMES_PATH . $skin . DS;

        return [
            [
                // Поиск файла в теме (папка для модулей)
                $skinPath . 'modules' . DS . $moduleDir . DS . $type . DS . $file,
                $skinLink . 'modules/' . $moduleDir . '/' . $type . '/' . $file
            ],
            [
                // Поиск файла в модуле
                ASSETS_PATH . 'modules' . DS . $moduleDir . DS . $type . DS . $file,
                $url . '/assets/modules/' . $moduleDir . '/' . $type . '/' . $file
            ],
            [
                // Поиск файла в теме
                $skinPath . $type . DS . $file,
                $skinLink . $type . '/' . $file
            ],
            [
                // Поиск файла в теме по умолчанию
                ASSETS_PATH . 'template' . DS . $type . DS . $file,
                $url . '/assets/template/' . $type . '/' . $file
            ]
        ];
    }
}
