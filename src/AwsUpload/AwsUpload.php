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

use cli\Arguments;
use AwsUpload\Rsync;
use AwsUpload\SettingFiles;

class AwsUpload
{
    /**
     * It containst to arguments passed to the shell script.
     *
     * @var array
     */
    protected $args;

    /**
     * It define if aws-upload has to print additional info.
     *
     * @var bool
     */
    protected $is_verbose = false;

    /**
     * Initializes the command.
     * 
     * The main purpose is to define the args for the script 
     * and populate `$thhis->args`.
     */
    public function __construct()
    {
        $strict = in_array('--strict', $_SERVER['argv']);
        $arguments = new Arguments(compact('strict'));

        $arguments->addFlag(array('quiet', 'q'), 'Turn off verboseness, without being quiet');
        $arguments->addFlag(array('verbose', 'v'), 'Increase the verbosity of messages');
        $arguments->addFlag(array('version', 'V'), 'Display this application version');
        $arguments->addFlag(array('help', 'h'), 'Display this help message');
        $arguments->addFlag(array('projs', 'p'), 'Print all the projects');
        $arguments->addFlag('simulate', 'Simulate the command without to upload anything');

        $arguments->addOption(
            array('envs', 'e'),
            array(
                'default'     => '',
                'description' => 'Print all the environments'
            )
        );

        $arguments->parse();

        $this->args = $arguments;
    }

    /**
     * Method to run the aws-upload cmd.
     *
     * @return void
     */
    public function run()
    {
        if ($this->args['verbose']) {
            $this->is_verbose = true;
        }

        if ($this->args['help']) {
            $this->cmdHelp();
        }

        if ($this->args['version']) {
            $this->cmdVersion();
        }

        if ($this->args['projs']) {
            $this->cmdProjs();
        }

        if ($this->args['envs']) {
            $this->cmdEnvs();
        }

        if ($this->hasWildArgs()) {
            list($proj, $env) = $this->getWildArgs();

            $this->cmdUpload($proj, $env);
        } else {
            Facilitator::banner();
            Facilitator::version();
            $this->cmdHelp();
        }

    }

    /**
     * Method used to print additional text with the flag verbose.
     *
     * @param string $text
     *
     * @return void
     */
    public function verbose($text)
    {
        if ($this->is_verbose) {
            echo $text . "\n\n";
        }
    }

    /**
     * Method used to print the version.
     *
     * @return void
     */
    public function cmdVersion()
    {
        Facilitator::version();
        exit(0);
    }

    /**
     * Method used to print the help.
     *
     * @return void
     */
    public function cmdHelp()
    {
        Facilitator::help();
        echo "\n\n";
        exit(0);
    }

    /**
     * Method used to print the projects available.
     *
     * The main idea is that you can get the projects from the files in
     * the aws-upload home folder.
     * Eg:
     *     - proj-1.dev.json    -> proj: proj-1
     *     - proj-1.stagin.json -> proj: proj-1
     *     - proj-2.prod.json   -> proj: proj-2
     *
     * @return void
     */
    public function cmdProjs()
    {
        $quiet = $this->args['quiet'];

        $projs = SettingFiles::getProjs();
        if (count($projs) === 0 && ! $quiet) {
            Facilitator::onNoProjects();
        }

        $projs = implode(' ', $projs);

        echo $projs . "\n";
        exit(0);
    }

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
    public function cmdEnvs()
    {
        $quiet      = $this->args['quiet'];
        $projFilter = $this->args['envs'];

        $projs = SettingFiles::getProjs();
        if (count($projs) === 0 && ! $quiet) {
            Facilitator::onNoProjects();
        }

        $envs = SettingFiles::getEnvs($projFilter);
        if (count($envs) === 0 && ! $quiet) {
            Facilitator::onGetEnvsForProj($projFilter);
        }
        $envs = implode(' ', $envs);

        echo $envs . "\n";
        exit(0);
    }


    /**
     * Method to run the rsync cmd.
     *
     * The main idea is:
     *     1 - get [$proj].[$env].json file
     *     2 - convert the file to an obj
     *     3 - run rsync with the details in the obj
     * 
     * @param string $proj It defines the project you want to upload.
     * @parma string $env  It defines the environment you want to upload to.
     * 
     * @return void
     */
    public function cmdUpload($proj, $env)
    {
        $key = $proj . "." . $env;
        $settings = SettingFiles::getObject($key);
        $rsync = new Rsync($settings);

        echo "=================================" . "\n";
        echo "Proj: " . escapeshellarg($proj) . "\n";
        echo "Env: " . escapeshellarg($env) . "\n";
        echo "Cmd: " . "\n";
        echo $rsync->cmd . "\n";
        echo "=================================" . "\n";

        if ($this->args['simulate']) {
            echo 'Simulation mode' . "\n";
            exit(0);
        }

        $rsync->run();
    }

    /**
     * Method to check if there are spare wild arguments to use as
     * input to select the project and the environment.
     * 
     * @return boolean
     */
    public function hasWildArgs()
    {
        $args = $this->getWildArgs();
        $hasWildArgs = count($args) > 0;

        return $hasWildArgs;
    }

    /**
     * Get the wild argument.
     * 
     * The case is when someone is typing:
     *     aws-upload proj env 
     * 
     * @return array
     */
    public function getWildArgs()
    {
        $args = $_SERVER['argv'];

        // remove script name
        unset($args[0]);

        $toDelete = array('-v', '--verbose', '--simulate');
        foreach ($args as $key => $arg) {
            if (in_array($arg, $toDelete)) {
                unset($args[$key]);
            }
        }

        return $args;
    }

}
