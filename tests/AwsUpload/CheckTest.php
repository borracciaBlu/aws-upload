<?php

namespace AwsUpload\Tests;

use AwsUpload\Check;
use AwsUpload\Tests\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;

class CheckTest extends BaseTestCase
{
    /**
     * fileExists
     */

    public function test_fileExists_noFile_false()
    {
        $exist = Check::fileExists('proj.dev');

        $this->assertFalse($exist);
    }

    public function test_fileExists_yesFile_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $exist = Check::fileExists('proj.dev');
        $this->assertFalse($exist);

        $exist = Check::fileExists('project-1.dev');
        $this->assertTrue($exist);
    }

    /**
     * isValidKey
     */

    public function test_isValidKey_noDot_false()
    {
        $valid = Check::isValidKey('proj');

        $this->assertFalse($valid);
    }

    public function test_isValidKey_oneDot_true()
    {
        $valid = Check::isValidKey('proj.env');

        $this->assertTrue($valid);
    }

    public function test_isValidKey_moreDots_false()
    {
        $valid = Check::isValidKey('proj.env.biz');

        $this->assertFalse($valid);
    }
}
