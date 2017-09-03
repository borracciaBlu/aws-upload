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

class File
{

    /**
     * Copy a file.
     *
     * @param  string $src The current file path.
     * @param  string $dst The new file path.
     *
     * @return bool
     */
    public static function copy($src, $dst)
    {
        return copy($src, $dst);
    }

    /**
     * Move a file.
     *
     * @param  string $src The current file path.
     * @param  string $dst The new file path.
     *
     * @return bool
     */
    public static function move($src, $dst)
    {
        return rename($src, $dst);
    }

    /**
     * Delete a file.
     *
     * @param  string $src The file path.
     *
     * @return bool
     */
    public static function delete($src)
    {
        return unlink($src);
    }
}
