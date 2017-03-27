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

namespace AwsUpload;

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

    /**
     * Method to initiate the rsync settings object
     *
     * The setting object is a object version of one of the files in the
     * aws-upload folder.
     *
     * @see SettingFiles::getObjcet($key)
     *
     * @param object $settings The object version of one of the
     *                         files in the aws-upload dir.
     */
    public function __construct($settings)
    {
        if (!is_object($settings)) {
            throw new \Exception("Settings has to be an objec", 1);
        }

        $this->settings = $settings;
        $this->cmd = $this->buildCmd();
    }

    /**
     * Method to build the rsync command from the settings object
     *
     * @return string The rsync command.
     */
    public function buildCmd()
    {
        $settings = $this->settings;
        $cmd = "rsync -ravze \"ssh -i " . $settings->pem . "\" ";
     
        if (isset($settings->exclude) && is_array($settings->exclude)) {
            foreach ($settings->exclude as $elem) {
                $cmd .= " --exclude " . escapeshellarg($elem) . " ";
            }
        }

        $cmd .= " --exclude .DS_Store ";
        $cmd .= $settings->local . " " . escapeshellarg($settings->remote);

        return $cmd;
    }

    /**
     * Method to run the rsync command
     *
     * @return void
     */
    public function run()
    {
        $escaped_command = $this->cmd;
        system($escaped_command);
    }
}
