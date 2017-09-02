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

class CommonMessage
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
}
