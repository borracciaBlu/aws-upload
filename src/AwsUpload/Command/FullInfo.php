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

use AwsUpload\Facilitator;
use AwsUpload\Command\Command;

class FullInfo extends BasicCommand
{
    /**
     * Method used to print the full aws-upload info.
     *
     * -  banner
     * -  version
     * -  help
     *
     * @return void
     */
    public function run()
    {
        $msg = Facilitator::banner();
        $msg .= Facilitator::version($this->app->version);
        $msg .= Facilitator::help();

        $this->app->display($msg, 0);
    }
}
