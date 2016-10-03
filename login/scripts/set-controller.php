<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * Script: Save the controller (index.php) for the site.
 * 
 */
require_once("init.php");
if (!isset($_POST["Submit"])) die('xx'); 
if (!$_SESSION['editcontroller']) {header("Location: ../controllers.php?flg=noperms");exit;}	// permission denied
if (isset($_POST["txtContents"])) $contents = $_POST["txtContents"]; else die('xxx');
$filename = '../../index.php';
if (is_writable($filename)) {
	if (fwrite(fopen($filename, "w+"),$contents)) 
		header("Location: ../controllers.php?flg=green");
	else header("Location: ../controllers.php?flg=red");
} else header("Location: ../controllers.php?flg=pink");
exit;?>