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

class Rsync
{
    /**
     * It contains the text version of the command to run.
     *
     * @var string
     */
    public $cmd;

    /**
     * It containg the settings object
     *
     * Eg:
     * { pem , exclude, remote, local }
     *
     * @var object
     */
    public $settings;

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

    /**
     * Method to build the rsync command from the settings object
     *
     * @return string The rsync command.
     */
    public function getCmd()
    {
        $settings = $this->settings;

        $cmd = "rsync ";
        $cmd .= ($this->is_verbose) ? " -v --stats --progress " : "";
        $cmd .= "-ravze \"ssh -i " . $settings->pem . "\" ";

        // exclude
        $cmd .= $this->getExclude();
        $cmd .= " --exclude .DS_Store ";

        $cmd .= $settings->local . " " . escapeshellarg($settings->remote);

        return $cmd;
    }

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

        return $cmd;
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
