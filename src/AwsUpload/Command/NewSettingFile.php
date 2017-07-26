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
use AwsUpload\Model\Status;
use AwsUpload\Command\Command;
use AwsUpload\Setting\SettingFiles;

class NewSettingFile extends FileCommand
{
    public function init()
    {
        $this->key = $this->app->args->getFirst('new');
    }

    /**
     * Method used tocreate a new setting file.
     *
     * @return int The status code.
     */
    public function exec()
    {
        SettingFiles::create($this->key);
        SettingFiles::edit($this->key);

        $this->msg = Facilitator::onNewSettingFileSuccess($this->key);
    }

    /**
     * Method to check if key isValid and good to proceed.
     *
     * @return boolean
     */
    public function isValid()
    {
        $tests = array(
            "file_not_exists" => !Check::fileExists($this->key),
            "is_valid_key"    => Check::isValidKey($this->key),
            "is_project"      => !empty($this->key),
        );

        $msgs = array(
            "file_not_exists" => Facilitator::onKeyAlreadyExists($this->key),
            "is_valid_key"    => Facilitator::onNoValidKey($this->key),
            "is_project"      => Facilitator::onNoProjects(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
