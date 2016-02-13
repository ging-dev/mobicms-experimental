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
 * Class FindById
 *
 * @package Mobicms\Checkpoint\User
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-07-22
 */
class FindById
{
    /**
     * Search of the User by ID
     *
     * @param int  $id
     * @param \PDO $db
     * @return array
     * @throws UserExceptionInterface
     */
    public static function find($id, \PDO $db)
    {
        try {
            self::checkInquiry($id);

            $stmt = $db->prepare('SELECT * FROM `usr__users` WHERE `id` = :id');
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch();

            self::checkResult($user, $id);

            return $user;
        } catch (UserExceptionInterface $e) {
            throw $e;
        }
    }

    /**
     * Check input data
     *
     * @param int $id
     * @throws InvalidInputException
     */
    private static function checkInquiry($id)
    {
        if (!is_numeric($id) || $id < 1) {
            throw new InvalidInputException('The argument [' . $id . '] must be a valid User ID');
        }
    }

    /**
     * Check result
     *
     * @param mixed $result
     * @param int   $id
     * @throws UserNotFoundException
     */
    private static function checkResult($result, $id)
    {
        if (false === $result || !is_array($result)) {
            throw new UserNotFoundException('A user with ID [' . $id . '] not found.');
        }
    }
}
