<?php

namespace AwsUpload\Tests\Command;

use AwsUpload\Io\Output;
use AwsUpload\AwsUpload;
use AwsUpload\Message\NewMessage;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Message\ErrorMessage;
use Symfony\Component\Filesystem\Filesystem;

class NewTest extends BaseTestCase
{

    public function test_noKey_expectedNoProjectMsg()
    {
        $msg = NewMessage::noArgs();
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'new'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\NewCommand($aws);
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
             . "\n" . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'new', 'aaa'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\NewCommand($aws);
        $cmd->run();
    }

    public function test_keyExists_expectedKeyAlreadyExists()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $msg = ErrorMessage::keyAlreadyExists('project-1.dev');
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");
        
        self::clearArgv();
        self::pushToArgv(array('asd.php', 'new', 'project-1.dev'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\NewCommand($aws);
        $cmd->run();
    }
}
