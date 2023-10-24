<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

/**
 * Cookie helper class.
 *
 * $Id: cookie.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class cookie_Core
{
    /**
     * Sets a cookie with the given parameters.
     *
     * @param   string   cookie name or array of config options
     * @param   string   cookie value
     * @param   integer  number of seconds before the cookie expires
     * @param   string   URL path to allow
     * @param   string   URL domain to allow
     * @param   boolean  HTTPS only
     * @param   boolean  HTTP only (requires PHP 5.2 or higher)
     * @return  boolean
     */
    public static function set($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        if (headers_sent()) {
            return false;
        }

        // If the name param is an array, we import it
        is_array($name) and extract($name, EXTR_OVERWRITE);

        // Fetch default options
        $config = Kohana::config('cookie');

        foreach (array('value', 'expire', 'domain', 'path', 'secure', 'httponly') as $item) {
            if ($$item === null and isset($config[$item])) {
                $$item = $config[$item];
            }
        }

        // Expiration timestamp
        $expire = ($expire == 0) ? 0 : time() + (int) $expire;

        return setcookie($name, $value, $expire, $path, $domain, $secure);
    }

    /**
     * Fetch a cookie value, using the Input library.
     *
     * @param   string   cookie name
     * @param   mixed    default value
     * @param   boolean  use XSS cleaning on the value
     * @return  string
     */
    public static function get($name, $default = null, $xss_clean = false)
    {
        return Input::instance()->cookie($name, $default, $xss_clean);
    }

    /**
     * Nullify and unset a cookie.
     *
     * @param   string   cookie name
     * @param   string   URL path
     * @param   string   URL domain
     * @return  boolean
     */
    public static function delete($name, $path = null, $domain = null)
    {
        if (! isset($_COOKIE[$name])) {
            return false;
        }

        // Delete the cookie from globals
        unset($_COOKIE[$name]);

        // Sets the cookie value to an empty string, and the expiration to 24 hours ago
        return cookie::set($name, '', -86400, $path, $domain, false, false);
    }
}
// End cookie
