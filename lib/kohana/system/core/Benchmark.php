<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects


/**
 * Simple benchmarking.
 *
 * $Id: Benchmark.php 4149 2009-04-01 13:32:50Z Shadowhand $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
final class Benchmark
{
    // Benchmark timestamps
    private static $marks;

    /**
     * Set a benchmark start point.
     *
     * @param   string  benchmark name
     * @return  void
     */
    public static function start($name)
    {
        if (! isset(self::$marks[$name])) {
            self::$marks[$name] = array();
        }

        $mark = array
        (
            'start'        => microtime(true),
            'stop'         => false,
            'memory_start' => self::memory_usage(),
            'memory_stop'  => false
        );

        array_unshift(self::$marks[$name], $mark);
    }

    /**
     * Set a benchmark stop point.
     *
     * @param   string  benchmark name
     * @return  void
     */
    public static function stop($name)
    {
        if (isset(self::$marks[$name]) and self::$marks[$name][0]['stop'] === false) {
            self::$marks[$name][0]['stop'] = microtime(true);
            self::$marks[$name][0]['memory_stop'] = self::memory_usage();
        }
    }

    /**
     * Get the elapsed time between a start and stop.
     *
     * @param   string   benchmark name, TRUE for all
     * @param   integer  number of decimal places to count to
     * @return  array
     */
    public static function get($name, $decimals = 4)
    {
        if ($name === true) {
            $times = array();
            $names = array_keys(self::$marks);

            foreach ($names as $name) {
                // Get each mark recursively
                $times[$name] = self::get($name, $decimals);
            }

            // Return the array
            return $times;
        }

        if (! isset(self::$marks[$name])) {
            return false;
        }

        if (self::$marks[$name][0]['stop'] === false) {
            // Stop the benchmark to prevent mis-matched results
            self::stop($name);
        }

        // Return a string version of the time between the start and stop points
        // Properly reading a float requires using number_format or sprintf
        $time = $memory = 0;
        for ($i = 0; $i < count(self::$marks[$name]); $i++) {
            $time += self::$marks[$name][$i]['stop'] - self::$marks[$name][$i]['start'];
            $memory += self::$marks[$name][$i]['memory_stop'] - self::$marks[$name][$i]['memory_start'];
        }

        return array
        (
            'time'   => number_format($time, $decimals),
            'memory' => $memory,
            'count'  => count(self::$marks[$name])
        );
    }

    /**
     * Returns the current memory usage. This is only possible if the
     * memory_get_usage function is supported in PHP.
     *
     * @return  integer
     */
    private static function memory_usage()
    {
        static $func;

        if ($func === null) {
            // Test if memory usage can be seen
            $func = function_exists('memory_get_usage');
        }

        return $func ? memory_get_usage() : 0;
    }
}
// End Benchmark
