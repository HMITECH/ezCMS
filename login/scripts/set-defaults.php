<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Save the default setting for the site.
 * 
 */
require_once("init.php");
if (!isset($_POST["Submit"])) die('xx'); 
if (!$_SESSION['editsettings']) {header("Location: ../setting.php?flg=noperms");exit;}	// permission denied
if($_POST['ckapptitle' ]!='') $apptitle  =1; else $apptitle = 0;
if($_POST['ckappkey'   ]!='') $appkey    =1; else $appkey   = 0;
if($_POST['ckappdesc'  ]!='') $appdesc   =1; else $appdesc  = 0;

// Create a Revision 
mysql_query("INSERT INTO `site` ( 
			`title`,
			`keywords`,
			`description`,
			`headercontent`,
			`footercontent`,
			`sidecontent`,
			`sidercontent`,
			`appendtitle`,
			`appendkey`,
			`appenddesc`,
			`createdby` ) SELECT 
			`title`,
			`keywords`,
			`description`,
			`headercontent`,
			`footercontent`,
			`sidecontent`,
			`sidercontent`,
			`appendtitle`,
			`appendkey`,
			`appenddesc`,
			'".$_SESSION['USERID']."' as `createdby` 
			FROM `site` WHERE `id` = 1");

$qry = "UPDATE `site` SET ";
$qry .= "`title`='"        .mysql_real_escape_string($_POST["txtTitle"    ])."' , ";
$qry .= "`keywords`='"     .mysql_real_escape_string($_POST["txtKeywords" ])."' , ";
$qry .= "`description`='"  .mysql_real_escape_string($_POST["txtDesc"     ])."' , ";
$qry .= "`headercontent`='".mysql_real_escape_string($_POST["txtHeader"   ])."' , ";
$qry .= "`sidecontent`='"  .mysql_real_escape_string($_POST["txtSide"     ])."' , ";
$qry .= "`sidercontent`='" .mysql_real_escape_string($_POST["txtrSide"    ])."' , ";
$qry .= "`appendtitle`  = '" . $apptitle  . "', ";
$qry .= "`appendkey`  = '"   . $appkey   . "', ";
$qry .= "`appenddesc`  = '"  . $appdesc  . "', ";				
$qry .= "`footercontent`='".mysql_real_escape_string($_POST["txtFooter"   ])."'   ";		
$qry .= "where `id` = 1";
if (mysql_query($qry)) header("Location: ../setting.php?flg=green");
else header("Location: ../setting.php?flg=red");
exit;?>