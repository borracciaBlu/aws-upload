<?php

namespace AwsUpload\Tests\Settings;

use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Setting\SettingFiles;
use Symfony\Component\Filesystem\Filesystem;

class SettingFilesTest extends BaseTestCase
{

    public function test_getList_noFiles_true()
    {
        $list = SettingFiles::getList();

        $this->assertCount(0, $list);
    }

    public function test_getList_oneFile_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $list = SettingFiles::getList();

        $this->assertCount(1, $list);
    }

    public function test_getList_moreFiles_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $list = SettingFiles::getList();

        $this->assertCount(3, $list);
    }

    public function test_getKeys_oneFile_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $list = SettingFiles::getKeys();

        $this->assertCount(1, $list);
    }

    public function test_getKeys_moreFiles_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $list = SettingFiles::getKeys();

        $this->assertCount(3, $list);
    }

    public function test_getObject_oneFile_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{"pem": "", "local":"", "remote":"", "exclude":[""]}');

        $settings = SettingFiles::getObject('project-1.dev');

        $this->assertEquals('', $settings->pem);
        $this->assertEquals('', $settings->local);
        $this->assertEquals('', $settings->remote);
        $this->assertEquals(array(''), $settings->exclude);
    }

    public function test_getProjs_noProjects_true()
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
        $filesystem->dumpFile($this->directory . '/old/project-2.staging.json', '{}');

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

    public function test_noArgs()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-2.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $envs = SettingFiles::getEnvs('');

        $this->assertEquals([], $envs);
    }

    public function test_extractProjEnv()
    {
        list($proj, $env) = SettingFiles::extractProjEnv(array());
        $this->assertEquals('no-project-given', $proj);
        $this->assertEquals('no-environment-given', $env);

        list($proj, $env) = SettingFiles::extractProjEnv(array('a'));
        $this->assertEquals('no-project-given', $proj);
        $this->assertEquals('no-environment-given', $env);

        list($proj, $env) = SettingFiles::extractProjEnv(array('a', 'b'));
        $this->assertEquals('a', $proj);
        $this->assertEquals('b', $env);

        list($proj, $env) = SettingFiles::extractProjEnv(array('a.b'));
        $this->assertEquals('a', $proj);
        $this->assertEquals('b', $env);

        list($proj, $env) = SettingFiles::extractProjEnv(array(2 => 'a', 4 => 'b'));
        $this->assertEquals('a', $proj);
        $this->assertEquals('b', $env);
    }
}
