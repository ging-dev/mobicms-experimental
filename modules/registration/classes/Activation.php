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

namespace Registration;

use Mobicms\Checkpoint\Facade as User;
use Mobicms\Checkpoint\Exceptions\UserExceptionInterface;
use Zend\Validator\AbstractValidator;

/**
 * Class RegActivation
 *
 * @package Mobicms\Validator
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2016-02-03
 */
class Activation extends AbstractValidator
{
    const INVALIDTOKEN = 'invalidtoken';
    const TOKENNOTFOUND = 'tokennotfound';
    const TOKENEXPIRED = 'tokenexpired';
    const USERNOTFOUND = 'usernotfound';

    protected $messageTemplates =
        [
            self::INVALIDTOKEN  => 'Invalid activation token',
            self::TOKENNOTFOUND => 'Data for activation aren\'t found',
            self::TOKENEXPIRED  => 'Outdated token',
            self::USERNOTFOUND  => 'The user for activation aren\'t found',
        ];

    /**
     * @var \PDO
     */
    private $db;

    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $lifetime = 24;

    public function __construct(User $user, $options = null)
    {
        $this->db = \App::getContainer()->get(\PDO::class);
        $this->user = $user;
        parent::__construct($options);
    }

    /**
     * @param int $lifetime
     */
    public function setFifetime($lifetime)
    {
        $this->lifetime = intval($lifetime);
    }

    /**
     * @param string $token
     * @return bool
     */
    public function isValid($token)
    {
        $token = filter_var(trim($token), FILTER_SANITIZE_STRING);
        $this->setValue($token);

        try {
            $this->checkToken($token);                             // Проверяем токен активации на длину
            $result = $this->getActivationToken($token);           // Получаем данные токена активации из базы
            $this->deleteUserTokens((int)$result['userId']);       // Удаляем все токены данного пользователя
            $this->checkExpired($result);                          // Если токен устарел, удаляем связанного пользователя
            $user = $this->user->findById((int)$result['userId']); // Получаем данные пользователя

            if ($user->activated) {
                return true;                                       // Если пользователь уже активирован, возвращаем true
            }

            $user->activated = 1;                                  // Активируем пользователя
            $user->save();                                         // Записываем данные пользователя
        } catch (UserExceptionInterface $e) {
            $this->error(self::USERNOTFOUND);

            return false;                                          // Если пользователь не найден, возвращаем false
        } catch (\Exception $e) {
            return false;                                          // Если проблемы с токеном, возвращаем false
        }

        return true;
    }

    /**
     * Check activation token
     *
     * @param int $token
     * @throws \Exception
     */
    private function checkToken($token)
    {
        if (strlen($token) < 32 || strlen($token) > 40) {
            $this->error(self::INVALIDTOKEN);
            throw new \Exception(self::INVALIDTOKEN);
        }
    }

    /**
     * Find activation token
     *
     * @param $token
     * @return array
     * @throws \Exception
     */
    private function getActivationToken($token)
    {
        // Ищем валидный токен активации
        $stmt = $this->db->prepare('SELECT * FROM `users_activations` WHERE `activation` = :token AND `type` = 0 AND `isValid` = 1');
        $stmt->bindParam(':token', $token, \PDO::PARAM_STR);
        $stmt->execute();

        // Если токен не найден, бросаем исключение
        if (!$stmt->rowCount()) {
            $this->error(self::TOKENNOTFOUND);
            throw new \Exception(self::TOKENNOTFOUND);
        }

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Delete all activation tokens for given user
     *
     * @param int $userId
     */
    private function deleteUserTokens($userId)
    {
        $stmt = $this->db->prepare('DELETE FROM `users_activations` WHERE `userId` = :uid AND `type` = 0');
        $stmt->bindParam(':uid', $userId, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * If the token expired, delete the associated user
     *
     * @param array $result
     * @throws \Exception
     */
    private function checkExpired(array $result)
    {
        if ((int)$result['timestamp'] < time() - $this->lifetime * 3600) {
            $this->error(self::TOKENEXPIRED);

            $stmt = $this->db->prepare('DELETE FROM `users` WHERE `id` = :uid AND `activated` = 0');
            $stmt->bindParam(':uid', $result['userId'], \PDO::PARAM_INT);
            $stmt->execute();

            throw new \Exception(self::TOKENEXPIRED);
        }
    }
}
