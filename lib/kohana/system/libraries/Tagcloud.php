<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps


/**
 * [Tag cloud][ref-tcl] creation library.
 *
 * [ref-tcl]: http://en.wikipedia.org/wiki/Tag_cloud
 *
 * $Id: Tagcloud.php 3824 2008-12-20 17:13:06Z samsoir $
 *
 * @package    Tagcloud
 * @author     Kohana Team
 * @copyright  (c) 2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Tagcloud_Core
{
    /**
     * Creates a new Tagcloud instance and returns it.
     *
     * @chainable
     * @param   array    elements of the tagcloud
     * @param   integer  minimum font size
     * @param   integer  maximum font size
     * @return  Tagcloud
     */
    public static function factory(array $elements, $min_size = null, $max_size = null, $shuffle = false)
    {
        return new Tagcloud($elements, $min_size, $max_size, $shuffle);
    }

    public $min_size   = 80;
    public $max_size   = 140;
    public $attributes = array('class' => 'tag');
    public $shuffle    = false;

    // Tag elements, biggest and smallest values
    protected $elements;
    protected $biggest;
    protected $smallest;

    /**
     * Construct a new tagcloud. The elements must be passed in as an array,
     * with each entry in the array having a "title" ,"link", and "count" key.
     * Font sizes will be applied via the "style" attribute as a percentage.
     *
     * @param   array    elements of the tagcloud
     * @param   integer  minimum font size
     * @param   integer  maximum font size
     * @return  void
     */
    public function __construct(array $elements, $min_size = null, $max_size = null, $shuffle = false)
    {
        $this->elements = $elements;

        if ($shuffle !== false) {
            $this->shuffle = true;
        }

        $counts = array();
        foreach ($elements as $data) {
            $counts[] = $data['count'];
        }

        // Find the biggest and smallest values of the elements
        $this->biggest  = max($counts);
        $this->smallest = min($counts);

        if ($min_size !== null) {
            $this->min_size = $min_size;
        }

        if ($max_size !== null) {
            $this->max_size = $max_size;
        }
    }

    /**
     * Magic __toString method. Returns all of the links as a single string.
     *
     * @return  string
     */
    public function __toString()
    {
        return implode("\n", $this->render());
    }

    /**
     * Renders the elements of the tagcloud into an array of links.
     *
     * @return  array
     */
    public function render()
    {
        if ($this->shuffle === true) {
            shuffle($this->elements);
        }

        // Minimum values must be 1 to prevent divide by zero errors
        $range = max($this->biggest  - $this->smallest, 1);
        $scale = max($this->max_size - $this->min_size, 1);

        // Import the attributes locally to prevent overwrites
        $attr = $this->attributes;

        $output = array();
        foreach ($this->elements as $data) {
            if (strpos($data['title'], ' ') !== false) {
                // Replace spaces with non-breaking spaces to prevent line wrapping
                // in the middle of a link
                $data['title'] = str_replace(' ', '&nbsp;', $data['title']);
            }

            // Determine the size based on the min/max scale and the smallest/biggest range
            $size = ((($data['count'] - $this->smallest) * $scale) / $range) + $this->min_size;

            $attr['style'] = 'font-size: ' . round($size, 0) . '%';

            $output[] = html::anchor($data['link'], $data['title'], $attr) . "\n";
        }

        return $output;
    }
}
// End Tagcloud
