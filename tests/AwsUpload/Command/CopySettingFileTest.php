<?php

namespace AwsUpload\Tests\Settings;

use AwsUpload\Io\Output;
use AwsUpload\AwsUpload;
use AwsUpload\Facilitator;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Setting\SettingFiles;
use Symfony\Component\Filesystem\Filesystem;

class CopySettingFileTest extends BaseTestCase
{

    // test isValidArgs
    public function test_noKey_expected_NoArgsMsg()
    {
        $msg = Facilitator::onNoCopyArgs();
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
    }

    // test isValidArgs
    public function test_noValidKey_expected_NoArgsMsg_oneParam()
    {
        $msg = Facilitator::onNoCopyArgs();
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'aaa'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
    }

    // test isValidKey
    public function test_noValidKey_expectedNoValidKey_first()
    {
        $msg = Facilitator::onNoValidKey('bbbb');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'aaa', 'bbbb'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
    }

    // test isValidKey
    public function test_noValidKey_expectedNoValidKey_second()
    {
        $msg = Facilitator::onNoValidKey('aaa');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'aaa', 'bbb.bbb'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
    }

    // "file_not_exists"  => !Check::fileExists($source),
    public function test_validKeyNoExists_expectedNoFileFound()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $msg = Facilitator::onNoFileFound('project-2.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-2.dev', 'project-3.dev'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
    }

    // "file_exists" => Check::fileExists($dest),
    public function test_validKeyNoExists_expected_DestAlreadyExists()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-2.dev.json', '{}');

        $msg = Facilitator::onKeyAlreadyExists('project-2.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-1.dev', 'project-2.dev'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
    }

    public function test_copyFile()
    {   
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{"pem": "", "local":"", "remote":"", "exclude":[""]}');


        $msg = Facilitator::onNewSettingFileSuccess('project-2.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-1.dev', 'project-2.dev'));


        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
       
        $settings = SettingFiles::getObject('project-2.dev');

        $sample = (object) ["pem" => "", "local" => "", "remote" => "", "exclude" => [""]];
        $this->assertEquals($sample, $settings);
    }
}
