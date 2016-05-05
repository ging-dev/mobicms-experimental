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

namespace Mobicms\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\EmailAddress;

/**
 * Class Email
 *
 * @package Mobicms\Validator
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2016-01-15
 */
class Email extends AbstractValidator
{
    const EMAIL = 'email';
    const EXISTS = 'exists';

    protected $messageTemplates =
        [
            self::EMAIL  => 'Invalid Email address',
            self::EXISTS => 'This Email is already taken',
        ];

    protected $options =
        [
            'useMxCheck'  => true,
            'checkExists' => true,
        ];

    protected $valid = true;

    /**
     * Validate Email address
     *
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->checkEmail($value);
        $this->checkExists($value);

        return $this->valid;
    }

    /**
     * @param string $value
     */
    private function checkEmail($value)
    {
        $check = new EmailAddress(['useMxCheck' => $this->options['useMxCheck']]);

        if (!$check->isValid($value)) {
            $this->error(self::EMAIL);
            $this->valid = false;
        }
    }

    /**
     * @param string $value
     */
    private function checkExists($value)
    {
        if ($this->options['checkExists']) {
            $stmt = \App::getInstance()->db()->prepare('SELECT `email` FROM users WHERE `email` = :email LIMIT 1');
            $stmt->bindValue(':email', $value, \PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount()) {
                $this->error(self::EXISTS);
                $this->valid = false;
            }
        }
    }
}
