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
 * Returns the plural rules for a given locale.
 *
 * @package Mobicms\i18n\Handlers
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @link    http://localization-guide.readthedocs.org/en/latest/l10n/pluralforms.html
 * @link    https://developer.mozilla.org/en-US/docs/Mozilla/Localization/Localization_and_Plurals#List_of_Plural_Rules
 * @version v.1.0.0 2015-07-09
 */
class Pluralization
{
    private static $locales = [
        'af'  => 1,
        'am'  => 2,
        'ar'  => 14,
        'be'  => 3,
        'bg'  => 1,
        'bh'  => 2,
        'bn'  => 1,
        'bs'  => 3,
        'ca'  => 1,
        'cs'  => 4,
        'cy'  => 12,
        'da'  => 1,
        'de'  => 1,
        'el'  => 1,
        'en'  => 1,
        'eo'  => 1,
        'es'  => 1,
        'et'  => 1,
        'eu'  => 1,
        'fa'  => 1,
        'fi'  => 1,
        'fil' => 2,
        'fo'  => 1,
        'fr'  => 2,
        'fur' => 1,
        'fy'  => 1,
        'ga'  => 5,
        'gl'  => 1,
        'gu'  => 1,
        'gun' => 2,
        'ha'  => 1,
        'he'  => 1,
        'hi'  => 2,
        'hr'  => 3,
        'hu'  => 1,
        'is'  => 1,
        'it'  => 1,
        'ku'  => 1,
        'lb'  => 1,
        'ln'  => 2,
        'lt'  => 6,
        'lv'  => 10,
        'mg'  => 2,
        'mk'  => 8,
        'ml'  => 1,
        'mn'  => 1,
        'mr'  => 1,
        'mt'  => 9,
        'nah' => 1,
        'nb'  => 1,
        'ne'  => 1,
        'nl'  => 1,
        'nn'  => 1,
        'no'  => 1,
        'nso' => 2,
        'om'  => 1,
        'or'  => 1,
        'pa'  => 1,
        'pap' => 1,
        'pl'  => 11,
        'ps'  => 1,
        'pt'  => 1,
        'ro'  => 13,
        'ru'  => 3,
        'sk'  => 4,
        'sl'  => 7,
        'so'  => 1,
        'sq'  => 1,
        'sr'  => 3,
        'sv'  => 1,
        'sw'  => 1,
        'ta'  => 1,
        'te'  => 1,
        'ti'  => 2,
        'tk'  => 1,
        'uk'  => 3,
        'ur'  => 1,
        'wa'  => 2,
        'xbr' => 2,
        'zu'  => 1,
    ];

    /**
     * Returns the plural position
     *
     * @param int    $number
     * @param string $locale ISO code for the locale
     *
     * @return int The plural position
     */
    public static function get($number, $locale)
    {
        if (isset(self::$locales[$locale])) {
            $class = __NAMESPACE__ . '\Rule' . self::$locales[$locale];

            return $class::getPosition($number);
        }

        return 0;
    }
}
