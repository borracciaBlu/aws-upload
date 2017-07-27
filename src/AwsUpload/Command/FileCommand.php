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

use AwsUpload\Check;
use AwsUpload\Facilitator;

abstract class FileCommand extends BasicCommand implements Command, ValidCommand
{
    /**
     * @var string
     */
    public $key;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->init();
    }

    /**
     * Initializes the props.
     */
    abstract public function init();

    /**
     * Execute the main operations.
     */
    abstract public function exec();

    /**
     * Run the command.
     *
     * @return int The status code.
     */
    public function run()
    {
        if (!$this->isValid()) {
            return $this->handleError();
        }

        $res = $this->exec();
        if (!is_null($res)) {
            return $res;
        }

        return $this->handleSuccess();
    }

    /**
     * Method to check if key isValid and good to proceed.
     *
     * @return boolean
     */
    public function isValid()
    {
        $tests = array(
            "file_exists"  => Check::fileExists($this->key),
            "is_valid_key" => Check::isValidKey($this->key),
            "has_project"   => !empty($this->key),
        );

        $msgs = array(
            "file_exists"  => Facilitator::onNoFileFound($this->key),
            "is_valid_key" => Facilitator::onNoValidKey($this->key),
            "has_project"   => Facilitator::onNoProjects(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
