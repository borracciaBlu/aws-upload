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
use AwsUpload\System\Rsync;
use AwsUpload\Setting\SettingFiles;
use AwsUpload\Command\BasicCommand;

class Upload extends FileCommand
{
    public function init()
    {
        $items = $this->app->args->getParams('wild');
        $this->is_simulate = $this->app->args->simulate;

        list($proj, $env) = SettingFiles::extractProjEnv($items);
        $this->key  = $proj . "." . $env;
        $this->proj = $proj;
        $this->env  = $env;
    }

    /**
     * Method to run the rsync cmd.
     *
     * The main idea is:
     *     1 - get [$proj].[$env].json file
     *     2 - convert the file to an obj
     *     3 - run rsync with the details in the obj
     *
     * @return mixed The status code.
     */
    public function exec()
    {
        $settings = SettingFiles::getObject($this->key);
        $rsync = new Rsync($settings);

        $msg = Facilitator::rsyncBanner($this->proj, $this->env, $rsync->cmd);
        $this->app->inline($msg);

        if ($this->is_simulate) {
            return $this->simulate();
        }

        $rsync->run();
    }

    public function simulate()
    {
        $this->msg = 'Simulation mode' . "\n";

        return $this->handleSuccess();
    }
}
