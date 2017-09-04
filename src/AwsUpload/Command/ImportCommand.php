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
use AwsUpload\Message\ImportMessage;
use AwsUpload\Setting\SettingFolder;

class ImportCommand extends FileCommand
{
    /**
     * @var string
     */
    public $setting_path;

    /**
     * @var string
     */
    public $new_key;

    /**
     * Initializes the command.
     */
    public function init()
    {
        $this->setting_path = $this->app->args->getFirst('import');
        $this->new_key = basename($this->setting_path, ".json");
    }

    /**
     * Exec the import.
     *
     * @see FileCommand::run
     * @return void
     */
    public function exec()
    {
        $oldPath = $this->setting_path;
        $newPath = $this->getNewPath();
        File::copy($oldPath, $newPath);

        $this->msg = ImportMessage::success($this->new_key);
    }

    public function getNewPath()
    {
        $path = SettingFolder::getPath();
        return $path . '/' . $this->new_key . '.json';
    }

    /**
     * Method to check if key isValid and good to proceed.
     *
     * @return boolean
     */
    public function isValid()
    {
        $new_settings = new Settings($this->setting_path);

        $tests = array(
            "is_valid_key"    => SettingFile::isValidKey($this->new_key),
            "dest_not_exists" => !SettingFile::exists($this->new_key),
            "src_exists"      => $new_settings->fileExists(),
            "has_arg"         => !empty($this->setting_path),
        );

        $msgs = array(
            "dest_not_exists" => ErrorMessage::keyAlreadyExists($this->new_key),
            "is_valid_key"    => ErrorMessage::noValidKey($this->new_key),
            "src_exists"      => ImportMessage::errorNotFound($this->setting_path),
            "has_arg"         => ImportMessage::noArgs(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
