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

namespace Mobicms\Checkpoint;

use Mobicms\Checkpoint\Authentication\Identification;
use Mobicms\Checkpoint\Authentication\Logout;
use Mobicms\Checkpoint\Exceptions\UserExceptionInterface;
use Mobicms\Checkpoint\Authentication\Authentication;
use Mobicms\Checkpoint\Tools\FindById;
use Mobicms\Checkpoint\Tools\FindByLogin;
use Mobicms\Checkpoint\Tools\Validator;
use Mobicms\Checkpoint\User\AbstractUser;
use Mobicms\Checkpoint\User\EmptyUser;
use Mobicms\Checkpoint\User\User;
use Mobicms\Environment\Network;
use Zend\Session\SessionManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Session\Container as Session;

/**
 * Class Facade
 *
 * @package Mobicms\Checkpoint
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.2.0.0 2016-02-06
 */
class Facade
{
    /**
     * @var \PDO
     */
    private $db;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Network
     */
    private $network;

    /**
     * @var AbstractUser
     */
    private $userInstance;

    /**
     * @var string
     */
    public $domain = 'mobicms';

    public function __construct(
        Request $request,
        SessionManager $manager,
        Network $network
    ) {
        $this->db = \App::getContainer()->get(\PDO::class);
        $this->session = new Session('auth', $manager);
        $this->request = $request;
        $this->network = $network;
        $this->userInstance = (new Identification($this, $this->session, $request, $network))->getUser();
    }

    /**
     * Check whether a user is valid (is authenticated)?
     *
     * @return bool
     */
    public function isValid()
    {
        if ($this->userInstance !== null
            && $this->userInstance->activated == 1
            && $this->userInstance->approved == 1
        ) {
            return true;
        }

        return false;
    }

    /**
     * Get current User
     *
     * @return User|EmptyUser
     */
    public function get()
    {
        if ($this->userInstance !== null) {
            return $this->userInstance;
        }

        return new EmptyUser($this->network);
    }

    /**
     * Set current User
     *
     * @param AbstractUser $user
     */
    public function setUser(AbstractUser $user)
    {
        $this->userInstance = $user;
    }

    /**
     * Search of the User by ID
     *
     * @param int $id
     * @return User
     * @throws UserExceptionInterface
     */
    public function findById($id)
    {
        try {
            return new User(FindById::find($id, $this->db), $this->db);
        } catch (UserExceptionInterface $e) {
            throw $e;
        }
    }

    /**
     * Search of the User by a Nickname or Email
     *
     * @param string $login
     * @return User
     * @throws UserExceptionInterface
     */
    public function findByLogin($login)
    {
        try {
            return new User(FindByLogin::find($login, $this->db), $this->db);
        } catch (UserExceptionInterface $e) {
            throw $e;
        }
    }

    /**
     * Login tre User with credentials
     *
     * @param string $login
     * @param string $password
     * @param bool   $remember
     * @throws \Exception
     */
    public function login($login, $password, $remember = false)
    {
        try {
            $auth = new Authentication($this, $this->session, $this->request, $this->network);
            $auth->authenticate($login, $password, $remember);
        } catch (UserExceptionInterface $e) {
            throw $e;
        }
    }

    /**
     * Logout the User
     *
     * @param bool $clearToken
     */
    public function logout($clearToken = false)
    {
        new Logout($this, $clearToken);
    }

    /**
     * Validators
     *
     * @return Validator
     */
    public function validate()
    {
        return new Validator($this->db);
    }

    /**
     * Generate Token
     *
     * @param int    $length
     * @param string $pool
     * @return string
     */
    public function generateToken($length = 42, $pool = '')
    {
        if (empty($pool)) {
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        return substr(str_shuffle(str_repeat($pool, 3)), 0, $length);
    }
}
