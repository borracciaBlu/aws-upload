<?php

use AwsUpload\AwsUpload;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/../BaseTestCase.php';


class ListEnvironmentsTest extends BaseTestCase
{

    public function test_noKey_expectedNoProjectMsg()
    {
        $this->expectOutputString("It seems that you don't have any project setup.\nTry to type:\n\n"
             . "    \e[32maws-upload new project.test\e[0m\n"
             . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'new'));

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\NewSettingFile($aws);
        $cmd->run();
    }

    public function test_noValidKey_expectedNoValidKey()
    {
        $this->expectOutputString("It seems that the key \e[33maaa\e[0m is not valid:\n\n"
             . "Please try to use this format:\n"
             . "    - [project].[environmet]\n\n"
             . "Examples of valid key to create a new setting file:\n"
             . "    - \e[32mmy-site.staging\e[0m\n"
             . "    - \e[32mmy-site.dev\e[0m\n"
             . "    - \e[32mmy-site.prod\e[0m\n\n"
             . "Tips on choosing the key name:\n"
             . "    - for [project] and [environmet] try to be: short, sweet, to the point\n"
             . "    - use only one 'dot' . in the name\n"
             . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'new', 'aaa'));

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\NewSettingFile($aws);
        $cmd->run();
    }

    public function test_keyExists_expectedKeyAlreadyExists()
    {
        $this->expectOutputString(
            "It seems that the key \e[33mproject-1.dev\e[0m already exists try to use another one.\n\n"
             ."Please consider you already have the following elements:\n"
             . "+-----------+-------------+\n"
             ."| Project   | Environment |\n"
             ."+-----------+-------------+\n"
             ."| \e[32mproject-1\e[0m | \e[32mdev\e[0m         |\n"
             ."+-----------+-------------+\n"
             . "\n");
        
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
        
        self::clearArgv();
        self::pushToArgv(array('asd.php', 'new', 'project-1.dev'));

        $aws = new AwsUpload();
        $aws->is_phpunit = true;

        $cmd = new \AwsUpload\Command\NewSettingFile($aws);
        $cmd->run();
    }

//     public function test_cmdEnvs_moreFilesSameProj_expectedProposeAlternative()
//     {
//         $this->expectOutputString("The project \e[31mproj-3\e[0m you are tring to use doesn't exist.

// These are the available projects: 

//   +  \e[32mproject-1\e[0m
//   +  \e[32mproject-2\e[0m

// To get the envs from one of them, run (for example):

//    aws-upload -e project-1

// ");
        
//         $filesystem = new Filesystem();
//         $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
//         $filesystem->dumpFile($this->directory . '/project-2.prod.json', '{}');
//         $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');
        
//         self::clearArgv();
//         self::pushToArgv(array('asd.php', '-e', 'proj-3'));

//         $aws = new AwsUpload();
//         $aws->is_phpunit = true;

//         $cmd = new \AwsUpload\Command\ListEnvironments($aws);
//         $cmd->run();
//     }
}
