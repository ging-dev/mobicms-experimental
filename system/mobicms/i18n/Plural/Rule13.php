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
 * Class Rule13
 *
 * @package Mobicms\i18n\Plural
 */
class Rule13
{
    /**
     * Valid fol locales: ro
     *
     * @param int $number
     * @return int
     */
    public static function getPosition($number)
    {
        return $number == 1
            ? 0
            : self::checkMultiple($number);
    }

    private static function checkMultiple($number)
    {
        return $number == 0 || ($number % 100 > 0 && $number % 100 < 20)
            ? 1
            : 2;
    }
}
