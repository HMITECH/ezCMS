<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Copies a page in the site.
 * 
 */
require_once("init.php");
require_once("../include/pages.functions.php");

if (isset($_REQUEST['copyid'])) $id = $_REQUEST['copyid']; else die('xx'); 
if (!$_SESSION['editpage']) {header("Location: ../pages.php?id=$id&flg=noperms");exit;}	// permission denied

$qry = "INSERT INTO `pages` ( ";
$qry .= "`pagename` , `title` , `url` , `keywords` , `description`, `maincontent` , ";
$qry .= "`useheader` , `headercontent` , `head`, `layout`, ";
$qry .= "`usefooter` , `footercontent` ,`useside` , `sidecontent` , `usesider` , `sidercontent` ,";
$qry .= "`published` , `parentid` ) ";
$qry .= "SELECT ";
$qry .= "`pagename` , `title` , `url` ,`keywords` , `description`, `maincontent` , ";
$qry .= "`useheader` , `headercontent` , `head`, `layout`, ";
$qry .= "`usefooter` , `footercontent` ,`useside` , `sidecontent` ,  `usesider` , `sidercontent` ,";
$qry .= "`published` , if(`parentid`=0,1,`parentid`)";
$qry .= " FROM `pages` WHERE id=" . $id;
//die($qry);
if (mysql_query($qry)) {
	$id = mysql_insert_id();
	// update name and title
	mysql_query('UPDATE `pages` SET `pagename` = concat( `pagename` , "-copy", `id` ) ,'.
					'`title` = concat( `title` , "-copy", `id` ) WHERE id ='.$id.' LIMIT 1 ');	
	resolveplace();
	reIndexPages();
	mysql_query('OPTIMIZE TABLE `pages`;');
	header("Location: ../pages.php?id=".$id."&flg=copied");	// added
} else
	header("Location: ../pages.php?id=".$id."&flg=copyfailed");	// failed
exit;

?>