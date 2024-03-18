<?php

// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
/**
 * @package  Core
 *
 * Allowed non-php view types. Most file extensions are supported.
 */
$config['allowed_filetypes'] = array
(
    'gif',
    'jpg', 'jpeg',
    'png',
    'tif', 'tiff',
    'swf',
    'htm', 'html',
    'css',
    'js'
);
