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

use AwsUpload\Setting\SettingFile;

class EnvsMessage
{
    /**
     * Method to echo the help message about when the project
     * selected doesn't exist.
     *
     * @param string $projFilter The project name.
     *
     * @return string
     */
    public static function errorNoEnvsProj($projFilter)
    {
        $projs = SettingFile::getProjs();
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
}
