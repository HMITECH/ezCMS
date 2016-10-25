<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Save the controller (index.php) for the site.
 * 
 */
require_once("init.php");
if (!isset($_POST["Submit"])) die('xx'); 
if (!$_SESSION['editcontroller']) {header("Location: ../controllers.php?flg=noperms");exit;}	// permission denied
if (isset($_POST["txtContents"])) $contents = $_POST["txtContents"]; else die('xxx');
$filename = '../../index.php';
if (is_writable($filename)) {
	// create the backup here
	$original = file_get_contents($filename);
	
	// don't save if nothing has changed
	if ($original == $contents) {
		header("Location: ../controllers.php?flg=nochange");
		exit;
	}
	
	$original = mysql_real_escape_string($original);
	
	mysql_query("INSERT INTO `git_files` ( `content`, `fullpath`, `createdby`) VALUES 
				('$original', 'index.php', '".$_SESSION['USERID']."')");
	if (fwrite(fopen($filename, "w+"),$contents)) 
		header("Location: ../controllers.php?flg=green");
	else header("Location: ../controllers.php?flg=red");
} else header("Location: ../controllers.php?flg=pink");
exit;?>