<?php

use AwsUpload\AwsUpload;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/BaseTestCase.php';


class AwsUploadTest extends BaseTestCase
{
    public function test_getCmdName()
    {
        self::clearArgv();
        self::pushToArgv(array('asd.php', '-e' , 'asd'));

        $aws = new AwsUpload();
        $cmd = $aws->getCmdName();
        $this->assertEquals('AwsUpload\Command\ListEnvs', $cmd);
    }

    public function test_getCmdName_withQuiet()
    {
        self::clearArgv();
        self::pushToArgv(array('asd.php', '-p' , '-q', 'asd'));

        $aws = new AwsUpload();
        $cmd = $aws->getCmdName();
        $this->assertEquals('AwsUpload\Command\ListProjects', $cmd);
        $this->assertEquals(true, $aws->args->quiet);
    }
}
