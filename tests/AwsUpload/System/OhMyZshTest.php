<?php

namespace AwsUpload\Tests\System;

use AwsUpload\System\OhMyZsh;
use AwsUpload\Tests\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;

class OhMyZshTest extends BaseTestCase
{
    public function test_activate_no_plugin()
    {
        $zshrc_path = $this->aws_home . '/../.zshrc';
        $zshrc_body = 'aaaa';

        $filesystem = new Filesystem();
        $filesystem->dumpFile($zshrc_path, $zshrc_body);

        $pre_content = file_get_contents($zshrc_path);
        $this->assertEquals($pre_content, 'aaaa');

        OhMyZsh::activate();

        $post_content = file_get_contents($zshrc_path);
        $this->assertEquals($post_content, 'aaaa' . "\n" . 'plugins=(aws-upload)');
    }

    public function test_activate_plugin_one_line()
    {
        $zshrc_path = $this->aws_home . '/../.zshrc';
        $zshrc_body = 'plugins=(git)';

        $filesystem = new Filesystem();
        $filesystem->dumpFile($zshrc_path, $zshrc_body);

        $pre_content = file_get_contents($zshrc_path);
        $this->assertEquals($pre_content, 'plugins=(git)');

        OhMyZsh::activate();

        $post_content = file_get_contents($zshrc_path);
        $this->assertEquals('plugins=(git aws-upload)', $post_content);
    }

    public function test_activate_plugin_one_line_has_plugin_already()
    {
        $zshrc_path = $this->aws_home . '/../.zshrc';
        $zshrc_body = 'plugins=(git aws-upload)';

        $filesystem = new Filesystem();
        $filesystem->dumpFile($zshrc_path, $zshrc_body);

        $pre_content = file_get_contents($zshrc_path);
        $this->assertEquals($pre_content, 'plugins=(git aws-upload)');

        OhMyZsh::activate();

        $post_content = file_get_contents($zshrc_path);
        $this->assertEquals('plugins=(git aws-upload)', $post_content);
    }

    public function test_activate_plugin_do_not_touch_comments_line()
    {
        $zshrc_path = $this->aws_home . '/../.zshrc';
        $zshrc_body = ' # plugins=(git)';

        $filesystem = new Filesystem();
        $filesystem->dumpFile($zshrc_path, $zshrc_body);

        $pre_content = file_get_contents($zshrc_path);
        $this->assertEquals($pre_content, ' # plugins=(git)');

        OhMyZsh::activate();

        $post_content = file_get_contents($zshrc_path);
        $this->assertEquals(' # plugins=(git)' . "\n" . 'plugins=(aws-upload)',
                            $post_content);
    }
}
