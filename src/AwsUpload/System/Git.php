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

class Git
{
    /**
     * Define if git is installed.
     *
     * @return bool
     */
    public static function isInstalled()
    {
        $hasGit = exec('hash git 2>&1');
        return (strlen($hasGit) === 0);
    }

    /**
     * In case if not in the system.
     *
     * @return string
     */
    public static function errorMsg()
    {
        $msg = "\n   It seems that git is not installed.\n" .
                "   Please run (or equivalent for your system):\n\n" .
                "       <y>sudo apt-get install git</y>\n";
        return $msg;
    }

    /**
     * In case if not in the system.
     *
     * @param string $repo The repo url.
     * @param string $dest The folder path.
     *
     * @return string
     */
    public static function clone($repo, $dest)
    {
        $cmd = 'env git clone ' . $repo . ' ' . $dest;
        return exec($cmd);
    }
}
