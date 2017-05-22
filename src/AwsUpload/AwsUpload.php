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
use AwsUpload\Io\Output;

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
    public $is_verbose = false;

    /**
     * It define if aws-upload has to stay quiet and do not print additional information.
     *
     * @var bool
     */
    public $is_quiet = false;

    /**
     * It containst to arguments passed to the shell script.
     *
     * @var \cli\Arguments
     */
    public $args;

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

        $arguments = new Arguments();
        $arguments->addFlag(array('quiet', 'q'), 'Turn off verboseness, without being quiet');
        $arguments->addFlag(array('verbose', 'v'), 'Increase the verbosity of messages');
        $arguments->addFlag(array('version', 'V'), 'Display this application version');
        $arguments->addFlag(array('help', 'h'), 'Display this help message');
        $arguments->addFlag(array('keys', 'k'), 'Print all the projects\' keys');
        $arguments->addFlag(array('projs', 'p'), 'Print all the projects');
        $arguments->addFlag(array('self-update', 'selfupdate'), 'Updates aws-upload to the latest version');
        $arguments->addFlag('simulate', 'Simulate the command without to upload anything');

        $arguments->addOption(
            array('envs', 'e'),
            array(
                'default'     => '',
                'description' => 'Print all the environments'
            )
        );
        $arguments->addOption(
            array('new', 'n'),
            array(
                'default'     => '',
                'description' => 'Create a new setting file'
            )
        );
        $arguments->addOption(
            array('edit', 'E'),
            array(
                'default'     => '',
                'description' => 'Edit a setting file'
            )
        );
        $arguments->addOption(
            array('copy', 'cp'),
            array(
                'default'     => '',
                'description' => 'Copy a setting file'
            )
        );
        $arguments->addOption(
            array('check', 'c'),
            array(
                'default'     => '',
                'description' => 'Check a setting file'
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
        
        if ($this->args['quiet']) {
            $this->is_quiet = true;
        }

        $cmdName = $this->getCmdName();
        $cmd = new $cmdName($this);
        $cmd->run();
    }

    /**
     * Method to decide which cmd to run.
     *
     * @return string
     */
    public function getCmdName()
    {
        $cmd = '';
        $cmdList = array(
            "help" => "AwsUpload\Command\Help",
            "version" => "AwsUpload\Command\Version",
            "keys" => "AwsUpload\Command\ListKeys",
            "projs" => "AwsUpload\Command\ListProjects",
            "envs" => "AwsUpload\Command\ListEnvs",
            "new" => "AwsUpload\Command\NewSettingFile",
            "edit" => "AwsUpload\Command\EditSettingFile",
            "check" => "AwsUpload\Command\CheckSettingFile",
            "copy" => "AwsUpload\Command\CopySettingFile",
            "selfupdate" => "AwsUpload\Command\SelfUpdate",
            "self-update" => "AwsUpload\Command\SelfUpdate",
        );

        foreach ($cmdList as $arg => $cmdName) {
            if ($this->args[$arg] && empty($cmd)) {
                $cmd = $cmdName;
            }
        }

        if (empty($cmd)) {
            $cmd = 'AwsUpload\Command\FullInfo';

            if ($this->hasWildArgs()) {
                $cmd = 'AwsUpload\Command\Upload';
            }
        }

        return $cmd;
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
        $this->out->render($msg . "\n");
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
}
