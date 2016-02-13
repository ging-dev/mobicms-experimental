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

namespace Mobicms\HtmlFilter;

/**
 * Class Purify
 *
 * @package Mobicms\HtmlFilter
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-02-01
 */
class Purify
{
    private $purifier = null;
    private $config;
    private $html = '';

    public function __construct(array $arguments)
    {
        $this->html = $arguments[0];

        // Base configuration
        $this->config = \HTMLPurifier_Config::createDefault();
        $cachePath = CACHE_PATH . 'htmlpurifier';

        if (!is_dir($cachePath)) {
            mkdir($cachePath, 0700, true);
        }

        $this->config->set('Cache.SerializerPath', realpath($cachePath));

        // Custom Filters (optimized for Wysiwyg editor)
        $this->config->set('HTML.Allowed', 'a[href],strong,em,span[style],p,br,code,pre,hr');
        $this->config->set('Attr.AllowedFrameTargets', ['_blank', '_self', '_target', '_top']);

        $this->purifier = new \HTMLPurifier($this->config);
    }

    /**
     * Return purified string (with default settings)
     *
     * @return string
     */
    public function __toString()
    {
        return $this->purifier->purify($this->html);
    }

    /**
     * Strict filtering
     *
     * @return string
     */
    public function strictFilter()
    {
        $this->config->set('HTML.Allowed', '');

        return $this->purifier->purify($this->html, $this->config);
    }
}
