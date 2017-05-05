<?php

use AwsUpload\AwsUpload;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';


class ListProjectsTest extends BaseTestCase
{
    /**
     * Check cmdProj with no projects
     */
    public function test_noProjects_expectedNoProjectMsg()
    {
        $this->expectOutputString("It seems that you don't have any project setup.\nTry to type:\n\n"
             . "    \e[32maws-upload new project.test\e[0m\n"
             . "\n");

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\ListProjects($aws);
        $cmd->run();
    }

    public function test_oneFile_expectedProjName()
    {
        $this->expectOutputString("project-1\n");
        
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\ListProjects($aws);
        $cmd->run();
    }

    public function test_moreFilesSameProj_expectedProjName()
    {
        $this->expectOutputString("project-1\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\ListProjects($aws);
        $cmd->run();
    }
    
    public function test_moreFilesDiffProj_expectedProjsName()
    {
        $this->expectOutputString("project-1 project-2\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-2.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\ListProjects($aws);
        $cmd->run();
    }
}
