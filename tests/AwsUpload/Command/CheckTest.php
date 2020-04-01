<?php

namespace AwsUpload\Tests\Command;

use function AwsUpload\Io\color;
use AwsUpload\AwsUpload;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Message\CheckMessage;
use Symfony\Component\Filesystem\Filesystem;

class CheckTest extends BaseTestCase
{
    public function test_validKeyNoExists_expectedNoFileFound()
    {

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/file.pem', '');
        $filesystem->mkdir($this->aws_home . '/local');
        $filesystem->dumpFile($this->aws_home . '/project-2.dev.json', '{ "pem" : "' . $this->aws_home . '/file.pem", "local" : "' . $this->aws_home . '/local"}');


        $pem_perms = decoct(fileperms($this->aws_home . '/file.pem') & 0777);

        $report = array(
            "path" => $this->aws_home . '/project-2.dev.json',
            "is_valid_json" => true,
            "pem" => $this->aws_home . '/file.pem',
            "pem_exists" => true,
            "is_400" => false,
            "pem_perms" => $pem_perms,
            "local" => $this->aws_home . '/local',
            "local_exists" => true,
            "error_json" => ''
        );

        $msg = CheckMessage::report($report);
        $msg = color($msg);
        $this->expectOutputString($msg);

        self::clearArgv();
        self::pushToArgv(array('asd.php', 'check', 'project-2.dev'));

        $aws = new AwsUpload();
        $args = $aws->getArgs();

        $output = new \AwsUpload\Io\OutputEcho($args);

        $cmd = new \AwsUpload\Command\CheckCommand($aws, $args, $output);
        $cmd->run();
    }
}
