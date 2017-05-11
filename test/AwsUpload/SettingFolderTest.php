<?php

use AwsUpload\Setting\SettingFolder;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/BaseTestCase.php';

class SettingFolderTest extends BaseTestCase
{

    public function test_getPath_useAwsuploadHome_true()
    {
        $home = SettingFolder::getPath();

        $this->assertEquals($this->directory, $home);
    }
}
