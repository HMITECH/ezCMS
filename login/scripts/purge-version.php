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
if (isset($_GET['controller'])) {

	// permission denied
	if (!$_SESSION['editcontroller']) {
		header("Location: ../controllers.php?flg=noperms");
		exit;
	}
	
	$id = intval($_GET['controller']);
	
	// delete the revision
	if (mysql_query("DELETE FROM `git_files` WHERE `fullpath` = 'index.php' AND `id` = $id"))
		header("Location: ../controllers.php?flg=greenrev");
	else header("Location: ../controllers.php?flg=redrev");
	exit;
	
} die('xx'); ?>