<?php

namespace AwsUpload\Tests;

use AwsUpload\System\Rsync;
use AwsUpload\Tests\BaseTestCase;
use AwsUpload\Setting\SettingFile;
use Symfony\Component\Filesystem\Filesystem;

class RsyncTest extends BaseTestCase
{
    public function test_buildCmd_object_true()
    {
        $cmd = 'rsync -ravze "ssh -i /Users/jhon.doe/Documents/certificates/site.pem"  --exclude \'.env\'  ' .
            '--exclude \'.git/\'  --exclude .DS_Store /Users/jhon.doe/Documents/w/html/ ' .
            '\'ec2-user@ec2-xx-xx-xx-xx.ap-southeast-2.compute.amazonaws.com:/var/www/html/site\'';

        $json = '{
            "pem": "/Users/jhon.doe/Documents/certificates/site.pem",
            "local":"/Users/jhon.doe/Documents/w/html/",
            "remote":"ec2-user@ec2-xx-xx-xx-xx.ap-southeast-2.compute.amazonaws.com:/var/www/html/site",
            "exclude":[".env", ".git/"]
        }';

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->aws_home . '/project-1.dev.json', $json);
        $settings = SettingFile::getObject('project-1.dev');

        $rsync = new Rsync($settings);
        $this->assertEquals($rsync->getCmd(), $cmd);
    }
}
