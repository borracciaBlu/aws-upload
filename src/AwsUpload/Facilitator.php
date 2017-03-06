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
    const VERISON = '0.0.1';

    public static function color($text)
    {
        $text = str_replace("<r>", "\e[31m", $text);
        $text = str_replace("</r>", "\e[30", $text);
        $text = str_replace("<g>", "\e[32m", $text);
        $text = str_replace("</g>", "\e[0m", $text);
        $text = str_replace('<y>', "\e[33m", $text);
        $text = str_replace('</y>', "\e[0m", $text);

        return  "$text";
    }

    /**
     * Show the help message.
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

    public static function version()
    {
        $msg = "<g>aws-upload</g> version <y>" . self::VERISON . "</y> \n";
        echo self::color($msg);
    }


    /**
     * Show the help message.
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


    public static function onNoProjects()
    {
        $msg = "It seems that you don't have any project setup." . "\n\n";

        echo $msg;
        exit(0);
    }

    /**
     * Error rised when the project selected doesn't exist.
     *
     * @param string $projFilter the project name.
     *
     * @return void
     */
    public static function onGetEnvsForProj($projFilter)
    {
        $projs = SettingFiles::getProjs();
        $msg = "The project \e[31m". $projFilter ."\e[0m you are tring to use doesn't exist." . "\n\n";

        $next = "These are the available projects: \n\n" ;
        foreach ($projs as $proj) {
            $next .= "  +  \e[32m" . $proj . "\e[0m\n";
        }

        //             homebrew/php/php70-v8js                      homebrew/php/php71-yaml
        // homebrew/php/php70-xdebug                    homebrew/php/php71
        // To install one of them, run (for example):
        //   brew install homebrew/php/php70-amqp

        echo $msg . $next . "\n";
        exit(0);
    }
}
