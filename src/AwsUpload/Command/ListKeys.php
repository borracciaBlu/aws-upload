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

use AwsUpload\Status;
use AwsUpload\Facilitator;
use AwsUpload\Command\Command;
use AwsUpload\Setting\SettingFiles;

class ListKeys extends BasicCommand
{
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
        $quiet = $this->app->is_quiet;
        $keys = SettingFiles::getKeys();

        if (count($keys) === 0 && !$quiet) {
            $msg = Facilitator::onNoProjects();

            $this->app->inline($msg);

            return Status::ERROR_INVALID;
        }

        $keys = implode(' ', $keys);
        $msg = $keys . "\n";
        $this->app->inline($msg);

        return Status::SUCCESS;
    }
}
