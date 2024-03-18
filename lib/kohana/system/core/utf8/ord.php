<?php

// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
/**
 * utf8::ord
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _ord($chr)
{
    $ord0 = ord($chr);

    if ($ord0 >= 0 and $ord0 <= 127) {
        return $ord0;
    }

    if (! isset($chr[1])) {
        trigger_error('Short sequence - at least 2 bytes expected, only 1 seen', E_USER_WARNING);
        return false;
    }

    $ord1 = ord($chr[1]);

    if ($ord0 >= 192 and $ord0 <= 223) {
        return ($ord0 - 192) * 64 + ($ord1 - 128);
    }

    if (! isset($chr[2])) {
        trigger_error('Short sequence - at least 3 bytes expected, only 2 seen', E_USER_WARNING);
        return false;
    }

    $ord2 = ord($chr[2]);

    if ($ord0 >= 224 and $ord0 <= 239) {
        return ($ord0 - 224) * 4096 + ($ord1 - 128) * 64 + ($ord2 - 128);
    }

    if (! isset($chr[3])) {
        trigger_error('Short sequence - at least 4 bytes expected, only 3 seen', E_USER_WARNING);
        return false;
    }

    $ord3 = ord($chr[3]);

    if ($ord0 >= 240 and $ord0 <= 247) {
        return ($ord0 - 240) * 262144 + ($ord1 - 128) * 4096 + ($ord2 - 128) * 64 + ($ord3 - 128);
    }

    if (! isset($chr[4])) {
        trigger_error('Short sequence - at least 5 bytes expected, only 4 seen', E_USER_WARNING);
        return false;
    }

    $ord4 = ord($chr[4]);

    if ($ord0 >= 248 and $ord0 <= 251) {
        return ($ord0 - 248) * 16777216 + ($ord1 - 128) * 262144 + ($ord2 - 128) * 4096 + ($ord3 - 128) * 64 + ($ord4 - 128);
    }

    if (! isset($chr[5])) {
        trigger_error('Short sequence - at least 6 bytes expected, only 5 seen', E_USER_WARNING);
        return false;
    }

    if ($ord0 >= 252 and $ord0 <= 253) {
        return ($ord0 - 252) * 1073741824 + ($ord1 - 128) * 16777216 + ($ord2 - 128) * 262144 + ($ord3 - 128) * 4096 + ($ord4 - 128) * 64 + (ord($chr[5]) - 128);
    }

    if ($ord0 >= 254 and $ord0 <= 255) {
        trigger_error('Invalid UTF-8 with surrogate ordinal ' . $ord0, E_USER_WARNING);
        return false;
    }
}
