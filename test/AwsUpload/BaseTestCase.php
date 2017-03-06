<?php
require __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

abstract class BaseTestCase extends TestCase
{

    /**
     * @var string
     */
    protected $directory;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->directory = __DIR__.'/../../build/' . strtolower(get_class($this));
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
    }
}
