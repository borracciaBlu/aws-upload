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

namespace AwsUpload\Message;

use cli\Table;
use AwsUpload\Io\Output;
use AwsUpload\Setting\SettingFile;

class ErrorMessage
{

    /**
     * Method to echo the help message about no project.
     *
     * @return string
     */
    public static function noProjects()
    {
        $msg = "It seems that you don't have any project setup.\nTry to type:\n\n"
             . "    <g>aws-upload new project.test</g>\n"
             . "\n";

        return $msg;
    }

    /**
     * Method to echo the help message about no project.
     *
     * @return string
     */
    public static function noCopyArgs()
    {
        $msg = "It seems that you don't proper arguments for this command.\nTry to type:\n\n"
             . "    <g>aws-upload copy oldproject.test project.test</g>\n"
             . "\n";

        return $msg;
    }

    /**
     * Method to echo the help message about when the pair project
     * environment doesn't exist.
     *
     * So actually, we check that project.env.json it does exist
     * otherwise we print this message.
     *
     * @param string $project The project name or key.
     * @param string $env     The env name.
     *
     * @return string
     */
    public static function noFileFound($project, $env = null)
    {
        $files = SettingFile::getList();
        if (count($files) === 0) {
            $msg = static::noProjects();
            return $msg;
        }

        $msg = "It seems that there is <r>NO</r> setting files for <y>" . $project .
               "</y>, <y>" . $env . "</y>\n\n";

        if (is_null($env)) {
            $msg = "It seems that there is <r>NO</r> setting files for <y>" . $project .
               "</y>\n\n";
        }

        $msg .= static::getProjEnvTable();

        return $msg;
    }

    /**
     * Method support if in AwsUpload::new the key is not valid.
     *
     * @param string $key The key prompted.
     *
     * @return string
     */
    public static function noValidKey($key)
    {
        $msg = "It seems that the key <y>" . $key . "</y> is not valid:\n\n"
             . "Please try to use this format:\n"
             . "    - [project].[environmet]\n\n"
             . "Examples of valid key to create a new setting file:\n"
             . "    - <g>my-site.staging</g>\n"
             . "    - <g>my-site.dev</g>\n"
             . "    - <g>my-site.prod</g>\n\n"
             . "Tips on choosing the key name:\n"
             . "    - for [project] and [environmet] try to be: short, sweet, to the point\n"
             . "    - use only one 'dot' . in the name\n"
             . "\n";
        return $msg;
    }

    /**
     * Method to support if in AwsUpload::new the key already exists.
     *
     * @param string $key E.g: proj.env
     *
     * @return string
     */
    public static function keyAlreadyExists($key)
    {
        $msg = "It seems that the key <y>" . $key . "</y> already exists try to use another one.\n\n"
             . "Please consider you already have the following elements:\n"
             . static::getProjEnvTable()
             . "\n";
        return $msg;
    }


    /**
     * Method to get the proj/env table.
     *
     * @return string
     */
    public static function getProjEnvTable()
    {
        $files = SettingFile::getList();

        $headers = array('Project', 'Environment');
        $data = array();
        foreach ($files as $file) {
            list($proj, $env, $ext) = explode(".", $file);
            $proj = Output::color("<g>" . $proj . "</g>");
            $env = Output::color("<g>" . $env . "</g>");

            $data[] = array($proj, $env);
        }

        $table = new Table();
        $table->setHeaders($headers);
        $table->setRows($data);

        $msg = '';
        foreach ($table->getDisplayLines() as $key => $line) {
            $msg .= $line . "\n";
        }

        return $msg;
    }
}
