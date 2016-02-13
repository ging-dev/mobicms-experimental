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

/**
 * Class Identification
 *
 * @package Mobicms\Checkpoint\Authentication
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.1.0 2015-08-13
 */
class Identification extends AbstractAuth
{
    public function getUser()
    {
        $auth = $this->getAuthData();

        if (empty($auth)) {
            return null;
        }

        try {
            $user = $this->facade->findById($auth[0]);
            $user->checkToken($auth[1]);
            $this->updateAttributes($user);
            $user->save();

            return $user;
        } catch (\Exception $e) {
            $this->facade->logout();

            return null;
        }
    }

    /**
     * Try to get Session or Cookies identification data
     *
     * @return array
     */
    private function getAuthData()
    {
        $domain = $this->facade->domain;

        if (isset($this->session->$domain)) {
            return unserialize($this->session->$domain);
        } elseif (isset($this->request->getCookie()->$domain)) {
            return $this->prepareCookie($this->request->getCookie()->$domain);
        }

        return [];
    }

    /**
     * Get Cookies identification data
     *
     * @return array
     */
    private function prepareCookie($cookie)
    {
        $auth = explode('::', $cookie);

        if (empty($auth[0]) || empty($auth[1]) || !is_numeric($auth[0])) {
            $this->facade->logout();

            return [];
        }

        return $auth;
    }
}
