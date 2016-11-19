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

/**
 * Class Nickname
 *
 * @package Mobicms\Validator
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2016-01-15
 */
class Nickname extends AbstractValidator
{
    const LENGTH = 'length';
    const SYMBOLS = 'symbols';
    const CHARSET = 'charset';
    const DIGITS = 'digits';
    const RECURRING = 'repeat';
    const EMAIL = 'email';
    const EXISTS = 'exists';

    protected $messageTemplates =
        [
            self::LENGTH    => 'Nickname must be at least 2 and no more than 20 characters in length',
            self::SYMBOLS   => 'Nickname contains illegal characters',
            self::CHARSET   => 'Nickname contains characters from different languages',
            self::DIGITS    => 'Nicknames consisting only of numbers are prohibited',
            self::RECURRING => 'Nickname contains recurring characters',
            self::EMAIL     => 'Email cannot be used as the Nickname',
            self::EXISTS    => 'This Nickname is already taken',
        ];

    protected $options =
        [
            'minLength'        => 2,
            'maxLength'        => 20,
            'allowDigitsOnly'  => false,
            'checkIfEmail'     => true,
            'checkExists'      => true,
            'charsPattern'     => '/[^\da-zа-я\-\.\ \@\*\(\)\?\!\~\_\=\[\]]+/iu',
            'charsetsPattern'  => '~(([a-z]+)([а-я]+)|([а-я]+)([a-z]+))~iu',
            'repeatingPattern' => '/(.)\1\1\1/iu',
        ];

    protected $valid = true;

    /**
     * Validate Nickname
     *
     * @param string $nickname
     * @return bool
     */
    public function isValid($nickname)
    {
        $this->setValue($nickname);

        $this->checkLength($nickname);
        $this->checkChars($nickname);
        $this->checkCharsets($nickname);
        $this->checkIfDigits($nickname);
        $this->checkRepeatingChars($nickname);
        $this->checkIfEmail($nickname);
        $this->checkExists($nickname);

        return $this->valid;
    }

    /**
     * Check Nickname length
     *
     * @param string $value
     */
    private function checkLength($value)
    {
        if (mb_strlen($value) < $this->options['minLength'] || mb_strlen($value) > $this->options['maxLength']) {
            $this->error(self::LENGTH);
            $this->valid = false;
        }
    }

    /**
     * Check on the illegal characters
     *
     * @param string $value
     */
    private function checkChars($value)
    {
        if (preg_match($this->options['charsPattern'], $value)) {
            $this->error(self::SYMBOLS);
            $this->valid = false;
        }
    }

    /**
     * Check on use of characters from different languages
     *
     * @param string $value
     */
    private function checkCharsets($value)
    {
        if (preg_match($this->options['charsetsPattern'], $value)) {
            $this->error(self::CHARSET);
            $this->valid = false;
        }
    }

    /**
     * Check, whether Nickname consists only of digits?
     *
     * @param string $value
     */
    private function checkIfDigits($value)
    {
        if (!$this->options['allowDigitsOnly'] && ctype_digit($value)) {
            $this->error(self::DIGITS);
            $this->valid = false;
        }
    }

    /**
     * Check on the repeating characters
     *
     * @param string $value
     */
    private function checkRepeatingChars($value)
    {
        if (preg_match($this->options['repeatingPattern'], $value)) {
            $this->error(self::RECURRING);
            $this->valid = false;
        }
    }

    /**
     * Check on attempt to use Email address as Nickname
     *
     * @param string $value
     */
    private function checkIfEmail($value)
    {
        if ($this->options['checkIfEmail'] && filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->error(self::EMAIL);
            $this->valid = false;
        }
    }

    /**
     * Check if Nickname already exists
     *
     * @param string $value
     */
    private function checkExists($value)
    {
        if ($this->options['checkExists']) {
            /** @var \PDO $db */
            $db = \App::getContainer()->get(\PDO::class);

            $stmt = $db->prepare('SELECT `nickname` FROM users WHERE `nickname` = :nickname LIMIT 1');
            $stmt->bindValue(':nickname', $value, \PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount()) {
                $this->error(self::EXISTS);
                $this->valid = false;
            }
        }
    }
}
