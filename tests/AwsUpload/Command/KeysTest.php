<?php

namespace AwsUpload\Tests\Command;

use AwsUpload\AwsUpload;
use AwsUpload\Tests\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;

class KeysTest extends BaseTestCase
{
    /**
     * Check cmdProj with no projects
     */
    public function test_noProjects_expectedNoProjectMsg()
    {
        $this->expectOutputString("It seems that you don't have any project setup.\nTry to type:\n\n"
             . "    \e[32maws-upload new project.test\e[0m\n"
             . "\n\n");

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\KeysCommand($aws);
        $cmd->run();
    }

    public function test_oneFile_expectedProjName()
    {
        $this->expectOutputString("project-1.dev\n\n");
        
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\KeysCommand($aws);
        $cmd->run();
    }

    public function test_moreFilesSameProj_expectedProjName()
    {
        $this->expectOutputString("project-1.dev project-1.prod project-1.staging\n\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\KeysCommand($aws);
        $cmd->run();
    }
    
    public function test_moreFilesDiffProj_expectedProjsName()
    {
        $this->expectOutputString("project-1.prod project-1.staging project-2.dev\n\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-2.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\KeysCommand($aws);
        $cmd->run();
    }
}
