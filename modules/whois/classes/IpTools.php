<?php

/**
 * Utilities for parsing ip addresses
 */
class IpTools
{
    /**
     * Check if ip address is valid
     *
     * @param string  $ip     IP address for validation
     * @param string  $type   Type of ip address. Possible value are: any, ipv4, ipv6
     * @param boolean $strict If true - fail validation on reserved and private ip ranges
     *
     * @return boolean True if ip is valid. False otherwise
     */
    public function validIp($ip, $type = 'any', $strict = true)
    {
        switch ($type) {
            case 'any':
                return $this->validIpv4($ip, $strict) || $this->validIpv6($ip, $strict);
                break;
            case 'ipv4':
                return $this->validIpv4($ip, $strict);
                break;
            case 'ipv6':
                return $this->validIpv6($ip, $strict);
                break;
        }

        return false;
    }

    /**
     * Check if given IP is valid ipv4 address and doesn't belong to private and
     * reserved ranges
     *
     * @param string  $ip     Ip address
     * @param boolean $strict If true - fail validation on reserved and private ip ranges
     *
     * @return boolean
     */
    public function validIpv4($ip, $strict = true)
    {
        $flags = FILTER_FLAG_IPV4;
        if ($strict) {
            $flags = FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, ['flags' => $flags]) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Check if given IP is valid ipv6 address and doesn't belong to private ranges
     *
     * @param string  $ip     Ip address
     * @param boolean $strict If true - fail validation on reserved and private ip ranges
     *
     * @return boolean
     */
    public function validIpv6($ip, $strict = true)
    {
        $flags = FILTER_FLAG_IPV6;
        if ($strict) {
            $flags = FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, ['flags' => $flags]) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Try to get real IP from client web request
     *
     * @return string
     */
    public function getClientIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && $this->validIp($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            foreach (explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']) as $ip) {
                if ($this->validIp(trim($ip))) {
                    return trim($ip);
                }
            }
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED']) && $this->validIp($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        }

        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && $this->validIp($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }

        if (!empty($_SERVER['HTTP_FORWARDED']) && $this->validIp($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Convert CIDR to net range
     *
     * @TODO provide example
     *
     * @param string $net
     * @return string
     */
    public function cidrConv($net)
    {
        $start = strtok($net, '/');
        $n = 3 - substr_count($net, '.');

        if ($n > 0) {
            for ($i = $n; $i > 0; $i--) {
                $start .= '.0';
            }
        }

        $bits1 = str_pad(decbin(ip2long($start)), 32, '0', 'STR_PAD_LEFT');
        $net = pow(2, (32 - substr(strstr($net, '/'), 1))) - 1;
        $bits2 = str_pad(decbin($net), 32, '0', 'STR_PAD_LEFT');
        $final = '';

        for ($i = 0; $i < 32; $i++) {
            if ($bits1[$i] == $bits2[$i]) {
                $final .= $bits1[$i];
            }
            if ($bits1[$i] == 1 and $bits2[$i] == 0) {
                $final .= $bits1[$i];
            }
            if ($bits1[$i] == 0 and $bits2[$i] == 1) {
                $final .= $bits2[$i];
            }
        }

        return $start . " - " . long2ip(bindec($final));
    }
}
