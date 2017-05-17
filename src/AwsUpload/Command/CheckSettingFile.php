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
use AwsUpload\Command\Command;
use AwsUpload\Setting\SettingFiles;

class CheckSettingFile extends BasicCommand
{
    /**
     * The error messsage.
     *
     * @var string
     */
    public $msg;

    /**
     * Method used to chek a setting file for debug purpose.
     *
     * @return void
     */
    public function run()
    {
        $key = $this->app->args['check'];

        if (!$this->isValid($key)) {
            $this->app->display($this->msg, 0);
            return;
        }

        $path = SettingFiles::getPath($key);
        $settings = SettingFiles::getObject($key);

        $is_valid_json = Check::isValidJSON($path);
        $pem_exists    = file_exists($settings->pem);
        $pem_perms     = ($pem_exists) ? decoct(fileperms($settings->pem)  & 0777) : '-';
        $clean_local   = str_replace('*', '', $settings->local);
        $local_exists  = file_exists($clean_local);

        $report = array(
            "path" => $path,
            "is_valid_json" => $is_valid_json,
            "pem" => $settings->pem,
            "pem_exists" => $pem_exists,
            "pem_perms" => $pem_perms,
            "local" => $settings->local,
            "local_exists" => $local_exists,
        );

        $msg = Facilitator::reportBanner($report);
        $this->app->display($msg, 0);
    }

    /**
     * Method to check if key isValid and good to proceed.
     *
     * @param  string  $key The setting file key.
     *
     * @return boolean
     */
    public function isValid($key)
    {
        $valid = true;

        if (empty($key)) {
            $this->msg = Facilitator::onNoProjects();
            $valid = false;
        }

        if ($valid && !Check::isValidKey($key)) {
            $this->msg = Facilitator::onNoValidKey($key);
            $valid = false;
        }

        if ($valid && !Check::fileExists($key)) {
            $this->msg = Facilitator::onNoFileFound($key);
            $valid = false;
        }

        return $valid;
    }
}
