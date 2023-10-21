<?php

// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
/**
 * @package  Core
 *
 * This path is relative to your index file. Absolute paths are also supported.
 */
$config['directory'] = DOCROOT . 'upload';

/**
 * Enable or disable directory creation.
 */
$config['create_directories'] = false;

/**
 * Remove spaces from uploaded filenames.
 */
$config['remove_spaces'] = true;
