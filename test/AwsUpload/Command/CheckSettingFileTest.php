<?php

use AwsUpload\Io\Output;
use AwsUpload\AwsUpload;
use AwsUpload\Facilitator;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';


class CheckSettingsFileTest extends BaseTestCase
{
    public function test_validKeyNoExists_expectedNoFileFound()
    {   

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/file.pem', '');
        $filesystem->mkdir($this->directory . '/local');
        $filesystem->dumpFile($this->directory . '/project-2.dev.json', '{ "pem" : "' . $this->directory . '/file.pem", "local" : "' . $this->directory . '/local"}');

        $report = array(
            "path" => $this->directory . '/project-2.dev.json',
            "is_valid_json" => true,
            "pem" => $this->directory . '/file.pem',
            "pem_exists" => true,
            "pem_perms" => '664',
            "local" => $this->directory . '/local',
            "local_exists" => true,
        );

        $msg = Facilitator::reportBanner($report);
        $msg = Output::color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'check', 'project-2.dev'));

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\CheckSettingFile($aws);
        $cmd->run();
    }
}
