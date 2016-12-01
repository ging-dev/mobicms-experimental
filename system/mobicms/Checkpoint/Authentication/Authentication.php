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

use Mobicms\Checkpoint\Exceptions\WrongPasswordException;
use Mobicms\Checkpoint\Exceptions\UserExceptionInterface;
use Mobicms\Checkpoint\User\AbstractUser;
use Zend\Http\Header\SetCookie;

/**
 * Class Native
 *
 * @package Mobicms\Checkpoint\Authentication
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.1.1 2016-02-06
 */
class Authentication extends AbstractAuth
{
    /**
     * Authenticate the User with credentials
     *
     * @param string $login
     * @param string $password
     * @param bool   $remember
     * @throws \Exception
     */
    public function authenticate($login, $password, $remember = false)
    {
        try {
            // Ищем юзера в базе данных по Логину
            $user = $this->facade->findByLogin($login);

            // Если юзер найден, проверяем пароль
            if (!$user->checkPassword($password)) {
                //TODO: Внедрить защиту от подбора пароля
                throw new WrongPasswordException('Password does not match');
            }
        } catch (UserExceptionInterface $e) {
            throw $e;
        }

        $this->checkToken($user);
        $this->writeSession($user);
        $this->writeCookie($user, $remember);
        $this->updateAttributes($user);
        $this->facade->setUser($user);
        $user->save();
    }

    /**
     * Check identification token
     *
     * @param AbstractUser $user
     */
    private function checkToken(AbstractUser $user)
    {
        $token = $user->offsetGet('token', true);

        if (empty($token)) {
            $user->setToken($this->facade->generateToken());
        }
    }

    /**
     * Write identification session
     *
     * @param AbstractUser $user
     */
    private function writeSession(AbstractUser $user)
    {
        $this->session->offsetSet($this->facade->domain,
            serialize([$user->offsetGet('id'), $user->offsetGet('token', true)]));
    }

    /**
     * Write identification Cookie
     *
     * @param AbstractUser $user
     * @param bool         $remember
     */
    private function writeCookie(AbstractUser $user, $remember)
    {
        if ($remember) {
            setcookie($this->facade->domain, $user->offsetGet('id') . '::' . $user->offsetGet('token', true), time() + 3600 * 24 * 31, '/');
        }
    }
}
