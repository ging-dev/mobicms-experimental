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

namespace Mobicms\Environment;

/**
 * Class Vars
 *
 * @package Mobicms\Environment
 */
class Vars
{
    public $page = 1;
    public $start = 0;

    public function __construct()
    {
        $size = \App::getInstance()->user()->get()->config()->pageSize;

        // Obtain variables
        if (filter_has_var(INPUT_ENV, 'page')) {
            $this->page = filter_input(INPUT_ENV, 'page', FILTER_SANITIZE_NUMBER_INT);
            $this->start = intval($this->page * $size - $size);
        } elseif (filter_has_var(INPUT_ENV, 'start')) {
            $this->start = filter_input(INPUT_ENV, 'start', FILTER_SANITIZE_NUMBER_INT);
        }
    }
}
