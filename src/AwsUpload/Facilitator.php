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

use cli\Table;
use AwsUpload\Io\Output;
use AwsUpload\Setting\SettingFiles;

class Facilitator
{
    /**
     * Method to echo the aws-upload banner.
     *
     * @return string
     */
    public static function banner()
    {
        $banner = <<<EOT
                                       _                 _ 
                                      | |               | |
  __ ___      _____ ______ _   _ _ __ | | ___   __ _  __| |
 / _` \ \ /\ / / __|______| | | | '_ \| |/ _ \ / _` |/ _` |
| (_| |\ V  V /\__ \      | |_| | |_) | | (_) | (_| | (_| |
 \__,_| \_/\_/ |___/       \__,_| .__/|_|\___/ \__,_|\__,_|
                                | |                        
                                |_|                        

EOT;
        return "<g>" . $banner . "</g>";
    }

    /**
     * Method to echo the current version.
     *
     * @param string $version The version.
     *
     * @return string
     */
    public static function version($version)
    {
        $msg = "<g>aws-upload</g> version <y>" . $version . "</y> \n";
        return $msg;
    }

    /**
     * Method to echo the help message.
     *
     * @return string
     */
    public static function help()
    {
        $msg = <<<EOT
        
<y>Usage:</y>
  aws-upload [options] [project] [environment]

<y>Output Options:</y>

  <g>-v|--verbose</g>                Output more verbose information.
  <g>-q|--quiet</g>                  Checks that version is greater than min and exits.
  <g>--simulate</g>                  It simulates the rsync command without upload anything.

<y>Miscellaneous Options:</y>

   <g>-h|--help</g>                  Prints this usage information.
   <g>-V|--version</g>               Prints the version and exits.

<y>Available commands:</y>

   <g>-k|--keys</g>                  Print all the projects' keys.
   <g>-p|--projs</g>                 Print all the projects.
   <g>-e|--envs <proj></g>           Print all the environments for a specific project.
   <g>-n|--new <proj>.<env></g>      Create a new setting file.
   <g>-E|--edit <proj>.<env></g>     Edit a setting file.
   <g>-cp|--copy <src> <dest></g>    Copy a setting file.
   <g>-c|--check <proj>.<env></g>    Check a setting file for debug.
   <g>self-update</g>                Updates aws-upload to the latest version.
   <g>selfupdate</g>                 Updates aws-upload to the latest version.


EOT;
        return $msg;
    }

    /**
     * Method to echo the aws-upload banner.
     *
     * @param string $proj The project name.
     * @param string $env  The env name.
     * @param string $cmd  The rsync cmd.
     *
     * @return string
     */
    public static function rsyncBanner($proj, $env, $cmd)
    {
        $proj = escapeshellarg($proj);
        $env = escapeshellarg($env);

        $msg = <<<EOT
=================================
Proj:  $proj
Env: $env
Cmd:
$cmd
=================================

EOT;
        return $msg;
    }

    /**
     * Method to echo the aws-upload check report.
     *
     * @param array $report The report value
     *
     * @return string
     */
    public static function reportBanner($report)
    {
        // Json
        $msg = "File analysing:\n"
             . "<y>" . $report['path'] . "</y>" . "\n";

        $msg .= ($report['is_valid_json']) ? "Json:            <g>VALID</g>\n": "Json:            <r>INVALID</r>\n";

        if (!$report['is_valid_json']) {
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    $error = ' - No errors';
                break;
                case JSON_ERROR_DEPTH:
                    $error = ' - Maximum stack depth exceeded';
                break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error = ' - Underflow or the modes mismatch';
                break;
                case JSON_ERROR_CTRL_CHAR:
                    $error = ' - Unexpected control character found';
                break;
                case JSON_ERROR_SYNTAX:
                    $error = ' - Syntax error, malformed JSON';
                break;
                case JSON_ERROR_UTF8:
                    $error = ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
                default:
                    $error = ' - Unknown error';
                break;
            }

             $msg .= $error . "\n";
        }

        // Pem
        $msg .= "\nPem File:\n"
             . "<y>" . $report['pem'] . "</y>" . "\n";
        $msg .= ($report['pem_exists']) ?   "Pem:              <g>EXISTS</g>\n": "Pem:              <r>NOT EXISTS</r>\n";

        if ($report['pem_exists']) {
            $msg .= "Pem Perm:         ";
            $msg .= ($report['pem_perms'] === '400') ?  "<g>". $report['pem_perms'] . "</g>" : "<r>". $report['pem_perms'] . "</r>";
            $msg .= "\n";

            if ($report['pem_perms'] !== '400') {
                $msg .=  'Try to type: chmod 400 ' . $report['pem'] . "\n";
            }
        }

        // Local
        $msg .= "\nLocal Folder:\n"
             . "<y>" . $report['local'] . "</y>" . "\n";
        $msg .= ($report['local_exists']) ? "Local Folder:     <g>EXISTS</g>\n": "Local Folder:     <r>NOT EXISTS</r>\n";

        return $msg;
    }

    /**
     * Method to echo the help message about no project.
     *
     * @return string
     */
    public static function onNoProjects()
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
    public static function onNoCopyArgs()
    {
        $msg = "It seems that you don't proper arguments for this command.\nTry to type:\n\n"
             . "    <g>aws-upload copy oldproject.test project.test</g>\n"
             . "\n";

        return $msg;
    }

    /**
     * Method to echo the help message about when the project
     * selected doesn't exist.
     *
     * @param string $projFilter The project name.
     *
     * @return string
     */
    public static function onGetEnvsForProj($projFilter)
    {
        $projs = SettingFiles::getProjs();
        $msg = "The project <r>" . $projFilter . "</r> you are tring to use doesn't exist." . "\n\n";

        $next = "These are the available projects: \n\n";
        foreach ($projs as $proj) {
            $next .= "  +  <g>" . $proj . "</g>\n";
        }

        $next .= "\nTo get the envs from one of them, run (for example):\n\n" .
                 "   aws-upload -e " . $projs[0] . "\n";

        return $msg . $next . "\n";
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
    public static function onNoFileFound($project, $env = null)
    {
        $files = SettingFiles::getList();
        if (count($files) === 0) {
            $msg = static::onNoProjects();
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
    public static function onNoValidKey($key)
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
    public static function onKeyAlreadyExists($key)
    {
        $msg = "It seems that the key <y>" . $key . "</y> already exists try to use another one.\n\n"
             . "Please consider you already have the following elements:\n"
             . static::getProjEnvTable()
             . "\n";
        return $msg;
    }

    /**
     * Method to support if when AwsUpload::new is successfull.
     *
     * @param string $key E.g: proj.env
     *
     * @return string
     */
    public static function onNewSettingFileSuccess($key)
    {
        $msg = "The setting file <y>" . $key . ".json</y> has been created succesfully.\n\n"
             . "To edit the file type:\n"
             . "    aws-upload edit " . $key . "\n"
             . "\n";
        return $msg;
    }

    /**
     * Method to support if when AwsUpload::edit is successfull.
     *
     * @param string $key E.g: proj.env
     *
     * @return string
     */
    public static function onEditSettingFileSuccess($key)
    {
        $msg = "The setting file <y>" . $key . ".json</y> has been edited succesfully.\n\n";

        return $msg;
    }

    /**
     * Method to get the proj/env table.
     *
     * @return string
     */
    public static function getProjEnvTable()
    {
        $files = SettingFiles::getList();

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
