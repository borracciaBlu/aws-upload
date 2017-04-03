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

use cli\Arguments;
use AwsUpload\Check;
use AwsUpload\Rsync;
use AwsUpload\Output;
use AwsUpload\SettingFiles;

class AwsUpload
{
    /**
     * Given a version number MAJOR.MINOR.PATCH, increment the:
     *
     * MAJOR version when you make incompatible API changes,
     * MINOR version when you add functionality in a backwards-compatible manner, and
     * PATCH version when you make backwards-compatible bug fixes.
     *
     * @see http://semver.org/
     * @var string VERSION
     */
    public $version;

    /**
     * It defines if the class is running under phpunit.
     *
     * The issue is all the time we have exit(0) becuase it kills the
     * execution of phpunit.
     *
     * @var bool
     */
    public $is_phpunit = false;

    /**
     * It define if aws-upload has to print additional info.
     *
     * @var bool
     */
    protected $is_verbose = false;

    /**
     * It containst to arguments passed to the shell script.
     *
     * @var array
     */
    protected $args;

    /**
     * It containst output class to manage the script output.
     *
     * @var Output
     */
    protected $out;

    /**
     * Initializes the command.
     *
     * The main purpose is to define the args for the script
     * and populate `$thhis->args`.
     */
    public function __construct($verison = 'test')
    {

        $this->version = $verison;

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

        // collect all the errors rised by cli\Arguments
        // [cli\Arguments] no value given for -e
        // /vendor/wp-cli/php-cli-tools/lib/cli/Arguments.php:433
        // /vendor/wp-cli/php-cli-tools/lib/cli/Arguments.php:465
        // /vendor/wp-cli/php-cli-tools/lib/cli/Arguments.php:402
        // /src/AwsUpload/AwsUpload.php:86
        $errorHandler = function () {
        };
        set_error_handler($errorHandler);
        $arguments->parse();
        restore_error_handler();

        $this->args = $arguments;
        $this->out = new Output();
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
            $this->cmdUpload();
        } else {
            $this->cmdFullInfo();
        }
    }

    /**
     * Method to wrap render and graceExit.
     *
     * The main idea is to setup the system to print with exit
     * and be ready for phpunit.
     *
     * @param string|null $msg
     * @param integer $status
     *
     * @return  void
     */
    public function display($msg, $status)
    {
        $this->out->is_phpunit = $this->is_phpunit;
        $this->out->render($msg);
        $this->out->graceExit($status);
    }

    /**
     * Method to wrap render and graceExit.
     *
     * The main idea is to setup the system to print and be ready
     * for phpunit.
     *
     * @param string|null $msg
     *
     * @return  void
     */
    public function inline($msg)
    {
        $this->out->is_phpunit = $this->is_phpunit;
        $this->out->render($msg);
    }

    /**
     * Method used to print additional text with the flag verbose.
     *
     * @param string $msg The text to print in verbose state
     *
     * @return void
     */
    public function verbose($msg)
    {
        if ($this->is_verbose) {
            $this->inline($msg . "\n\n");
        }
    }

    /**
     * Method used to print the version.
     *
     * @return void
     */
    public function cmdVersion()
    {
        $msg = Facilitator::version($this->version);

        $this->display($msg, 0);
    }

    /**
     * Method used to print the help.
     *
     * @return void
     */
    public function cmdHelp()
    {
        $msg = Facilitator::help();

        $this->display($msg, 0);
    }

    /**
     * Method used to print the full aws-upload info.
     *
     * -  banner
     * -  version
     * -  help
     *
     * @return void
     */
    public function cmdFullInfo()
    {
        $msg = Facilitator::banner();
        $msg .= Facilitator::version($this->version);
        $msg .= Facilitator::help();

        $this->display($msg, 0);
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

        if (count($projs) === 0 && !$quiet) {
            $msg = Facilitator::onNoProjects();

            $this->display($msg, 0);
            return;
        }

        $projs = implode(' ', $projs);
        $msg = $projs . "\n";

        $this->display($msg, 0);
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
        if (count($projs) === 0 && !$quiet) {
            $msg = Facilitator::onNoProjects();

            $this->display($msg, 0);
            return;
        }

        $envs = SettingFiles::getEnvs($projFilter);
        if (count($envs) === 0 && !$quiet) {
            $msg = Facilitator::onGetEnvsForProj($projFilter);

            $this->display($msg, 0);
            return;
        }

        $envs = implode(' ', $envs);
        $msg = $envs . "\n";

        $this->display($msg, 0);
    }

    /**
     * Method to run the rsync cmd.
     *
     * The main idea is:
     *     1 - get [$proj].[$env].json file
     *     2 - convert the file to an obj
     *     3 - run rsync with the details in the obj
     *
     * @return void
     */
    public function cmdUpload()
    {
        $items = $this->getWildArgs();
        list($proj, $env) = $this->extractProjEnv($items);

        $key = $proj . "." . $env;
        if (!Check::fileExists($key)) {
            $msg = Facilitator::onNoFileFound($proj, $env);
            
            $this->display($msg, 0);
            return;
        }

        $settings = SettingFiles::getObject($key);
        $rsync = new Rsync($settings);

        $msg = Facilitator::rsyncBanner($proj, $env, $rsync->cmd);
        $this->inline($msg);

        if ($this->args['simulate']) {
            $msg = 'Simulation mode' . "\n";

            $this->display($msg, 0);
            return;
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

        $toDelete = array('-v', '--verbose', '--simulate', '-q', '--quiet');
        foreach ($args as $key => $arg) {
            if (in_array($arg, $toDelete)) {
                unset($args[$key]);
            }
        }

        return array_values($args);
    }

    /**
     * Method to extract the project and the environment from an array
     *
     * This method is to cover two cases:
     * - aws-upload proj env // double notation
     * - aws-upload proj.env // key notation
     *
     * @param array $items It contains all the extra args.
     *
     * @return array       The array will contain 2 elements in any case.
     */
    public function extractProjEnv($items)
    {
        $proj = 'no-project-given';
        $env  = 'no-environment-given';

        // reorder items in array
        if (is_array($items)) {
            $items = array_values($items);
        }

        if (count($items) === 1) {
            if (strpos($items[0], '.') !== false) {
                $items = explode('.', $items[0]);
            }
        }

        if (count($items) === 2) {
            $proj = $items[0];
            $env  = $items[1];
        }

        return array($proj, $env);
    }
}
