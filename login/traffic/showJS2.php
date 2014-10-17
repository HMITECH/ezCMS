<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2008 ZoneO-soft, Butchu (email: "butchu" with the domain "zoneo.net")

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 More Info About The Licence At http://www.gnu.org/copyleft/gpl.html
****************************************************************************/

include ("./Php/config.php");
include ("./Php/config_sql.php");
include ("./Php/Functions/login.inc.php");
$c = mysql_connect("$server","$user","$password") or die("<br>Can not connect to database in index.php[".__LINE__."]: ".mysql_error());
$db = mysql_select_db("$base",$c) or die("<br>Can not select base in index.php[".__LINE__."]: ".mysql_error());
$isLoggedIn = isloggedin ($c);
@mysql_close ($c);
if (!$isLoggedIn) header('Location: index.php');
if (!$isLoggedIn) die("Nothing to see around here");

$host = preg_replace("/^(.*\.)?([^.]*\..*)$/", "$2", $_SERVER['HTTP_HOST']);

$js = "
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2008 ZoneO-soft, Butchu (email: \"butchu\" with the domain \"zoneo.net\")

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 More Info About The Licence At http://www.gnu.org/copyleft/gpl.html
****************************************************************************/

function writePhpTACookie() {
	date=new Date;
	date.setMonth(date.getMonth()+1);
	var name = \"phpTA_resolution\";
	var value = screen.width +\"x\"+ screen.height;
	var domain = \"$host\";
	var path= \"/\";
	document.cookie=name+\"=\"+escape(value)+\"; expires=\"+date.toGMTString()+\"; path=\"+path+\"; domain=\"+domain;
}
window.onload=writePhpTACookie;
";

header('Content-type: text/plain');
echo $js;
?>