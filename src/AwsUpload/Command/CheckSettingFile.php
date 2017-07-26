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

use AwsUpload\Facilitator;
use AwsUpload\Setting\SettingFiles;

class CheckSettingFile extends FileCommand
{
    /**
     * Initializes the command.
     */
    public function init()
    {
        $this->key = $this->app->args->getFirst('check');
    }

    /**
     * Exec the check on the setting file.
     *
     * @see FileCommand::run
     * @return void
     */
    public function exec()
    {
        $report = $this->getReport();
        $this->msg = Facilitator::reportBanner($report);
    }

    public function getReport()
    {
        $path = SettingFiles::getPath($this->key);
        $settings = SettingFiles::getObject($this->key);

        $pem_exists    = file_exists($settings->pem);
        $pem_perms     = ($pem_exists) ? decoct(fileperms($settings->pem) & 0777) : '-';
        $is_400        = ($pem_perms === '400');
        $clean_local   = str_replace('*', '', $settings->local);
        $local_exists  = file_exists($clean_local);

        $report = array(
            "path" => $path,
            "is_valid_json" => $settings->is_valid_json,
            "error_json" => $settings->error_json,
            "pem" => $settings->pem,
            "pem_exists" => $pem_exists,
            "pem_perms" => $pem_perms,
            "is_400" => $is_400,
            "local" => $settings->local,
            "local_exists" => $local_exists,
        );

        return $report;
    }
}
