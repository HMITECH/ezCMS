<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Save the scripts (main.js) for the site.
 * There are multiple js files
 */
require_once("init.php");
if (!$_SESSION['editjs']) {header("Location: ../scripts.php?flg=noperms");exit;}	// permission denied
if (!isset($_POST["Submit"])) die('xx'); 
if (isset($_POST["txtContents"])) $contents = ($_POST["txtContents"]); else die('xxxa');
if (isset($_POST["txtName"])) $filename = $_POST["txtName"]; else die('xxxxaa');

if ( (!preg_match('/^\.\.\/site-assets\/js\/[a-z0-9_.-]+\.js$/i',$filename)) &&
	($filename!='../main.js') ) die('xxxx.');

$retfile = '&show='.str_replace('../site-assets/js/','',$filename);
if ($filename=='../main.js') $retfile = '';

// check if the file exists
if (!file_exists("../$filename")) {
	if (file_put_contents("../$filename",$contents)) 
		header("Location: ../scripts.php?flg=green&show=$retfile");
	else header("Location: ../scripts.php?flg=red&show=$retfile");
	exit;
}

if (is_writable("../$filename")) {
	// create the backup here
	$original = mysql_real_escape_string(@fread(fopen("../$filename", "r"), filesize("../$filename")));
	mysql_query("INSERT INTO `git_files` ( `content`, `fullpath`, `createdby`) VALUES 
				('$original', '$filename', '".$_SESSION['USERID']."')");
	if (fwrite(fopen("../$filename", "w+"),$contents)) 
		header("Location: ../scripts.php?flg=green$retfile");
	else header("Location: ../scripts.php?flg=red$retfile");
} else header("Location: ../scripts.php?flg=pink$retfile");
exit;?>