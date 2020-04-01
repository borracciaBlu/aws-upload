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

/**
 * Color the bash output.
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
function color($text)
{
    $text = str_replace("<r>", "\e[31m", $text);
    $text = str_replace("</r>", "\e[0m", $text);
    $text = str_replace("<g>", "\e[32m", $text);
    $text = str_replace("</g>", "\e[0m", $text);
    $text = str_replace('<y>', "\e[33m", $text);
    $text = str_replace('</y>', "\e[0m", $text);
    $text = str_replace('<b>', "\e[34m", $text);
    $text = str_replace('</b>', "\e[0m", $text);

    return  $text;
}
