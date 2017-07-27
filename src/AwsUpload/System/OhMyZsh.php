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

class OhMyZsh
{
    public static function isInstalled()
    {
        $dir  = self::getPath();
        $file = self::getPath() . '/oh-my-zsh.sh';
        return is_dir($dir) && is_file($file);
    }

    public static function errorMsg()
    {
        $msg = "\n   It seems that oh-my-zsh is not installed.\n" .
               "   Please run (or equivalent for your system):\n\n" .
               "       <y>sh -c \"$(wget https://raw.githubusercontent.com/" .
               "robbyrussell/oh-my-zsh/master/tools/install.sh -O -)\"</y>\n";
        return $msg;
    }

    public static function getPath()
    {
        $omzDir = exec('echo $ZSH');
        $omzDir = (strlen($omzDir) > 0) ? $omzDir : '~/.oh-my-zsh';

        return $omzDir;
    }

    public static function hasPluginFiles()
    {
        $dir = self::getPath() . '/plugins/aws-upload/';
        return is_dir($dir);
    }

    public static function isPluginActive()
    {
        $isActive = exec("grep aws-upload ~/.zshrc");
        return (strlen($isActive) > 0);
    }

    public static function activate()
    {
        $cmd = "sed -i '/^plugins=(/ s/)$/ aws-upload)/' ~/.zshrc";
        return exec($cmd);
    }
}
