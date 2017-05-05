<?php

use AwsUpload\AwsUpload;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';


class ListEnvironmentsTest extends BaseTestCase
{

    public function test_noProjects_expectedNoProjectMsg()
    {
        $this->expectOutputString("It seems that you don't have any project setup.\nTry to type:\n\n"
             . "    \e[32maws-upload new project.test\e[0m\n"
             . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', '-e', 'proj-3'));

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\ListEnvironments($aws);
        $cmd->run();
    }

    public function test_moreFilesSameProj_expectedProposeAlternative()
    {
        $this->expectOutputString("The project \e[31mproj-3\e[0m you are tring to use doesn't exist.

These are the available projects: 

  +  \e[32mproject-1\e[0m
  +  \e[32mproject-2\e[0m

To get the envs from one of them, run (for example):

   aws-upload -e project-1

");
        
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-2.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');
        
        self::clearArgv();
        self::pushToArgv(array('asd.php', '-e', 'proj-3'));

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\ListEnvironments($aws);
        $cmd->run();
    }
}
