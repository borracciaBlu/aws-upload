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

class HelpMessage
{

    /**
     * Method to echo the help message.
     *
     * @return string
     */
    public static function success()
    {
        $text = <<<EOT

<y>Usage:</y>

  aws-upload <proj> <env> [--simulate|--dry-run] [-v|--verbose]

  aws-upload keys [-q|--quiet]
  aws-upload projs [-q|--quiet]
  aws-upload envs <proj> [-q|--quiet]

  aws-upload diff <key>              # The <key> format is proj.env eg: landing.test
  aws-upload new <key>               # The <key> format is proj.env eg: landing.test
  aws-upload edit <key>              # The <key> format is proj.env eg: landing.test
  aws-upload copy <src> <dest>       # <src> and <dest> are in the <key> format proj.env
  aws-upload delete <key>            # The <key> format is proj.env eg: landing.test
  aws-upload import <src>            # The <src> is the path to a json file.
  aws-upload export <key> [<dest>]   # The <dest> is a the directory path.
  aws-upload check <key>             # The <key> format is proj.env eg: landing.test

  aws-upload (self-update | selfupdate)
  aws-upload autocomplete

<y>Output Options:</y>

  <g>-v|--verbose</g>                Output more verbose information.
  <g>-q|--quiet</g>                  Reduce or suppress additional information.

<y>Miscellaneous Options:</y>

  <g>-h|--help</g>                   Prints this usage information.
  <g>-V|--version</g>                Prints the application version.
  <g>--dry-run</g>                   It simulates the rsync command without upload anything.
  <g>--simulate</g>                  It simulates the rsync command without upload anything.

<y>Available commands:</y>

  <g>-df|diff <key></g>              Show the files that are not yet synced.
  <g>-k|keys</g>                     Print all the projects' keys.
  <g>-p|projs</g>                    Print all the projects.
  <g>-e|envs <proj></g>              Print all the environments for a specific project.
  <g>-n|new <key></g>                Create a new setting file.
  <g>-E|edit <key></g>               Edit a setting file.
  <g>-cp|copy <src> <dest></g>       Copy a setting file.
  <g>-rm|delete <key></g>            Delete a setting file.
  <g>-i|import <src></g>             Import a setting file.
  <g>-ex|export <key> <dest></g>     Export a setting file.
  <g>-c|check <key></g>              Check a setting file for debug.
  <g>self-update</g>                 Updates aws-upload to the latest version.
  <g>selfupdate</g>                  Updates aws-upload to the latest version.
  <g>autocomplete</g>                Enable the autocomplete for oh-my-zsh.


EOT;
        return $text;
    }
}
