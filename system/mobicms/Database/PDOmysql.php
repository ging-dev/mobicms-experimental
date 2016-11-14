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

namespace Mobicms\Database;

/**
 * Class PDOmysql
 *
 * @package Mobicms\Database
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.2.0.0 2015-08-09
 */
class PDOmysql extends \PDO
{
    public function __construct($dbHost, $dbName, $dbUser, $dbPass)
    {
        try {
            parent::__construct('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPass,
                [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                    \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY
                ]
            );
        } catch (\PDOException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}
