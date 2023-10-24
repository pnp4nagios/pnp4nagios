<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

/**
 * Retrieves the PNP config files
 */
class Auth_Model extends System_Model
{
    public $SOCKET = null;

    public $socketPath = null;

    public $socketDOMAIN = null;

    public $socketTYPE = null;

    public $socketHOST = null;

    public $socketPORT = 0;

    public $socketPROTO = null;

    public $ERR_TXT = '';

    public $AUTH_ENABLED = false;

    public $REMOTE_USER = null;

    public $config = '';


    public function __construct()
    {
        $this->config = new Config_Model();
        $this->config->read_config();
        if ($this->config->conf['auth_enabled'] == 1) {
            $this->AUTH_ENABLED = true;
            $this->socketPath   = $this->config->conf['livestatus_socket'];
        }

        // Try to get the login of the user
        if (isset($_SERVER['REMOTE_USER'])) {
            $this->REMOTE_USER = $_SERVER['REMOTE_USER'];
        }
        if ($this->REMOTE_USER === null && $this->config->conf['auth_multisite_enabled'] == 1) {
            $MSAUTH            = new Auth_Multisite_Model(
                $this->config->conf['auth_multisite_htpasswd'],
                $this->config->conf['auth_multisite_serials'],
                $this->config->conf['auth_multisite_secret'],
                $this->config->conf['auth_multisite_login_url']
            );
            $this->REMOTE_USER = $MSAUTH->check();
            if ($this->REMOTE_USER !== null) {
                return;
            }
        }

        if ($this->AUTH_ENABLED === true && $this->REMOTE_USER === null) {
            throw new Kohana_exception('error.remote_user_missing');
        }
    }
    //end __construct()


    public function __destruct()
    {
        if ($this->SOCKET !== null) {
            socket_close($this->SOCKET);
            $this->SOCKET = null;
        }
    }
    //end __destruct()


    public function connect()
    {
        $this->getSocketDetails($this->socketPath);
        $this->SOCKET = socket_create($this->socketDOMAIN, $this->socketTYPE, $this->socketPROTO);
        if ($this->SOCKET === false) {
            throw new Kohana_exception('error.livestatus_socket_error', socket_strerror(socket_last_error($this->SOCKET)), $this->socketPath);
        }
        if ($this->socketDOMAIN === AF_UNIX) {
            $result = @socket_connect($this->SOCKET, $this->socketPATH);
        } else {
            $result = @socket_connect($this->SOCKET, $this->socketHOST, $this->socketPORT);
        }
        if (!$result) {
            throw new Kohana_exception('error.livestatus_socket_error', socket_strerror(socket_last_error($this->SOCKET)), $this->socketPath);
        }
    }
    //end connect()


    private function queryLivestatus($query)
    {
        if ($this->SOCKET === null) {
            $this->connect();
        }
        @socket_write($this->SOCKET, $query . "\nOutputFormat: json\n\n");
        // Read 16 bytes to get the status code and body size
        $read = @socket_read($this->SOCKET, 2048);
        if (!$read) {
            throw new Kohana_exception('error.livestatus_socket_error', socket_strerror(socket_last_error($this->SOCKET)));
        }
        // print Kohana::debug("read ". $read);
        // Catch problem while reading
        if ($read === false) {
            throw new Kohana_exception('error.livestatus_socket_error', socket_strerror(socket_last_error($this->SOCKET)));
        }

        // Decode the json response
        $obj = json_decode(utf8_encode($read));
        socket_close($this->SOCKET);
        $this->SOCKET = null;
        return $obj;
    }
    //end queryLivestatus()


    public function is_authorized($host = false, $service = null)
    {
        if ($this->AUTH_ENABLED === false) {
            return true;
        }

        if ($host == 'pnp-internal') {
            return true;
        }

        if ($service === null || $service == '_HOST_' || $service == 'Host Perfdata') {
            $users = explode(',', $this->config->conf['allowed_for_all_hosts']);
            if (in_array($this->REMOTE_USER, $users)) {
                return true;
            }
            $query  = "GET hosts\nColumns: name\nFilter: name = $host\nAuthUser: " . $this->REMOTE_USER;
            $result = $this->queryLivestatus($query);
        } else {
            $users = explode(',', $this->config->conf['allowed_for_all_services']);
            if (in_array($this->REMOTE_USER, $users)) {
                return true;
            }
            $query  = "GET services\nColumns: host_name description\nFilter: host_name = $host\nFilter: description = $service\nAuthUser: " . $this->REMOTE_USER;
            $result = $this->queryLivestatus($query);
        }

        return (!empty($result));
    }
    //end is_authorized()


    public function getSocketDetails($string = false)
    {
        if (preg_match('/^unix:(.*)$/', $string, $match)) {
            $this->socketDOMAIN = AF_UNIX;
            $this->socketTYPE   = SOCK_STREAM;
            $this->socketPATH   = $match[1];
            $this->socketPROTO  = 0;
            return;
        }
        if (preg_match('/^tcp:([a-zA-Z0-9-\.]+):([0-9]+)$/', $string, $match)) {
            $this->socketDOMAIN = AF_INET;
            $this->socketTYPE   = SOCK_STREAM;
            $this->socketHOST   = $match[1];
            $this->socketPORT   = $match[2];
            $this->socketPROTO  = SOL_TCP;
            return;
        }
        // Fallback
        if (preg_match('/^\/.*$/', $string, $match)) {
            $this->socketDOMAIN = AF_UNIX;
            $this->socketTYPE   = SOCK_STREAM;
            $this->socketPATH   = $string;
            $this->socketPROTO  = 0;
            return;
        }
        return false;
    }
    //end getSocketDetails()
}
//end class
