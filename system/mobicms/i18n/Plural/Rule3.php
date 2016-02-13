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
 * Class Rule3
 *
 * @package Mobicms\i18n\Plural
 */
class Rule3
{
    /**
     * Valid fol locales: be, bs, hr, ru, sr, uk
     *
     * @param int $number
     * @return int
     */
    public static function getPosition($number)
    {
        return $number % 10 == 1 && $number % 100 != 11
            ? 0
            : self::checkMultiple($number);
    }

    private static function checkMultiple($number)
    {
        return self::expression($number)
            ? 1
            : 2;
    }

    private static function expression($number)
    {
        return $number % 10 >= 2 && $number % 10 <= 4 && ($number % 100 < 10 || $number % 100 >= 20);
    }
}
