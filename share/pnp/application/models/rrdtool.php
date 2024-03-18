<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

/**
 * Retrieves and manipulates current status of hosts (and services?)
 */
class Rrdtool_Model extends System_Model
{
    private $RRD_CMD = false;

    public $config = '';


    public function __construct()
    {
        $this->config = new Config_Model();
        $this->config->read_config();
        // print Kohana::debug($this->config->views);
    }
//end __construct()


    private function rrdtool_execute()
    {
        $descriptorspec = [
            0 => [
                'pipe',
                'r',
            ],
// stdin is a pipe that the child will read from
            1 => [
                'pipe',
                'w',
            ],
// stdout is a pipe that the child will write to
            2 => [
                'pipe',
                'w',
            ],
// stderr is a pipe that the child will write to
        ];

        if (!isset($this->config->conf['rrdtool'])) {
            return false;
        }

        if (!is_executable($this->config->conf['rrdtool'])) {
            $data = 'ERROR: ' . $this->config->conf['rrdtool'] . " is not executable by PHP\n\n";
            return $data;
        }

        $rrdtool = $this->config->conf['rrdtool'] . ' - ';

    /*
        this is needed by Fontconfig, used by pango, used by rrdtool graph
     ** it's automatically set up for 'interactive' sessions, but  web servers
     ** don't have it (usually?). If there are 'red screens' instead of graphs,
    ** there's a good chance this is why */

        $fc_cache = $this->config->conf['fontconfig_cache'];


        putenv('XDG_CACHE_HOME=' . $fc_cache);
        $xdg_cache = getenv('XDG_CACHE_HOME');
        if (!$xdg_cache) {
            $data = 'ERROR: environment var XDG_CACHE_HOME not defined (should be writable cache dir)';
            return $data;
        }
        if (!is_dir($xdg_cache) || !is_writeable($xdg_cache)) {
            $data = 'ERROR: env XDG_CACHE_HOME (' . $xdg_cache . ') is not writable directory';
            return $data;
        }



        $command = $this->RRD_CMD;
        $process = proc_open($rrdtool, $descriptorspec, $pipes);
        $debug   = [];
        $data    = '';
        if (is_resource($process)) {
            fwrite($pipes[0], $command);
            fclose($pipes[0]);
            stream_set_timeout($pipes[1], 1);
            $data = stream_get_contents($pipes[1]);
            stream_set_timeout($pipes[2], 1);
            $stderr      = stream_get_contents($pipes[2]);
            $stdout_meta = stream_get_meta_data($pipes[1]);
            if ($stdout_meta['timed_out'] == 1) {
                $data = "ERROR: Timeout while reading rrdtool data.\n\n";
            }
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
            // Catch STDERR
            if ($stderr && strlen($stderr) >= 0) {
                $data = 'ERROR: STDERR => ' . $stderr . "\n\n";
                return $data;
            }
            // Catch STDOUT < 50 Characters
            if ($data && strlen($data) < 50) {
                $data = 'ERROR: STDOUT => ' . $data . "\n\n";
                return $data;
            }
        } else {
            $data = 'ERROR: proc_open(' . $rrdtool . ' ... failed';
        }
//end if
        return $data;
    }
//end rrdtool_execute()


    public function doImage($RRD_CMD, $out = 'STDOUT')
    {
        $conf = $this->config->conf;
        // construct $command to rrdtool
        if (isset($conf['RRD_DAEMON_OPTS']) && $conf['RRD_DAEMON_OPTS'] != '') {
            $command = ' graph --daemon=' . $conf['RRD_DAEMON_OPTS'] . ' - ';
        } else {
            $command = ' graph - ';
        }

        $width  = 0;
        $height = 0;
        if ($out == 'PDF') {
            if ($conf['pdf_graph_opt']) {
                $command .= $conf['pdf_graph_opt'];
            }
            if (isset($conf['pdf_width']) && is_numeric($conf['pdf_width'])) {
                $width = abs($conf['pdf_width']);
            }
            if (isset($conf['pdf_height']) && is_numeric($conf['pdf_height'])) {
                $height = abs($conf['pdf_height']);
            }
        } else {
            if ($conf['graph_opt']) {
                $command .= $conf['graph_opt'];
            }
            if (is_numeric($conf['graph_width'])) {
                $width = abs($conf['graph_width']);
            }
            if (is_numeric($conf['graph_height'])) {
                $height = abs($conf['graph_height']);
            }
        }
//end if

        if ($width > 0) {
            $command .= " --width=$width";
        }
        if ($height > 0) {
            $command .= " --height=$height";
        }
        if ($height < 81 || (isset($conf['graph_only']) && $conf['graph_only'])) {
            $command .= ' --only-graph';
        } elseif (isset($conf['no_legend']) && $conf['no_legend']) {
            $command .= ' --no-legend';
        }

        $command .= $RRD_CMD;

    // Force empty vertical label
        if (! preg_match_all('/(-l|--vertical-label)/i', $command, $match)) {
            $command .= " --vertical-label=' ' ";
        }

        $this->RRD_CMD = $command;
        $data          = $this->rrdtool_execute();
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }
//end doImage()


