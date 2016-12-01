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

namespace Mobicms\Checkpoint\Authentication;

use Mobicms\Checkpoint\Facade;

/**
 * Class Logout
 *
 * @package Mobicms\Checkpoint\Authentication
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.2.0 2015-08-013
 */
class Logout
{
    /**
     * Logout the User
     *
     * @param Facade $facade
     * @param bool   $clearToken
     */
    public function __construct(Facade $facade, $clearToken = false)
    {
        $this->clearToken($facade, $clearToken);
        $this->clearCookie($facade->domain);
        session_destroy();
    }

    /**
     * Clear authorization token
     *
     * @param Facade $facade
     * @param bool   $clearToken
     */
    private function clearToken(Facade $facade, $clearToken)
    {
        if ($clearToken && $facade->isValid()) {
            $user = $facade->get();
            $user->setToken('');
            $user->save();
        }
    }

    /**
     * Clear authorization Cookie
     *
     * @param string $authDomain
     */
    private function clearCookie($authDomain)
    {
        if (isset($_COOKIE[$authDomain])) {
            setcookie($authDomain, '', strtotime('-1 Year', time()), '/');
        }
    }
}
