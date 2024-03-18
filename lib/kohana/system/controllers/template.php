<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps


/**
 * Allows a template to be automatically loaded and displayed. Display can be
 * dynamically turned off in the controller methods, and the template file
 * can be overloaded.
 *
 * To use it, declare your controller to extend this class:
 * `class Your_Controller extends Template_Controller`
 *
 * $Id: template.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
abstract class Template_Controller extends Controller
{
    // Template view name
    public $template = 'template';

    // Default to do auto-rendering
    public $auto_render = true;

    /**
     * Template loading and setup routine.
     */
    public function __construct()
    {
        parent::__construct();

        // Load the template
        $this->template = new View($this->template);

        if ($this->auto_render == true) {
            // Render the template immediately after the controller method
            Event::add('system.post_controller', array($this, 'Xrender'));
        }
    }

    /**
     * Render the loaded template.
     */
    public function Xrender()
    {
        if ($this->auto_render == true) {
            // Render the template when the class is destroyed
            $this->template->render(true);
        }
    }
}
// End Template_Controller