    public function doXport($RRD_CMD)
    {
        $conf = $this->config->conf;
        if (isset($conf['RRD_DAEMON_OPTS']) && $conf['RRD_DAEMON_OPTS'] != '') {
            $command = ' xport --daemon=' . $conf['RRD_DAEMON_OPTS'];
        } else {
            $command = ' xport ';
        }
        $command      .= $RRD_CMD;
        $this->RRD_CMD = $command;
        $data          = $this->rrdtool_execute();
        $data          = preg_replace('/OK.*/', '', $data);
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }
//end doXport()


    public function streamImage($data = false)
    {
        if ($data === false) {
            header('Content-type: image/png');
            echo base64_decode(
                'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A
                /wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9kCCAoDKSKZ0rEAAAAZdEVYdENv
                bW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAADUlEQVQI12NgYGBgAAAABQABXvMqOgAAAABJ
                RU5ErkJggg=='
            );
            return;
        }
        if (preg_match('/^ERROR/', $data)) {
            if (preg_match('/NOT_AUTHORIZED/', $data)) {
                // TODO: i18n
                $data .= "\n\nYou are not authorized to view this Image";
                // Set font size
                $font_size = 3;
            } else {
                $data .= $this->format_rrd_debug($this->config->conf['rrdtool'] . $this->RRD_CMD);
                // Set font size
                $font_size = 2;
            }
            $ts    = explode("\n", $data);
            $width = 0;
            foreach ($ts as $k => $string) {
                $width = max($width, strlen($string));
            }

            $width = imagefontwidth($font_size) * $width;
            if ($width <= ($this->config->conf['graph_width'] + 100)) {
                $width = ($this->config->conf['graph_width'] + 100);
            }
            $height = imagefontheight($font_size) * count($ts);
            if ($height <= ($this->config->conf['graph_height'] + 60)) {
                $height = ($this->config->conf['graph_height'] + 60);
            }
            $el = imagefontheight($font_size);
            $em = imagefontwidth($font_size);
            // Create the image pallette
            $img = imagecreatetruecolor($width, $height);
            // Dark red background
            $bg = imagecolorallocate($img, 0xAA, 0x00, 0x00);
            imagefilledrectangle($img, 0, 0, $width, $height, $bg);
            // White font color
            $color = imagecolorallocate($img, 255, 255, 255);

            foreach ($ts as $k => $string) {
                // Length of the string
                $len = strlen($string);
                // Y-coordinate of character, X changes, Y is static
                $ypos_offset = 5;
                $xpos_offset = 5;
                // Loop through the string
                for ($i = 0; $i < $len; $i++) {
                      // Position of the character horizontally
                      $xpos = ($i * $em + $ypos_offset);
                      $ypos = ($k * $el + $xpos_offset);
                      // Draw character
                      imagechar($img, $font_size, $xpos, $ypos, $string, $color);
                      // Remove character from string
                      $string = substr($string, 1);
                }
            }
            header('Content-type: image/png');
            imagepng($img);
            imagedestroy($img);
        } else {
            header('Content-type: image/png');
            echo $data;
        }
//end if
    }
//end streamImage()


    public function saveImage($data = false)
    {
        $img         = [];
        $img['file'] = tempnam($this->config->conf['temp'], 'PNP');
        if (!$fh = fopen($img['file'], 'w')) {
            throw new Kohana_Exception('save-rrd-image', $img['file']);
        }
        fwrite($fh, $data);
        fclose($fh);
        if (function_exists('imagecreatefrompng')) {
            $image = imagecreatefrompng($img['file']);
            imagepng($image, $img['file']);
            list ($img['width'], $img['height'], $img['type'], $img['attr']) = getimagesize($img['file']);
        } else {
            throw new Kohana_Exception('error.gd-missing');
        }
        return $img;
    }
//end saveImage()


    private function format_rrd_debug($data)
    {
        $data = preg_replace('/(HRULE|VDEF|DEF|CDEF|GPRINT|LINE|AREA|COMMENT)/', "\n\${1}", $data);
        return $data;
    }
//end format_rrd_debug()
}
//end class
