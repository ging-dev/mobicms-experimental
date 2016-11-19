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
 * Class AbstractUser
 *
 * @package Mobicms\Checkpoint\User
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.1.0 2015-08-29
 *
 * @property int    $id
 * @property string $email
 * @property string $nickname
 * @property string $password
 * @property string $token
 * @property bool   $activated
 * @property bool   $approved
 * @property bool   $quarantine
 * @property int    $rights
 * @property string $sex
 * @property string $avatar
 * @property string $showEmail
 * @property string $status
 * @property int    $joinDate
 * @property int    $lastVisit
 * @property int    $lastActivity
 * @property string $ip
 * @property string $userAgent
 * @property string $reputation
 */
abstract class AbstractUser extends \ArrayObject
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * @var Config
     */
    protected $configInstance;

    protected $fields =
        [
            'id'           => ['type' => 'int', 'readonly' => true, 'hidden' => false,],
            'email'        => ['type' => 'str', 'readonly' => false, 'hidden' => false,],
            'nickname'     => ['type' => 'str', 'readonly' => false, 'hidden' => false,],
            'password'     => ['type' => 'str', 'readonly' => true, 'hidden' => true,],
            'token'        => ['type' => 'str', 'readonly' => true, 'hidden' => true,],
            'activated'    => ['type' => 'bool', 'readonly' => false, 'hidden' => false,],
            'approved'     => ['type' => 'bool', 'readonly' => false, 'hidden' => false,],
            'quarantine'   => ['type' => 'bool', 'readonly' => false, 'hidden' => false,],
            'rights'       => ['type' => 'int', 'readonly' => true, 'hidden' => false,],
            'sex'          => ['type' => 'str', 'readonly' => false, 'hidden' => false,],
            'config'       => ['type' => 'text', 'readonly' => true, 'hidden' => false,],
            'showEmail'    => ['type' => 'bool', 'readonly' => false, 'hidden' => false,],
            'avatar'       => ['type' => 'str', 'readonly' => false, 'hidden' => false,],
            'status'       => ['type' => 'str', 'readonly' => false, 'hidden' => false,],
            'joinDate'     => ['type' => 'int', 'readonly' => false, 'hidden' => false,],
            'lastVisit'    => ['type' => 'int', 'readonly' => false, 'hidden' => false,],
            'lastActivity' => ['type' => 'int', 'readonly' => false, 'hidden' => false,],
            'ip'           => ['type' => 'int', 'readonly' => false, 'hidden' => false,],
            'userAgent'    => ['type' => 'int', 'readonly' => false, 'hidden' => false,],
            'reputation'   => ['type' => 'text', 'readonly' => false, 'hidden' => false,],
        ];

    protected $pdoTypes =
        [
            'int'  => \PDO::PARAM_INT,
            'str'  => \PDO::PARAM_STR,
            'bool' => \PDO::PARAM_BOOL,
            'text' => \PDO::PARAM_STR,
        ];

    protected $userDataInstances = [];
    protected $changedFields = [];

    /**
     * @param array    $user
     */
    public function __construct(array $user)
    {
        $this->db = \App::getContainer()->get(\PDO::class);
        parent::__construct($user, \ArrayObject::ARRAY_AS_PROPS);
    }

    abstract public function checkPassword($password);

    abstract public function setPassword($password);

    abstract public function checkToken($token);

    abstract public function setToken($token);

    abstract public function save();

    /**
     * Returns the value at the specified index
     *
     * @param mixed $key
     * @param bool  $ignoreHidden
     * @return mixed
     */
    public function offsetGet($key, $ignoreHidden = false)
    {
        if (!isset($this->fields[$key])) {
            throw new \InvalidArgumentException('[' . $key . '] is unknown field');
        } elseif (!$ignoreHidden && $this->fields[$key]['hidden']) {
            throw new \InvalidArgumentException('[' . $key . '] is hidden field');
        }

        return parent::offsetGet($key);
    }

    /**
     * Sets the value at the specified index
     *
     * @param string $key
     * @param mixed  $value
     * @param bool   $ignoreReadonly
     */
    public function offsetSet($key, $value, $ignoreReadonly = false)
    {
        if (!isset($this->fields[$key])) {
            throw new \InvalidArgumentException('[' . $key . '] is unknown field');
        } elseif (!$ignoreReadonly && $this->fields[$key]['readonly']) {
            throw new \InvalidArgumentException('[' . $key . '] is read only field');
        }

        $this->changedFields[$key] = $this->fields[$key]['type'];
        parent::offsetSet($key, $value);
    }

    /**
     * Get User configuration
     *
     * @return Config
     */
    public function getConfig()
    {
        if (null === $this->configInstance) {
            $this->configInstance = new Config($this);
        }

        return $this->configInstance;
    }

    /**
     * @param $section
     * @return Data
     */
    public function getUserData($section)
    {
        if (!isset($this->userDataInstances[$section])) {
            $this->userDataInstances[$section] = new Data($this->db, $this->offsetGet('id'), $section);
        }

        return $this->userDataInstances[$section];
    }
}
