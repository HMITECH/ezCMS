<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Script: Changes the dedault editor for the site builder.
 * 
 */
require_once("init.php");
if (!isset($_GET['etype'])) die('xx');
if ($_GET['etype']=='0') $_SESSION['EDITORTYPE']=0;
elseif ($_GET['etype']=='1') $_SESSION['EDITORTYPE']=1;
elseif ($_GET['etype']=='2') $_SESSION['EDITORTYPE']=2;
elseif ($_GET['etype']=='3') $_SESSION['EDITORTYPE']=3;
//die('ma '.$_SESSION['EDITORTYPE']);
header('Location: '.$_SERVER["HTTP_REFERER"]);
exit;?>