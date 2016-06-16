#!/usr/bin/php
<?php

// Commands
if (strpos($argv[1], "-h") !== false || strpos($argv[1], "--help") !== false) { 
	echo "-h	print help\n";
	echo "-p	print projects\n";
	echo "-e	print environments\n";
	exit(0);
}

if (strpos($argv[1], "-p") !== false || strpos($argv[1], "--projs") !== false) { 
	$files = getSettingsFiles();
	$projs = "";
	foreach ($files as $key) {
		list($proj, $env) = explode("-", $key);

		$projs .= empty($projs) ? $proj : " " . $proj; 
	}

	echo $projs . "\n";
	exit(0);
}

if (strpos($argv[1], "-e") !== false || strpos($argv[1], "--envs") !== false) { 
	$proj_sel = $argv[2];

	$files = getSettingsFiles();
	$projs = array();
	foreach ($files as $key) {
		$key = substr($key, 0, -5);
		list($proj, $env) = explode("-", $key);

		if (!isset($projs[$proj])) {
			$projs[$proj] = array();
		}

		$projs[$proj][] = $env; 
	}

	$envs = "";
	if (isset($projs[$proj_sel])) {
		foreach ($projs[$proj_sel] as $env) {
			$envs .= empty($envs) ? $env : " " . $env; 
		}
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

$settings = getSettingObject($key);
$cmd = generateCmd($settings);

// Debug
echo "=================================" . "\n";
echo "Env: " . $env . "\n";
echo "Code: " . $code . "\n";
echo "Bash cmd: " . $sync_cmd . "\n";
echo $cmd . "\n";
echo "=================================" . "\n";

system($cmd);

function getSettingsFiles() {
	$path = $_SERVER['HOME'] . '/.aws-upload/';
	$files = scandir($path);

	unset($files[0]); // .
	unset($files[1]); // ..

	return $files;
}

function getSettingObject($key) {
	$string = file_get_contents($_SERVER['HOME'] . '/.aws-upload/' . $key . ".json");
	$settings = (object) json_decode($string, true);

	return $settings;
}

function generateCmd($settings) {

	$cmd = "rsync -ravze \"ssh -i " . $settings->pem . "\" ";
	if (isset($settings->exclude) && is_array($settings->exclude)) {
		foreach ($settings->exclude as $elem) {
			$cmd .= " --exclude $elem ";		
		}
	}

	$cmd .= $settings->local . " " . $settings->remote . "";

	return $cmd;
}
