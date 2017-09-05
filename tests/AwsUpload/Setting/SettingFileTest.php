<?php

namespace AwsUpload\Tests\Setting;

use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Setting\SettingFile;
use Symfony\Component\Filesystem\Filesystem;

class SettingFileTest extends BaseTestCase
{

    public function test_getList_noFiles_true()
    {
        $list = SettingFile::getList();

        $this->assertCount(0, $list);
    }

    public function test_getList_oneFile_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $list = SettingFile::getList();

        $this->assertCount(1, $list);
    }

    public function test_getList_moreFiles_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        $list = SettingFile::getList();

        $this->assertCount(3, $list);
    }

    public function test_getKeys_oneFile_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $list = SettingFile::getKeys();

        $this->assertCount(1, $list);
    }

    public function test_getKeys_moreFiles_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        $list = SettingFile::getKeys();

        $this->assertCount(3, $list);
    }

    public function test_getObject_oneFile_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{"pem": "", "local":"", "remote":"", "exclude":[""]}');

        $settings = SettingFile::getObject('project-1.dev');

        $this->assertEquals('', $settings->pem);
        $this->assertEquals('', $settings->local);
        $this->assertEquals('', $settings->remote);
        $this->assertEquals(array(''), $settings->exclude);
    }

    public function test_getProjs_noProjects_true()
    {
        $projs = SettingFile::getProjs();

        $this->assertEquals([], $projs);
    }

    public function testGetProjsOneFile()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $projs = SettingFile::getProjs();
        $envs = SettingFile::getEnvs('project-1');

        $this->assertEquals(['project-1'], $projs);
        $this->assertEquals(['dev'], $envs);
    }

    public function testGetProjsMoreFilesSameProj()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/old/project-2.staging.json', '{}');

        $projs = SettingFile::getProjs();
        $envs = SettingFile::getEnvs('project-1');
        
        $this->assertEquals(['project-1'], $projs);
        $this->assertEquals(['dev', 'prod', 'staging'], $envs);
    }
    
    public function testGetProjsMoreFilesDiffProj()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-2.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        $projs = SettingFile::getProjs();
        $envs = SettingFile::getEnvs('project-2');

        $this->assertEquals(['project-1', 'project-2'], $projs);
        $this->assertEquals(['dev'], $envs);
    }

    public function test_noArgs()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-2.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        $envs = SettingFile::getEnvs('');

        $this->assertEquals([], $envs);
    }

    public function test_extractProjEnv()
    {
        list($proj, $env) = SettingFile::extractProjEnv(array());
        $this->assertEquals('no-project-given', $proj);
        $this->assertEquals('no-environment-given', $env);

        list($proj, $env) = SettingFile::extractProjEnv(array('a'));
        $this->assertEquals('no-project-given', $proj);
        $this->assertEquals('no-environment-given', $env);

        list($proj, $env) = SettingFile::extractProjEnv(array('a', 'b'));
        $this->assertEquals('a', $proj);
        $this->assertEquals('b', $env);

        list($proj, $env) = SettingFile::extractProjEnv(array('a.b'));
        $this->assertEquals('a', $proj);
        $this->assertEquals('b', $env);

        list($proj, $env) = SettingFile::extractProjEnv(array(2 => 'a', 4 => 'b'));
        $this->assertEquals('a', $proj);
        $this->assertEquals('b', $env);
    }

    /**
     * fileExists
     */

    public function test_fileExists_noFile_false()
    {
        $exist = SettingFile::exists('proj.dev');

        $this->assertFalse($exist);
    }

    public function test_fileExists_yesFile_true()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $exist = SettingFile::exists('proj.dev');
        $this->assertFalse($exist);

        $exist = SettingFile::exists('project-1.dev');
        $this->assertTrue($exist);
    }

    /**
     * isValidKey
     */

    public function test_isValidKey_noDot_false()
    {
        $valid = SettingFile::isValidKey('proj');

        $this->assertFalse($valid);
    }

    public function test_isValidKey_oneDot_true()
    {
        $valid = SettingFile::isValidKey('proj.env');

        $this->assertTrue($valid);
    }

    public function test_isValidKey_moreDots_false()
    {
        $valid = SettingFile::isValidKey('proj.env.biz');

        $this->assertFalse($valid);
    }
}
