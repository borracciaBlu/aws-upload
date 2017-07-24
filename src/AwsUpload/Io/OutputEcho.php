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
use AwsUpload\Io\Output;

class OutputEcho extends Output
{
    /**
     * Method to render the text with echo.
     *
     * @param string $text The text to echo.
     *
     * @return void
     */
    public function render($text)
    {
        $text = $this->color($text);

        echo $text;
    }
}
