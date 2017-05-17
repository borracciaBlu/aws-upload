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

class EditSettingFile extends AdvancedCommand
{
    /**
     * Method used to edit a setting file.
     *
     * @return void
     */
    public function run()
    {
        $key = $this->app->args['edit'];

        if (!$this->isValid($key)) {
            $this->app->display($this->msg, 0);
            return;
        }

        if (!$this->app->is_phpunit) {
            SettingFiles::edit($key);
        }

        $msg = Facilitator::onEditSettingFileSuccess($key);

        $this->app->display($msg, 0);
    }

    /**
     * Method to check if key isValid and good to proceed.
     *
     * @param  string  $key The setting file key.
     *
     * @return boolean
     */
    public function isValid($key)
    {
        $tests = array(
            "file_not_exists" => !Check::fileExists($key),
            "is_valid_key"    => !Check::isValidKey($key),
            "is_no_project"   => empty($key),
        );

        $msgs = array(
            "file_not_exists" => Facilitator::onNoFileFound($key),
            "is_valid_key"    => Facilitator::onNoValidKey($key),
            "is_no_project"   => Facilitator::onNoProjects(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
