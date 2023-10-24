<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

/**
 * Default controller.
 *
 * @package    pnp4nagios
 * @author     Joerg Linge
 * @license    GPL
 */
class Start_Controller extends System_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
            url::redirect("graph", 302);
    }
}
