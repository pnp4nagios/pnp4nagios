<?php

// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
/**
 * @package  Cache:Memcache
 *
 * memcache server configuration.
 */
$config['servers'] = array
(
    array
    (
        'host' => '127.0.0.1',
        'port' => 11211,
        'persistent' => false,
    )
);

/**
 * Enable cache data compression.
 */
$config['compression'] = false;
