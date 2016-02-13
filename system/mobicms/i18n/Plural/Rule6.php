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
 * Class Rule6
 *
 * @package Mobicms\i18n\Plural
 */
class Rule6
{
    /**
     * Valid fol locales: lt
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
        return $number % 10 >= 2 && ($number % 100 < 10 || $number % 100 >= 20)
            ? 1
            : 2;
    }
}
