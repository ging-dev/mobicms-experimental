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

use Mobicms\Database\PDOmysql;
use Mobicms\Environment\Network;

/**
 * Class EmptyUser
 *
 * @package Mobicms\Checkpoint\User
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-07-23
 */
class EmptyUser extends AbstractUser
{
    private $defaults =
        [
            'id'           => 0,
            'email'        => '',
            'nickname'     => '',
            'password'     => '',
            'token'        => '',
            'activated'    => 0,
            'approved'     => 0,
            'quarantine'   => 0,
            'rights'       => 0,
            'sex'          => 'm',
            'config'       => '',
            'avatar'       => '',
            'status'       => '',
            'joinDate'     => 0,
            'lastVisit'    => 0,
            'lastActivity' => 0,
            'ip'           => '',
            'userAgent'    => '',
            'reputation'   => '',
        ];

    public function __construct(PDOmysql $db, Network $network)
    {
        $this->defaults['ip'] = $network->getClientIp();
        $this->defaults['userAgent'] = $network->getUserAgent();
        $this->defaults['joinDate'] = $this->defaults['lastVisit'] = $this->defaults['lastActivity'] = time();
        parent::__construct($this->defaults, $db);
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
