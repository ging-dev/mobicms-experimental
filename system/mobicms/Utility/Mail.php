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

namespace Mobicms\Utility;

use Zend\Mail as ZendMail;

/**
 * Class Mail
 *
 * @package Mobicms\Utility
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-12-29
 */
class Mail
{
    public function __construct($from, $fromName, array $to, $subject, $body)
    {
        $message = new ZendMail\Message();
        $message->setEncoding('UTF-8');
        $message->setFrom($from, $fromName);

        foreach ($to as $toAddress => $toName) {
            $message->addTo($toAddress, $toName);
        }

        $message->setSubject($subject);
        $message->setBody($body);

        try {
            $transport = new ZendMail\Transport\Sendmail();
            $transport->send($message);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}
