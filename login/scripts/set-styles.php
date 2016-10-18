<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Save the styles (style.css) for the site.
 * There are multiple style sheets
 */
require_once("init.php");
if (!$_SESSION['editcss']) {header("Location: ../styles.php?flg=noperms");exit;}	// permission denied
if (!isset($_POST["Submit"])) die('xx'); 
if (isset($_POST["txtContents"])) $contents = ($_POST["txtContents"]); else die('xxx');
if (isset($_POST["txtName"])) $filename = $_POST["txtName"]; else die('xxxx');
//die($filename);
if ( (!preg_match('/^\.\.\/site-assets\/css\/[a-z0-9_-]+\.css$/i',$filename)) &&
	($filename!='../style.css') ) die('xxxx.');

$retfile = '&show='.str_replace('../site-assets/css/','',$filename);
if ($filename=='../style.css') $retfile = '';

// check if the file exists
if (!file_exists("../$filename")) {
	if (file_put_contents("../$filename",$contents)) 
		header("Location: ../styles.php?flg=green&show=$retfile");
	else header("Location: ../styles.php?flg=red&show=$retfile");
	exit;
}

if (is_writable("../$filename")) {
	// create the backup here
	$original = mysql_real_escape_string(@fread(fopen("../$filename", "r"), filesize("../$filename")));
	mysql_query("INSERT INTO `git_files` ( `content`, `fullpath`, `createdby`) VALUES 
				('$original', '$filename', '".$_SESSION['USERID']."')");
	if (fwrite(fopen("../$filename", "w+"),$contents)) 
		header("Location: ../styles.php?flg=green$retfile");
	else header("Location: ../styles.php?flg=red$retfile");
} else header("Location: ../styles.php?flg=pink$retfile");
exit;?>