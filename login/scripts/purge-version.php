<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Deletes the Revsions (PURGE).
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
	
	
} else if (isset($_GET['layout'])) {

	// permission denied
	if (!$_SESSION['editlayout']) {header("Location: ../layouts.php?flg=noperms");exit;}
	
	$id = intval($_GET['layout']);

} else if (isset($_GET['script'])) {

	// permission denied
	if (!$_SESSION['editjs']) {header("Location: ../scripts.php?flg=noperms");exit;}	
	
	$id = intval($_GET['script']);
	
} else if (isset($_GET['style'])) {	

	// permission denied
	if (!$_SESSION['editcss']) {header("Location: ../styles.php?flg=noperms");exit;}
	
	$id = intval($_GET['style']);
	
} die('xx'); ?>