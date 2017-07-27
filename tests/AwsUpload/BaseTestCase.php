<?php

namespace AwsUpload\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

abstract class BaseTestCase extends TestCase
{

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $directoryBuild;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->directoryBuild = __DIR__ . '/../../build/';

        $uid = strtolower(get_class($this));
        $uid = str_replace('\\', '-', $uid);
        $this->directory = __DIR__ . '/../../build/' . $uid;
        putenv("AWSUPLOAD_HOME=" . $this->directory);

        $filesystem = new Filesystem();
        $filesystem->mkdir($this->directory);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        unset($_ENV['AWSUPLOAD_HOME']);

        $filesystem = new Filesystem();
        $filesystem->remove($this->directory);
        $filesystem->remove($this->directoryBuild);
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
