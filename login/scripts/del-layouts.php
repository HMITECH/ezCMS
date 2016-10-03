<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Delete a layout from the site.
 * There are multiple layouts - default cannot be deleted.
 */
require_once("init.php");
if (!$_SESSION['editlayout']) {header("Location: ../layouts.php?flg=noperms");exit;}	// permission denied
if (isset($_REQUEST['delfile'])) $filename = $_REQUEST['delfile']; else die('xx'); 
if ($filename=='layout.php') die('xxx'); 
if (unlink("../../$filename"))
	header("Location: ../layouts.php?flg=deleted");
else header("Location: ../layouts.php?flg=delfailed&show=$filename");	
exit;?>