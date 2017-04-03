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

use function cli\out;

class Output
{
    /**
     * It defines if the class is running under phpunit.
     *
     * The issue is all the time we have exit(0) becuase it kills the
     * execution of phpunit.
     *
     * @var bool
     */
    public $is_phpunit = false;

    /**
     * Method to color the bash output.
     *
     * The method is going to replace some custom tags with the equivalent
     * color in bash.
     *
     * Eg:
     *     <r> -> \e[31m
     *     <g> -> \e[32m
     *     <y> -> \e[33m
     *
     * @param string $text The text to parse and inject with the colors.
     *
     * @return string
     */
    public static function color($text)
    {
        $text = str_replace("<r>", "\e[31m", $text);
        $text = str_replace("</r>", "\e[0m", $text);
        $text = str_replace("<g>", "\e[32m", $text);
        $text = str_replace("</g>", "\e[0m", $text);
        $text = str_replace('<y>', "\e[33m", $text);
        $text = str_replace('</y>', "\e[0m", $text);

        return  $text;
    }

    /**
     * Method used to avoid the issue in testing caused by exit(0)
     *
     * It does need is_phpunit as true for working properly with phpunit.
     *
     * @param int $status The code we want the script to exit.
     *
     * @return int|void
     */
    public function graceExit($status)
    {
        if ($this->is_phpunit) {
            return $status;
        }

        exit($status);
    }

    /**
     * Method to render the text in the bash output.
     *
     * The method is going to write on the STDOUT.
     *
     * @param string $text The text to put on STDOUT.
     *
     * @return void
     */
    public function render($text)
    {
        $text = $this->color($text);

        if ($this->is_phpunit) {
            echo $text;
        } else {
            out($text);
        }
    }
}
