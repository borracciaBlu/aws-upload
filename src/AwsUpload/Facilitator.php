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
use AwsUpload\Output;
use AwsUpload\SettingFiles;

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

<y>Configuration Options:</y>

   <g>-p|--projs</g>                 Print all the projects.
   <g>-e|--envs <proj></g>           Print all the environments for a specific project.


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
     * Method to echo the help message about no project.
     *
     * @return string
     */
    public static function onNoProjects()
    {
        $msg = "It seems that you don't have any project setup.\n" .
               // . "Try to type: aws-upload new\n"
               "\n";

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
     * @param string $project The project name.
     * @param string $env     The env name.
     *
     * @return string
     */
    public static function onNoFileFound($project, $env)
    {
        $msg = "It seems that there is <r>NO</r> setting files for <y>" . $project .
               "</y>, <y>" . $env . "</y>\n\n";

        $files = SettingFiles::getList();
        if (count($files) === 0) {
            static::onNoProjects();
            return;
        }

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

        foreach ($table->getDisplayLines() as $key => $line) {
            $msg .= $line . "\n";
        }

        return $msg;
    }
}
