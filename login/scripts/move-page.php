<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Move a page in the site. (change order of listing)
 * 
 */
require_once("init.php");

if (isset($_REQUEST['upid']) || isset($_REQUEST['downid'])) {

	if (isset($_REQUEST['upid'])) $id = $_REQUEST['upid'  ];
	else $id = $_REQUEST['downid'];

	if (!$_SESSION['editpage']) {header("Location: ../pages.php?id=$id&flg=noperms");exit;}	// permission denied


	$qry = "SELECT `place`, `parentid` FROM `pages` WHERE `id` = " . $id;
	$rs = mysql_query($qry);
	$arr = mysql_fetch_array($rs);
	$currplace = $arr["place"   ];
	$parentid  = $arr["parentid"];

	if (isset($_REQUEST['upid']))
		$qry = "SELECT id, place, pagename FROM `pages` WHERE parentid=" . $parentid .
				" and place < " . $currplace . " order by place desc limit 1";
	else
		$qry = "SELECT id, place, pagename FROM `pages` WHERE parentid=" . $parentid .
				" and place > " . $currplace . " order by place limit 1";

	$rs = mysql_query($qry);
	$arr = mysql_fetch_array($rs);
	$swpplace = $arr["place"   ];
	$swpid    = $arr["id"];

	$qry = "Update `pages` set `place` = " . $swpplace . " where `id` = " . $id;
	mysql_query($qry);
	$qry = "Update `pages` set `place` = " . $currplace . " where `id` = " . $swpid;
	mysql_query($qry);
	header("Location: ../pages.php?id=".$id);	// updated
	exit;

} else die('xx');
?>