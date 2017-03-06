<?php

use AwsUpload\SettingFolder;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/BaseTestCase.php';

class SettingFolderTest extends BaseTestCase
{

    public function testGetPath()
    {
        $home = SettingFolder::getPath();

        $this->assertEquals($this->directory, $home);
    }

} 