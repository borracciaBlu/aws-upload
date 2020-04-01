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
use AwsUpload\Io\OutputCli;

class AwsUpload
{
    /**
     * Application version.
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
     * Zsh plugin version.
     * Given a version number MAJOR.MINOR.PATCH, increment the:
     *
     * MAJOR version when you make incompatible API changes,
     * MINOR version when you add functionality in a backwards-compatible manner, and
     * PATCH version when you make backwards-compatible bug fixes.
     *
     * @see http://semver.org/
     * @var string VERSION
     */
    public $zshPlugin;

    /**
     * Initializes the command.
     *
     * The main purpose is to define the args for the script
     * and populate `$this->args`.
     */
    public function __construct($version = 'test', $zshPlugin = 'test')
    {

        $this->version = $version;
        $this->zshPlugin = $zshPlugin;
    }

    /**
     * Method to run the aws-upload cmd.
     *
     * @return int The status code.
     */
    public function run()
    {
        $args = $this->getArgs();
        $output = new OutputCli($args);

        $cmdName = $this->getCmdName($args);
        $cmd = new $cmdName($this, $args, $output);

        return $cmd->run();
    }

    /**
     * Method to get the args passed in cli
     *
     * @return \AwsUpload\Io\Args
     */
    public function getArgs()
    {
        $args = new Args();

        $args->addFlags(array(
            'quiet'    => array('quiet', 'q'),
            'verbose'  => array('verbose', 'v'),
            'simulate' => array('simulate', 'dry-run')
        ));

        $args->addCmds(array(
            'new'        => array('new', 'n'),
            'envs'       => array('envs', 'e'),
            'edit'       => array('edit', 'E'),
            'diff'       => array('diff', 'df'),
            'delete'     => array('delete', 'rm'),
            'copy'       => array('copy', 'cp'),
            'help'       => array('help', 'h'),
            'keys'       => array('keys', 'k'),
            'projs'      => array('projs', 'p'),
            'check'      => array('check', 'c'),
            'import'     => array('import', 'i'),
            'export'     => array('export', 'ex'),
            'version'    => array('version', 'V'),
            'selfupdate' => array('self-update', 'selfupdate'),
            'autocomplete' => array('autocomplete'),
        ));

        $args->parse();

        return $args;
    }

    /**
     * Method to decide which cmd to run.
     *
     * @var \AwsUpload\Io\Args $argList
     * @return string
     */
    public function getCmdName($argList)
    {
        $cmd = '';
        $cmdList = array(
            'help'    => 'AwsUpload\Command\Help',
            'version' => 'AwsUpload\Command\Version',
            'keys'    => 'AwsUpload\Command\Keys',
            'projs'   => 'AwsUpload\Command\Projs',
            'envs'    => 'AwsUpload\Command\Envs',
            'new'     => 'AwsUpload\Command\New',
            'diff'    => 'AwsUpload\Command\Diff',
            'edit'    => 'AwsUpload\Command\Edit',
            'copy'    => 'AwsUpload\Command\Copy',
            'delete'  => 'AwsUpload\Command\Delete',
            'check'   => 'AwsUpload\Command\Check',
            'import'  => 'AwsUpload\Command\Import',
            'export'  => 'AwsUpload\Command\Export',
            'selfupdate'   => 'AwsUpload\Command\SelfUpdate',
            'autocomplete' => 'AwsUpload\Command\AutoComplete',
        );

        foreach ($cmdList as $arg => $cmdName) {
            if ($argList->{$arg} && empty($cmd)) {
                $cmd = $cmdName;
            }
        }

        if (empty($cmd)) {
            $cmd = 'AwsUpload\Command\FullInfo';

            if ($argList->wild) {
                $cmd = 'AwsUpload\Command\Upload';
            }
        }

        return $cmd . 'Command';
    }
}
