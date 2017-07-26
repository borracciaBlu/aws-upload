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

use AwsUpload\System\Git;
use AwsUpload\System\Zsh;
use AwsUpload\Facilitator;
use AwsUpload\Model\Status;
use AwsUpload\System\OhMyZsh;

class AutoComplete extends BasicCommand
{
    /**
     * Method used to install the autocomplete plugin.
     *
     * @return int The status code.
     */
    public function run()
    {
        $this->app->inline('Checking System:');

        $this->printSystemStatus();

        if (!$this->isValidSystem()) {
            $this->app->inline($this->error_msg);

            return Status::SYSTEM_NOT_READY;
        }
        
        $this->app->inline("Procede to the installation:\n");
        
        if (!OhMyZsh::hasPluginFiles()) {
            $this->app->inline('   <b>•</b> Download plugin');
            $this->clone();
        } else {
            $this->app->inline('   <b>•</b> Plugin files already present.');
        }

        if (!OhMyZsh::isPluginActive()) {
            $this->app->inline('   <b>•</b> Activating plugin');
            OhMyZsh::activate();
        } else {
            $this->app->inline('   <b>•</b> Plugin already activated');
        }


        $this->app->inline("\nProcedure complete. Please reload the shell.");
        return Status::SUCCESS;
    }


    /**
     * Method to check if key isValid and good to proceed.
     *
     * @param  string  $key The setting file key.
     *
     * @return boolean
     */
    public function isValidSystem()
    {
        $tests = array(
            "no_git" => Git::isInstalled(),
            "no_zsh" => Zsh::isInstalled(),
            "no_omz" => OhMyZsh::isInstalled(),
        );

        $msgs = array(
            "no_git" => Git::errorMsg(),
            "no_zsh" => Zsh::errorMsg(),
            "no_omz" => OhMyZsh::errorMsg(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }

    public function printSystemStatus()
    {
        $check = array('✔', '✖');
        $labels = array('INSTALLED', 'NOT INSTALLED');

        $git = Git::isInstalled();
        $zsh = Zsh::isInstalled();
        $omz = OhMyZsh::isInstalled();

        $this->app->inline("");
        $this->app->inline("   " . Facilitator::plot($git, $check) . " Git      \t" . Facilitator::plot($git, $labels));
        $this->app->inline("   " . Facilitator::plot($zsh, $check) . " Zsh      \t" . Facilitator::plot($zsh, $labels));
        $this->app->inline("   " . Facilitator::plot($omz, $check) . " Oh-my-zsh \t" . Facilitator::plot($omz, $labels));
        $this->app->inline("");
    }

    public function clone()
    {
        $dest = OhMyZsh::getPath() . '/plugins/aws-upload/';
        $repo = 'https://github.com/borracciaBlu/aws-upload-zsh.git';
        return Git::clone($repo, $dest);
    }
}
