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

class SettingFolder
{
    /**
     * Returns the location of the user directory from the environment.
     * @throws RuntimeException If the environment value does not exists
     *
     * @return string
     */
    public static function getUserDir()
    {
        $userEnv = defined('PHP_WINDOWS_VERSION_MAJOR') ? 'APPDATA' : 'HOME';
        $userDir = getenv($userEnv);

        if (!$userDir) {
            $msg = 'The ' . $userEnv . ' or AWSUPLOAD_HOME environment variable ' .
                    'must be set for aws-upload to run correctly';
            throw new \RuntimeException($msg);
        }

        return rtrim(strtr($userDir, '\\', '/'), '/');
    }

    /**
     * Returns the system-dependent aws-upload home location, which may not exist.
     *
     * @return string
     */
    public static function getHomeDir()
    {
        $home = getenv('AWSUPLOAD_HOME');

        if (!$home) {
            $userDir = self::getUserDir();

            if (defined('PHP_WINDOWS_VERSION_MAJOR')) {
                $home = $userDir . '/Aws-upload';
            } else {
                $home = $userDir . '/.aws-upload';
            }
        }

        return $home;
    }

    /**
     * Returns the aws-upload home directory, creating it if required.
     * @throws RuntimeException If the directory cannot be created
     *
     * @return string
     */
    public static function getPath()
    {
        $home = self::getHomeDir();

        if (!is_dir($home)) {
            if (!mkdir($home, 0777, true)) {
                $msg = 'Unable to create aws-upload home directory "%s"';
                throw new \RuntimeException(sprintf($msg, $home));
            }
        }

        return $home;
    }
}
