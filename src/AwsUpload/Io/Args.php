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

namespace AwsUpload\Io;

/**
 * @property bool quiet   If args quiet is present.
 * @property bool envs    If args envs is present.
 * @property bool proj    If args proj is present.
 * @property bool version If args version is present.
 * @property bool verbose If args verbose is present.
 * @property bool wild    If args wild is present.
 */
class Args
{

    /**
     * Flag that define if input $is_argv or a normal array.
     *
     * @var boolean
     */
    public $is_argv = true;

    /**
     * The input array to parse.
     *
     * @var array
     */
    public $input = array();

    /**
     * The flags array.
     *
     * @var array
     */
    protected $flags = array();

    /**
     * The command array.
     *
     * @var array
     */
    protected $cmds = array();

    /**
     * Multi dimensional array of boolean values to define if
     * a flag/option has been used.
     *
     * @var array
     */
    protected $props = array("wild" => false);

    /**
     * Multi dimensional array to contain the values for
     * a flag/option used.
     *
     * @var array
     */
    protected $params = array("wild" => array());

    /**
     * Define the parser state.
     * So which command we are currently parsing.
     *
     * @var string
     */
    protected $state = "wild";

    /**
     * The constructor for the Args.
     * You can define an alternative input source.
     *
     * @param array $input Alternative input.
     */
    public function __construct($input = null)
    {
        $this->is_argv = is_null($input);
        $this->input   = ($this->is_argv) ? $_SERVER['argv'] : $input;
    }

    /**
     * Magic method to access the $props array.
     *
     * E.g.: $args->quiet
     *
     * @param string $key The property we are trying to access.
     *
     * @return bool
     */
    public function __get($key)
    {
        return (array_key_exists($key, $this->props)) ? $this->props[$key] : false;
    }

    /**
     * Method to get the params collected for a specific command.
     *
     * @param string $key The command key.
     *
     * @return array
     */
    public function getParams($key)
    {
        $params = (array_key_exists($key, $this->params)) ? $this->params[$key] : array();
        return $params;
    }

    /**
     * Method to get the first element from params[key].
     *
     * @param string $key The command key.
     *
     * @return string
     */
    public function getFirst($key)
    {
        $params = $this->getParams($key);

        return (array_key_exists(0, $params)) ? $params[0] : null;
    }

    /**
     * Add the flags to parse.
     *
     * E.g.: $flags = [
     *                  "copy" => ["copy", "cp"],
     *                  "verbose" => ["verbose", "v"],
     *              ];
     *
     * @param array $flags
     */
    public function addFlags($flags)
    {
        $this->flags = $flags;
    }

    /**
     * Add the flags and options to parse as commands.
     *
     * E.g.: $cmd = [
     *                  "copy" => ["copy", "cp"],
     *                  "verbose" => ["verbose", "v"],
     *              ];
     *
     * @param array $cmds
     */
    public function addCmds($cmds)
    {
        $this->cmds = $cmds;
    }

    /**
     * Method to init the $props array.
     *
     * It contains if a command or an option exists.
     * e.g.: $this->props['wild'] = true;
     *
     * @return void
     */
    public function initProps()
    {
        foreach ($this->cmds as $key => $parseValues) {
            $this->props[$key] = false;
        }

        foreach ($this->flags as $key => $parseValues) {
            $this->props[$key] = false;
        }
    }

    /**
     * Method to init the $params array.
     *
     * It contains the arguments passed to a command.
     * e.g.: $this->params['wild'] = ['blog', 'dev'];
     *
     * @return void
     */
    public function initParams()
    {
        foreach ($this->cmds as $key => $parseValues) {
            $this->params[$key] = array();
        }
    }
    
    /**
     * Method to parse the input.
     *
     * @return void
     */
    public function parse()
    {
        $this->initProps();
        $this->initParams();
        $this->load();
    }

    /**
     * Method to load the values from input.
     *
     * @return void
     */
    public function load()
    {
        $input = $this->getCleanInput();
        $this->populate($input);
    }

    /**
     * Method to remove script name if input is from argv.
     *
     * @return array
     */
    public function getCleanInput()
    {
        $input = $this->input;

        if ($this->is_argv) {
            unset($input[0]);
        }

        return $input;
    }

    /**
     * The parser.
     *
     * @param array $input The cleaned input array.
     *
     * @return void
     */
    public function populate($input)
    {
        foreach ($input as $arg) {
            if ($this->isFlag($arg)) {
                continue;
            }

            if ($this->isCmd($arg)) {
                continue;
            }

            if ($this->state === 'wild') {
                $this->props['wild'] = true;
            }

            $this->params[$this->state][] = $arg;
        }
    }

    /**
     * Check if it's a isFlag
     *
     * @param string $arg One of the input values.
     *
     * @return boolean
     */
    public function isFlag($arg)
    {
        $clean_arg = trim($arg, "-");
        $is_flag   = false;

        foreach ($this->flags as $key => $parseValues) {
            foreach ($parseValues as $flag) {
                if ($flag === $clean_arg) {
                    $this->props[$key] = true;
                    $is_flag = true;
                }
            }
        }

        return $is_flag;
    }

    /**
     * Check if it's a isCmd
     *
     * @param string $arg One of the input values.
     *
     * @return boolean
     */
    public function isCmd($arg)
    {
        $clean_arg = trim($arg, "-");
        $is_cmd    = false;

        foreach ($this->cmds as $key => $parseValues) {
            foreach ($parseValues as $cmd) {
                if ($cmd === $clean_arg) {
                    $this->props[$key] = true;
                    $this->state = $key;
                    $is_cmd = true;
                }
            }
        }

        return $is_cmd;
    }
}
