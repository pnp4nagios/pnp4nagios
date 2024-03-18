<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

/**
 * Database expression class to allow for explicit joins and where expressions.
 *
 * $Id: Database_Expression.php 4037 2009-03-04 23:35:53Z jheathco $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Database_Expression_Core
{
    protected $expression;

    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    public function __toString()
    {
        return (string) $this->expression;
    }
}
// End Database Expr Class
