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

use AwsUpload\Status;
use AwsUpload\Facilitator;
use AwsUpload\Command\Command;
use AwsUpload\Setting\SettingFiles;

class ListEnvs extends AdvancedCommand
{
    /**
     * Method used to print the environments available for a project.
     *
     * The main idea is that for each project you have different envs.
     * Eg:
     *     - proj.dev.json    -> env: dev
     *     - proj.stagin.json -> env: staging
     *     - proj.prod.json   -> env: prod
     *
     * @return int The status code.
     */
    public function run()
    {
        $quiet      = $this->app->is_quiet;
        $projFilter = $this->app->args->getFirst('envs');

        if (!$this->isValid($projFilter) && !$quiet) {
            $this->app->inline($this->msg);

            return Status::ERROR_INVALID;
        }

        $envs  = SettingFiles::getEnvs($projFilter);

        $envs = implode(' ', $envs);
        $msg  = $envs . "\n";
        $this->app->inline($msg);

        return Status::SUCCESS;
    }

    /**
     * Method to check if key isValid and good to proceed.
     *
     * @param  string  $projFilter The filter to get the envs.
     *
     * @return boolean
     */
    public function isValid($projFilter)
    {
        $projs = SettingFiles::getProjs();
        $envs  = SettingFiles::getEnvs($projFilter);

        $tests = array(
            "is_no_env"     => count($envs) === 0,
            "is_no_project" => count($projs) === 0,
        );

        $msgs = array(
            "is_no_env"     => Facilitator::onGetEnvsForProj($projFilter),
            "is_no_project" => Facilitator::onNoProjects(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
