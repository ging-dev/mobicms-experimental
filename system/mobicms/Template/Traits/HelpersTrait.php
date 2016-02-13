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

namespace Mobicms\Template\Traits;

/**
 * Class HelpersTrait
 *
 * @package Mobicms\Template\Traits
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-06-11
 */
trait HelpersTrait
{
    /**
     * Sanitize arrays
     *
     * @param array $array
     * @return array
     */
    protected function sanitizeArray(array $array)
    {
        foreach ($array as $key => $val) {
            if (is_array($array[$key])) {
                $array[$key] = $this->sanitizeArray($array[$key]);
            } else {
                $array[$key] = htmlspecialchars($val, ENT_QUOTES, 'UTF-8', true);
            }
        }

        return $array;
    }
}
