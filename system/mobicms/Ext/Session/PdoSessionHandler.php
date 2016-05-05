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

namespace Mobicms\Ext\Session;

use Mobicms\Database\PDOmysql;
use Mobicms\Environment\Network;
use Mobicms\Routing\Router;
use Zend\Session\SaveHandler\SaveHandlerInterface;

/**
 * Class PdoSessionHandler
 *
 * @package Mobicms\Ext\Session
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.2.0.0 2015-07-16
 */
class PdoSessionHandler implements SaveHandlerInterface
{
    /**
     * @var \PDO
     */
    private $db;

    /**
     * @var Network
     */
    private $network;

    private $router;

    private $transaction = false;
    private $doGc = false;
    private $views = 1;
    private $movings = 1;

    /**
     * @var int session.gc_maxlifetime
     */
    public $lifetime;

    public function __construct(PDOmysql $db, Network $network, Router $router)
    {
        $this->db = $db;
        $this->router = $router;
        $this->network = $network;
        $this->lifetime = (int)ini_get('session.gc_maxlifetime');
    }

    /**
     * Open Session
     *
     * @param string $savePath
     * @param string $sessionId
     * @return bool
     */
    public function open($savePath, $sessionId)
    {
        return true;
    }

    /**
     * Read session data
     *
     * @param string $sessionId
     * @return string
     */
    public function read($sessionId)
    {
        try {
            $this->db->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
            $this->db->beginTransaction();
            $this->transaction = true;

            $stmt = $this->db->prepare('SELECT * FROM `sessions` WHERE `id` = :id FOR UPDATE');
            $stmt->bindParam(':id', $sessionId, \PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->rowCount() ? $this->getResult($stmt) : $this->insertRecord($stmt, $sessionId);
        } catch (\PDOException $e) {
            $this->rollback();

            throw $e;
        }
    }

    /**
     * Garbage collector
     *
     * @param int $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        $this->doGc = true;

        return true;
    }

    /**
     * Destroy Session
     *
     * @param string $sessionId
     * @return bool
     */
    public function destroy($sessionId)
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM `sessions` WHERE `id` = :id');
            $stmt->bindParam(':id', $sessionId, \PDO::PARAM_STR);
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->rollback();
            throw $e;
        }

        return true;
    }

    /**
     * Write session data
     *
     * @param string $sessionId
     * @param string $data
     * @return bool
     */
    public function write($sessionId, $data)
    {
        try {
            $stmt = $this->db->prepare(
                'UPDATE `sessions` SET
                `data`      = :data,
                `timestamp` = :time,
                `ip`        = :ip,
                `userAgent` = :ua,
                `place`     = :place,
                `views`     = :views,
                `movings`   = :movings,
                `userId`    = :uid
                WHERE `id`  = :id'
            );

            $stmt->bindParam(':id', $sessionId, \PDO::PARAM_STR);
            $stmt->bindParam(':data', $data, \PDO::PARAM_LOB);
            $stmt->bindValue(':time', time(), \PDO::PARAM_INT);
            $stmt->bindValue(':ip', $this->network->getClientIp(), \PDO::PARAM_STR);
            $stmt->bindValue(':ua', $this->network->getUserAgent(), \PDO::PARAM_STR);
            $stmt->bindValue(':place', $this->router->getCurrentModule(), \PDO::PARAM_STR);
            $stmt->bindValue(':views', $this->views, \PDO::PARAM_INT);
            $stmt->bindValue(':movings', $this->movings, \PDO::PARAM_INT);
            $stmt->bindValue(':uid', \App::getInstance()->user()->get()->id, \PDO::PARAM_INT);

            return $stmt->execute();
        } catch (\PDOException $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * Close the session
     *
     * @return bool
     */
    public function close()
    {
        $this->commit();
        $this->garbage();

        return true;
    }

    private function commit()
    {
        if ($this->transaction) {
            try {
                $this->db->commit();
                $this->transaction = false;
            } catch (\PDOException $e) {
                $this->rollback();
                throw $e;
            }
        }
    }

    /**
     * Rollback a transaction.
     */
    private function rollback()
    {
        if ($this->transaction) {
            $this->db->rollback();
            $this->transaction = false;
        }
    }

    /**
     * Get session data
     *
     * @param \PDOStatement $stmt
     * @return string
     */
    private function getResult(\PDOStatement $stmt)
    {
        $result = $stmt->fetch();

        // If the session is expired
        if ($result['timestamp'] < time() - $this->lifetime) {
            return '';
        }

        $this->countViews($result['views'], $result['timestamp']);
        $this->countMovings($result['movings'], $result['place'], $result['timestamp']);

        return $result['data'];
    }

    /**
     * Insert new session record
     *
     * @param \PDOStatement $stmt
     * @param string        $sessionId
     * @return string
     * @throws \HttpRuntimeException
     */
    private function insertRecord(\PDOStatement $stmt, $sessionId)
    {
        try {
            $insertStmt = $this->db->prepare('INSERT
                INTO `sessions` (`id`, `data`, `timestamp`, `ip`, `userAgent`, `place`, `views`, `movings`)
                VALUES (:id, :data, :time, :ip, :ua, :place, 1, 1)'
            );
            $insertStmt->bindParam(':id', $sessionId, \PDO::PARAM_STR);
            $insertStmt->bindValue(':data', '', \PDO::PARAM_LOB);
            $insertStmt->bindValue(':time', time(), \PDO::PARAM_INT);
            $insertStmt->bindValue(':ip', $this->network->getClientIp(), \PDO::PARAM_STR);
            $insertStmt->bindValue(':ua', $this->network->getUserAgent(), \PDO::PARAM_STR);
            $insertStmt->bindValue(':place', '', \PDO::PARAM_STR);
            $insertStmt->execute();
        } catch (\PDOException $e) {
            $this->catchDuplicateKeyError($e, $stmt);
        }

        return '';
    }

    /**
     * Catch duplicate key error
     *
     * @param \PDOException $e
     * @param \PDOStatement $stmt
     * @return string
     */
    private function catchDuplicateKeyError(\PDOException $e, \PDOStatement $stmt)
    {
        if (0 === strpos($e->getCode(), '23')) {
            $stmt->execute();

            return $stmt->rowCount() ? $this->getResult($stmt) : '';
        }

        throw $e;
    }

    /**
     * Garbage collector
     */
    private function garbage()
    {
        if ($this->doGc) {
            $this->doGc = false;
            $stmt = $this->db->prepare('DELETE FROM `sessions` WHERE `timestamp` < :time');
            $stmt->bindValue(':time', time() - $this->lifetime, \PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    private function countViews($views, $timestamp)
    {
        if ($timestamp > time() - 300) {
            $this->views = $views + 1;
        }
    }

    private function countMovings($movings, $place, $timestamp)
    {
        if ($timestamp > time() - 300) {
            $this->movings = $place != $this->router->getCurrentModule() ? $movings + 1 : $movings;
        }
    }
}
