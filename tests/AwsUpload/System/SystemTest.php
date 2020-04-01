<?php

namespace AwsUpload\Tests\System;

use AwsUpload\Tests\BaseTestCase;
use AwsUpload\System\System;
use Symfony\Component\Filesystem\Filesystem;

class SystemTest extends BaseTestCase
{
    public function test_getEditor_ENV_case()
    {
        $_ENV['EDITOR'] = 'env_case';
        $editor = System::getEditor();

        $this->assertEquals($editor, 'env_case');
    }

    public function test_getEditor_SERVER_case()
    {
        unset($_ENV['EDITOR']);
        $_SERVER['EDITOR'] = 'server_case';
        $editor = System::getEditor();

        $this->assertEquals($editor, 'server_case');
    }

    public function test_getEditor_default_case()
    {
        unset($_ENV['EDITOR']);
        unset($_SERVER['EDITOR']);
        $editor = System::getEditor();

        $this->assertEquals($editor, 'vim');
    }
}
