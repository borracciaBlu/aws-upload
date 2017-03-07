<?php
/**
 * aws-upload - aws-upload is a CLI Tool to manage rsync
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 * @author    Marco Buttini <marco.asdman@gmail.com>
 * @copyright 2017 Marco Buttini
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License
 */

namespace AwsUpload;

use AwsUpload\SettingFiles;

class Facilitator
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
    const VERISON = '0.1.0';

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
     * @return void
     */
    public static function version()
    {
        $msg = "<g>aws-upload</g> version <y>" . self::VERISON . "</y> \n";
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
   <g>-V|--version</g>                 Prints the version and exits.

Configuration Options:

  --debug                   Display debugging information during test execution.
  --bootstrap <file>        A "bootstrap" PHP file that is run before the tests.
  -c|--configuration <file> Read configuration from XML file.
  --no-configuration        Ignore default configuration file (phpunit.xml).
  --no-coverage             Ignore code coverage configuration.
  --no-extensions           Do not load PHPUnit extensions.
  --include-path <path(s)>  Prepend PHP's include_path with given path(s).
  -d key[=value]            Sets a php.ini value.
  --generate-configuration  Generate configuration file with suggested settings.

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
        $msg = "It seems that you don't have any project setup." . "\n\n";

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
}
