<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps


/**
 * Upload helper class for working with the global $_FILES
 * array and Validation library.
 *
 * $Id: upload.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class upload_Core
{
    /**
     * Save an uploaded file to a new location.
     *
     * @param   mixed    name of $_FILE input or array of upload data
     * @param   string   new filename
     * @param   string   new directory
     * @param   integer  chmod mask
     * @return  string   full path to new file
     */
    public static function save($file, $filename = null, $directory = null, $chmod = 0644)
    {
        // Load file data from FILES if not passed as array
        $file = is_array($file) ? $file : $_FILES[$file];

        if ($filename === null) {
            // Use the default filename, with a timestamp pre-pended
            $filename = time() . $file['name'];
        }

        if (Kohana::config('upload.remove_spaces') === true) {
            // Remove spaces from the filename
            $filename = preg_replace('/\s+/', '_', $filename);
        }

        if ($directory === null) {
            // Use the pre-configured upload directory
            $directory = Kohana::config('upload.directory', true);
        }

        // Make sure the directory ends with a slash
        $directory = rtrim($directory, '/') . '/';

        if (! is_dir($directory) and Kohana::config('upload.create_directories') === true) {
            // Create the upload directory
            mkdir($directory, 0777, true);
        }

        if (! is_writable($directory)) {
            throw new Kohana_Exception('upload.not_writable', $directory);
        }

        if (is_uploaded_file($file['tmp_name']) and move_uploaded_file($file['tmp_name'], $filename = $directory . $filename)) {
            if ($chmod !== false) {
                // Set permissions on filename
                chmod($filename, $chmod);
            }

            // Return new file path
            return $filename;
        }

        return false;
    }

    /* Validation Rules */

    /**
     * Tests if input data is valid file type, even if no upload is present.
     *
     * @param   array  $_FILES item
     * @return  bool
     */
    public static function valid($file)
    {
        return (is_array($file)
            and isset($file['error'])
            and isset($file['name'])
            and isset($file['type'])
            and isset($file['tmp_name'])
            and isset($file['size']));
    }

    /**
     * Tests if input data has valid upload data.
     *
     * @param   array    $_FILES item
     * @return  bool
     */
    public static function required(array $file)
    {
        return (isset($file['tmp_name'])
            and isset($file['error'])
            and is_uploaded_file($file['tmp_name'])
            and (int) $file['error'] === UPLOAD_ERR_OK);
    }

    /**
     * Validation rule to test if an uploaded file is allowed by extension.
     *
     * @param   array    $_FILES item
     * @param   array    allowed file extensions
     * @return  bool
     */
    public static function type(array $file, array $allowed_types)
    {
        if ((int) $file['error'] !== UPLOAD_ERR_OK) {
            return true;
        }

        // Get the default extension of the file
        $extension = strtolower(substr(strrchr($file['name'], '.'), 1));

        // Get the mime types for the extension
        $mime_types = Kohana::config('mimes.' . $extension);

        // Make sure there is an extension, that the extension is allowed, and that mime types exist
        return ( ! empty($extension) and in_array($extension, $allowed_types) and is_array($mime_types));
    }

    /**
     * Validation rule to test if an uploaded file is allowed by file size.
     * File sizes are defined as: SB, where S is the size (1, 15, 300, etc) and
     * B is the byte modifier: (B)ytes, (K)ilobytes, (M)egabytes, (G)igabytes.
     * Eg: to limit the size to 1MB or less, you would use "1M".
     *
     * @param   array    $_FILES item
     * @param   array    maximum file size
     * @return  bool
     */
    public static function size(array $file, array $size)
    {
        if ((int) $file['error'] !== UPLOAD_ERR_OK) {
            return true;
        }

        // Only one size is allowed
        $size = strtoupper($size[0]);

        if (! preg_match('/[0-9]++[BKMG]/', $size)) {
            return false;
        }

        // Make the size into a power of 1024
        switch (substr($size, -1)) {
            case 'G':
                $size = intval($size) * pow(1024, 3);
                break;
            case 'M':
                $size = intval($size) * pow(1024, 2);
                break;
            case 'K':
                $size = intval($size) * pow(1024, 1);
                break;
            default:
                $size = intval($size);
                break;
        }

        // Test that the file is under or equal to the max size
        return ($file['size'] <= $size);
    }
}
// End upload
