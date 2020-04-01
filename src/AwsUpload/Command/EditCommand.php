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

use AwsUpload\Message\EditMessage;
use AwsUpload\Setting\SettingFile;

class EditCommand extends FileCommand
{

    /**
     * @see FileCommand::init
     * @return void
     */
    public function init()
    {
        $this->key = $this->args->getFirst('edit');
    }

    /**
     * Method used to edit a setting file.
     *
     * @see FileCommand::run
     * @return void
     */
    public function exec()
    {
        SettingFile::edit($this->key);

        $this->msg = EditMessage::success($this->key);
    }
}
