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

function showSetCookie() {
global $phpTrafficA;
global $DEMO;
global $strings;
global $lang;
if ($DEMO) {
	$str = "(".$strings['disabled'].")";
} else {
	$str = "";
}
if ($phpTrafficA == "") { $phpTrafficA = $_COOKIE["phpTrafficA"];}
echo "<div id=\"text\"><P>".$strings['cookiestring']."\n";
if ($phpTrafficA == "Admin") {
	echo "<P>".$strings['cookieset'];
	echo "<div align=\"center\">
		<form><form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
			<input type=\"submit\" name=\"submit\" value=\"".$strings['removecookie']." $str\">
			<input type=\"hidden\" name=\"mode\" value=\"removecookie\">
			<input type=\"hidden\" name=\"lang\" value=\"$lang\">
		</form></div>";
} else {
	echo "<P>".$strings['cookienotset'];
	echo "<div align=\"center\"><form><form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"".$strings['addcookie']." $str\"><input type=\"hidden\" name=\"mode\" value=\"addcookie\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"></form></div></div>";
}
}

?>