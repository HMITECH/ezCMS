<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Copies the default blocks like header, footer, sidebarA, sidebarB to the page.
 * 
 */
require_once("init.php");

if (!$_SESSION['editpage']) {header("Location: ../pages.php?flg=noperms");exit;}	// permission denied

if (isset($_REQUEST['headcopyid'])) {

	$id = $_REQUEST['headcopyid'];
	$qry = "UPDATE `pages` SET `pages`.`headercontent` = ( ";
	$qry .= "SELECT `headercontent` ";
	$qry .= "FROM `site` WHERE `site`.`id` =1 ) ";
	$qry .= "WHERE `pages`.`id` =" . $id . " LIMIT 1;  ";
	if (mysql_query($qry)) header("Location: ../pages.php?id=".$id."&flg=headcopied#header");	
	else header("Location: ../pages.php?id=".$id."&flg=headcopyfailed#header");
	exit;
	
} elseif (isset($_REQUEST['sidecopyid'])) {

	$id = $_REQUEST['sidecopyid'];
	$qry = "UPDATE `pages` SET `pages`.`sidecontent` = ( ";
	$qry .= "SELECT `sidecontent` ";
	$qry .= "FROM `site` WHERE `site`.`id` =1 ) ";
	$qry .= "WHERE `pages`.`id` =" . $id . " LIMIT 1;  ";
	if (mysql_query($qry)) header("Location: ../pages.php?id=".$id."&flg=sidecopied#sidebar");	// added
	else header("Location: ../pages.php?id=".$id."&flg=sidecopyfailed#sidebar");	// failed
	exit;

} elseif (isset($_REQUEST['sidercopyid'])) {
	$id = $_REQUEST['sidercopyid'];
	$qry = "UPDATE `pages` SET `pages`.`sidercontent` = ( ";
	$qry .= "SELECT `sidercontent` ";
	$qry .= "FROM `site` WHERE `site`.`id` =1 ) ";
	$qry .= "WHERE `pages`.`id` =" . $id . " LIMIT 1;  ";
	if (mysql_query($qry)) header("Location: ../pages.php?id=".$id."&flg=sidercopied#siderbar");	// added
	else header("Location: ../pages.php?id=".$id."&flg=sidercopyfailed#siderbar");	// failed
	exit;	
		
} elseif (isset($_REQUEST['footcopyid'])) {

	$id = $_REQUEST['footcopyid'];
	$qry = "UPDATE `pages` SET `pages`.`footercontent` = ( ";
	$qry .= "SELECT `footercontent` ";
	$qry .= "FROM `site` WHERE `site`.`id` =1 ) ";
	$qry .= "WHERE `pages`.`id` =" . $id . " LIMIT 1;  ";
	if (mysql_query($qry)) header("Location: ../pages.php?id=".$id."&flg=footcopied#footer");	// added
	else header("Location: ../pages.php?id=".$id."&flg=footcopyfailed#footer");	// failed
	exit;
	
} else die('xx'); ?>