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
use AwsUpload\SettingFiles;

class Facilitator
{
    /**
     * Method to color the bash output.
     *
     * The method is going to replace some custom tags with the equivalent
     * color in bash.
     *
     * Eg:
     *     <r> -> \e[31m
     *     <g> -> \e[32m
     *     <y> -> \e[33m
     *
     * @param string $text The text to parse and inject with the colors.
     * @return string
     */
    public static function color($text)
    {
        $text = str_replace("<r>", "\e[31m", $text);
        $text = str_replace("</r>", "\e[0m", $text);
        $text = str_replace("<g>", "\e[32m", $text);
        $text = str_replace("</g>", "\e[0m", $text);
        $text = str_replace('<y>', "\e[33m", $text);
        $text = str_replace('</y>', "\e[0m", $text);

        return  "$text";
    }

    /**
     * Method to echo the aws-upload banner.
     *
     * @return  void
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
        echo self::color("<g>".$banner."</g>");
    }

    /**
     * Method to echo the current version.
     *
     * @param  string $version The version.
     * @return void
     */
    public static function version($version)
    {
        $msg = "<g>aws-upload</g> version <y>" . $version . "</y> \n";
        echo self::color($msg);
    }

    /**
     * Method to echo the help message.
     *
     * @return void
     */
    public static function help()
    {
        $msg = <<<EOT
<y>Usage:</y>
  aws-upload [options] [project] [environment]

<y>Output Options:</y>

  <g>-v|--verbose</g>              Output more verbose information.
  <g>-q|--quiet</g>                Checks that version is greater than min and exits.

<y>Miscellaneous Options:</y>

   <g>-h|--help</g>                 Prints this usage information.
   <g>-V|--version</g>              Prints the version and exits.

<y>Configuration Options:</y>

   <g>-p|--projs</g>                 Print all the projects.
   <g>-e|--envs <proj></g>           Print all the environments for a specific project.

EOT;
        echo self::color($msg);
    }

    /**
     * Method to echo the help message about no project.
     *
     * @return void
     */
    public static function onNoProjects()
    {
        $msg = "It seems that you don't have any project setup.\n"
             // . "Try to type: aws-upload new\n"
             . "\n";

        echo $msg;
    }

    /**
     * Method to echo the help message about when the project
     * selected doesn't exist.
     *
     * @param string $projFilter The project name.
     *
     * @return void
     */
    public static function onGetEnvsForProj($projFilter)
    {
        $projs = SettingFiles::getProjs();
        $msg = "The project <r>". $projFilter ."</r> you are tring to use doesn't exist." . "\n\n";

        $next = "These are the available projects: \n\n" ;
        foreach ($projs as $proj) {
            $next .= "  +  <g>" . $proj . "</g>\n";
        }

        $next .= "\nTo get the envs from one of them, run (for example):\n\n" .
                 "   aws-upload -e " . $projs[0] . "\n";

        echo self::color($msg . $next . "\n");
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
     * @return void
     */
    public static function onNoFileFound($project, $env)
    {
        $msg = "It seems that there is <r>NO</r> setting files for <y>" . $project
              ."</y>, <y>" . $env ."</y>\n";
        echo self::color($msg . "\n");

        $files = SettingFiles::getList();
        if (count($files) === 0) {
            self::onNoProjects();
            return;
        }

        $headers = array('Project', 'Environment');
        $data = array();
        foreach ($files as $file) {
            list($proj, $env, $ext) = explode(".", $file);
            $proj = self::color("<g>" . $proj . "</g>");
            $env = self::color("<g>" . $env . "</g>");
            
            $data[] = array($proj, $env);
        }

        $table = new Table();
        $table->setHeaders($headers);
        $table->setRows($data);
        $table->display();

        echo "\n";
    }
}
