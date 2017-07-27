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
use AwsUpload\Model\Status;
use AwsUpload\Command\Command;
use AwsUpload\Setting\SettingFiles;

class ListEnvs extends BasicCommand implements ValidCommand
{
    /**
     * The project to use as filter.
     *
     * @var string
     */
    public $proj;

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
        $this->proj = $this->app->args->getFirst('envs');

        if (!$this->isValid() && !$quiet) {
            return $this->handleError();
        }

        $envs = SettingFiles::getEnvs($this->proj);
        $envs = implode(' ', $envs);
        $this->msg = $envs . "\n";

        return $this->handleSuccess();
    }

    /**
     * Method to check if key isValid and good to proceed.
     *
     * @return boolean
     */
    public function isValid()
    {
        $projs = SettingFiles::getProjs();
        $envs  = SettingFiles::getEnvs($this->proj);

        $tests = array(
            "is_env"     => count($envs) > 0,
            "is_project" => count($projs) > 0,
        );

        $msgs = array(
            "is_env"     => Facilitator::onGetEnvsForProj($this->proj),
            "is_project" => Facilitator::onNoProjects(),
        );

        $valid = $this->validate($tests, $msgs);

        return $valid;
    }
}
