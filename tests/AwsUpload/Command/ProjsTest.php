<?php

namespace AwsUpload\Tests\Command;

use AwsUpload\AwsUpload;
use AwsUpload\Io\Output;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Message\ErrorMessage;
use Symfony\Component\Filesystem\Filesystem;

class ProjsTest extends BaseTestCase
{
    /**
     * Check cmdProj with no projects
     */
    public function test_noProjects_expectedNoProjectMsg()
    {
        $msg = ErrorMessage::noProjects();
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ProjsCommand($aws);
        $cmd->run();
    }

    public function test_oneFile_expectedProjName()
    {
        $this->expectOutputString("project-1\n\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ProjsCommand($aws);
        $cmd->run();
    }

    public function test_moreFilesSameProj_expectedProjName()
    {
        $this->expectOutputString("project-1\n\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ProjsCommand($aws);
        $cmd->run();
    }

    public function test_moreFilesDiffProj_expectedProjsName()
    {
        $this->expectOutputString("project-1 project-2\n\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-2.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\ProjsCommand($aws);
        $cmd->run();
    }
}
