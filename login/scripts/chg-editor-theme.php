<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * Script: Changes code mirror theme
 * 
 */
require_once("init.php");
if (isset($_GET['theme'])) $theme = $_GET['theme']; else die('xx');
if ( ($theme!='default') && (!file_exists("../codemirror/theme/$theme.css")) )
	die('<h1>Missing theme, please install it first.</h1>');
$_SESSION['CMTHEME'] = $theme;
header('Location: '.$_SERVER["HTTP_REFERER"]);
exit; ?>