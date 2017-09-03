<?php

namespace AwsUpload\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

abstract class BaseTestCase extends TestCase
{

    /**
     * The directory that contain the AWSUPLOAD_HOME.
     *
     * Foreach test we want this folder clean.
     * So, each time we use the class to make it unique.
     *
     * @var string
     */
    protected $aws_home;

    /**
     * The directory to simulate outside of AWSUPLOAD_HOME.
     *
     * @var string
     */
    protected $external;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $uid = strtolower(get_class($this));
        $uid = str_replace('\\', '-', $uid);


        $this->aws_home = __DIR__ . '/../../build/' . $uid;
        putenv("AWSUPLOAD_HOME=" . $this->aws_home);

        $this->external = __DIR__ . '/../../external/' . $uid;
        $filesystem = new Filesystem();
        $filesystem->mkdir($this->aws_home);
        $filesystem->mkdir($this->external);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        unset($_ENV['AWSUPLOAD_HOME']);

        $filesystem = new Filesystem();
        $filesystem->remove($this->aws_home);
        $filesystem->remove($this->external);
    }

    /**
     * Clear the $_SERVER['argv'] array
     */
    public static function clearArgv()
    {
        $_SERVER['argv'] = array();
        $_SERVER['argc'] = 0;
    }

    /**
     * Add one or more element(s) at the end of the $_SERVER['argv'] array
     *
     * @param string[] $args Value to add to the argv array.
     */
    public static function pushToArgv($args)
    {
        if (is_string($args)) {
            $args = explode(' ', $args);
        }

        foreach ($args as $arg) {
            array_push($_SERVER['argv'], $arg);
        }

        $_SERVER['argc'] += count($args);
    }
}
