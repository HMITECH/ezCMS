<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Deletes a page in the site.
 * 
 */
require_once("init.php");
require_once("../include/pages.functions.php");

if (isset($_REQUEST['delid'])) $id = $_REQUEST['delid']; else die('xx'); 
if (!$_SESSION['delpage']) {header("Location: ../pages.php?id=$id&flg=noperms");exit;}	// permission denied
if (($id==1) || ($id==2)) {header("Location: ../pages.php");exit;}	// cannot delete home page

$qry = "SELECT `parentid` FROM `pages` WHERE `id` = " . $id;
$rs = mysql_query($qry);
$arr = mysql_fetch_array($rs);
$parentid  = $arr["parentid"];
$qry = "Update `pages` set `parentid` = " . $parentid . " where `parentid` = " . $id;
mysql_query($qry);

$sql  = "DELETE FROM `pages` WHERE `id`=".$id;
if (mysql_query($sql)) {
	mysql_query('OPTIMIZE TABLE `pages`;');
	resolveplace();
	reIndexPages();		
	header("Location: ../pages.php?&flg=deleted");	} // updated
else
	header("Location: ../pages.php?id=".$id."&flg=delfailed");	// failed
exit;
?>