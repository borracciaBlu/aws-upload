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
use AwsUpload\Status;
use AwsUpload\Facilitator;
use AwsUpload\Command\Command;
use AwsUpload\Setting\SettingFiles;

class CopySettingFile extends AdvancedCommand
{
    /**
     * Method used to chek a setting file for debug purpose.
     *
     * @return int The status code.
     */
    public function run()
    {
        $keys = $this->app->args->getParams('copy');

        if (!$this->isValid($keys)) {
            $this->app->inline($this->msg);

            return Status::ERROR_INVALID;
        }

        list($source, $dest) = $keys;

        SettingFiles::copy($source, $dest);
        $msg = Facilitator::onNewSettingFileSuccess($dest);
        $this->app->inline($msg);

        return Status::SUCCESS;
    }

    /**
     * Method to check if keys isValid and good to proceed.
     *
     * @param  array $keys The setting file key.
     *
     * @return boolean
     */
    public function isValid($keys)
    {
        if (!$this->isValidArgs($keys)) {
            $this->msg = Facilitator::onNoCopyArgs();
            $valid = false;

            return $valid;
        }

        list($source, $dest) = $keys;

        $tests = array(
            "file_exists"      => Check::fileExists($dest),
            "file_not_exists"  => !Check::fileExists($source),
            "is_valid_key_src" => !Check::isValidKey($source),
            "is_valid_key_dst" => !Check::isValidKey($dest),
        );

        $msgs = array(
            "file_exists"      => Facilitator::onKeyAlreadyExists($dest),
            "file_not_exists"  => Facilitator::onNoFileFound($source),
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
