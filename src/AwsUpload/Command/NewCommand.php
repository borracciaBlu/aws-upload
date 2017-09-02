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
use AwsUpload\Model\Status;
use AwsUpload\Command\Command;
use AwsUpload\Setting\SettingFiles;
use AwsUpload\Message\ErrorMessage;
use AwsUpload\Message\NewSettingFileMessage;

class NewCommand extends FileCommand
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

        $this->msg = NewSettingFileMessage::success($this->key);
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
            "file_not_exists" => ErrorMessage::keyAlreadyExists($this->key),
            "is_valid_key"    => ErrorMessage::noValidKey($this->key),
            "is_project"      => ErrorMessage::noProjects(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
