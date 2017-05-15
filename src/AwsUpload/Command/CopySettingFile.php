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

        if (empty($keys) || count($keys) < 2) {
            $msg = Facilitator::onNoCopyArgs();

            $this->app->display($msg, 0);
            return;
        }

        list($source, $dest) = $keys;

        foreach ($keys as $key) {
            if (!Check::isValidKey($key)) {
                $msg = Facilitator::onNoValidKey($key);

                $this->app->display($msg, 0);
                return;
            }
        }

        if (!Check::fileExists($source)) {
            $msg = Facilitator::onNoFileFound($source);

            $this->app->display($msg, 0);
            return;
        }

        if (Check::fileExists($dest)) {
            $msg = Facilitator::onKeyAlreadyExists($dest);

            $this->app->display($msg, 0);
            return;
        }

        SettingFiles::copy($source, $dest);
        $msg = Facilitator::onNewSettingFileSuccess($dest);

        $this->app->display($msg, 0);
    }
}
