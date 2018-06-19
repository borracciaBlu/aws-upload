<?php

namespace AwsUpload\Tests\Command;

use AwsUpload\Io\Output;
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
        $msg = Output::color($msg);
        $this->expectOutputString($msg . "\n");

        self::clearArgv();
        self::pushToArgv(array('asd.php', '-e', 'proj-3'));

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\EnvsCommand($aws);
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

        $error = EnvsMessage::errorNoEnvsProj('proj-3') . "\n";
        $error = Output::color($error);
        $this->expectOutputString($error);

        $aws = new AwsUpload();
        $aws->setOutput(new \AwsUpload\Io\OutputEcho());

        $cmd = new \AwsUpload\Command\EnvsCommand($aws);
        $cmd->run();
    }
}
