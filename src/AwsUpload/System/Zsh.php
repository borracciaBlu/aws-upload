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

namespace AwsUpload\System;

class Zsh
{
    public static function isInstalled()
    {
        $count = (int) exec('grep /zsh$ /etc/shells | wc -l');
        return ($count >= 1);
    }

    public static function errorMsg()
    {
        $msg = "\n   It seems that zsh is not installed.\n" .
                "   Please run (or equivalent for your system):\n\n" .
                "       <y>sudo apt-get install zsh</y>\n" .
                "       <y>sudo chsh zsh</y>\n";
        return $msg;
    }
}
