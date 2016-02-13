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

/**
 * Class EmptyUser
 *
 * @package Mobicms\Checkpoint\User
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-07-23
 */
class EmptyUser extends AbstractUser
{
    public function __construct()
    {
        $this->setFlags(parent::ARRAY_AS_PROPS);
    }

    public function offsetGet($key, $ignoreHidden = false)
    {
        $values = [
            'id'        => 0,
            'rights'    => 0,
            'nickname'  => '',
            'config'    => '',
            'activated' => 0,
            'approved'  => 2,
        ];

        return isset($values[$key]) ? $values[$key] : false;
    }

    public function checkPassword($password = null)
    {
        return false;
    }

    public function setPassword($password)
    {
        return false;
    }

    public function checkToken($token = null)
    {
        return false;
    }

    public function setToken($token)
    {
        return false;
    }

    public function save()
    {
        return false;
    }
}
