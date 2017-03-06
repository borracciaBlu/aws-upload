<?php
/**
 * aws-upload - aws-upload is a CLI Tool to manage rsync
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
    public $cmd;
    public $settings;

    public function __construct($settings)
    {
        if (empty($settings)) {
            throw new Exception("You MUST provide a settings object as parameter", 1);
        }

        if (!is_object($settings)) {
            throw new Exception("Settings has to be an objec", 1);
        }

        $this->settings = $settings;
        $this->cmd = $this->buildCmd();
    }


    public function buildCmd()
    {
        $settings = $this->settings;
        $cmd = "rsync -ravze \"ssh -i " . $settings->pem . "\" ";
     
        if (isset($settings->exclude) && is_array($settings->exclude)) {
            foreach ($settings->exclude as $elem) {
                $cmd .= " --exclude " . $elem . " ";
            }
        }
        $cmd .= " --exclude .DS_Store ";

        $cmd .= $settings->local . " " . $settings->remote . "";

        return $cmd;
    }

    public function run()
    {
        $escaped_command = $this->cmd;
        system($escaped_command);
    }
}
