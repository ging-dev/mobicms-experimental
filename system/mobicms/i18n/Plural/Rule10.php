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

namespace Mobicms\i18n\Plural;

/**
 * Class Rule10
 *
 * @package Mobicms\i18n\Plural
 */
class Rule10
{
    /**
     * Valid fol locales: lv
     *
     * @param int $number
     * @return int
     */
    public static function getPosition($number)
    {
        return $number == 0
            ? 0
            : $number % 10 == 1 && $number % 100 != 11
                ? 1
                : 2;
    }
}
