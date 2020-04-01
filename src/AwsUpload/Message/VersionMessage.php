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

class VersionMessage
{
    /**
     * Method to echo the current version.
     *
     * @param string $version The version.
     *
     * @return string
     */
    public static function success($version)
    {
        $text = "<g>aws-upload</g> version <y>" . $version . "</y> \n";

        return $text;
    }
}
