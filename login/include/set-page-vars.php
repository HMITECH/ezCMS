<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Include: Sets the posted variable back incase of error. (used in pages.php)
 * 
 */
$keywords    	= htmlspecialchars($_REQUEST['txtKeywords']);
$description 	= htmlspecialchars($_REQUEST['txtDesc']);
$maincontent 	= htmlspecialchars($_REQUEST['txtMain']);
$sidebar 		= htmlspecialchars($_REQUEST['txtSide']);
$siderbar 		= htmlspecialchars($_REQUEST['txtrSide']);
$header 		= htmlspecialchars($_REQUEST['txtHeader']);
$footer		 	= htmlspecialchars($_REQUEST['txtFooter']);			
if ($isredirected == 1) $isredirected = "checked";
if ($published    == 1) $published    = "checked";
if ($showinmenu   == 1) $showinmenu   = "checked";
if ($showinsmenu  == 1) $showinsmenu  = "checked";	
if ($useside      == 1) $useside      = "checked";
if ($usesider     == 1) $usesider     = "checked";
if ($useheader    == 1) $useheader    = "checked";
if ($usefooter    == 1) $usefooter    = "checked";
?>