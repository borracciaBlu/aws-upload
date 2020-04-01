<?php

namespace AwsUpload\Tests\Command;

use function AwsUpload\Io\color;
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
        $msg = ImportMessage::noArgs();
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('aws-upload', 'import'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();

        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\ImportCommand($aws, $args, $output);
        $cmd->run();
    }

    // test isValidArgs
    public function test_noValidKey_expected_NoArgsMsg_oneParam()
    {
        $msg = ErrorMessage::noValidKey('aaa');
        $msg = color($msg);
        $this->expectOutputString($msg);

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->external . '/aaa.json', '{}');

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'import', $this->external . '/aaa.json'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();

        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\ImportCommand($aws, $args, $output);
        $cmd->run();
    }

    public function test_validKeyNoExists_expected_DestAlreadyExists()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->external . '/project-1.dev.json', '{}');

        $msg = ErrorMessage::keyAlreadyExists('project-1.dev');
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'import', $this->external . '/project-1.dev.json'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();

        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\ImportCommand($aws, $args, $output);
        $cmd->run();
    }

    public function test_importFile()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->external . '/project-1.dev.json', '{"pem": "", "local":"", "remote":"", "exclude":[""]}');


        $msg = ImportMessage::success('project-1.dev');
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'import', $this->external . '/project-1.dev.json'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();

        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\ImportCommand($aws, $args, $output);
        $cmd->run();

        $settings = SettingFile::getObject('project-1.dev');

        $this->assertEquals('', $settings->pem);
        $this->assertEquals('', $settings->local);
        $this->assertEquals('', $settings->remote);
        $this->assertEquals(array(''), $settings->exclude);
    }
}
