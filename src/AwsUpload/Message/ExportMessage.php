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

use AwsUpload\Message\ArgCommandMessage;

class ExportMessage implements ArgCommandMessage
{
    /**
     * Method to support if when AwsUpload::export is successfull.
     *
     * @param string $key E.g: proj.env
     *
     * @return string
     */
    public static function success($key)
    {
        $msg = "The setting file <y>" . $key . ".json</y> has been exported successfully.\n\n";
        return $msg;
    }

    public static function noArgs()
    {
        $msg = "It seems that you don't proper arguments for this command.\n\n" .

                "<y>How to use export:</y>\n\n" .
                "    <g>aws-upload export <key> [<dir>]</g>\n" .
                "    <b>E.g.:</b> aws-upload exprot blog.dev\n" .
                "    <b>E.g.:</b> aws-upload exprot blog.dev ~/Desktop/\n\n" .

                "\n";

        return $msg;
    }
}
