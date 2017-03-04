<?php
/**
 * aws-upload - aws-upload is a CLI Tool to manage rsync
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 * @author    Marco Buttini <marco.asdman@gmail.com>
 * @copyright 2017 Marco Buttini
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License
 */

namespace AwsUpload;

use AwsUpload\SettingFolder;

class SettingFiles
{
    /**
     * Returns the location of the user directory from the environment
     *
     * @return array
     */
    function getList()
    {
        $home = SettingFolder::getPath();
        $files = scandir($path);

        // clean . and ..
        unset($files[0]);
        unset($files[1]);

        return $files;
    }

    function getObject($key)
    {
        $home = SettingFolder::getPath();

        $string = file_get_contents($home . '/' . $key . '.json');
        $settings = (object) json_decode($string, true);

        return $settings;
    }
}
