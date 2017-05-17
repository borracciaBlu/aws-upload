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
use AwsUpload\Command\Command;
use AwsUpload\Setting\SettingFiles;

class CopySettingFile extends BasicCommand
{
    /**
     * Method used to chek a setting file for debug purpose.
     *
     * @return void
     */
    public function run()
    {
        $args = $this->app->args['copy'];
        $keys = explode(" ", $args);

        if (!$this->isValid($keys)) {
            $this->app->display($this->msg, 0);
            return;
        }

        list($source, $dest) = $keys;

        SettingFiles::copy($source, $dest);
        $msg = Facilitator::onNewSettingFileSuccess($dest);

        $this->app->display($msg, 0);
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
        $valid = true;

        if (empty($keys) || count($keys) < 2) {
            $this->msg = Facilitator::onNoCopyArgs();
            $valid = false;

            return $valid;
        }

        list($source, $dest) = $keys;

        if (Check::fileExists($dest)) {
            $this->msg = Facilitator::onKeyAlreadyExists($dest);
            $valid = false;
        }

        if (!Check::fileExists($source)) {
            $this->msg = Facilitator::onNoFileFound($source);
            $valid = false;
        }

        foreach ($keys as $key) {
            if (!Check::isValidKey($key)) {
                $this->msg = Facilitator::onNoValidKey($key);
                $valid = false;
            }
        }

        return $valid;
    }
}
