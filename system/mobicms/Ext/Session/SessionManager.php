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

namespace Mobicms\Ext\Session;

use Zend\Session\Config\StandardConfig;
use Zend\Session\Storage\SessionArrayStorage;

/**
 * Class SessionManager
 *
 * @package Mobicms\Ext\Session
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.2.0.0 2015-07-16
 */
class SessionManager extends \Zend\Session\SessionManager
{
    public function __construct(PdoSessionHandler $saveHandler, array $validators = [])
    {
        $config = new StandardConfig();
        $config->setOptions([
            'name'                => 'mobicms',
            'remember_me_seconds' => 1800,
            'use_cookies'         => true,
            'cookie_httponly'     => true,
        ]);
        $this->config = $config;

        $this->storage = new SessionArrayStorage;
        $this->saveHandler = $saveHandler;
        $this->validators = $validators;
    }
}
