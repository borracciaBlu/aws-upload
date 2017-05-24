<?php

use AwsUpload\Io\Args;

require_once __DIR__ . '/../BaseTestCase.php';


class ArgsTest extends BaseTestCase
{

    public function test_flags()
    {
    	$args = new Args(array('e', '-q', 'v-', 'P'));
        $args->addFlags(array(
        	'projs' => array('p', 'projs'),
        	'envs' => array('e', 'envs'),
        	'quiet' => array('quiet', 'q'),
        	'version' => array('version', 'v'),
        ));
        $args->parse();
        
        $this->assertEquals($args->envs, true);
        $this->assertEquals($args->quiet, true);
        $this->assertEquals($args->version, true);
        $this->assertEquals($args->proj, false);
    }

    public function test_cmds()
    {
		$args = new Args(array('e', 'P'));
        $args->addCmds(array(
        	'projs' => array('p', 'projs'),
        	'envs' => array('e', 'envs'),
        	'version' => array('version', 'v'),
        ));
        $args->parse();
        
        $this->assertEquals($args->envs, true);
        $this->assertEquals($args->getParams('envs'), array('P'));
        $this->assertEquals($args->getFirst('envs'), 'P');
    }

    public function test_cmd_with_flags()
    {
		$args = new Args(array('e', '-q', 'P'));
        $args->addCmds(array(
        	'projs' => array('p', 'projs'),
        	'envs' => array('e', 'envs'),
        	'version' => array('version', 'v'),
        ));
        $args->parse();

    }
}
