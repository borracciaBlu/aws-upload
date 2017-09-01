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
     * Clone a repo to a directory.
     *
     * @param string $repo     The repo url.
     * @param string $repo_dir The folder path.
     *
     * @return string
     */
    public static function clone($repo, $repo_dir)
    {
        $git_cmd = 'git clone ' . $repo . ' ' . $repo_dir;
        $cmd = self::silentGit($git_cmd);

        return exec($cmd);
    }

    /**
     * Pull a repo in a directory.
     *
     * @param string $repo_dir The repo folder path.
     *
     * @return string
     */
    public static function pull($repo_dir)
    {
        $git_cmd = 'git pull origin master';
        $cmd = self::silentGit($git_cmd);
        $cmd = self::goAndComeback($repo_dir, $cmd);

        return exec($cmd);
    }

    /**
     * Checkout a a tag version.
     *
     * @param string $repo_dir The repo folder path.
     * @param string $tag      The tag to checkout.
     *
     * @return string
     */
    public static function checkoutTag($repo_dir, $tag)
    {
        $git_cmd =  'git checkout ' . $tag;
        $cmd = self::silentGit($git_cmd);
        $cmd = self::goAndComeback($repo_dir, $cmd);

        return exec($cmd);
    }

    /**
     * Create command to comeback to current folder.
     *
     * @param string $repo_dir The repo folder path.
     * @param string $cmd      The command to run.
     *
     * @return string
     */
    public static function goAndComeback($repo_dir, $cmd)
    {
        $cmd = 'cd ' . $repo_dir . ' && ' . $cmd . ' && cd -  ';
        return $cmd;
    }

    /**
     * Run a git command without output.
     *
     * @param string $git_cmd The git command.
     *
     * @return string
     */
    public static function silentGit($git_cmd)
    {
        $cmd = 'env ' . $git_cmd . ' > /dev/null 2>&1';
        return $cmd;
    }
}
