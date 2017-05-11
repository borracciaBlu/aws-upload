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

use AwsUpload\Setting\SettingFolder;

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

    /**
     * Method to chek if a file is a isValidJSON
     *
     * @param  string  $path The file path.
     *
     * @return bool
     */
    public static function isValidJSON($path)
    {
        $json = file_get_contents($path);
        json_decode($json, true);
        return (json_last_error() === JSON_ERROR_NONE);
    }
}
