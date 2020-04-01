<?php

namespace AwsUpload\Tests\Command;

use function AwsUpload\Io\color;
use AwsUpload\AwsUpload;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Message\EditMessage;
use AwsUpload\Message\ErrorMessage;
use Symfony\Component\Filesystem\Filesystem;

class EditTest extends BaseTestCase
{

    public function test_noKey_expectedNoProjectMsg()
    {
        $msg = EditMessage::noArgs();
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'edit'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\EditCommand($aws, $args, $output);
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
        self::pushToArgv(array('asd.php', 'edit', 'aaa'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\EditCommand($aws, $args, $output);
        $cmd->run();
    }

    public function test_validKeyNoExists_expectedNoFileFound()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');

        $msg = ErrorMessage::noFileFound('project-2.dev');
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'edit', 'project-2.dev'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\EditCommand($aws, $args, $output);
        $cmd->run();
    }
}
