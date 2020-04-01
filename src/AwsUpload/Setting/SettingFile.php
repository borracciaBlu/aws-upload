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

namespace AwsUpload\Setting;

use AwsUpload\System\File;
use AwsUpload\System\System;
use AwsUpload\Model\Settings;
use AwsUpload\Setting\SettingFolder;

class SettingFile
{
    /**
     * Returns the list or setting files in the aws-upload folder.
     *
     * @return array
     */
    public static function getList()
    {
        $path = SettingFolder::getPath();
        $items = scandir($path);

        // clean . and ..
        unset($items[0]);
        unset($items[1]);

        $files = array();
        foreach ($items as $key => $item) {
            if (is_dir($path . "/" . $item)) {
                continue;
            }

            if (strpos($item, ".json") === false) {
                continue;
            }

            $files[] = $item;
        }

        return $files;
    }

    /**
     * Returns the setting file path from a key.
     *
     * @param string $key The setting file identifier.
     *
     * @return string
     */
    public static function getPath($key)
    {
        $path = SettingFolder::getPath();
        $path = $path . '/' . $key . '.json';

        return $path;
    }

    /**
     * Returns the list or setting files in the aws-upload folder.
     *
     * @param string $key The setting file identifier.
     *
     * @return Settings
     */
    public static function getObject($key)
    {
        $path = static::getPath($key);
        $settings = new Settings($path);

        return $settings;
    }

    /**
     * Method used to get the projects' key available.
     *
     * @return array
     */
    public static function getKeys()
    {
        $files = SettingFile::getList();

        $keys = array();
        foreach ($files as $fileName) {
            $key = str_replace('.json', '', $fileName);

            if (!in_array($key, $keys)) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * Method used to get the projects available.
     *
     * @return array
     */
    public static function getProjs()
    {
        $files = SettingFile::getList();

        $projs = array();
        foreach ($files as $key) {
            list($proj, $env, $ext) = explode(".", $key);

            if (!in_array($proj, $projs)) {
                $projs[] = $proj;
            }
        }

        return $projs;
    }

    /**
     * Method used to print the environments available for a project.
     *
     * @param string $projFilter The project for which we want the envs.
     *
     * @return array | string
     */
    public static function getEnvs($projFilter)
    {
        $files = SettingFile::getList();
        $store = array();
        foreach ($files as $filename) {
            list($proj, $env, $ext) = explode(".", $filename);

            if (!isset($store[$proj])) {
                $store[$proj] = array();
            }

            $store[$proj][] = $env;
        }

        $envs    = array();
        $envsRaw = isset($store[$projFilter]) ? $store[$projFilter] : array();
        foreach ($envsRaw as $env) {
            if (!in_array($env, $envs)) {
                $envs[] = $env;
            }
        }

        return $envs;
    }

    /**
     * Method used to create a setting file.
     *
     * @param string $key The project identifier proj.envs .
     *
     * @return void
     */
    public static function create($key)
    {
        $template = "{\n" .
                    '   "pem": "/home/ssh/key.pem",' . "\n" .
                    '   "local": "/home/project/*",' . "\n" .
                    '   "remote": "ubuntu@xxx.xxx.xxx.xxx:/var/www/project",' . "\n" .
                    '   "exclude": [' . "\n" .
                    '       ".env",' . "\n" .
                    '       ".git/",' . "\n" .
                    '       "node_modules"' . "\n" .
                    '   ]' . "\n" .
                    "}\n";
        $path = SettingFolder::getPath();

        file_put_contents($path . '/' . $key . '.json', $template);
    }

    /**
     * Method used to edit a setting file.
     *
     * @param string $key The project identifier proj.envs .
     *
     * @return void
     */
    public static function edit($key)
    {
        $path = SettingFolder::getPath();
        $editor = System::getEditor();

        system($editor . ' ' . $path . '/' . $key . '.json  < `tty` > `tty`');
    }

    /**
     * Method to copy a setting file
     *
     * @param string $oldKey The key for the exisiting setting.
     * @param string $newKey The key for the new setting.
     *
     * @return void
     */
    public static function copy($oldKey, $newKey)
    {
        $path = SettingFolder::getPath();
        $source = $path . '/' . $oldKey . '.json';
        $dest   = $path . '/' . $newKey . '.json';

        File::copy($source, $dest);
    }

    /**
     * Method to delete a setting file
     *
     * @param string $key The key for the exisiting setting.
     *
     * @return void
     */
    public static function delete($key)
    {
        $path = SettingFolder::getPath();
        $src = $path . '/' . $key . '.json';

        File::delete($src);
    }

    /**
     * Method to check if the setting file it does exist.
     *
     * @param string $key The setting file name without the extenion.
     *
     * @return bool
     */
    public static function exists($key)
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
     * Method to extract the project and the environment from an array
     *
     * This method is to cover two cases:
     * - aws-upload proj env // double notation
     * - aws-upload proj.env // key notation
     *
     * @param array $items It contains all the extra args.
     *
     * @return array       The array will contain 2 elements in any case.
     */
    public static function extractProjEnv(array $items)
    {
        $proj = 'no-project-given';
        $env  = 'no-environment-given';

        // reorder items in array
        // [ 4 => 'a', 6 => 'b' ] ~> [ 0 => 'a', 1 => 'b' ]
        if (is_array($items)) {
            $items = array_values($items);
        }

        if (count($items) === 1) {
            if (strpos($items[0], '.') !== false) {
                $items = explode('.', $items[0]);
            }
        }

        if (count($items) === 2) {
            $proj = $items[0];
            $env  = $items[1];
        }

        return array($proj, $env);
    }
}
