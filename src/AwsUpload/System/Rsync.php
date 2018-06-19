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

namespace AwsUpload\System;

use AwsUpload\System\RsyncCommands;

class Rsync
{
    /**
     * It contains the text version of the command to run.
     *
     * @var string
     */
    public $cmd;

    /**
     * It contains the settings object
     *
     * Eg:
     * { pem , exclude, remote, local }
     *
     * @var object
     */
    public $settings;

    /**
     * It contains the action to perform
     *
     * @see AwsUpload\System\RsyncCommands
     * @var string
     */
    public $action;

    public $is_verbose = false;

    /**
     * Method to initiate the rsync settings object
     *
     * The setting object is a object version of one of the files in the
     * aws-upload folder.
     *
     * @see SettingFile::getObjcet($key)
     *
     * @param \AwsUpload\Model\Settings $settings The object version of one of the
     *                                           files in the aws-upload dir.
     */
    public function __construct(\AwsUpload\Model\Settings $settings)
    {
        $this->settings = $settings;
    }

    public function setVerbose($verbose)
    {
        $this->is_verbose = (bool) $verbose;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Method to build the rsync command from the settings object
     *
     * @return string The rsync command.
     */
    public function getCmd()
    {
        $cmd = "";

        if (RsyncCommands::UPLOAD === $this->action) {
            $cmd = $this->getUploadCommand();
        }

        if (RsyncCommands::DIFF === $this->action) {
            $cmd = $this->getDiffCommand();
        }

        return $cmd;
    }

    /**
     * @return string
     */
    public function getUploadCommand()
    {
        $cmd = "rsync ";
        $cmd .= $this->getVerboseFlags();
        $cmd .= $this->getSshDetails();
        $cmd .= $this->getExclude();
        $cmd .= $this->getLocal();
        $cmd .= $this->getRemote();

        return $cmd;
    }

    /**
     * @return string
     */
    public function getDiffCommand()
    {
         $cmd = "rsync --dry-run ";

        $cmd .= $this->getVerboseFlags();
        $cmd .= $this->getSshDetails();
        $cmd .= $this->getExclude();
        $cmd .= $this->getOnlyPathLocal();
        $cmd .= $this->getRemote();

        return $cmd;
    }

    /**
     * @return string
     */
    public function getVerboseFlags()
    {
        return ($this->is_verbose) ? " -v --stats --progress " : "";
    }

    /**
     * @return string
     */
    public function getSshDetails()
    {
        return "-ravze \"ssh -i " . $this->settings->pem . "\" ";
    }

    /**
     * @return string
     */
    public function getExclude()
    {
        $settings = $this->settings;

        $cmd = "";
        if (!isset($settings->exclude) || !is_array($settings->exclude)) {
            return $cmd;
        }

        foreach ($settings->exclude as $elem) {
            $cmd .= " --exclude " . escapeshellarg($elem) . " ";
        }

        $cmd .= " --exclude .DS_Store ";

        return $cmd;
    }

    /**
     * @return string
     */
    public function getLocal()
    {
        return $this->settings->local . " ";
    }

    /**
     * @return string
     */
    public function getOnlyPathLocal()
    {
        $local = trim($this->settings->local);

        if (strpos($local, '*') === strlen($local) - 1) {
            $local = substr($local, 0, -1);
        }

        return escapeshellarg($local) . " ";
    }

    /**
     * @return string
     */
    public function getRemote()
    {
        return escapeshellarg($this->settings->remote) . " ";
    }

    /**
     * Method to run the rsync command
     *
     * @return void
     */
    public function run()
    {
        $escaped_command = $this->getCmd();
        system($escaped_command);
    }

    /**
     * Define if rsync is installed.
     *
     * @return bool
     */
    public function isInstalled()
    {
        $has = exec('hash rsync 2>&1');
        return (strlen($has) === 0);
    }
}
