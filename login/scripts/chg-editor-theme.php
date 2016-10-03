<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Changes code mirror theme
 * 
 */
require_once("init.php");
if (isset($_GET['theme'])) $theme = $_GET['theme']; else die('xx');
if ( ($theme!='default') && (!file_exists("../codemirror/theme/$theme.css")) )
	die('<h1>Missing theme, please install it first.</h1>');
$_SESSION['CMTHEME'] = $theme;
header('Location: '.$_SERVER["HTTP_REFERER"]);
exit; ?>