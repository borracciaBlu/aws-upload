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

use AwsUpload\Message\NewMessage;
use AwsUpload\Message\CopyMessage;
use AwsUpload\Message\ErrorMessage;
use AwsUpload\Setting\SettingFile;

class CopyCommand extends FileCommand
{
    /**
     * @var array
     */
    public $keys;

    public function init()
    {
        $this->keys = $this->app->args->getParams('copy');
    }

    /**
     * Exec the copy files.
     *
     * @see FileCommand::run
     * @return void
     */
    public function exec()
    {
        list($source, $dest) = $this->keys;

        SettingFile::copy($source, $dest);
        $this->msg = NewMessage::success($dest);
    }

    /**
     * Method to check if keys isValid and good to proceed.
     *
     * @return boolean
     */
    public function isValid()
    {
        if (!$this->isValidArgs($this->keys)) {
            $this->error_msg = CopyMessage::noArgs();
            $valid = false;

            return $valid;
        }

        list($source, $dest) = $this->keys;

        $tests = array(
            "dest_not_exists"  => !SettingFile::exists($dest),
            "src_exists"       => SettingFile::exists($source),
            "is_valid_key_src" => SettingFile::isValidKey($source),
            "is_valid_key_dst" => SettingFile::isValidKey($dest),
        );

        $msgs = array(
            "dest_not_exists"  => ErrorMessage::keyAlreadyExists($dest),
            "src_exists"       => ErrorMessage::noFileFound($source),
            "is_valid_key_src" => ErrorMessage::noValidKey($source),
            "is_valid_key_dst" => ErrorMessage::noValidKey($dest),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }

    /**
     * Method to check the validity of the copy arguments
     *
     * @param  array  $keys The copy arguments.
     *
     * @return boolean
     */
    public function isValidArgs($keys)
    {
        return (empty($keys) || count($keys) < 2) ? false : true;
    }
}
