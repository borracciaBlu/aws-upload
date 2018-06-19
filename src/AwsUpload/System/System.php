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

class System
{
    private static function getValue($array, $key)
    {
        return isset($array[$key]) ? $array[$key] : '';
    }

    /**
     * Method to get a default editor.
     *
     * @return string
     */
    public static function getEditor()
    {
        $editor = self::getValue($_ENV, 'EDITOR');

        if (empty($editor)) {
            $editor = self::getValue($_SERVER, 'EDITOR');
        }

        if (empty($editor)) {
            $editor = 'vim';
        }

        return $editor;
    }
}
