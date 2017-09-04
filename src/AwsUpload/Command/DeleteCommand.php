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

namespace AwsUpload\Command;

use AwsUpload\Message\DeleteMessage;
use AwsUpload\Setting\SettingFile;

class DeleteCommand extends FileCommand
{
    public function init()
    {
        $this->key = $this->app->args->getFirst('delete');
    }

    /**
     * Method used to delete a setting file.
     *
     * @see FileCommand::run
     * @return void
     */
    public function exec()
    {
        $line = $this->getConfirmation();
        if (! $this->isYes($line)) {
            $this->app->inline("Aborting delete operation");
            return;
        }

        SettingFile::delete($this->key);
        $this->msg = DeleteMessage::success($this->key);
    }

    public function getConfirmation()
    {
        $this->app->inline("<r>Are you sure you want delete " . $this->key . "?(y|n)</r>");

        $handle = fopen("php://stdin","r");
        $line = fgets($handle);
        fclose($handle);

        return $line;
    }

    public function isYes($line)
    {
        $line = trim($line);
        $line = strtolower($line);

        return ($line === 'yes' || $line === 'y');
    }
}
