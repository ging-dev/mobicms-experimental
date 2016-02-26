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

/**
 * Class UserData
 *
 * @package Mobicms\Checkpoint\User
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2016-02-26
 */
class Data extends \ArrayObject
{
    private $db;
    private $userId;
    private $section;
    private $allowModifications = false;
    private $changedFields = [];

    public function __construct(PDOmysql $db, $userId, $section)
    {
        $this->db = $db;
        $this->userId = $userId;
        $this->section = $section;
        parent::__construct([], \ArrayObject::ARRAY_AS_PROPS);

        if ($userId > 0) {
            $this->getData();
        }
    }

    public function allowModifications($allow)
    {
        $this->allowModifications = $allow;
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        if ($this->allowModifications) {
            parent::offsetSet($key, $value);
            $this->changedFields[] = $key;
        } else {
            throw new \RuntimeException('User Data is read only');
        }
    }

    public function get($key, $default = null)
    {
        return $this->offsetExists($key) ? parent::offsetGet($key) : $default;
    }

    public function save()
    {
        if ($this->userId > 0 && $this->allowModifications) {
            $this->saveData();
        }
    }

    protected function getData()
    {
        $stmt = $this->db->prepare('SELECT * FROM `usr__data` WHERE `userId` = :user AND `section` = :section');
        $stmt->bindParam(':user', $this->userId, \PDO::PARAM_INT);
        $stmt->bindParam(':section', $this->section, \PDO::PARAM_STR);
        $stmt->execute();

        while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            parent::offsetSet($result['key'], $result['value']);
        }
    }

    protected function saveData()
    {
        $stmt = $this->db->prepare('
          INSERT INTO `usr__data` (`userId`, `section`, `key`, `value`)
          VALUES(:user, :section, :key, :value)
          ON DUPLICATE KEY UPDATE
          `value` = :value
        ');

        $stmt->bindParam(':user', $this->userId, \PDO::PARAM_INT);
        $stmt->bindParam(':section', $this->section, \PDO::PARAM_STR);

        foreach ($this->changedFields as $key) {
            $stmt->bindValue(':key', $key, \PDO::PARAM_STR);
            $stmt->bindValue(':value', $this->get($key), \PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}
