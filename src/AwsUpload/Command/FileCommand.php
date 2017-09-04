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

use AwsUpload\Setting\SettingFile;
use AwsUpload\Message\ErrorMessage;

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

    public function hasArgs()
    {
        return !empty($this->key);
    }

    public function getErrorMsg()
    {
        $class = str_replace('Command', 'Message', static::class);
        $msg = ErrorMessage::noArgs();

        if (class_exists($class)) {
            $msg = call_user_func(array($class, 'noArgs'));
        }

        return $msg;
    }

    /**
     * Method to check if key isValid and good to proceed.
     *
     * @return boolean
     */
    public function isValid()
    {
        $tests = array(
            "file_exists"  => SettingFile::exists($this->key),
            "is_valid_key" => SettingFile::isValidKey($this->key),
            "has_args"     => $this->hasArgs(),
        );

        $msgs = array(
            "file_exists"  => ErrorMessage::noFileFound($this->key),
            "is_valid_key" => ErrorMessage::noValidKey($this->key),
            "has_args"     => $this->getErrorMsg(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
