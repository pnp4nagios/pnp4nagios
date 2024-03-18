<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps


/**
 * Xcache Cache driver.
 *
 * $Id: Xcache.php 4046 2009-03-05 19:23:29Z Shadowhand $
 *
 * @package    Cache
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Cache_Xcache_Driver implements Cache_Driver
{
    public function __construct()
    {
        if (! extension_loaded('xcache')) {
            throw new Kohana_Exception('cache.extension_not_loaded', 'xcache');
        }
    }

    public function get($id)
    {
        if (xcache_isset($id)) {
            return xcache_get($id);
        }

        return null;
    }

    public function set($id, $data, array $tags = null, $lifetime)
    {
        if (! empty($tags)) {
            Kohana::log('error', 'Cache: tags are unsupported by the Xcache driver');
        }

        return xcache_set($id, $data, $lifetime);
    }

    public function find($tag)
    {
        Kohana::log('error', 'Cache: tags are unsupported by the Xcache driver');
        return false;
    }

    public function delete($id, $tag = false)
    {
        if ($tag !== false) {
            Kohana::log('error', 'Cache: tags are unsupported by the Xcache driver');
            return true;
        } elseif ($id !== true) {
            if (xcache_isset($id)) {
                return xcache_unset($id);
            }

            return false;
        } else {
            // Do the login
            $this->auth();
            $result = true;
            for ($i = 0, $max = xcache_count(XC_TYPE_VAR); $i < $max; $i++) {
                if (xcache_clear_cache(XC_TYPE_VAR, $i) !== null) {
                    $result = false;
                    break;
                }
            }

            // Undo the login
            $this->auth(true);
            return $result;
        }

        return true;
    }

    public function delete_expired()
    {
        return true;
    }

    private function auth($reverse = false)
    {
        static $backup = array();

        $keys = array('PHP_AUTH_USER', 'PHP_AUTH_PW');

        foreach ($keys as $key) {
            if ($reverse) {
                if (isset($backup[$key])) {
                    $_SERVER[$key] = $backup[$key];
                    unset($backup[$key]);
                } else {
                    unset($_SERVER[$key]);
                }
            } else {
                $value = getenv($key);

                if (! empty($value)) {
                    $backup[$key] = $value;
                }

                $_SERVER[$key] = Kohana::config('cache_xcache.' . $key);
            }
        }
    }
}
// End Cache Xcache Driver
