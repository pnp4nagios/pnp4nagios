<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps


/**
 * Number helper class.
 *
 * $Id: num.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class num_Core
{
    /**
     * Round a number to the nearest nth
     *
     * @param   integer  number to round
     * @param   integer  number to round to
     * @return  integer
     */
    public static function round($number, $nearest = 5)
    {
        return round($number / $nearest) * $nearest;
    }
}
// End num
