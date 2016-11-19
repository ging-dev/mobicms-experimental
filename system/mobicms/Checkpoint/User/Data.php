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

    public function __construct(\PDO $db, $userId, $section)
    {
        $this->db = $db;
        $this->userId = $userId;
        $this->section = $section;
        parent::__construct(($userId > 0 ? $this->getData() : []), \ArrayObject::ARRAY_AS_PROPS);
    }

    public function allowModifications($allow)
    {
        $this->allowModifications = $allow;
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

    public function offsetGet($key)
    {
        return $this->offsetExists($key) ? parent::offsetGet($key) : null;
    }

    public function save()
    {
        if ($this->userId > 0 && $this->allowModifications) {
            $this->saveData();
        }
    }

    protected function getData()
    {
        $stmt = $this->db->prepare('SELECT `key`, `value` FROM `users_data` WHERE `userId` = :user AND `section` = :section');
        $stmt->bindParam(':user', $this->userId, \PDO::PARAM_INT);
        $stmt->bindParam(':section', $this->section, \PDO::PARAM_STR);
        $stmt->execute();

        $out = [];

        while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $out[$result['key']] = $result['value'];
        }

        return $out;
    }

    protected function saveData()
    {
        $stmt = $this->db->prepare('
          INSERT INTO `users_data` (`userId`, `section`, `key`, `value`)
          VALUES(:user, :section, :key, :value)
          ON DUPLICATE KEY UPDATE
          `value` = :value
        ');

        $stmt->bindParam(':user', $this->userId, \PDO::PARAM_INT);
        $stmt->bindParam(':section', $this->section, \PDO::PARAM_STR);

        foreach ($this->changedFields as $key) {
            $value = $this->offsetGet($key);

            if (empty($value)) {
                $this->deleteKey($key);
            } else {
                $stmt->bindValue(':key', $key, \PDO::PARAM_STR);
                $stmt->bindValue(':value', $this->offsetGet($key), \PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    }

    protected function deleteKey($key)
    {
        $stmt = $this->db->prepare('DELETE FROM `users_data` WHERE `userId` = :user AND `section` = :section AND `key` = :key');
        $stmt->bindParam(':user', $this->userId, \PDO::PARAM_INT);
        $stmt->bindParam(':section', $this->section, \PDO::PARAM_STR);
        $stmt->bindValue(':key', $key, \PDO::PARAM_STR);
        $stmt->execute();
    }
}
