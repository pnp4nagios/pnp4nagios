<?php

// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
/**
 * Credit card validation configuration.
 *
 * Options for each credit card:
 *  length - All the allowed card number lengths, in a comma separated string
 *  prefix - The digits the card needs to start with, in regex format
 *  luhn   - Enable or disable card number validation by the Luhn algorithm
 */
$config = array
(
    'default' => array
    (
        'length' => '13,14,15,16,17,18,19',
        'prefix' => '',
        'luhn'   => true
    ),
    'american express' => array
    (
        'length' => '15',
        'prefix' => '3[47]',
        'luhn'   => true
    ),
    'diners club' => array
    (
        'length' => '14,16',
        'prefix' => '36|55|30[0-5]',
        'luhn'   => true
    ),
    'discover' => array
    (
        'length' => '16',
        'prefix' => '6(?:5|011)',
        'luhn'   => true,
    ),
    'jcb' => array
    (
        'length' => '15,16',
        'prefix' => '3|1800|2131',
        'luhn'   => true
    ),
    'maestro' => array
    (
        'length' => '16,18',
        'prefix' => '50(?:20|38)|6(?:304|759)',
        'luhn'   => true
    ),
    'mastercard' => array
    (
        'length' => '16',
        'prefix' => '5[1-5]',
        'luhn'   => true
    ),
    'visa' => array
    (
        'length' => '13,16',
        'prefix' => '4',
        'luhn'   => true
    ),
);
