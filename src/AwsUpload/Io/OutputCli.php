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

namespace AwsUpload\Io;

use function cli\out;
use function AwsUpload\Io\color;

class OutputCli extends Output
{
    /**
     * Write text in the bash output.
     *
     * The method is going to write on the STDOUT.
     *
     * @param string $text The text to put on STDOUT.
     *
     * @return void
     */
    public function write($text)
    {
        $text = color($text);

        out($text . "\n");
    }
}
