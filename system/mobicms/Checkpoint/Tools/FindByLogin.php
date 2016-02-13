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

use Mobicms\Checkpoint\Exceptions\InvalidInputException;
use Mobicms\Checkpoint\Exceptions\UserExceptionInterface;
use Mobicms\Checkpoint\Exceptions\UserNotFoundException;

/**
 * Class FindByLogin
 *
 * @package Mobicms\Checkpoint\User
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-07-22
 */
class FindByLogin
{
    /**
     * Search of the User by a Nickname or Email
     *
     * @param string $login
     * @param \PDO   $db
     * @return array
     * @throws UserExceptionInterface
     */
    public static function find($login, \PDO $db)
    {
        try {
            self::checkInquiry($login);

            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $stmt = $db->prepare('SELECT * FROM `usr__users` WHERE `email` = :login LIMIT 1');
            } else {
                $stmt = $db->prepare('SELECT * FROM `usr__users` WHERE `nickname` = :login LIMIT 1');
            }

            $stmt->bindParam(':login', $login, \PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();

            self::checkResult($user, $login);

            return $user;
        } catch (UserExceptionInterface $e) {
            throw $e;
        }
    }

    /**
     * Check input data
     *
     * @param string $login
     * @throws InvalidInputException
     */
    private static function checkInquiry($login)
    {
        if (empty($login)) {
            throw new InvalidInputException('The login is required');
        }
    }

    /**
     * Check result
     *
     * @param mixed  $result
     * @param string $login
     * @throws UserNotFoundException
     */
    private static function checkResult($result, $login)
    {
        if (false === $result || !is_array($result)) {
            throw new UserNotFoundException('A user with login [' . $login . '] not found.');
        }
    }
}
