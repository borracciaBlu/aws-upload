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

  aws-upload <proj> <env> [--simulate] [-v|--verbose]

  aws-upload keys [-q|--quiet]
  aws-upload projs [-q|--quiet]
  aws-upload envs <proj> [-q|--quiet]

  aws-upload new <key>               # The <key> format is proj.env eg: landing.test
  aws-upload edit <key>              # The <key> format is proj.env eg: landing.test
  aws-upload copy <src> <dest>       # <src> and <dest> are in the <key> format proj.env
  aws-upload check <key>             # The <key> format is proj.env eg: landing.test

  aws-upload (self-update | selfupdate)
  aws-upload autocomplete

<y>Output Options:</y>

  <g>-v|--verbose</g>                Output more verbose information.
  <g>-q|--quiet</g>                  Reduce or suppress additional information.

<y>Miscellaneous Options:</y>

  <g>-h|--help</g>                   Prints this usage information.
  <g>-V|--version</g>                Prints the application version.
  <g>--simulate</g>                  It simulates the rsync command without upload anything.

<y>Available commands:</y>

  <g>-k|keys</g>                     Print all the projects' keys.
  <g>-p|projs</g>                    Print all the projects.
  <g>-e|envs <proj></g>              Print all the environments for a specific project.
  <g>-n|new <key></g>                Create a new setting file.
  <g>-E|edit <key></g>               Edit a setting file.
  <g>-cp|copy <src> <dest></g>       Copy a setting file.
  <g>-c|check <key></g>              Check a setting file for debug.
  <g>self-update</g>                 Updates aws-upload to the latest version.
  <g>selfupdate</g>                  Updates aws-upload to the latest version.
  <g>autocomplete</g>                Enable the autocomplete for oh-my-zsh.


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
        $env    = escapeshellarg($env);
        $proj   = escapeshellarg($proj);

        $msg = <<<EOT
<b>==================================================================</b>
<g>
                                          _                 _
                                         | |               | |
     __ ___      _____ ______ _   _ _ __ | | ___   __ _  __| |
    / _` \ \ /\ / / __|______| | | | '_ \| |/ _ \ / _` |/ _` |
   | (_| |\ V  V /\__ \      | |_| | |_) | | (_) | (_| | (_| |
    \__,_| \_/\_/ |___/       \__,_| .__/|_|\___/ \__,_|\__,_|
                                   | |
                                   |_|

</g>
<b>==================================================================</b>

   Start processing:
   <g>Proj:</g> <y>$proj</y>
   <g>Env:</g>  <y>$env</y>

<b>==================================================================</b>

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
        // Labels
        $check_labels = array('✔', '✖');
        $valid_labels = array("VALID", "INVALID");
        $exist_labels = array("EXISTS", "NOT EXISTS");
        $perms_labels = array($report['pem_perms'], $report['pem_perms']);

        $check_json = static::plot($report['is_valid_json'], $check_labels);
        $check_pem  = static::plot($report['pem_exists'], $check_labels);
        $check_400  = static::plot($report['is_400'], $check_labels);
        $check_loc  = static::plot($report['local_exists'], $check_labels);

        $is_valid_json = static::plot($report['is_valid_json'], $valid_labels);
        $pem_exists    = static::plot($report['pem_exists'], $exist_labels);
        $is_400_perms  = static::plot($report['is_400'], $perms_labels);
        $local_exists  = static::plot($report['local_exists'], $exist_labels);

        // Json
        $msg = "Checking...\n\n"
             . "   <b>File analysing:</b>\n"
             . "   <y>" . $report['path'] . "</y>" . "\n"
             . "   " . $check_json . " Json      " . $is_valid_json . "\n"
             . "   " . $report['error_json'];

        // Pem
        $msg .= "\n"
              . "   <b>Pem File:</b>\n"
              . "   <y>" . $report['pem'] . "</y>\n"
              . "   " . $check_pem . " Pem       " . $pem_exists . "\n";

        if ($report['pem_exists']) {
            $msg .= "   " . $check_400 . " Pem Perm  " . $is_400_perms . "\n";

            if (!$report['is_400']) {
                $msg .= '    Try to type: chmod 400 ' . $report['pem'] . "\n";
            }
        }

        // Local
        $msg .= "\n"
              . "   <b>Local Folder:</b>\n"
              . "   <y>" . $report['local'] . "</y>" . "\n"
              . "   " . $check_loc . " Folder    " . $local_exists . "\n";

        return $msg;
    }

    /**
     * Method to facilitate the report building.
     *
     * @param bool     $condition The state to evaluate
     * @param string[] $labels    The possible values to display
     *
     * @return string
     */
    public static function plot($condition, $labels)
    {
        return ($condition) ? "<g>" . $labels[0] . "</g>" : "<r>" . $labels[1] . "</r>";
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

        if (count($projs) > 0) {
            $next .= "\nTo get the envs from one of them, run (for example):\n\n" .
                     "   aws-upload -e " . $projs[0] . "\n";
        }

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
             . "To edit again the file type:\n"
             . "    <g>aws-upload edit " . $key . "</g>\n"
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
