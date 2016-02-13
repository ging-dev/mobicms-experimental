<?php

/**
 * phpWhois basic class
 *
 * This is the basic client class
 */
class WhoisClient
{
    /** @var boolean Is recursion allowed? */
    public $gtldRecurse = false;

    /** @var int Default WHOIS port */
    public $port = 43;

    /** @var int Maximum number of retries on connection failure */
    public $retry = 0;

    /** @var int Time to wait between retries */
    public $sleep = 2;

    /** @var int Read buffer size (0 == char by char) */
    public $buffer = 1024;

    /** @var int Communications timeout */
    public $stimeout = 10;

    /** @var string[] Array to contain all query publiciables */
    public $query = [];

    /** @var string Current release of the package */
    public $codeVersion = null;

    /** @var string Full code and data version string (e.g. 'Whois2.php v3.01:16') */
    public $version;

    /**
     * Constructor function
     */
    public function __construct()
    {
        $this->codeVersion = '5';
        // Set version
        $this->version = sprintf("phpWhois v%s", $this->codeVersion);
    }

    /**
     * Perform lookup
     *
     * @return array Raw response as array separated by "\n"
     */
    public function getRawData($query)
    {
        $this->query['query'] = $query;

        // Get args
        if (strpos($this->query['server'], '?')) {
            $parts = explode('?', $this->query['server']);
            $this->query['server'] = trim($parts[0]);
            $query_args = trim($parts[1]);

            // replace substitution parameters
            $query_args = str_replace('{query}', $query, $query_args);
            $query_args = str_replace('{version}', 'phpWhois' . $this->codeVersion, $query_args);

            $iptools = new IpTools;
            if (strpos($query_args, '{ip}') !== false) {
                $query_args = str_replace('{ip}', $iptools->getClientIp(), $query_args);
            }

            if (strpos($query_args, '{hname}') !== false) {
                $query_args = str_replace('{hname}', gethostbyaddr($iptools->getClientIp()), $query_args);
            }
        } else {
            if (empty($this->query['args'])) {
                $query_args = $query;
            } else {
                $query_args = $this->query['args'];
            }
        }

        $this->query['args'] = $query_args;

        if (substr($this->query['server'], 0, 9) == 'rwhois://') {
            $this->query['server'] = substr($this->query['server'], 9);
        }

        if (substr($this->query['server'], 0, 8) == 'whois://') {
            $this->query['server'] = substr($this->query['server'], 8);
        }

        // Get port
        if (strpos($this->query['server'], ':')) {
            $parts = explode(':', $this->query['server']);
            $this->query['server'] = trim($parts[0]);
            $this->query['server_port'] = trim($parts[1]);
        } else {
            $this->query['server_port'] = $this->port;
        }

        // Connect to whois server, or return if failed
        $ptr = $this->connect();

        if ($ptr === false) {
            $this->query['status'] = 'error';
            $this->query['errstr'][] = 'Connect failed to: ' . $this->query['server'];

            return [];
        }

        stream_set_timeout($ptr, $this->stimeout);
        stream_set_blocking($ptr, 0);

        // Send query
        fputs($ptr, trim($query_args) . "\r\n");

        // Prepare to receive result
        $raw = '';
        $start = time();
        $null = null;
        $r = [$ptr];

        while (!feof($ptr)) {
            if (!empty($r)) {
                if (stream_select($r, $null, $null, $this->stimeout)) {
                    $raw .= fgets($ptr, $this->buffer);
                }
            }

            if (time() - $start > $this->stimeout) {
                $this->query['status'] = 'error';
                $this->query['errstr'][] = 'Timeout reading from ' . $this->query['server'];

                return [];
            }
        }

        $output = explode("\n", $raw);

        // Drop empty last line (if it's empty! - saleck)
        if (empty($output[count($output) - 1])) {
            unset($output[count($output) - 1]);
        }

        return $output;
    }

