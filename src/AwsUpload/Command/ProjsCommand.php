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

use AwsUpload\Model\Status;
use AwsUpload\Command\Command;
use AwsUpload\Message\ErrorMessage;
use AwsUpload\Setting\SettingFile;

class ProjsCommand extends BasicCommand implements ValidCommand
{
    /**
     * It contains the projects label, if any.
     *
     * @var array
     */
    public $projs;

    /**
     * Method used to print the projects available.
     *
     * The main idea is that you can get the projects from the files in
     * the aws-upload home folder.
     * Eg:
     *     - proj-1.dev.json    -> proj: proj-1
     *     - proj-1.stagin.json -> proj: proj-1
     *     - proj-2.prod.json   -> proj: proj-2
     *
     * @return int The status code.
     */
    public function run()
    {
        $quiet = $this->app->is_quiet;
        $this->projs = SettingFile::getProjs();

        if (!$this->isValid() && !$quiet) {
            return $this->handleError();
        }

        $projs = implode(' ', $this->projs);
        $this->msg = $projs . "\n";

        return $this->handleSuccess();
    }

    /**
     * Method to check if key isValid and good to proceed.
     *
     * @return boolean
     */
    public function isValid()
    {
        $tests = array(
            "is_project" => (count($this->projs) > 0),
        );
        $msgs = array(
            "is_project" => ErrorMessage::noProjects(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
