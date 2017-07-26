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

use AwsUpload\Io\Args;
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
     * @var \AwsUpload\Io\Args
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
     * and populate `$this->args`.
     */
    public function __construct($version = 'test')
    {

        $this->version = $version;

        $args = new Args();
        $args->addFlags(array(
            'quiet'    => array('quiet', 'q'),
            'verbose'  => array('verbose', 'v'),
            'simulate' => array('simulate')
        ));
        $args->addCmds(array(
            'new'        => array('new', 'n'),
            'envs'       => array('envs', 'e'),
            'edit'       => array('edit', 'E'),
            'copy'       => array('copy', 'cp'),
            'help'       => array('help', 'h'),
            'keys'       => array('keys', 'k'),
            'projs'      => array('projs', 'p'),
            'check'      => array('check', 'c'),
            'version'    => array('version', 'V'),
            'selfupdate' => array('self-update', 'selfupdate'),
            'autocomplete' => array('autocomplete'),
        ));
        $args->parse();

        $this->args = $args;
        $this->out = new Output();
    }

    /**
     * Method to run the aws-upload cmd.
     *
     * @return int The status code.
     */
    public function run()
    {
        if ($this->args->verbose) {
            $this->is_verbose = true;
        }
        
        if ($this->args->quiet) {
            $this->is_quiet = true;
        }

        $cmdName = $this->getCmdName();
        $cmd = new $cmdName($this);

        return $cmd->run();
    }

    /**
     * Method to set different output.
     *
     * @return void
     */
    public function setOutput(\AwsUpload\Io\Output $output)
    {
        $this->out = $output;
    }

    /**
     * Method to decide which cmd to run.
     *
     * @return string
     */
    public function getCmdName()
    {
        $cmd = $this->fetchArgsCmd();

        if (empty($cmd)) {
            $cmd = 'AwsUpload\Command\FullInfo';

            if ($this->args->wild) {
                $cmd = 'AwsUpload\Command\Upload';
            }
        }

        return $cmd;
    }

    /**
     * Method to check cmd to run against list of cmds with arguments.
     *
     * @return string
     */
    public function fetchArgsCmd()
    {
        $cmd = '';
        $cmdList = array(
            "help"    => "AwsUpload\Command\Help",
            "version" => "AwsUpload\Command\Version",
            "keys"    => "AwsUpload\Command\ListKeys",
            "projs"   => "AwsUpload\Command\ListProjects",
            "envs"    => "AwsUpload\Command\ListEnvs",
            "new"     => "AwsUpload\Command\NewSettingFile",
            "edit"    => "AwsUpload\Command\EditSettingFile",
            "check"   => "AwsUpload\Command\CheckSettingFile",
            "copy"    => "AwsUpload\Command\CopySettingFile",
            "selfupdate"   => "AwsUpload\Command\SelfUpdate",
            "autocomplete" => "AwsUpload\Command\AutoComplete",
        );

        foreach ($cmdList as $arg => $cmdName) {
            if ($this->args->{$arg} && empty($cmd)) {
                $cmd = $cmdName;
            }
        }

        return $cmd;
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
}
