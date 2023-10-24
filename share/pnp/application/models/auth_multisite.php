<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

class Auth_Multisite_Model
{
    private $htpasswdPath;

    private $serialsPath;

    private $secretPath;

    private $authFile;


    public function __construct($htpasswdPath, $serialsPath, $secretPath, $loginUrl)
    {
        $this->htpasswdPath = $htpasswdPath;
        $this->serialsPath  = $serialsPath;
        $this->secretPath   = $secretPath;
        $this->loginUrl     = $loginUrl;

        // When the auth.serial file exists, use this instead of the htpasswd
        // for validating the cookie. The structure of the file is equal, so
        // the same code can be used.
        if (file_exists($this->serialsPath)) {
            $this->authFile = 'serial';
        } elseif (file_exists($this->htpasswdPath)) {
            $this->authFile = 'htpasswd';
        } else {
            throw new Kohana_exception('error.auth_multisite_missing_htpasswd');
        }

        if (!file_exists($this->secretPath)) {
            $this->redirectToLogin();
        }
    }
    //end __construct()


    private function loadAuthFile($path)
    {
        $creds = [];
        foreach (file($path) as $line) {
            if (strpos($line, ':') !== false) {
                list($username, $secret) = explode(':', $line, 2);
                $creds[$username]        = rtrim($secret);
            }
        }
        return $creds;
    }
    //end loadAuthFile()


    private function loadSecret()
    {
        return trim(file_get_contents($this->secretPath));
    }
    //end loadSecret()


    private function generateHash($username, $now, $user_secret)
    {
        $secret = $this->loadSecret();
        return md5($username . $now . $user_secret . $secret);
    }
    //end generateHash()


    private function checkAuthCookie($cookieName)
    {
        if (!isset($_COOKIE[$cookieName]) || $_COOKIE[$cookieName] == '') {
            throw new Exception();
        }

        $cookie = $_COOKIE[$cookieName];
        if ($cookie[0] == '"') {
            $cookie = trim($cookie, '"');
        }
        list($username, $issueTime, $cookieHash) = explode(':', $cookie, 3);

        if ($this->authFile == 'htpasswd') {
            $users = $this->loadAuthFile($this->htpasswdPath);
        } else {
            $users = $this->loadAuthFile($this->serialsPath);
        }

        if (!isset($users[$username])) {
            throw new Exception();
        }
        $user_secret = $users[$username];

        // Validate the hash
        if ($cookieHash != $this->generateHash($username, $issueTime, $user_secret)) {
            throw new Exception();
        }

        // FIXME: Maybe renew the cookie here too
        return $username;
    }
    //end checkAuthCookie()


    private function checkAuth()
    {
        // Loop all cookies trying to fetch a valid authentication
        // cookie for this installation
        foreach (array_keys($_COOKIE) as $cookieName) {
            if (substr($cookieName, 0, 5) != 'auth_') {
                continue;
            }
            try {
                $name = $this->checkAuthCookie($cookieName);
                return $name;
            } catch (Exception $e) {
            }
        }
        return '';
    }
    //end checkAuth()


    private function redirectToLogin()
    {
        header('Location:' . $this->loginUrl . '?_origtarget=' . $_SERVER['REQUEST_URI']);
    }
    //end redirectToLogin()


    public function check()
    {
        $username = $this->checkAuth();
        if ($username === '') {
            $this->redirectToLogin();
            exit(0);
        }

        return $username;
    }
    //end check()
}
//end class
