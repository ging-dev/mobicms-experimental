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

namespace Mobicms\Checkpoint\User;

use Mobicms\Checkpoint\Exceptions\InvalidTokenException;

/**
 * Class User
 *
 * @package Mobicms\Checkpoint\User
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-07-23
 */
class User extends AbstractUser
{
    /**
     * Check password
     *
     * @param string $password
     * @return bool
     */
    public function checkPassword($password)
    {
        if (password_verify($password, $this->offsetGet('password', true))) {
            return true;
        }

        return false;
    }

    /**
     * Set new password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->offsetSet('password', password_hash($password, PASSWORD_DEFAULT), true);
    }

    /**
     * Check Token
     *
     * @param string $token
     * @throws InvalidTokenException
     */
    public function checkToken($token)
    {
        $userToken = $this->offsetGet('token', true);

        if (empty($userToken) || $userToken !== $token) {
            throw new InvalidTokenException('Invalid Token');
        }
    }

    /**
     * Set new token
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->offsetSet('token', $token, true);
    }

    /**
     * Save changes
     */
    public function save()
    {
        if (count($this->changedFields)) {
            $sql = [];

            foreach ($this->changedFields as $key => $val) {
                $sql[] = '`' . $key . '`=:' . $key;
            }

            $stmt = $this->db->prepare(
                'UPDATE `usr__users` SET '
                . implode(', ', $sql)
                . ' WHERE `id` = ' . $this->offsetGet('id')
            );

            foreach ($this->changedFields as $key => $type) {
                $stmt->bindValue(':' . $key, $this->offsetGet($key, true), $this->pdoTypes[$type]);
            }

            $stmt->execute();
        }
    }
}
