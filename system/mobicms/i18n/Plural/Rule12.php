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
 * Class Rule12
 *
 * @package Mobicms\i18n\Plural
 */
class Rule12
{
    /**
     * Valid fol locales: cy
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
        return $number == 2
            ? 1
            : $number == 8 || $number == 11
                ? 2
                : 3;
    }
}
