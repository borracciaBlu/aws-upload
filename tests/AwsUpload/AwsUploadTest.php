<?php
namespace AwsUpload\Tests;

use AwsUpload\AwsUpload;
use AwsUpload\Tests\BaseTestCase;

class AwsUploadTest extends BaseTestCase
{
    public function test_getCmdName_envs()
    {
        self::clearArgv();
        self::pushToArgv(array('asd.php', '-e', 'asd'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $cmd = $aws->getCmdName($args);
        $this->assertEquals('AwsUpload\Command\EnvsCommand', $cmd);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'envs', 'asd'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $cmd = $aws->getCmdName($args);
        $this->assertEquals('AwsUpload\Command\EnvsCommand', $cmd);

    }

    public function test_getCmdName_projs_withQuiet()
    {
        self::clearArgv();
        self::pushToArgv(array('aws-upload', '-p', '-q', 'asd'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $cmd = $aws->getCmdName($args);
        $this->assertEquals('AwsUpload\Command\ProjsCommand', $cmd);
        $this->assertEquals(true, $args->quiet);

        self::clearArgv();
        self::pushToArgv(array('aws-upload', 'projs', '-q', 'asd'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $cmd = $aws->getCmdName($args);
        $this->assertEquals('AwsUpload\Command\ProjsCommand', $cmd);
        $this->assertEquals(true, $args->quiet);
    }

    public function test_getCmdName_import()
    {
        self::clearArgv();
        self::pushToArgv(array('aws-upload', '-i', './dir/blog.test.json'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $cmd = $aws->getCmdName($args);
        $this->assertEquals('AwsUpload\Command\ImportCommand', $cmd);

        self::clearArgv();
        self::pushToArgv(array('aws-upload', 'import', './dir/blog.test.json'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $cmd = $aws->getCmdName($args);
        $this->assertEquals('AwsUpload\Command\ImportCommand', $cmd);
    }

    public function test_getCmdName_export()
    {
        self::clearArgv();
        self::pushToArgv(array('aws-upload', '-ex', 'test.env', './dir/blog.test.json'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $cmd = $aws->getCmdName($args);
        $this->assertEquals('AwsUpload\Command\ExportCommand', $cmd);

        self::clearArgv();
        self::pushToArgv(array('aws-upload', 'export', 'test.env', './dir/blog.test.json'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $cmd = $aws->getCmdName($args);
        $this->assertEquals('AwsUpload\Command\ExportCommand', $cmd);
    }
}
