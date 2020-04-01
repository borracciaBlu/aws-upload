<?php

namespace AwsUpload\Tests\Command;

use function AwsUpload\Io\color;
use AwsUpload\AwsUpload;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Message\ErrorMessage;
use AwsUpload\Message\EnvsMessage;
use Symfony\Component\Filesystem\Filesystem;

class EnvsTest extends BaseTestCase
{

    public function test_noProjects_expectedNoProjectMsg()
    {
        $msg = ErrorMessage::noProjects();
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', '-e', 'proj-3'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\EnvsCommand($aws, $args, $output);
        $cmd->run();
    }

    public function test_moreFilesSameProj_expectedProposeAlternative()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-2.prod.json', '{}');
        $filesystem->dumpFile($this->aws_home . '/project-1.staging.json', '{}');

        self::clearArgv();
        self::pushToArgv(array('asd.php', '-e', 'proj-3'));

        $error = EnvsMessage::errorNoEnvsProj('proj-3');
        $error = color($error);
        $this->expectOutputString($error);

        $aws = new AwsUpload();
        $args = $aws->getArgs();
        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\EnvsCommand($aws, $args, $output);
        $cmd->run();
    }
}
