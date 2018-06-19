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

namespace AwsUpload\Message;

use AwsUpload\Message\CommonMessage;
use AwsUpload\Message\ArgCommandMessage;

class CheckMessage implements ArgCommandMessage
{
    /**
     * Method to echo the aws-upload check report.
     *
     * @param array $report The report value
     *
     * @return string
     */
    public static function report($report)
    {
        // Labels
        $check_labels = array('✔', '✖');
        $valid_labels = array("VALID", "INVALID");
        $exist_labels = array("EXISTS", "NOT EXISTS");
        $perms_labels = array($report['pem_perms'], $report['pem_perms']);

        $check_json = CommonMessage::plot($report['is_valid_json'], $check_labels);
        $check_pem  = CommonMessage::plot($report['pem_exists'], $check_labels);
        $check_400  = CommonMessage::plot($report['is_400'], $check_labels);
        $check_loc  = CommonMessage::plot($report['local_exists'], $check_labels);

        $is_valid_json = CommonMessage::plot($report['is_valid_json'], $valid_labels);
        $pem_exists    = CommonMessage::plot($report['pem_exists'], $exist_labels);
        $is_400_perms  = CommonMessage::plot($report['is_400'], $perms_labels);
        $local_exists  = CommonMessage::plot($report['local_exists'], $exist_labels);

        // Json
        $text = "Checking...\n\n" .
                "   <b>File analysing:</b>\n" .
                "   <y>" . $report['path'] . "</y>" . "\n" .
                "   " . $check_json . " Json      " . $is_valid_json . "\n" .
                "   " . $report['error_json'];

        // Pem
        $text .= "\n" .
                "   <b>Pem File:</b>\n" .
                "   <y>" . $report['pem'] . "</y>\n" .
                "   " . $check_pem . " Pem       " . $pem_exists . "\n";

        if ($report['pem_exists']) {
            $text .= "   " . $check_400 . " Pem Perm  " . $is_400_perms . "\n";

            if (!$report['is_400']) {
                $text .= '    Try to type: chmod 400 ' . $report['pem'] . "\n";
            }
        }

        // Local
        $text .= "\n" .
                "   <b>Local Folder:</b>\n" .
                "   <y>" . $report['local'] . "</y>" . "\n" .
                "   " . $check_loc . " Folder    " . $local_exists . "\n";

        return $text;
    }

    public static function noArgs()
    {
        $text = "It seems that you don't proper arguments for this command.\n\n" .

                "<y>How to use check:</y>\n\n" .
                "    <g>aws-upload check <key></g>\n" .
                "    <b>E.g.:</b> aws-upload check blog.dev\n\n" .
                "\n";

        return $text;
    }
}
