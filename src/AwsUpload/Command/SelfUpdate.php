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

use AwsUpload\Command\Command;
use AwsUpload\Facilitator;

class SelfUpdate extends BasicCommand
{
    /**
     * Method used to update aws-upload via composer.
     *
     * @return void
     */
    public function run()
    {
        $this->app->inline('Self-update running..');
        system('composer -vvv global require aws-upload/aws-upload');
        $this->app->display("Self-update completed", 0);
    }
}
