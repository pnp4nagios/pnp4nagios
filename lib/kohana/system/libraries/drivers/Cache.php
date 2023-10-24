<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps


/**
 * Cache driver interface.
 *
 * $Id: Cache.php 4046 2009-03-05 19:23:29Z Shadowhand $
 *
 * @package    Cache
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
interface Cache_Driver
{
    /**
     * Set a cache item.
     */
    public function set($id, $data, array $tags = null, $lifetime);

    /**
     * Find all of the cache ids for a given tag.
     */
    public function find($tag);

    /**
     * Get a cache item.
     * Return NULL if the cache item is not found.
     */
    public function get($id);

    /**
     * Delete cache items by id or tag.
     */
    public function delete($id, $tag = false);

    /**
     * Deletes all expired cache items.
     */
    public function delete_expired();
}
// End Cache Driver
