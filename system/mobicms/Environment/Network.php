<?php

namespace Mobicms\Environment;

use Psr\Container\ContainerInterface;

/**
 * Class Network
 *
 * @package Mobicms\Environment
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 */
class Network
{
    private $ip;
    private $userAgent;
    private $trustedProxies = [];

    public function __invoke(ContainerInterface $container)
    {
        return $this;
    }

    /**
     * Returns the client IP address
     *
     * @return string
     */
    public function getClientIp()
    {
        if (null === $this->ip) {
            $this->ip = $this->getClientIps();
        }

        return $this->ip[0];
    }

    /**
     * Check whether the IP address of the proxy server
     *
     * @return bool
     */
    public function isProxyIp()
    {
        if (null === $this->ip) {
            $this->ip = $this->getClientIps();
        }

        return count($this->ip) > 1 ? true : false;
    }

    /**
     * Returns the User Agent
     *
     * @return string
     */
    public function getUserAgent()
    {
        if ($this->userAgent !== null) {
            return $this->userAgent;
        } elseif (isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) && strlen(trim($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])) > 5) {
            return $this->userAgent = 'Opera Mini: ' . mb_substr(filter_var($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'],
                    FILTER_SANITIZE_SPECIAL_CHARS), 0, 150);
        } elseif (isset($_SERVER['HTTP_USER_AGENT'])) {
            return $this->userAgent = mb_substr(filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_SPECIAL_CHARS),
                0, 150);
        } else {
            return $this->userAgent = 'Not Recognised';
        }
    }

    /**
     * Returns the client IP addresses.
     *
     * In the returned array the most trusted IP address is first, and the
     * least trusted one last. The "real" client IP address is the last one,
     * but this is also the least trusted one. Trusted proxies are stripped.
     * Use this method carefully; you should use getClientIp() instead.
     *
     * @return array The client IP addresses
     */
    public function getClientIps()
    {
        $clientIps = [];
        $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);

        if (!$this->isFromTrustedProxy()) {
            return [$ip];
        }

        if (filter_has_var(INPUT_SERVER, 'FORWARDED')) {
            $forwardedHeader = filter_input(INPUT_SERVER, 'FORWARDED');
            preg_match_all('{(for)=("?\[?)([a-z0-9\.:_\-/]*)}', $forwardedHeader, $matches);
            $clientIps = $matches[3];
        } elseif (filter_has_var(INPUT_SERVER, 'X_FORWARDED_FOR')) {
            $clientIps = array_map('trim', explode(',', filter_input(INPUT_SERVER, 'X_FORWARDED_FOR')));
        }

        $clientIps[] = $ip; // Complete the IP chain with the IP the request actually came from
        $ip = $clientIps[0]; // Fallback to this when the client IP falls into the range of trusted proxies

        foreach ($clientIps as $key => $clientIp) {
            // Remove port (unfortunately, it does happen)
            if (preg_match('{((?:\d+\.){3}\d+)\:\d+}', $clientIp, $match)) {
                $clientIps[$key] = $clientIp = $match[1];
            }

            if ($this->checkIp($clientIp, $this->trustedProxies)) {
                unset($clientIps[$key]);
            }
        }

        // Now the IP chain contains only untrusted proxies and the client IP
        return $clientIps ? array_reverse($clientIps) : [$ip];
    }

    /**
     * Gets the list of trusted proxies.
     *
     * @return array An array of trusted proxies.
     */
    public function getTrustedProxies()
    {
        return $this->trustedProxies;
    }

    /**
     * Checks if an IPv4 or IPv6 address is contained in the list of given IPs or subnets.
     *
     * @param string       $requestIp IP to check
     * @param string|array $ips       List of IPs or subnets (can be a string if only a single one)
     *
     * @return bool Whether the IP is valid
     */
    public function checkIp($requestIp, $ips)
    {
        if (!is_array($ips)) {
            $ips = [$ips];
        }

        $method = substr_count($requestIp, ':') > 1 ? 'checkIp6' : 'checkIp4';

        foreach ($ips as $ip) {
            if ($this->$method($requestIp, $ip)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compares two IPv4 addresses.
     * In case a subnet is given, it checks if it contains the request IP.
     *
     * @param string $requestIp IPv4 address to check
     * @param string $ip        IPv4 address or subnet in CIDR notation
     *
     * @return bool Whether the request IP matches the IP, or whether the request IP is within the CIDR subnet.
     */
    public function checkIp4($requestIp, $ip)
    {
        if (false !== strpos($ip, '/')) {
            list($address, $netmask) = explode('/', $ip, 2);

            if ($netmask === '0') {
                // Ensure IP is valid - using ip2long below implicitly validates, but we need to do it manually here
                return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
            }

            if ($netmask < 0 || $netmask > 32) {
                return false;
            }
        } else {
            $address = $ip;
            $netmask = 32;
        }

        return 0 === substr_compare(sprintf('%032b', ip2long($requestIp)), sprintf('%032b', ip2long($address)), 0,
                $netmask);
    }

    /**
     * Compares two IPv6 addresses.
     * In case a subnet is given, it checks if it contains the request IP.
     *
     * @author David Soria Parra <dsp at php dot net>
     *
     * @see    https://github.com/dsp/v6tools
     *
     * @param string $requestIp IPv6 address to check
     * @param string $ip        IPv6 address or subnet in CIDR notation
     *
     * @return bool Whether the IP is valid
     *
     * @throws \RuntimeException When IPV6 support is not enabled
     */
    public function checkIp6($requestIp, $ip)
    {
        if (!((extension_loaded('sockets') && defined('AF_INET6')) || inet_pton('::1'))) {
            throw new \RuntimeException('Unable to check Ipv6. Check that PHP was not compiled with option "disable-ipv6".');
        }

        if (false !== strpos($ip, '/')) {
            list($address, $netmask) = explode('/', $ip, 2);

            if ($netmask < 1 || $netmask > 128) {
                return false;
            }
        } else {
            $address = $ip;
            $netmask = 128;
        }

        $bytesAddr = unpack('n*', inet_pton($address));
        $bytesTest = unpack('n*', inet_pton($requestIp));

        for ($i = 1, $ceil = ceil($netmask / 16); $i <= $ceil; ++$i) {
            $left = $netmask - 16 * ($i - 1);
            $left = ($left <= 16) ? $left : 16;
            $mask = ~(0xffff >> $left) & 0xffff;
            if (($bytesAddr[$i] & $mask) != ($bytesTest[$i] & $mask)) {
                return false;
            }
        }

        return true;
    }

    private function isFromTrustedProxy()
    {
        return !empty($this->trustedProxies) && $this->checkIp(filter_input(INPUT_SERVER, 'REMOTE_ADDR'),
                $this->trustedProxies);
    }
}
