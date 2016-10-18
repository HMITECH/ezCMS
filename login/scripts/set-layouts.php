<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Save the layouts (layout.php) for the site.
 * There are multiple layouts
 */
require_once("init.php");
if (!isset($_POST["Submit"])) die('xx'); 
if (!$_SESSION['editlayout']) {header("Location: ../layouts.php?flg=noperms");exit;}	// permission denied
if (isset($_POST["txtContents"])) $contents = ($_POST["txtContents"]); else die('xxx');
if (isset($_POST["txtName"])) $filename = $_POST["txtName"]; else die('xxxx');
if ( (!preg_match('/^layout\.[a-z0-9_-]+\.php$/i',$filename)) && 
	($filename!='layout.php') ) die('xxxx.');
	
// check if the file exists
if (!file_exists("../../$filename")) {
	if (file_put_contents("../../$filename",$contents)) 
		header("Location: ../layouts.php?flg=green&show=$filename");
	else header("Location: ../layouts.php?flg=red&show=$filename");
	exit;
}

if (is_writable("../../$filename")) {
	// create the backup here
	$original = mysql_real_escape_string(@fread(fopen("../../$filename", "r"), filesize("../../$filename")));
	mysql_query("INSERT INTO `git_files` ( `content`, `fullpath`, `createdby`) VALUES 
				('$original', '$filename', '".$_SESSION['USERID']."')");
	if (fwrite(fopen("../../$filename", "w+"),$contents)) 
		header("Location: ../layouts.php?flg=green&show=$filename");
	else header("Location: ../layouts.php?flg=red&show=$filename");
} else header("Location: ../layouts.php?flg=pink&show=$filename");
exit;?>