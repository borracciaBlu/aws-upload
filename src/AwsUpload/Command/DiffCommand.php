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
use AwsUpload\System\Rsync;
use AwsUpload\Setting\SettingFile;
use AwsUpload\Message\RsyncMessage;
use AwsUpload\System\RsyncCommands;

class DiffCommand extends FileCommand
{
    /**
     * Property true if app is simulate.
     *
     * @var bool
     */
    public $is_simulate;

    /**
     * Property true if app is verbose.
     *
     * @var bool
     */
    public $is_verbose;

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $proj;

    /**
     * @var string
     */
    public $env;

    public function init()
    {
        $this->key = $this->app->args->getFirst('diff');
        $this->is_verbose  = $this->app->args->verbose;
        $this->is_simulate = $this->app->args->simulate;

        list($proj, $env) = explode('.', $this->key);
        $this->proj = $proj;
        $this->env  = $env;
    }

    /**
     * Method to run the rsync cmd.
     *
     * @return mixed The status code.
     */
    public function exec()
    {
        $settings = SettingFile::getObject($this->key);

        $rsync = new Rsync($settings);
        $rsync->setVerbose($this->is_verbose);
        $rsync->setAction(RsyncCommands::DIFF);

        $msg = RsyncMessage::banner($this->proj, $this->env, $rsync->cmd);
        $this->app->inline($msg);

        if ($this->is_simulate) {
            $this->app->inline($rsync->getCmd());
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
