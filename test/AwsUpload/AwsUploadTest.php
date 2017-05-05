<?php

use AwsUpload\AwsUpload;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . '/BaseTestCase.php';


class AwsUploadTest extends BaseTestCase
{
    public function test_hasWildArgs_noArgs_false()
    {
        self::clearArgv();
        self::pushToArgv(array('asd.php'));

        $aws = new AwsUpload();
        $hasWildArgs = $aws->hasWildArgs();
        $this->assertEquals(false, $hasWildArgs);
    }

    public function test_hasWildArgs_noise_false()
    {
    
        self::clearArgv();
        self::pushToArgv(array('asd.php', '-v', '--verbose', '--simulate'));

        $aws = new AwsUpload();
        $hasWildArgs = $aws->hasWildArgs();
        $this->assertEquals(false, $hasWildArgs);
    }

    public function test_hasWildArgs_yesWithNoise_true()
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
