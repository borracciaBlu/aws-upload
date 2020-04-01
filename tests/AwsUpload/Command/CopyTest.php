<?php

namespace AwsUpload\Tests\Command;

use function AwsUpload\Io\color;
use AwsUpload\AwsUpload;
use AwsUpload\Message\NewMessage;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Setting\SettingFile;
use AwsUpload\Message\CopyMessage;
use AwsUpload\Message\ErrorMessage;
use Symfony\Component\Filesystem\Filesystem;

class CopyTest extends BaseTestCase
{

    // test isValidArgs
    public function test_noKey_expected_NoArgsMsg()
    {
        $msg = CopyMessage::noArgs();
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\CopyCommand($aws, $args, $output);
        $cmd->run();
    }

    // test isValidArgs
    public function test_noValidKey_expected_NoArgsMsg_oneParam()
    {
        $msg = CopyMessage::noArgs();
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'aaa'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\CopyCommand($aws, $args, $output);
        $cmd->run();
    }

    // test isValidKey
    public function test_noValidKey_expectedNoValidKey_first()
    {
        $msg = ErrorMessage::noValidKey('bbbb');
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'aaa', 'bbbb'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\CopyCommand($aws, $args, $output);
        $cmd->run();
    }

    // test isValidKey
    public function test_noValidKey_expectedNoValidKey_second()
    {
        $msg = ErrorMessage::noValidKey('aaa');
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'aaa', 'bbb.bbb'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\CopyCommand($aws, $args, $output);
        $cmd->run();
    }

    // "file_not_exists"  => !Check::fileExists($source),
    public function test_validKeyNoExists_expectedNoFileFound()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $msg = ErrorMessage::noFileFound('project-2.dev');
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-2.dev', 'project-3.dev'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\CopyCommand($aws, $args, $output);
        $cmd->run();
    }

    // "file_exists" => Check::fileExists($dest),
    public function test_validKeyNoExists_expected_DestAlreadyExists()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-2.dev.json', '{}');

        $msg = ErrorMessage::keyAlreadyExists('project-2.dev');
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-1.dev', 'project-2.dev'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\CopyCommand($aws, $args, $output);
        $cmd->run();
    }

    public function test_copyFile()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{"pem": "", "local":"", "remote":"", "exclude":[""]}');


        $msg = NewMessage::success('project-2.dev');
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-1.dev', 'project-2.dev'));


        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\CopyCommand($aws, $args, $output);
        $cmd->run();

        $settings = SettingFile::getObject('project-2.dev');

        $this->assertEquals('', $settings->pem);
        $this->assertEquals('', $settings->local);
        $this->assertEquals('', $settings->remote);
        $this->assertEquals(array(''), $settings->exclude);
    }
}
