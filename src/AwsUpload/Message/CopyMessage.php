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

class CopyMessage implements ArgCommandMessage
{
    public static function noArgs()
    {
        $msg = "It seems that you don't proper arguments for this command.\n\n" .

                "<y>How to use copy:</y>\n\n" .
                "    <g>aws-upload copy <src> <dest></g>\n" .
                "    <b>E.g.:</b> aws-upload copy blog.dev blog.prod\n\n" .
                "\n";

        return $msg;
    }
}
