<?php

use AwsUpload\AwsUpload;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/BaseTestCase.php';


class AwsUploadTest extends BaseTestCase
{

    public function testCmdProjsExpectedNoProjectMsg()
    {
        $this->expectOutputString("It seems that you don't have any project setup.\n\n");

        $aws = new AwsUpload();
        $aws->is_phpunit = true;
        $projs = $aws->cmdProjs();
    }

    public function testCmdProjsOneFileExpectedProjName()
    {
        $this->expectOutputString("project-1\n");
        
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');

        $aws = new AwsUpload();
        $aws->is_phpunit = true;
        $projs = $aws->cmdProjs();
    }

    public function testCmdProjsMoreFilesSameProjExpectedProjName()
    {
        $this->expectOutputString("project-1\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-1.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $aws->is_phpunit = true;
        $projs = $aws->cmdProjs();
    }
    
    public function testCmdProjsMoreFilesDiffProjExpectedProjsName()
    {
        $this->expectOutputString("project-1 project-2\n");

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->directory . '/project-2.dev.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.prod.json', '{}');
        $filesystem->dumpFile($this->directory . '/project-1.staging.json', '{}');

        $aws = new AwsUpload();
        $aws->is_phpunit = true;
        $projs = $aws->cmdProjs();
    }

    public function testCmdEnvExpectedNoProjectMsg()
    {
        $this->expectOutputString("It seems that you don't have any project setup.\n\n");
        
        self::clearArgv();
        self::pushToArgv(array('asd.php', '-e', 'proj-3'));

        $aws = new AwsUpload();
        $aws->is_phpunit = true;
        $projs = $aws->cmdEnvs();
    }

    public function testCmdEnvMoreFilesSameProjExpectedProposeAlternative()
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
        $projs = $aws->cmdEnvs();
    }

    /**
     * Clear the $_SERVER['argv'] array
     */
    public static function clearArgv()
    {
        $_SERVER['argv'] = array();
        $_SERVER['argc'] = 0;
    }

    /**
     * Add one or more element(s) at the end of the $_SERVER['argv'] array
     *
     * @param array $args Value to add to the argv array.
     */
    public static function pushToArgv($args)
    {
        if (is_string($args)) {
            $args = explode(' ', $args);
        }

        foreach ($args as $arg) {
            array_push($_SERVER['argv'], $arg);
        }

        $_SERVER['argc'] += count($args);
    }

    public function testHasWildArgsBasic()
    {
        self::clearArgv();
        self::pushToArgv(array('asd.php'));

        $aws = new AwsUpload();
        $hasWildArgs = $aws->hasWildArgs();
        $this->assertEquals(false, $hasWildArgs);
    }

    public function testHasWildArgsBasicWithNoise()
    {
    
        self::clearArgv();
        self::pushToArgv(array('asd.php', '-v', '--verbose', '--simulate'));

        $aws = new AwsUpload();
        $hasWildArgs = $aws->hasWildArgs();
        $this->assertEquals(false, $hasWildArgs);
    }

    public function testHasWildArgsBasicWithNoisePositive()
    {
        self::clearArgv();
        self::pushToArgv(array('asd.php', 'proj', 'env', '-v', '--verbose', '--simulate'));

        $aws = new AwsUpload();
        $wildArgs = $aws->getWildArgs();
        $hasWildArgs = $aws->hasWildArgs();
        
        $this->assertEquals(true, $hasWildArgs);
        $this->assertEquals(array('proj', 'env'), array_values($wildArgs));
    }

    /**
     * This test is strictly related to cli\Arguments
     *
     * test case:
     *     aws-upload -es
     *
     * [cli\Arguments] no value given for -e
     * /vendor/wp-cli/php-cli-tools/lib/cli/Arguments.php:433
     * /vendor/wp-cli/php-cli-tools/lib/cli/Arguments.php:465
     * /vendor/wp-cli/php-cli-tools/lib/cli/Arguments.php:402
     * /src/AwsUpload/AwsUpload.php:86
     */
    public function testSuppressErrorsPhpCliTools()
    {
        self::clearArgv();
        self::pushToArgv(array('asd.php', '-es'));

        $aws = new AwsUpload();
        $wildArgs = $aws->getWildArgs();
        $hasWildArgs = $aws->hasWildArgs();

        $this->assertEquals(true, $hasWildArgs);
        $this->assertEquals(array('-es'), array_values($wildArgs));
    }
}
