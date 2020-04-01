<?php

namespace AwsUpload\Tests\Command;

use AwsUpload\AwsUpload;
use function AwsUpload\Io\color;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Message\ErrorMessage;
use Symfony\Component\Filesystem\Filesystem;

class KeysTest extends BaseTestCase
{
    /**
     * Check cmdProj with no projects
     */
    public function test_noProjects_expectedNoProjectMsg()
    {
        $msg = ErrorMessage::noProjects();
        $msg = color($msg);
        $this->expectOutputString($msg);

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\KeysCommand($aws, $args, $output);
        $cmd->run();
    }

    public function test_oneFile_expectedProjName()
    {
        $this->expectOutputString("project-1.dev\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\KeysCommand($aws, $args, $output);
        $cmd->run();
    }

    public function test_moreFilesSameProj_expectedProjName()
    {
        $this->expectOutputString("project-1.dev project-1.prod project-1.staging\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\KeysCommand($aws, $args, $output);
        $cmd->run();
    }

    public function test_moreFilesDiffProj_expectedProjsName()
    {
        $this->expectOutputString("project-1.prod project-1.staging project-2.dev\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-2.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\KeysCommand($aws, $args, $output);
        $cmd->run();
    }
}
