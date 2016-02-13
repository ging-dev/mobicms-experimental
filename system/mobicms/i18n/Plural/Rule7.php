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
 * Class Rule7
 *
 * @package Mobicms\i18n\Plural
 */
class Rule7
{
    /**
     * Valid fol locales: sl
     *
     * @param int $number
     * @return int
     */
    public static function getPosition($number)
    {
        return $number % 100 == 1
            ? 0
            : self::checkMultiple($number);
    }

    private static function checkMultiple($number)
    {
        return $number % 100 == 2
            ? 1
            : $number % 100 == 3 || $number % 100 == 4
                ? 2
                : 3;
    }
}
