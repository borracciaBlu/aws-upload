<?php

use AwsUpload\SettingFiles;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/BaseTestCase.php';

class SettingFilesTest extends BaseTestCase
{

    public function testGetListNoFiles()
    {
        $list = SettingFiles::getList();

        $this->assertCount(0, $list);
    }

    public function testGetListOneFile()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $list = SettingFiles::getList();

        $this->assertCount(1, $list);
    }

    public function testGetListMoreFiles()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $list = SettingFiles::getList();

        $this->assertCount(3, $list);
    }

    public function testGetObject()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{"pem": "", "local":"", "remote":"", "exclude":[""]}');

        $settings = SettingFiles::getObject('project-1.dev');

        $sample = (object) ["pem" => "", "local" => "", "remote" => "", "exclude" => [""]];
        $this->assertEquals($sample, $settings);
    }

    public function testGetProjs()
    {
        $projs = SettingFiles::getProjs();

        $this->assertEquals([], $projs);
    }

    public function testGetProjsOneFile()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $projs = SettingFiles::getProjs();
        $envs = SettingFiles::getEnvs('project-1');

        $this->assertEquals(['project-1'], $projs);
        $this->assertEquals(['dev'], $envs);
    }

    public function testGetProjsMoreFilesSameProj()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $projs = SettingFiles::getProjs();
        $envs = SettingFiles::getEnvs('project-1');
        
        $this->assertEquals(['project-1'], $projs);
        $this->assertEquals(['dev', 'prod', 'staging'], $envs);
    }
    
    public function testGetProjsMoreFilesDiffProj()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-2.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $projs = SettingFiles::getProjs();
        $envs = SettingFiles::getEnvs('project-2');

        $this->assertEquals(['project-1', 'project-2'], $projs);
        $this->assertEquals(['dev'], $envs);
    }
}
