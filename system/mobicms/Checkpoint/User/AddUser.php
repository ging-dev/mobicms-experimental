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
 * Class AddUser
 *
 * @package Mobicms\Checkpoint\User
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2016-02-04
 */
class AddUser extends AbstractUser
{
    private $insertId;

    public function checkPassword($password)
    {

    }

    public function setPassword($password)
    {
        $this->offsetSet('password', password_hash($password, PASSWORD_DEFAULT), true);
    }

    public function checkToken($token)
    {
        return false;
    }

    public function setToken($token)
    {

    }

    public function save()
    {
        if (count($this->changedFields)) {
            $this->checkTextFields();
            $sql = [];

            foreach ($this->changedFields as $key => $val) {
                $sql[] = '`' . $key . '`=:' . $key;
            }

            $stmt = $this->db->prepare(
                'INSERT INTO `users` SET '
                . implode(', ', $sql)
            );

            foreach ($this->changedFields as $key => $type) {
                $stmt->bindValue(':' . $key, $this->offsetGet($key, true), $this->pdoTypes[$type]);
            }

            $stmt->execute();
            $this->insertId = $this->db->lastInsertId();
        }
    }

    public function getInsertId()
    {
        return $this->insertId;
    }

    private function checkTextFields()
    {
        foreach ($this->fields as $key => $val) {
            if ($val['type'] == 'text' && !$this->offsetExists($key)) {
                $this->offsetSet($key, '', true);
            }
        }
    }
}
