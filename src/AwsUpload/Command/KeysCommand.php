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

use AwsUpload\Message\ErrorMessage;
use AwsUpload\Setting\SettingFiles;

class KeysCommand extends BasicCommand implements ValidCommand
{
    /**
     * @var array
     */
    public $keys;

    /**
     * Property true if app is quiet.
     *
     * @var bool
     */
    public $is_quiet;

    /**
     * Method used to print the projects' keys available.
     *
     * The main idea is that you can get the projects' keys from the files in
     * the aws-upload home folder.
     * Eg:
     *     - proj-1.dev.json    -> key: proj-1.dev
     *     - proj-1.stagin.json -> key: proj-1.staging
     *     - proj-2.prod.json   -> key: proj-2.prod
     *
     * @return int The status code.
     */
    public function run()
    {
        $this->keys     = SettingFiles::getKeys();
        $this->is_quiet = $this->app->is_quiet;

        if (!$this->isValid() && !$this->is_quiet) {
            return $this->handleError();
        }

        $keys = implode(' ', $this->keys);
        $this->msg = $keys . "\n";

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
            "is_project" => (bool) (count($this->keys) > 0),
        );

        $msgs = array(
            "is_project" => ErrorMessage::noProjects(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
