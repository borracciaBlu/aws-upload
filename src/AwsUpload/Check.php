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

namespace AwsUpload;

use AwsUpload\SettingFolder;

class Check
{
    /**
     * Method to check if the setting file it does exist.
     *
     * @param string $key The setting file name without the extenion.
     *
     * @return bool
     */
    public static function fileExists($key)
    {
        $path = SettingFolder::getPath();

        return file_exists($path . '/' . $key . '.json');
    }

    /**
     * Method to check if a give key is valid.
     *
     * The rules are:
     * - only one .
     *
     * @param string $key The setting file name without the extenion.
     *
     * @return bool
     */
    public static function isValidKey($key)
    {
        $parts = explode('.', $key);
        $isValid = (count($parts) === 2);

        return $isValid;
    }
}
