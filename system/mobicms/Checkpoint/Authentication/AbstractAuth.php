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
use Mobicms\Checkpoint\User\AbstractUser;
use Mobicms\Environment\Network;
use Zend\Http\PhpEnvironment\Request;
use Zend\Session\Container;

/**
 * Class AbstractAuth
 *
 * @package Mobicms\Checkpoint\Authentication
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.1.0 2015-11-17
 */
class AbstractAuth
{
    /**
     * @var Facade
     */
    protected $facade;

    /**
     * @var Container
     */
    protected $session;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Network
     */
    protected $network;

    public function __construct(
        Facade $facade,
        Container $session,
        Request $request,
        Network $network
    ) {
        $this->session = $session;
        $this->request = $request;
        $this->network = $network;
        $this->facade = $facade;
    }

    /**
     * Update attributes
     *
     * @param AbstractUser $user
     */
    protected function updateAttributes(AbstractUser $user)
    {
        $user->offsetSet('ip', $this->network->getClientIp());
        $user->offsetSet('userAgent', $this->network->getUserAgent());

        if ($user->offsetGet('lastVisit') < time() - 90) {
            $user->offsetSet('lastVisit', time());
        }
    }
}
