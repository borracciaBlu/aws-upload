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

abstract class BasicCommand implements Command
{
	/**
	 * 
	 */
	public $app;

	/**
	 * [__construct description]
	 * @param [type] $app [description]
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * [run description]
	 * @return [type] [description]
	 */
	abstract public function run();
}
