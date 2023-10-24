<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps


/**
 * Model base class.
 *
 * $Id: Model.php 4007 2009-02-20 01:54:00Z jheathco $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Model_Core
{
    // Database object
    protected $db = 'default';

    /**
     * Loads the database instance, if the database is not already loaded.
     *
     * @return  void
     */
    public function __construct()
    {
        if (! is_object($this->db)) {
            // Load the default database
            $this->db = Database::instance($this->db);
        }
    }
}
// End Model
