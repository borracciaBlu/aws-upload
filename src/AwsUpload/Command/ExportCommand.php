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

use AwsUpload\System\File;
use AwsUpload\Model\Settings;
use AwsUpload\Setting\SettingFile;
use AwsUpload\Message\ErrorMessage;
use AwsUpload\Message\ExportMessage;
use AwsUpload\Setting\SettingFolder;

class ExportCommand extends FileCommand
{
    /**
     * @var array
     */
    public $keys;

    /**
     * Initializes the command.
     *
     * @see FileCommand::init
     * @return void
     */
    public function init()
    {
        $this->key = $this->args->getFirst('export');
        $this->keys = $this->args->getParams('export');
    }

    /**
     * Exec the export.
     *
     * @see FileCommand::run
     * @return void
     */
    public function exec()
    {
        $oldPath = $this->getOldPath();
        $newPath = $this->getNewPath();

        File::copy($oldPath, $newPath);

        $this->msg = ExportMessage::success($this->key);
    }

    public function getOldPath()
    {
        $path = SettingFolder::getPath();
        return $path . '/' . $this->key . '.json';
    }

    public function getNewPath()
    {
        $path = $this->getNewDir();
        return $path . '/' . $this->key . '.json';
    }

    public function getNewDir()
    {
        $dir = getcwd();
        if (count($this->keys) === 2) {
            if (is_dir($this->keys[1])) {
                $dir = realpath($this->keys[1]);
            } else {
                $dir = dirname($this->keys[1]);
            }
        }

        return $dir;
    }

    /**
     * Method to check if key isValid and good to proceed.
     *
     * @return boolean
     */
    public function isValid()
    {
        $new_settings = new Settings($this->getNewPath());

        $tests = array(
            "dest_not_exists" => !$new_settings->fileExists(),
            "src_exists"      => SettingFile::exists($this->key),
            "is_valid_key"    => SettingFile::isValidKey($this->key),
            "no_args" => count($this->keys) > 0,
        );

        $msgs = array(
            "is_valid_key"    => ErrorMessage::noValidKey($this->key),
            "src_exists"      => ErrorMessage::noFileFound($this->key),
            "dest_not_exists" => 'file alreay present',
            "no_args" => ExportMessage::noArgs(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
