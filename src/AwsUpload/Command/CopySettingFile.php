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
use AwsUpload\Setting\SettingFiles;

class CopySettingFile extends FileCommand
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

        SettingFiles::copy($source, $dest);
        $this->msg = Facilitator::onNewSettingFileSuccess($dest);
    }

    /**
     * Method to check if keys isValid and good to proceed.
     *
     * @return boolean
     */
    public function isValid()
    {
        if (!$this->isValidArgs($this->keys)) {
            $this->error_msg = Facilitator::onNoCopyArgs();
            $valid = false;

            return $valid;
        }

        list($source, $dest) = $this->keys;

        $tests = array(
            "dest_not_exists"  => !Check::fileExists($dest),
            "src_exists"       => Check::fileExists($source),
            "is_valid_key_src" => Check::isValidKey($source),
            "is_valid_key_dst" => Check::isValidKey($dest),
        );

        $msgs = array(
            "dest_not_exists"  => Facilitator::onKeyAlreadyExists($dest),
            "src_exists"       => Facilitator::onNoFileFound($source),
            "is_valid_key_src" => Facilitator::onNoValidKey($source),
            "is_valid_key_dst" => Facilitator::onNoValidKey($dest),
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
