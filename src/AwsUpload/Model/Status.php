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

namespace AwsUpload\Model;

class Status
{
    /**
     * @var int
     */
    const SUCCESS = 0;

    /**
     * To use when the error is relative to aws-upload.
     *
     * @var int
     */
    const ERROR_INVALID = 1;

    /**
     * To use when the error is relative to the rest of the system.
     *
     * @var int
     */
    const SYSTEM_NOT_READY = 2;
}
