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
 * Class Config
 *
 * @package Mobicms\Checkpoint\User
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-07-27
 *
 * @property $directUrl
 * @property $editor
 * @property $lng
 * @property $pageSize
 * @property $skin
 * @property $timeShift
 */
class Config extends \ArrayObject
{
    /**
     * @var AbstractUser
     */
    private $userInstance;

    /**
     * @var array Default settings
     */
    private $defaults = [
        'directUrl' => 0,
        'editor'    => 1,
        'lng'       => '#',
        'pageSize'  => 20,
        'skin'      => 'thundercloud',
        'timeShift' => 0,
    ];

    /**
     * @param AbstractUser $user
     */
    public function __construct(AbstractUser $user)
    {
        $this->userInstance = $user;
        $cnf = $user->offsetGet('config');
        $config = !empty($cnf) ? unserialize($cnf) : $this->defaults;
        parent::__construct($config, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Sets the value at the specified index
     *
     * @param string $key
     * @param mixed  $value
     */
    public function offsetSet($key, $value)
    {
        if (!isset($this->defaults[$key])) {
            throw new \InvalidArgumentException('Unknown key [' . $key . ']');
        }

        parent::offsetSet($key, $value);
    }

    /**
     * Save settings
     */
    public function save()
    {
        $config = $this->getArrayCopy();
        $this->userInstance->offsetSet('config', serialize($config), true);
        $this->userInstance->save();
    }
}
