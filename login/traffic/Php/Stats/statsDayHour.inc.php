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

/*********************************************************************************/
/* Function: echoVisitHour
/* Role: Visits vs. time of the day
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base for table names
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 09/2007
/*********************************************************************************/
function echoVisitHour($c, $table) {
global $DEBUG;
global $strings;
global $tmpdirectory;

// Time at which the routine was called (used in giving names for temporary files)
$timecall = time();

$tableHour = $table."_hour";
$ylabel = $strings['plot-PCvisitors'];
$total=0;
$req = "SELECT * FROM ${tableHour} ORDER BY value ASC;";
$res = mysql_query($req,$c);
$nSteps = 0;
$total = 0;
$max = 0;
while ($row = mysql_fetch_array($res)) {
	$leg[$nSteps] = $row['value'];
	$count[$nSteps] = $row['count'];
	$total += $row['count'];
	if($count[$nSteps] > $max) $max = $count[$nSteps];
	$nSteps += 1;
}

// Table with list

echo "<table class='stat'>
<tr class='title'><td colspan=\"4\">".$strings['visitsTimeDay']."</td></tr>
<tr class=\"caption\"><td class=\"btop\">".$strings['TimeOfTheDay']."</td><td class=\"btop\">&nbsp;</td><td colspan=\"2\" class=\"btop\">".$strings['Visits']."</td></tr>\n";
$n = 0;
$data = array();
for ($j=0;$j<$nSteps;$j++) {
	$n += 1;
	if ($total == 0) {
		$width = 140;
		$pc = 0.;
		$thiscount = 0;
	} else {
		if (!isset($count[$j])) $count[$j] = 0;
		$pc = 100.*$count[$j]/$total;
		$thiscount = $count[$j];
		$width = max(intval(250*$thiscount/$max),1);
	}
	$time1 = $leg[$j];
	if ($time1 < 10) $time1 = "0$time1";
	$legende = "$time1:00 - $time1:59";
	$pctxt = format_float($pc,1);
	$data[] = array($legende, $thiscount);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "<tr class=\"data $even\"><td>$legende</td><td width=\"150px\"><img src=\"Img/System/bar.gif\" alt=\"$thiscount ".$strings['hits']."\" width=\"$width\" height=\"10\" border=\"1\"></td><td align=\"center\">".visits($thiscount)."</td><td>$pctxt %</td></tr>\n";
}
echo "</table>\n";

// Test of css bar plot...
// echo "<table class='stat'>
// <tr class='title'><td>".$strings['visitsTimeDay']."</td></tr>
// <tr><td>";
// include("Php/Functions/vertBars.php");
// echo plotBarVert('root', $data, 800, 300);
// echo "</td></tr></table>";
}

function echoVisitDay($c, $table) {
global $DEBUG;
global $strings;
global $tmpdirectory;
$timecall = time();
$tableHour = $table."_day";
$ylabel = $strings['plot-PCvisitors'];
$req = "SELECT * FROM ${tableHour} ORDER BY value ASC;";
$res = mysql_query($req,$c);
$nSteps = 0;
$total = 0;
$max = 1;
while ($row = mysql_fetch_array($res)) {
	$leg[$nSteps] = $row['value'];
	$count[$nSteps] = $row['count'];
	$total += $row['count'];
	if($count[$nSteps] > $max) $max = $count[$nSteps];
	$nSteps += 1;
}

// Table with list

echo "<table class='stat'> <tr class='title'><td colspan=\"4\">".$strings['visitsDayWeek']."</td></tr><tr class=\"caption\"><td class=\"btop\">".$strings['DayOfTheWeek']."</td><td class=\"btop\">&nbsp;</td><td colspan=\"2\" class=\"btop\">".$strings['Visits']."</td></tr>\n";
$n = 0;
for ($j=0;$j<$nSteps;$j++) {
	$n += 1;
	if ($total == 0) {
		$width = 250;
		$pc = 0.;
		$thiscount = 0;
	} else {
		if (!isset($count[$j])) $count[$j] = 0;
		$pc = 100.*$count[$j]/$total;
		$thiscount = $count[$j];
		$width = max(intval(250*$thiscount/$max),1);
	}
	$legende = $strings['day-'.$leg[$j]];
	$pctxt = format_float($pc,1);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "<tr class=\"data $even\"><td>$legende</td><td width=\"150px\"><img src=\"Img/System/bar.gif\" alt=\"$thiscount ".$strings['hits']."\" width=\"$width\" height=\"10\" border=\"1\"></td><td align=\"center\">".visits($thiscount)."</td><td>$pctxt %</td></tr>\n";
}
echo "</table>\n";
}
?>
