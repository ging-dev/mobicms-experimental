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

namespace Mobicms\Utility;

use Mobicms\Checkpoint\Facade;
use Mobicms\Routing\Router;
use Zend\Http\PhpEnvironment\Request;

/**
 * Class Image
 *
 * image(string $image [,array $attributes, boolean $isModule, boolean $generateIMGtag])
 * Supported attributes: alt, width, height, style
 *
 * Example:
 * App::image('image.jpg');                                                       System image
 * App::image('image.jpg', array('width'=>16, 'height'=>16, 'alt'=>'My Image');   System image with attributes
 * App::image('image.jpg', array(), true);                                        Module image
 * App::image('image.jpg', array(), false, false);                                Returns only path to system image file
 *
 * @package Mobicms\Helpers
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.2.0 2015-08-29
 */
class Image
{
    private $img;
    private $args = [];
    private $alt = '';
    private $isModule;
    private $imgTag;
    private $skin;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Router
     */
    private $router;

    /**
     * @param array $arguments
     */
    public function __construct(Request $request, Router $router, Facade $user, array $arguments)
    {
        if (!isset($arguments[0]) || empty($arguments[0])) {
            throw new \RuntimeException('Image not specified');
        }

        $this->router = $router;
        $this->request = $request;
        $this->skin = $user->get()->config()->skin;
        $this->prepareAttributes($arguments);
        $this->isModule = isset($arguments[2]) && $arguments[2] === true ? true : false;
        $this->imgTag = isset($arguments[3]) && $arguments[3] === false ? false : true;
        $this->img = $arguments[0];
    }

    /**
     * @param array $args
     */
    private function prepareAttributes(array $args)
    {
        if (isset($args[1])) {
            if (isset($args[1]['alt'])) {
                $this->alt = $args[1]['alt'];
            }

            if (isset($args[1]['width'])) {
                $this->args[] = 'width="' . $args[1]['width'] . '"';
            }

            if (isset($args[1]['height'])) {
                $this->args[] = 'height="' . $args[1]['height'] . '"';
            }

            if (isset($args[1]['style'])) {
                $this->args[] = 'style="' . $args[1]['style'] . '"';
            }
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->isModule) {
            return $this->getModuleImage();
        }

        return $this->getThemeImage();
    }

    /**
     * Get Module image
     *
     * @return string
     */
    private function getModuleImage()
    {
        $url = $this->request->getBaseUrl();

        if (is_file(THEMES_PATH . $this->skin . DS . 'modules' . DS . $this->router->dir . DS . 'images' . DS . $this->img)) {
            // Картинка из текущей темы (если есть)
            $file = $url . '/themes/' . $this->skin . '/modules/' . $this->router->dir . '/images/' . $this->img;

            return $this->getLink($file);
        } elseif (is_file(ASSETS_PATH . 'modules' . DS . $this->router->dir . DS . 'images' . DS . $this->img)) {
            // Если нет в теме, то выдаем картинку из модуля
            $file = $url . '/assets/modules/' . $this->router->dir . '/images/' . $this->img;

            return $this->getLink($file);
        }

        // Если картинка не найдена
        return '';
    }

    /**
     * Get Theme image
     *
     * @return string
     */
    private function getThemeImage()
    {
        $homeUrl = $this->request->getBaseUrl();

        if (is_file(THEMES_PATH . $this->skin . DS . 'images' . DS . $this->img)) {
            // Картинка из текущей темы (если есть)
            $file = $homeUrl . '/themes/' . $this->skin . '/images/' . $this->img;

            return $this->getLink($file);
        } elseif (is_file(ASSETS_PATH . 'template' . DS . 'images' . DS . $this->img)) {
            // Если нет в теме, то выдаем картинку по умолчанию
            $file = $homeUrl . '/assets/template/images/' . $this->img;

            return $this->getLink($file);
        }

        // Если картинка не найдена
        return '';
    }

    /**
     * Get IMG tag
     *
     * @param string $file
     * @return string
     */
    private function getLink($file)
    {
        return $this->imgTag ? '<img src="' . $file . '" alt="' . $this->alt . '" ' . implode(' ',
                $this->args) . '/>' : $file;
    }
}
