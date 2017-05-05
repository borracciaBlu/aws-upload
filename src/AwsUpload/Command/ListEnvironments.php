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

use AwsUpload\Command\Command;
use AwsUpload\Facilitator;
use AwsUpload\SettingFiles;

class ListEnvironments extends BasicCommand
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
     * @return void
     */
	public function run()
	{
        $quiet      = $this->app->is_quiet;
        $projFilter = $this->app->args['envs'];

        $projs = SettingFiles::getProjs();
        if (count($projs) === 0 && !$quiet) {
            $msg = Facilitator::onNoProjects();

            $this->app->display($msg, 0);
            return;
        }

        $envs = SettingFiles::getEnvs($projFilter);
        if (count($envs) === 0 && !$quiet) {
            $msg = Facilitator::onGetEnvsForProj($projFilter);

            $this->app->display($msg, 0);
            return;
        }

        $envs = implode(' ', $envs);
        $msg = $envs . "\n";

        $this->app->display($msg, 0);
	}
}
