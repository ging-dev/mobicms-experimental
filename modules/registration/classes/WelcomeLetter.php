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

use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

/**
 * Class WelcomeLetter
 *
 * @package Registration
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2016-02-11
 */
class WelcomeLetter
{
    private $app;
    private $userId;
    private $nickname;
    private $email;
    private $token;
    private $activationUrl;

    public function __construct(\APP $app, $userId, $nickname, $email)
    {
        $this->app = $app;
        $this->userId = $userId;
        $this->nickname = $nickname;
        $this->email = $email;
        $this->token = $userId . $app->user()->generateToken(32);
        $this->activationUrl = $app->homeurl() . '/registration/activation/' . $this->token;

        $app->lng()->setModule('registration');
    }

    public function send($force = false)
    {
        $message = new Message();
        $message->setEncoding('UTF-8');
        $message->setFrom(System::$siteEmail, System::$siteName);
        $message->setTo($this->email, $this->nickname);
        $message->setSubject('Registration');
        $message->setBody($this->prepareLetter());

        // Отправляем письмо
        try {
            $transport = new Sendmail();
            $transport->send($message);
            $this->addToDatabase($force);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getActivationToken()
    {
        return $this->token;
    }

    public function getActivationUrl()
    {
        return $this->activationUrl;
    }

    private function prepareLetter()
    {
        // Приветственное сообщение
        $letter[] = sprintf(
            _m("Hello %s,\n\nThank you for registering at %s."),
            $this->nickname,
            System::$siteName
        );

        if (Registration::$letterMode == 2 && Registration::$approveByAdmin) {
            // Сообщение, если требуется активация c модерацией
            $letter[] = sprintf(
                _m("Your account is created and must be verified before you can use it.\nTo verify the account select the following link or copy-paste it in your browser:\n%s\n\nThe link is valid for 24 hours.\nAfter verification an administrator will be notified to activate your account. You'll receive a confirmation when it's done.\nOnce that account has been activated you may login to %s using the username and password you entered during registration."),
                $this->activationUrl,
                System::$siteName
            );
        } elseif (Registration::$letterMode == 2 && !Registration::$approveByAdmin) {
            // Сообщение, если требуется активация без модерации
            $letter[] = sprintf(
                _m("Your account is created and must be activated before you can use it.\nTo activate the account select the following link or copy-paste it in your browser:\n%s\nThe link is valid for 24 hours.\n\nAfter activation you may login to %s using the username and password you entered during registration."),
                $this->activationUrl,
                System::$siteName
            );
        } elseif (Registration::$letterMode == 1 && Registration::$approveByAdmin) {
            // Сообщение, если требуется модерация, но без активации
            $letter[] = sprintf(
                _m("Administrator will be notified to activate your account. You'll receive a confirmation when it's done.\nOnce that account has been activated you may login to %s using the username and password you entered during registration."),
                System::$siteName
            );
        }

        $letter[] = "\nLOGIN:  " . $this->nickname . '  ' . _m('or') . '  ' . $this->email;
        $letter[] = 'PASSWORD: ' . _m('Only you know it :)');
        $letter[] = sprintf(_m("\nYours faithfully,\n%s"), System::$siteName);

        return implode("\n", $letter);
    }

    private function addToDatabase($force = false)
    {
        if ($force || Registration::$letterMode == 2) {
            // Делаем старые незавершенные активации недействительными
            $stmtDel = $this->app->db()->prepare('
              UPDATE `users_activations`
              SET
              `isValid` = 0
              WHERE `type` = 0
              AND `userId` = :userId
            ');
            $stmtDel->bindValue(':userId', $this->userId, \PDO::PARAM_INT);
            $stmtDel->execute();

            // Добавляем новую запись активации
            $stmt = $this->app->db()->prepare('
              INSERT INTO `users_activations`
              SET
              `type` = 0,
              `userId` = :userId,
              `activation` = :activation,
              `timestamp` = :time,
              `isValid` = 1
            ');
            $stmt->bindValue(':userId', $this->userId, \PDO::PARAM_INT);
            $stmt->bindValue(':activation', $this->token, \PDO::PARAM_STR);
            $stmt->bindValue(':time', time(), \PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}
