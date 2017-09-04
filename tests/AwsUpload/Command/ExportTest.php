<?php

namespace AwsUpload\Tests\Settings;

use AwsUpload\Io\Output;
use AwsUpload\AwsUpload;
use AwsUpload\Model\Settings;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Message\ErrorMessage;
use AwsUpload\Message\ExportMessage;
use Symfony\Component\Filesystem\Filesystem;

class ExportTest extends BaseTestCase
{

    // test no arguments passed
    public function test_noKey_expected_NoArgsMsg()
    {
        $msg = ExportMessage::noArgs('');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('aws-upload', 'export'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ExportCommand($aws);
        $cmd->run();
    }

    // test isValidArgs
    public function test_noValidKey_expected_NoArgsMsg_oneParam()
    {
        $msg = ErrorMessage::noValidKey('aaa');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/blog.dev.json', '{}');

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'export', 'aaa'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ExportCommand($aws);
        $cmd->run();
    }

    // "file_exists" => SettingFile::fileExists($dest),
    public function test_validKeyNoExists_expected_DestAlreadyExists()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->external . '/project-1.dev.json', '{}');

        $msg = 'file alreay present';
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'export', 'project-1.dev', $this->external . '/'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ExportCommand($aws);
        $cmd->run();
    }

    public function test_exportFile()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json',
                              '{"pem": "", "local":"", "remote":"", "exclude":[""]}');

        $msg = ExportMessage::success('project-1.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'export', 'project-1.dev', $this->external));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ExportCommand($aws);
        $cmd->run();
       
        $settings = new Settings($this->external . '/project-1.dev.json');

        $this->assertEquals('', $settings->pem);
        $this->assertEquals('', $settings->local);
        $this->assertEquals('', $settings->remote);
        $this->assertEquals(array(''), $settings->exclude);
    }
}
