<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects


/**
 * Debug controller.
 *
 * @package    PNP4Nagios
 * @author     Joerg Linge
 * @license    GPL
 */

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

class Color_Controller extends System_Controller
{
    public $scheme = array();

    public function __construct()
    {
        parent::__construct();
        $this->template          = $this->add_view('template');
        $this->template->color   = $this->add_view('color');
        $this->template->color->color_box   = $this->add_view('color_box');
        $this->template->color->logo_box    = $this->add_view('logo_box');
    }

    public function index()
    {
        $this->scheme    = $this->config->scheme;
    }
}
