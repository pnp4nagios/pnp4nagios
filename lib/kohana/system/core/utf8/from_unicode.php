<?php

// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects

/**
 * utf8::from_unicode
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _from_unicode($arr)
{
    ob_start();

    $keys = array_keys($arr);

    foreach ($keys as $k) {
        // ASCII range (including control chars)
        if (($arr[$k] >= 0) and ($arr[$k] <= 0x007f)) {
            echo chr($arr[$k]);
        } elseif ($arr[$k] <= 0x07ff) {
          // 2 byte sequence
            echo chr(0xc0 | ($arr[$k] >> 6));
            echo chr(0x80 | ($arr[$k] & 0x003f));
        } elseif ($arr[$k] == 0xFEFF) {
          // Byte order mark (skip)
          // nop -- zap the BOM
        } elseif ($arr[$k] >= 0xD800 and $arr[$k] <= 0xDFFF) {
          // Test for illegal surrogates
          // Found a surrogate
            trigger_error('utf8::from_unicode: Illegal surrogate at index: ' . $k . ', value: ' . $arr[$k], E_USER_WARNING);
            return false;
        } elseif ($arr[$k] <= 0xffff) {
          // 3 byte sequence
            echo chr(0xe0 | ($arr[$k] >> 12));
            echo chr(0x80 | (($arr[$k] >> 6) & 0x003f));
            echo chr(0x80 | ($arr[$k] & 0x003f));
        } elseif ($arr[$k] <= 0x10ffff) {
          //  4 byte sequence
            echo chr(0xf0 | ($arr[$k] >> 18));
            echo chr(0x80 | (($arr[$k] >> 12) & 0x3f));
            echo chr(0x80 | (($arr[$k] >> 6) & 0x3f));
            echo chr(0x80 | ($arr[$k] & 0x3f));
        } else {
          // Out of range
            trigger_error('utf8::from_unicode: Codepoint out of Unicode range at index: ' . $k . ', value: ' . $arr[$k], E_USER_WARNING);
            return false;
        }
    }

    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}
