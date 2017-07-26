<?php

namespace AwsUpload\Tests\Settings;

use AwsUpload\Io\Output;
use AwsUpload\AwsUpload;
use AwsUpload\Facilitator;
use AwsUpload\Tests\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;

class CheckSettingsFileTest extends BaseTestCase
{
    public function test_validKeyNoExists_expectedNoFileFound()
    {   

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/file.pem', '');
        $filesystem->mkdir($this->directory . '/local');
        $filesystem->dumpFile($this->directory . '/project-2.dev.json', '{ "pem" : "' . $this->directory . '/file.pem", "local" : "' . $this->directory . '/local"}');


        $pem_perms = decoct(fileperms($this->directory . '/file.pem') & 0777);

        $report = array(
            "path" => $this->directory . '/project-2.dev.json',
            "is_valid_json" => true,
            "pem" => $this->directory . '/file.pem',
            "pem_exists" => true,
            "is_400" => false,
            "pem_perms" => $pem_perms,
            "local" => $this->directory . '/local',
            "local_exists" => true,
            "error_json" => ''
        );

        $msg = Facilitator::reportBanner($report);
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'check', 'project-2.dev'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\CheckSettingFile($aws);
        $cmd->run();
    }
}
