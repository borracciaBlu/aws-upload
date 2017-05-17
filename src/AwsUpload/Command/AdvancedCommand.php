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

abstract class AdvancedCommand extends BasicCommand implements Command
{
    /**
     * The error messsage.
     *
     * @var string
     */
    public $msg;

    /**
     * Method to check if keys isValid and good to proceed.
     *
     * @param  mixed $param The param to validate.
     *
     * @return boolean
     */
    abstract public function isValid($param);


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
            if ($evaluation) {
                $this->msg = $msgs[$test_key];
                $valid = false;
            }
        }

        return $valid;
    }
}
