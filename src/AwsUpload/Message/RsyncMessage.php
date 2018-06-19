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

class RsyncMessage
{

    /**
     * Method to echo the aws-upload banner.
     *
     * @param string $proj The project name.
     * @param string $env  The env name.
     * @param string $cmd  The rsync cmd.
     *
     * @return string
     */
    public static function banner($proj, $env, $cmd)
    {
        $env  = escapeshellarg($env);
        $proj = escapeshellarg($proj);

        $text = <<<EOT
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
        return $text;
    }
}
