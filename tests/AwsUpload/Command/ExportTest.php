<?php

namespace AwsUpload\Tests\Command;

use function AwsUpload\Io\color;
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
        $msg = ExportMessage::noArgs();
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('aws-upload', 'export'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();

        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\ExportCommand($aws, $args, $output);
        $cmd->run();
    }

    // test isValidArgs
    public function test_noValidKey_expected_NoArgsMsg_oneParam()
    {
        $msg = ErrorMessage::noValidKey('aaa');
        $msg = color($msg);
        $this->expectOutputString($msg);

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/blog.dev.json', '{}');

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'export', 'aaa'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();

        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\ExportCommand($aws, $args, $output);
        $cmd->run();
    }

    // "file_exists" => SettingFile::fileExists($dest),
    public function test_validKeyNoExists_expected_DestAlreadyExists()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->external . '/project-1.dev.json', '{}');

        $msg = 'file alreay present';
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'export', 'project-1.dev', $this->external . '/'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();

        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\ExportCommand($aws, $args, $output);
        $cmd->run();
    }

    public function test_exportFile()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json',
                              '{"pem": "", "local":"", "remote":"", "exclude":[""]}');

        $msg = ExportMessage::success('project-1.dev');
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'export', 'project-1.dev', $this->external));

        $aws = new AwsUpload();
        $args = $aws->getArgs();

        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\ExportCommand($aws, $args, $output);
        $cmd->run();

        $settings = new Settings($this->external . '/project-1.dev.json');

        $this->assertEquals('', $settings->pem);
        $this->assertEquals('', $settings->local);
        $this->assertEquals('', $settings->remote);
        $this->assertEquals(array(''), $settings->exclude);
    }
}
