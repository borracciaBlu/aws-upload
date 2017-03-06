<?php

use AwsUpload\SettingFiles;
use AwsUpload\AwsUpload;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/BaseTestCase.php';

class AwsUploadTest extends BaseTestCase
{

    public function testCmdProjs()
    {
        $aws = new AwsUpload();
        $projs = $aws->cmdProjs();

        $this->assertEquals('', $projs);
    }

    public function testCmdProjs_OneFile()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $aws = new AwsUpload();
        $projs = $aws->cmdProjs();

        $this->assertEquals('project-1', $projs);
    }

    public function testCmdProjs_tMoreFilesSameProj()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $projs = $aws->cmdProjs();

        $this->assertEquals('project-1', $projs);
    }
    
    public function testCmdProjs_MoreFilesDiffProj()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-2.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $projs = $aws->cmdProjs();

        $this->assertEquals('project-1 project-2', $projs);
    }

} 