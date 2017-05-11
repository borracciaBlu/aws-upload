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
     * Method used to chek a setting file for debug purpose.
     *
     * @return void
     */
    public function run()
    {
        $key = $this->app->args['check'];
        if (empty($key)) {
            $msg = Facilitator::onNoProjects();

            $this->app->display($msg, 0);
            return;
        }

        if (!Check::isValidKey($key)) {
            $msg = Facilitator::onNoValidKey($key);

            $this->app->display($msg, 0);
            return;
        }

        if (!Check::fileExists($key)) {
            $msg = Facilitator::onNoFileFound($key);

            $this->app->display($msg, 0);
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
}
