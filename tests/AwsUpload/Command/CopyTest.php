<?php

namespace AwsUpload\Tests\Command;

use AwsUpload\Io\Output;
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
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopyCommand($aws);
        $cmd->run();
    }

    // test isValidArgs
    public function test_noValidKey_expected_NoArgsMsg_oneParam()
    {
        $msg = CopyMessage::noArgs();
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'aaa'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopyCommand($aws);
        $cmd->run();
    }

    // test isValidKey
    public function test_noValidKey_expectedNoValidKey_first()
    {
        $msg = ErrorMessage::noValidKey('bbbb');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'aaa', 'bbbb'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopyCommand($aws);
        $cmd->run();
    }

    // test isValidKey
    public function test_noValidKey_expectedNoValidKey_second()
    {
        $msg = ErrorMessage::noValidKey('aaa');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'aaa', 'bbb.bbb'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopyCommand($aws);
        $cmd->run();
    }

    // "file_not_exists"  => !Check::fileExists($source),
    public function test_validKeyNoExists_expectedNoFileFound()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $msg = ErrorMessage::noFileFound('project-2.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-2.dev', 'project-3.dev'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopyCommand($aws);
        $cmd->run();
    }

    // "file_exists" => Check::fileExists($dest),
    public function test_validKeyNoExists_expected_DestAlreadyExists()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-2.dev.json', '{}');

        $msg = ErrorMessage::keyAlreadyExists('project-2.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-1.dev', 'project-2.dev'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopyCommand($aws);
        $cmd->run();
    }

    public function test_copyFile()
    {   
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{"pem": "", "local":"", "remote":"", "exclude":[""]}');


        $msg = NewMessage::success('project-2.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-1.dev', 'project-2.dev'));


        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopyCommand($aws);
        $cmd->run();
       
        $settings = SettingFile::getObject('project-2.dev');

        $this->assertEquals('', $settings->pem);
        $this->assertEquals('', $settings->local);
        $this->assertEquals('', $settings->remote);
        $this->assertEquals(array(''), $settings->exclude);
    }
}
