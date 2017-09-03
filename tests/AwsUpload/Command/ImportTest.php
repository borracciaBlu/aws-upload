<?php

namespace AwsUpload\Tests\Settings;

use AwsUpload\Io\Output;
use AwsUpload\AwsUpload;
use AwsUpload\Message\ImportMessage;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Setting\SettingFile;
use AwsUpload\Message\ErrorMessage;
use Symfony\Component\Filesystem\Filesystem;

class ImportTest extends BaseTestCase
{

    // test no arguments passed
    public function test_noKey_expected_NoArgsMsg()
    {
        $msg = ImportMessage::errorNotFound('');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('aws-upload', 'import'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ImportCommand($aws);
        $cmd->run();
    }

    // test isValidArgs
    public function test_noValidKey_expected_NoArgsMsg_oneParam()
    {
        $msg = ErrorMessage::noValidKey('aaa');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->external . '/aaa.json', '{}');

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'import', $this->external . '/aaa.json'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ImportCommand($aws);
        $cmd->run();
    }

    // "file_exists" => SettingFile::fileExists($dest),
    public function test_validKeyNoExists_expected_DestAlreadyExists()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->external . '/project-1.dev.json', '{}');

        $msg = ErrorMessage::keyAlreadyExists('project-1.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'import', $this->external . '/project-1.dev.json'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ImportCommand($aws);
        $cmd->run();
    }

    public function test_importFile()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->external . '/project-1.dev.json', '{"pem": "", "local":"", "remote":"", "exclude":[""]}');


        $msg = ImportMessage::success('project-1.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'import', $this->external . '/project-1.dev.json'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ImportCommand($aws);
        $cmd->run();
       
        $settings = SettingFile::getObject('project-1.dev');

        $this->assertEquals('', $settings->pem);
        $this->assertEquals('', $settings->local);
        $this->assertEquals('', $settings->remote);
        $this->assertEquals(array(''), $settings->exclude);
    }
}
