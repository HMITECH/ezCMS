<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Delete a style from the site.
 * There are multiple styles - default cannot be deleted.
 */
require_once("init.php");
if (!$_SESSION['editcss']) {header("Location: ../styles.php?flg=noperms");exit;}	// permission denied
if (isset($_REQUEST['delfile'])) $filename = $_REQUEST['delfile']; else die('xx'); 
if ($filename=='../style.css') die('xxx'); 
if (!preg_match('/^\.\.\/site-assets\/css\/[a-z0-9_-]+\.css$/i',$filename)) die('xxxx');
if (unlink("../$filename"))
	header("Location: ../styles.php?flg=deleted");
else header("Location: ../styles.php?flg=delfailed&show=$filename");	
exit;?>