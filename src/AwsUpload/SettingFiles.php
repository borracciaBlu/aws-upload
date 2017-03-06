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

use AwsUpload\Facilitator;
use AwsUpload\SettingFolder;

class SettingFiles
{
    /**
     * Returns the list or setting files in the aws-upload folder.
     *
     * @return array
     */
    public function getList()
    {
        $path = SettingFolder::getPath();
        $files = scandir($path);

        // clean . and ..
        unset($files[0]);
        unset($files[1]);

        return $files;
    }

    /**
     * Returns the list or setting files in the aws-upload folder.
     *
     * @param string $key The setting file identifier.
     *
     * @return array
     */
    public function getObject($key)
    {
        $path = SettingFolder::getPath();

        $content = file_get_contents($path . '/' . $key . '.json');
        $settings = (object) json_decode($content, true);

        return $settings;
    }

    /**
     * Method used to get the projects available.
     *
     * @return array
     */
    public static function getProjs()
    {
        $files = SettingFiles::getList();

        $projs = [];
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
        $files = SettingFiles::getList();
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
}
