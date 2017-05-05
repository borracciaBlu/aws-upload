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

namespace AwsUpload\Command;

abstract class BasicCommand implements Command
{
    /**
     * The AwsUpload object
     */
    public $app;

    /**
     * Initializes the command.
     *
     * The main purpose is to define the app for the script
     * and populate `$this->app`.
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    abstract public function run();
}
