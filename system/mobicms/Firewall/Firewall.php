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

namespace Mobicms\Firewall;

/**
 * Class Firewall
 *
 * @package Mobicms\Environment
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.1.0 2015-08-07
 */
class Firewall
{
    /**
     * @var int The time period for calculating number of allowed requests [sec]
     */
    public $calculatingPeriod = 120;

    /**
     * @var int Set the maximum number of allowed requests per time period
     */
    public $requestsLimit = 40;

    /**
     * @var string File name for log the number of requests for each IP
     */
    public $requestsLogFile = 'ip-requests.log';

    /**
     * @var int Interval for writing LOG file
     */
    public $logInterval = 10;

    /**
     * @var string File name for cache of IP requests
     */
    public $requestsCacheFile = 'firewall.cache';

    /**
     * @var string File name for cache of IP black/white list
     */
    public $bwlistCacheFile = 'bw-list.cache';

    /**
     * Start the Firewall
     * Matches the IP with the black/white lists, check for HTTP flood
     *
     * @param int $ip
     * @throws \Exception
     */
    public function run($ip)
    {
        $ip = ip2long($ip);

        try {
            $doCheck = $this->match($ip);
            $count = $this->requestsCount($ip);
            $this->checkFlood($doCheck, $count);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Matches the IP with the black/white lists
     *
     * @param int $ip
     * @return bool
     * @throws \Exception
     */
    private function match($ip)
    {
        $check = true;

        switch ($this->bwListHandler($ip)) {
            case 2:
                // IP is found in the white list
                $check = false;
                break;

            case 1:
                // IP is found in the black list
                http_response_code(403);
                throw new \Exception('Access denied');
        }

        return $check;
    }

    /**
     * Check for HTTP Flood attack
     *
     * @param bool $check
     * @param int  $count
     * @return bool
     * @throws \Exception
     */
    private function checkFlood($check, $count)
    {
        if ($check && $count > $this->requestsLimit) {
            throw new \Exception('You have reached the limit of allowed requests. Please wait a few minutes');
        }

        return true;
    }

    /**
     * Processing the cache of white / black lists of IP
     *
     * @param int $ip
     * @return bool|int 0 = not found, 1 = found in the black list, 2 = found in the white list
     */
    private function bwListHandler($ip)
    {
        $file = CACHE_PATH . $this->bwlistCacheFile;

        if (file_exists($file)) {
            $in = fopen($file, 'r');

            while ($block = fread($in, 18)) {
                $arr = unpack('dip/dip_upto/Smode', $block);

                if ($ip >= $arr['ip'] && $ip <= $arr['ip_upto']) {
                    fclose($in);

                    return $arr['mode'];
                }
            }
            fclose($in);
        }

        return false;
    }

    /**
     * Counting the number of queries from IP addresses
     *
     * @param int $ip
     * @return int
     * @throws \Exception
     */
    private function requestsCount($ip)
    {
        $count = 1;
        $data = $requests = [];
        $time = time() - $this->calculatingPeriod;
        $handler = $this->readCache();

        while ($block = fread($handler, 8)) {
            $arr = unpack('Lip/Ltime', $block);

            if ($arr['time'] < $time) {
                continue;
            } elseif ($arr['ip'] == $ip) {
                $count++;
            }

            $data[] = $arr;
            $requests[] = $arr['ip'];
        }

        $requests[] = $ip;
        $this->writeCache($handler, $data, $ip);
        $this->log($requests);

        return $count;
    }

    /**
     * Read IP requests cache
     *
     * @return resource
     */
    private function readCache()
    {
        $handler = fopen(CACHE_PATH . $this->requestsCacheFile, 'c+');

        if (!flock($handler, LOCK_EX)) {
            throw new \RuntimeException('firewall cache file is not writable');
        }

        return $handler;
    }

    /**
     * Write IP requests cache
     *
     * @param resource $handler
     * @param array    $data
     * @param int      $ip
     */
    private function writeCache($handler, $data, $ip)
    {
        fseek($handler, 0);
        ftruncate($handler, 0);

        foreach ($data as $val) {
            fwrite($handler, pack('LL', $val['ip'], $val['time']));
        }

        fwrite($handler, pack('LL', $ip, time()));
        fclose($handler);
    }

    /**
     * LOG the number of calls for each IP
     *
     * @param array $requests
     */
    private function log(array $requests)
    {
        $file = LOG_PATH . $this->requestsLogFile;

        if (!is_file($file)
            || filemtime($file) < time() - $this->logInterval
        ) {
            $this->writeLog($requests, $file);
        }
    }

    /**
     * Write LOG file
     *
     * @param array $requests
     */
    private function writeLog(array $requests, $file)
    {
        $out = 'Date: GMT ' . date('d.m.Y H:i:s') . PHP_EOL;
        $out .= '-----------------------------' . PHP_EOL;

        $ip_list = array_count_values($requests);
        arsort($ip_list);

        foreach ($ip_list as $key => $val) {
            $out .= $val . '::' . long2ip($key) . PHP_EOL;
        }

        file_put_contents($file, $out);
    }
}
