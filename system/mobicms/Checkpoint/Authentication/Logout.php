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
use Mobicms\Checkpoint\User\EmptyUser;
use Zend\Http\Header\SetCookie;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

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
     * @param bool $clearToken
     */
    public function __construct(Facade $facade, Request $request, Response $response, $clearToken = false)
    {
        $this->clearToken($facade, $clearToken);
        $this->clearCookie($request, $response, $facade->domain);
        session_destroy();
        $facade->setUser(new EmptyUser());
    }

    /**
     * Clear authorization token
     *
     * @param Facade $facade
     * @param bool $clearToken
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
    private function clearCookie(Request $request, Response $response, $authDomain)
    {
        if (isset($request->getCookie()->$authDomain)) {
            $cookie = new SetCookie($authDomain, '', strtotime('-1 Year', time()), '/');
            $response->getHeaders()->addHeader($cookie);
            $response->send();
        }
    }
}
