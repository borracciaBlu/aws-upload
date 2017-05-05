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
use AwsUpload\SettingFiles;
use AwsUpload\Command\Command;

class NewSettingFile extends BasicCommand
{
    /**
     * Method used tocreate a new setting file.
     *
     * @return void
     */
	public function run()
	{
        $key = $this->app->args['new'];
        if (empty($key)) {
            $msg = Facilitator::onNoProjects();

            $this->app->display($msg, 0);
            return;
        }

        if (!Check::isValidKey($key)) {
            $msg = Facilitator::onNoValidKey($key);

            $this->app->display($msg, 0);
            return;
        }

        if (Check::fileExists($key)) {
            $msg = Facilitator::onKeyAlreadyExists($key);

            $this->app->display($msg, 0);
            return;
        }

        SettingFiles::create($key);
        $msg = Facilitator::onNewSettingFileSuccess($key);

        $this->app->display($msg, 0);
	}
}
