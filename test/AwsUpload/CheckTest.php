<?php

use AwsUpload\Check;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/BaseTestCase.php';

class CheckTest extends BaseTestCase
{

    public function testFileExists()
    {
        $exist = Check::fileExists('proj.dev');

        $this->assertFalse($exist);
    }

    public function testGetListOneFile()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $exist = Check::fileExists('proj.dev');
        $this->assertFalse($exist);

        $exist = Check::fileExists('project-1.dev');
        $this->assertTrue($exist);
    }
}
