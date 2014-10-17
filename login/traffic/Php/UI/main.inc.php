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

function echomain($c) {
global $isLoggedIn,$sites;
global $server, $user, $password, $base;
global $strings;
global $lang;
global $stylesheet, $stylesheetList;

// mysql_query("SET NAMES 'utf8'", $c);
$today = dateandtime(time(),1);
echo "<div id=\"stats\">\n<h2>$today</h2>";
$thismonth = monthYear(time());
echo "<center>
<table class='stat'>
<tr class=\"title\"><td colspan=\"7\">".$strings['Domainlist']."</td></tr>
<tr class=\"caption\"><td>".$strings['Domain']."</td>
<td>".$strings['Timeonserver']."</td>
<td>".$strings['Today']."</td>
<td>".$strings['Yesterday']."</td>
<td>$thismonth</td>
<td>".$strings['Total']."</td>
<td>&nbsp;</td></tr>\n";
$n = 0;
$thistime = time();
while ($bar=each($sites)) {
	$id = $bar[0];
	$table = $bar[1]['table'];
	$site = $bar[1]['site'];
	$public = $bar[1]['public'];
	$timediff = $bar[1]['timediff'];
	if ($public || $isLoggedIn) {
		$n += 1;
		$timeserver = $thistime+$timediff*3600;
		$todayserver = dateandtime($timeserver,1);
		//echo "Timediff for $site is $timediff, so it is $timeserver, or $todayserver. ".date("H:i", 1222843217)."<br>";
		if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
		echo "<tr class=\"data $even\"><td><a href=\"http://".$site."\" target=\"_blank\">$site</a></td>";
		echo "<td nowrap='nowrap'>$todayserver</td>";
		echo "<td nowrap='nowrap'>".hits(nToday($c,$table,$site,$timeserver))."<br>".visitors(vToday($c,$table,$site,$timeserver))."</td>";
		echo "<td nowrap='nowrap'>".hits(nYesterday($c,$table,$site,$timeserver))."<br>".visitors(vYesterday($c,$table,$site,$timeserver))."</td>";
		echo "<td nowrap='nowrap'>".hits(nThisMonth($c,$table,$site,$timeserver))."<br>".visitors(vThisMonth($c,$table,$site,$timeserver))."</td>";
		echo "<td nowrap='nowrap'>".hits(nTotal($c,$table,$site))." <br>".visitors(vTotal($c,$table,$site))."</td>";
		echo "<td><a href=\"index.php?mode=stats&amp;sid=$id&amp;lang=$lang\">".$strings['Getstats']."</a></td></tr>\n";
	}
}

if ($n == 0) {
	echo "<tr><td colspan=\"7\" align=\"center\">".$strings['Nothingyet']."</td></tr>";
}
echo "</table>";
// Language selection
$dir = "./Lang";
$list = lsexclude($dir,'*.php','(help|index|about).*');
//$names = array("fr" => "Français", "en" => "English", "ru"=> "Русский", "es" => "Español", "de" => "Deutsch", "nl" => "Nederlands");
echo "<form action=\"index.php\" method=\"post\">
<table class=\"form fixedwidth\"><tr><td>".$strings['LanguageInterface']."</td><td>&nbsp;&nbsp;<select name=\"lang\">
";
foreach ($list as $file) {
	$pos = strpos ($file,".");
	$thislang = substr($file, 0,$pos);
	//$langlong = $names["$thislang"];
	$langlong = languageName($thislang);
	if ($lang == $thislang) {
		echo "<option value=\"$thislang\" selected>$langlong</option>";
	} else {
		echo "<option value=\"$thislang\">$langlong</option>";
	}
}
echo "</select>&nbsp;&nbsp;</td><td><input type=\"hidden\" name=\"mode\" value=\"setlang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr></table></form>";
// Stylesheet selection
echo "<form action=\"index.php\" method=\"post\">
<table class=\"form fixedwidth\"><tr><td>".$strings['Stylesheet']."</td><td>&nbsp;&nbsp;<select name=\"style\">
";
foreach ($stylesheetList as $file) {
	if ($stylesheet == $file.".css") {
		echo "<option value=\"$file\" selected>$file</option>";
	} else {
		echo "<option value=\"$file\">$file</option>";
	}
}
echo "</select>&nbsp;&nbsp;</td><td><input type=\"submit\" value=\"".$strings['Submit']."\"><input type=\"hidden\" name=\"mode\" value=\"setstylesheet\"></td></tr></table></form>";
echo "</center></div>\n";
}
?>