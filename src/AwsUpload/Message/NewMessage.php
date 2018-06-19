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

class NewMessage implements ArgCommandMessage
{
    /**
     * Method to support if when AwsUpload::new is successfull.
     *
     * @param string $key E.g: proj.env
     *
     * @return string
     */
    public static function success($key)
    {
        $text = "The setting file <y>" . $key . ".json</y> has been created successfully.\n\n" .
                "To edit again the file type:\n" .
                "    <g>aws-upload edit " . $key . "</g>\n" .
                "\n";

        return $text;
    }

    /**
     * @return string
     */
    public static function noArgs()
    {
        $text = "It seems that you don't proper arguments for this command.\n\n" .
                "<y>How to use new:</y>\n\n" .
                "    <g>aws-upload new <key></g>\n" .
                "    <b>E.g.:</b> aws-upload new blog.prod\n\n" .
                "\n";

        return $text;
    }
}
