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

class EditSettingFile extends BasicCommand
{
    /**
     * Method used to edit a setting file.
     *
     * @return void
     */
	public function run()
	{
        $key = $this->app->args['edit'];
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

        if (!Check::fileExists($key)) {
            $msg = Facilitator::onNoFileFound($key);

            $this->app->display($msg, 0);
            return;
        }

        if (!$this->app->is_phpunit) {
            SettingFiles::edit($key);
        }

        $msg = Facilitator::onEditSettingFileSuccess($key);

        $this->app->display($msg, 0);
	}
}
