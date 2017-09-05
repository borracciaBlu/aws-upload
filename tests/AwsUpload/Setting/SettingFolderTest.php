<?php

namespace AwsUpload\Tests\Setting;

use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Setting\SettingFolder;
use Symfony\Component\Filesystem\Filesystem;

class SettingFolderTest extends BaseTestCase
{

    public function test_getPath_useAwsuploadHome_true()
    {
        $home = SettingFolder::getPath();

        $this->assertEquals($this->aws_home, $home);
    }
}
