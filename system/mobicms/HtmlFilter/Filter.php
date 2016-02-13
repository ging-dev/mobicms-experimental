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
 * Class Filter
 *
 * @package Mobicms\HtmlFilter
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-02-01
 */
class Filter
{
    private $html = '';

    public function __construct(array $arguments)
    {
        $this->html = $arguments[0];
    }

    public function __toString()
    {
        return $this->sanitizeString();
    }

    public function sanitizeString()
    {
        return filter_var($this->html, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    }

    public function specialchars()
    {
        return htmlspecialchars($this->html, ENT_QUOTES, 'UTF-8');
    }
}
