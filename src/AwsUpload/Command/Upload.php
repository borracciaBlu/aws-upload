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
use AwsUpload\Rsync;
use AwsUpload\Facilitator;
use AwsUpload\Setting\SettingFiles;
use AwsUpload\Command\BasicCommand;

class Upload extends BasicCommand
{
    /**
     * Method to run the rsync cmd.
     *
     * The main idea is:
     *     1 - get [$proj].[$env].json file
     *     2 - convert the file to an obj
     *     3 - run rsync with the details in the obj
     *
     * @return void
     */
    public function run()
    {
        $items = $this->app->args->getParams('wild');
        list($proj, $env) = SettingFiles::extractProjEnv($items);

        $key = $proj . "." . $env;
        if (!Check::fileExists($key)) {
            $msg = Facilitator::onNoFileFound($proj, $env);
            
            $this->app->display($msg, 0);
            return;
        }

        $settings = SettingFiles::getObject($key);
        $rsync = new Rsync($settings);

        $msg = Facilitator::rsyncBanner($proj, $env, $rsync->cmd);
        $this->app->inline($msg);

        if ($this->app->args->simulate) {
            $msg = 'Simulation mode' . "\n";

            $this->app->display($msg, 0);
            return;
        }

        $rsync->run();
    }
}
