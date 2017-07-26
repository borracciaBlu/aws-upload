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

use AwsUpload\Model\Status;

abstract class BasicCommand implements Command
{
    /**
     * The AwsUpload object
     */
    public $app;

    /**
     * The success messsage.
     *
     * @var string
     */
    public $msg;

    /**
     * The error message.
     *
     * @var string
     */
    public $error_msg;

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

    /**
     * Method to check to set the error msg.
     *
     * @param  array  $tests The conditions checked.
     * @param  array  $msgs  The msgs for each test condition.
     *
     * @return boolean
     */
    public function validate($tests, $msgs)
    {
        $valid = true;

        foreach ($tests as $test_key => $evaluation) {
            if (!$evaluation) {
                $this->error_msg = $msgs[$test_key];
                $valid = false;
            }
        }

        return $valid;
    }

    /**
     * Method to handle an error case.
     *
     * @return int The status code.
     */
    public function handleError()
    {
        $this->app->inline($this->error_msg);

        return Status::ERROR_INVALID;
    }

    /**
     * Method to handle a success case.
     *
     * @return int The status code.
     */
    public function handleSuccess()
    {
        $this->app->inline($this->msg);

        return Status::SUCCESS;
    }

    abstract public function run();
}
