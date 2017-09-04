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

class ImportMessage
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
        $msg = "The setting file <y>" . $key . ".json</y> has been imported successfully.\n\n" .
                "<y>To edit again the file type:</y>\n" .
                "    <g>aws-upload edit " . $key . "</g>\n" .
                "\n";
        return $msg;
    }

    public static function errorNotFound($path)
    {
        $msg = "It seems that you don't proper arguments for this command.\n\n" .

                "<y>Argument given:</y>\n\n" .
                "    <b>src:</b> " . $path . "\n\n" .

               
                "<y>How to use import:</y>\n\n" .
                "    <g>aws-upload import <src></g>\n" .
                "    <b>E.g.:</b> aws-upload import ~/Desktop/blog.dev.json\n\n" .

                "<y>The cause it may be:</y>\n\n" .
                "    <b>-</b> no file given\n" .
                "    <b>-</b> the argument give was a folder\n" .
                "    <b>-</b> the argument give was a file but it doesn't exist\n" .
                "\n";

        return $msg;
    }
}
