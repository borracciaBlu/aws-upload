<?php
/**
 * aws-upload - 🌈 A delicious CLI Tool for uploading files to ec2
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 * @author    Marco Buttini <marco.asdman@gmail.com>
 * @copyright 2017 Marco Buttini
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License
 */

namespace AwsUpload\Io;

use function cli\out;
use function AwsUpload\Io\color;

abstract class Output
{

    /**
     * It define if aws-upload has to print additional info.
     *
     * @var bool
     */
    public $is_verbose = false;

    /**
     * It define if aws-upload has to stay quiet and do not print additional information.
     *
     * @var bool
     */
    public $is_quiet = false;


    public function __construct($args)
    {
        if ($args->verbose) {
            $this->is_verbose = true;
        }

        if ($args->quiet) {
            $this->is_quiet = true;
        }
    }

    /**
     *
     * @param string $text The text to put on STDOUT.
     *
     * @return mixed
     */
    abstract public function write($text);

    /**
     * Method used to print additional text with the flag verbose.
     *
     * @param string $msg The text to print in verbose state
     *
     * @return void
     */
    public function verbose($msg)
    {
        if ($this->is_verbose) {
            $this->write($msg . "\n\n");
        }
    }
}