    /**
     * Perform lookup
     *
     * @return array The *rawdata* element contains an
     * array of lines gathered from the whois query. If a top level domain
     * handler class was found for the domain, other elements will have been
     * populated too.
     */

    public function getData($query = '', $deep_whois = true)
    {
        // If domain to query passed in, use it, otherwise use domain from initialisation
        $query = !empty($query) ? $query : $this->query['query'];

        $output = $this->getRawData($query);

        // Create result and set 'rawdata'
        $result = ['rawdata' => $output];

        // Return now on error
        if (empty($output)) {
            return $result;
        }

        // If we have a handler, post-process it with it
        if (isset($this->query['handler'])) {
            // Keep server list
            unset($result['regyinfo']['servers']);

            // Process data
            $result = $this->process($result, $deep_whois);

            // Handler may forget to set rawdata
            if (!isset($result['rawdata'])) {
                $result['rawdata'] = $output;
            }
        }

        // Add error information if any
        if (isset($this->query['errstr'])) {
            $result['errstr'] = $this->query['errstr'];
        }

        return ($result);
    }

    /**
     * Open a socket to the whois server.
     *
     * @param string|null $server Server address to connect. If null, $this->query['server'] will be used
     *
     * @return resource|false Returns a socket connection pointer on success, or -1 on failure
     */
    public function connect($server = null)
    {
        if (empty($server)) {
            $server = $this->query['server'];
        }

        /** @TODO Throw an exception here */
        if (empty($server)) {
            return false;
        }

        $port = $this->query['server_port'];

        // Enter connection attempt loop
        $retry = 0;

        while ($retry <= $this->retry) {
            // Set query status
            $this->query['status'] = 'ready';

            // Connect to whois port
            $ptr = @fsockopen($server, $port, $errno, $errstr, $this->stimeout);

            if ($ptr > 0) {
                $this->query['status'] = 'ok';

                return $ptr;
            }

            // Failed this attempt
            $this->query['status'] = 'error';
            $this->query['error'][] = $errstr;
            $retry++;

            // Sleep before retrying
            sleep($this->sleep);
        }

        // If we get this far, it hasn't worked
        return false;
    }

    /**
     * Post-process result with handler class.
     *
     * @return array On success, returns the result from the handler.
     * On failure, returns passed result unaltered.
     */

    public function process(&$result, $deep_whois = true)
    {
        $handler_name = str_replace('.', '_', $this->query['handler']);

        // If the handler has not already been included somehow, include it now
        $HANDLER_FLAG = sprintf("__%s_HANDLER__", strtoupper($handler_name));

        if (!defined($HANDLER_FLAG)) {
            include($this->query['file']);
        }

        // If the handler has still not been included, append to query errors list and return
        if (!defined($HANDLER_FLAG)) {
            $this->query['errstr'][] = "Can't find $handler_name handler: " . $this->query['file'];

            return $result;
        }

        if (!$this->gtldRecurse && $this->query['file'] == 'whois.gtld.php') {
            return $result;
        }

        // Pass result to handler
        $object = $handler_name . '_handler';

        $handler = new $object('');

        // If handler returned an error, append it to the query errors list
        if (isset($handler->query['errstr'])) {
            $this->query['errstr'][] = $handler->query['errstr'];
        }

        $handler->deepWhois = $deep_whois;

        // Process
        $res = $handler->parse($result, $this->query['query']);

        // Return the result
        return $res;
    }

    /**
     * Merge results
     *
     * @param array $a1
     * @param array $a2
     *
     * @return array
     */
    public function mergeResults($a1, $a2)
    {

        reset($a2);

        while (list($key, $val) = each($a2)) {
            if (isset($a1[$key])) {
                if (is_array($val)) {
                    if ($key != 'nserver') {
                        $a1[$key] = $this->mergeResults($a1[$key], $val);
                    }
                } else {
                    $val = trim($val);
                    if ($val != '') {
                        $a1[$key] = $val;
                    }
                }
            } else {
                $a1[$key] = $val;
            }
        }

        return $a1;
    }
}
