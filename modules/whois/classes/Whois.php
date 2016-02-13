<?php

/**
 * phpWhois main class
 *
 * This class supposed to be instantiated for using the phpWhois library
 */
class Whois extends WhoisClient
{
    /** @var string Network Solutions registry server */
    public $nsiRegistry = 'whois.nsiregistry.net';

    public function lookup($ip)
    {
        $this->query['server'] = 'whois.arin.net';
        $this->query['args'] = "n $ip";
        $this->query['file'] = 'whois.ip.php';
        $this->query['handler'] = 'ip';
        $this->query['host_ip'] = $ip;
        $this->query['query'] = $ip;
        $this->query['tld'] = 'ip';
        $this->query['host_name'] = @gethostbyaddr($ip);

        return $this->getData('', true);
    }
}
