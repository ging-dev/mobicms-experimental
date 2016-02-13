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
 * Class Rule1
 *
 * @package Mobicms\i18n\Plural
 */
class Rule1
{
    /**
     * Valid fol locales: af, bn, bg, ca, da, de, el, en, eo, es, et, eu, fa, fi,
     *                    fo, fur, fy, gl, gu, ha, he, hu, is, it, ku, lb, ml, mn,
     *                    mr, nah, nb, ne, nl, nn, no, om, or, pa, pap, ps, pt,
     *                    so, sq, sv, sw, ta, te, tk, ur, zu
     *
     * @param int $number
     * @return int
     */
    public static function getPosition($number)
    {
        return $number == 1
            ? 0
            : 1;
    }
}
