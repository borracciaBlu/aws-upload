#!/usr/bin/php
<?php

$list = array (
//////////////////////////////////////////////////////////////////////////
// 		
// 		
// 		
//////////////////////////////////////////////////////////////////////////

		"project-env" => array (
				"pem" => "/home/keys/your-key.pem ",
				"local" => "/var/www/project/* ",
				"remote" => "ubuntu@ec2-xxx-xxx-xxx-xxx.compute-1.amazonaws.com:" . 
							"/var/www/html",
				"exclude" => array (
						".env",
						".git/",
						"storage/",
						"tests/",
						"node_modules/",
					)
			),

		);

if (strpos($argv[1], "-h") !== false || strpos($argv[1], "--help") !== false) { 
	echo "-h	print help\n";
	echo "-p	print projects\n";
	echo "-e	print environments\n";
	exit(0);
}

if (strpos($argv[1], "-p") !== false || strpos($argv[1], "--projs") !== false) { 
	$projs = "";
	foreach ($list as $key => $settings) {
		list($proj, $env) = explode("-", $key);

		$projs .= empty($projs) ? $proj : " " . $proj; 
	}

	echo $projs . "\n";
	exit(0);
}

if (strpos($argv[1], "-e") !== false || strpos($argv[1], "--envs") !== false) { 
	$proj_sel = $argv[2];

	$projs = array();
	foreach ($list as $key => $settings) {
		list($proj, $env) = explode("-", $key);

		if (!isset($projs[$proj])) {
			$projs[$proj] = array();
		}

		$projs[$proj][] = $env; 
	}


	$envs = "";
	foreach ($projs[$proj_sel] as $env) {

		$envs .= empty($envs) ? $env : " " . $env; 
	}

	echo $envs . "\n";
	exit(0);
}

if (strpos($argv[1], "-") !== false) {
	list($argv[1], $argv[2]) = explode("-", $argv[1]);
}

if (empty($argv[2])) {
	echo "setup a env";
	exit(1);
}

if (empty($argv[1])) {
	echo "setup a what";
	exit(1);
}
$env = $argv[2];
$code = $argv[1];

$key = $code . "-" . $env;
$settings = (object) $list[$key];

// Rsync
$cmd = "rsync -rave \"ssh -i " . $settings->pem . "\" ";

foreach ($settings->exclude as $elem) {
	$cmd .= " --exclude $elem ";		
}

$cmd .= $settings->local . $settings->remote . "";

// Debug
echo "=================================" . "\n";
echo "Env: " . $env . "\n";
echo "Code: " . $code . "\n";
// echo "Bash cmd: " . $sync_cmd . "\n";
echo $cmd . "\n";
echo "=================================" . "\n";

system($cmd);

