<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Delete a js file from the site.
 * There are multiple js files - default cannot be deleted.
 */
require_once("init.php");
if (!$_SESSION['editjs']) {header("Location: ../scripts.php?flg=noperms");exit;}	// permission denied
if (isset($_REQUEST['delfile'])) $filename = $_REQUEST['delfile']; else die('xx'); 
if ($filename=='../main.js') die('xxx'); 
if (!preg_match('/^\.\.\/site-assets\/js\/[a-z0-9_-]+\.js$/i',$filename)) die('xxxx');
if (unlink("../$filename"))
	header("Location: ../scripts.php?flg=deleted");
else header("Location: ../scripts.php?flg=delfailed&show=$filename");	
exit;?>