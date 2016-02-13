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

namespace Mobicms\Checkpoint\Tools;

/**
 * Class Validator
 *
 * @package Mobicms\Checkpoint\Validator
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.2.0.0 2015-09-25
 */
class Validator
{
    /**
     * @var \PDO
     */
    private $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Validate Nickname characters
     *
     * @param string $nickname
     * @return bool
     */
    public function checkNicknameChars($nickname)
    {
        return preg_match('/[^\da-zа-я\-\.\ \@\*\(\)\?\!\~\_\=\[\]]+/iu', $nickname) ? false : true;
    }

    /**
     * Validate Nickname charsets
     *
     * @param string $nickname
     * @return bool
     */
    public function checkNicknameCharsets($nickname)
    {
        return preg_match('~(([a-z]+)([а-я]+)|([а-я]+)([a-z]+))~iu', $nickname) ? false : true;
    }

    /**
     * Validate Nickname repeated characters
     *
     * @param string $nickname
     * @return bool
     */
    public function checkNicknameRepeatedChars($nickname)
    {
        return preg_match('/(.)\1\1\1/', $nickname) ? false : true;
    }

    /**
     * @param string $nickname
     * @return bool
     */
    public function checkNicknameExists($nickname)
    {
        $stmt = $this->db->prepare('SELECT `nickname` FROM usr__users WHERE `nickname` = :nickname LIMIT 1');
        $stmt->bindValue(':nickname', $nickname, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() ? true : false;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function checkEmailExists($email)
    {
        $stmt = $this->db->prepare('SELECT `email` FROM usr__users WHERE `email` = :email LIMIT 1');
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() ? true : false;
    }
}
