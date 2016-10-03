<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Deletes a user in the CMS.
 * 
 */
require_once("init.php");
if (isset($_REQUEST['delid'])) $id = $_REQUEST['delid']; else die('xx'); 
// check user rights here
if (!$_SESSION['deluser']) {header("Location: ../users.php?id=$id&flg=noperms");exit;}	// permission denied
if (($id==1) || ($id==2)) {header("Location: ../users.php");exit;}	// cannot delete home page
if (mysql_query("delete from `users` where `id`=".$id)) 
	header("Location: ../users.php?&flg=deleted");	// updated		
else header("Location: ../users.php?id=".$id."&flg=delfailed");	// failed		
exit;
?>