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
use AwsUpload\Model\Status;
use AwsUpload\System\OhMyZsh;
use AwsUpload\Message\CommonMessage;

class AutoCompleteCommand extends BasicCommand
{
    /**
     * Method used to install the autocomplete plugin.
     *
     * @return int The status code.
     */
    public function run()
    {
        $this->output->write('Checking System:');

        $this->printSystemStatus();

        if (!$this->isValidSystem()) {
            $this->output->write($this->error_msg);
            return Status::SYSTEM_NOT_READY;
        }

        $this->output->write("Procede to the installation:\n");

        if (!OhMyZsh::hasPluginFiles()) {
            $this->output->write('   <b>•</b> Download plugin');
            $this->clone();
        } else {
            $this->output->write('   <b>•</b> Plugin files already present.');
            $this->pull();
        }

        if (!OhMyZsh::isPluginActive()) {
            $this->output->write('   <b>•</b> Activating plugin');
            OhMyZsh::activate();
        } else {
            $this->output->write('   <b>•</b> Plugin already activated');
        }

        $this->output->write("\nProcedure complete. Please reload the shell.");

        system('zsh');

        return Status::SUCCESS;
    }


    /**
     * Method to check if the system is good to proceed.
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

    /**
     * Print the system status report.
     *
     * @return void
     */
    public function printSystemStatus()
    {
        $check = array('✔', '✖');
        $labels = array('INSTALLED', 'NOT INSTALLED');

        $git = Git::isInstalled();
        $zsh = Zsh::isInstalled();
        $omz = OhMyZsh::isInstalled();

        $git_line = "   " .
                    CommonMessage::plot($git, $check) .
                    " Git      \t" .
                    CommonMessage::plot($git, $labels);
        $zsh_line = "   " .
                    CommonMessage::plot($zsh, $check) .
                    " Zsh      \t" .
                    CommonMessage::plot($zsh, $labels);
        $omz_line = "   " .
                    CommonMessage::plot($omz, $check) .
                    " Oh-my-zsh \t" .
                    CommonMessage::plot($omz, $labels);

        $this->output->write("");
        $this->output->write($git_line);
        $this->output->write($zsh_line);
        $this->output->write($omz_line);
        $this->output->write("");
    }

    /**
     * Clone the aws-upload-plugin repository.
     *
     * @return void
     */
    public function clone()
    {
        $dest = OhMyZsh::getPath() . '/plugins/aws-upload/';
        $repo = '--branch v' . $this->app->plugin .
                ' https://github.com/borracciaBlu/aws-upload-zsh.git';
        Git::clone($repo, $dest);
    }

    public function pull()
    {
        $repo = OhMyZsh::getPath() . '/plugins/aws-upload/';

        $this->output->write('     <y>-</y> Update repo');
        Git::pull($repo);

        $this->output->write('     <y>-</y> Checkout version v' . $this->app->plugin);
        Git::checkoutTag($repo, 'v' . $this->app->plugin);
    }
}
