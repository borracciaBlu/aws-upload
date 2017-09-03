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

namespace AwsUpload\Model;

class Settings
{
    /**
     * The path to the setting file.
     *
     * @var string
     */
    public $path;

    /**
     * The setting file exists.
     *
     * @var bool
     */
    public $file_exists;

    /**
     * Status of the setting file as json.
     *
     * @var bool
     */
    public $is_valid_json;

    /**
     * Error related to json.
     *
     * @var string
     */
    public $error_json = '';

    /**
     * @var string
     */
    public $pem;

    /**
     * @var string
     */
    public $local;

    /**
     * @var string
     */
    public $remote;

    /**
     * @var array
     */
    public $exclude;

    public function __construct($path)
    {
        $this->path = $path;

        $this->file_exists = $this->fileExists();
        $this->is_valid_json = $this->isValidJson();
        $this->error_json = $this->getErrorJson();

        $this->load();
    }

    /**
     * Method to check if the setting file it does exist.
     *
     * @return bool
     */
    public function fileExists()
    {
        return file_exists($this->path) && is_file($this->path);
    }

    /**
     * Method to get the json if the file exists.
     *
     * @return bool
     */
    public function getJson()
    {
        $json = '';
        if ($this->file_exists) {
            $tmp = file_get_contents($this->path);
            $json = json_decode($tmp, true);
        }

        return $json;
    }

    /**
     * Method to chek if a file is a valid json.
     *
     * @return bool
     */
    public function isValidJson()
    {
        $this->getJson();
        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * Method to get the json error description.
     *
     * @return string
     */
    public static function getErrorJson()
    {
        $errors = array(
            JSON_ERROR_NONE => '',
            JSON_ERROR_DEPTH => " - Maximum stack depth exceeded\n",
            JSON_ERROR_STATE_MISMATCH => " - Underflow or the modes mismatch\n",
            JSON_ERROR_CTRL_CHAR => " - Unexpected control character found\n",
            JSON_ERROR_SYNTAX => " - Syntax error, malformed JSON\n",
            JSON_ERROR_UTF8 => " - Malformed UTF-8 characters, possibly incorrectly encoded\n",
        );
        $last_error = json_last_error();
        
        $msg = ' - Unknown error';
        if (array_key_exists($last_error, $errors)) {
            $msg = $errors[$last_error];
        }

        return $msg;
    }

    /**
     * Load the values in the obj.
     *
     * @return void
     */
    public function load()
    {
        $tmp = (object) $this->getJson();

        $this->pem = property_exists($tmp, 'pem') ? $tmp->pem : '';
        $this->local = property_exists($tmp, 'local') ? $tmp->local : '';
        $this->remote = property_exists($tmp, 'remote') ? $tmp->remote : '';
        $this->exclude = property_exists($tmp, 'exclude') ? $tmp->exclude : array();
    }
}
