<?php

use AwsUpload\Io\Output;
use AwsUpload\AwsUpload;
use AwsUpload\Setting\SettingFiles;
use AwsUpload\Facilitator;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';


class CopySettingFileTest extends BaseTestCase
{

    public function test_noKey_expectedNoProjectMsg()
    {
        $msg = Facilitator::onNoCopyArgs();
        $msg = Output::color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy'));

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
    }

    public function test_noValidKey_expectedNoValidKey()
    {
        $msg = Facilitator::onNoCopyArgs();
        $msg = Output::color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'aaa'));

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
    }

    public function test_validKeyNoExists_expectedNoFileFound()
    {   
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $msg = Facilitator::onNoFileFound('project-2.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-2.dev', 'project-3.dev'));

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
    }

    public function test_copyFile()
    {   
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{"pem": "", "local":"", "remote":"", "exclude":[""]}');


        $msg = Facilitator::onNewSettingFileSuccess('project-2.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'copy', 'project-1.dev', 'project-2.dev'));


        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\CopySettingFile($aws);
        $cmd->run();
       
        $settings = SettingFiles::getObject('project-2.dev');

        $sample = (object) ["pem" => "", "local" => "", "remote" => "", "exclude" => [""]];
        $this->assertEquals($sample, $settings);
    }
}
