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

function echoTimeInterval($c,$table, $site, $interval, $sid, $enddate, $sort, $order) {
global $DEBUG;
global $strings;
global $lang;
global $tmpdirectory;
global $display;
global $sites;

// Number of pages, keywords and referers to list in the tables
$ntop = $display['ntop'];
// Time at which the routine was called (used in giving names for temporary files)
$timecall = time();

$enddatecall = $enddate;

if ($enddate == "today") {
	$timeserver = time() + 3600*$sites[$sid]['timediff'];
	$enddate =date("Y-m-d", $timeserver);
}
$enddate = date("Y-m-d 23:59:59",strtotime($enddate));
$endday = date("d", strtotime($enddate));
$endmonth = date("m", strtotime($enddate));
$endyear = date("Y", strtotime($enddate));
$firstdaytable = firstDay($c,$table);
$enddaytable = lastDay($c,$table);
if ($firstdaytable == 0) {
	$firstyear = date("Y", 0);
} else {
	$firstyear = date("Y", strtotime($firstdaytable));
}
if ($enddaytable == 0) {
	$lastyear = date("Y", 0);
} else {
	$lastyear = date("Y", strtotime($enddaytable));
}

$onedayseconds = 86400;
$afterdate = date("Y-m-d", strtotime("$enddate") + $onedayseconds);
if ($interval == 'month') {
	$mintime = strtotime($enddate)-30*$onedayseconds;
	$mindate = date("Y-m-d 23:59:59",$mintime);
	$mintime2 = strtotime($enddate)-61*$onedayseconds;
	$mindate2 = date("Y-m-d 23:59:59",$mintime2);
	$nexttime = strtotime($enddate)+30*$onedayseconds;
	$plot = 1;
	$title = $strings['between']." ".dayMmYear(strtotime($mindate),0)." ".$strings['and']." ".dayMmYear(strtotime($enddate),0);
} else if ($interval == 'week') {
	$mintime = strtotime($enddate)-7*$onedayseconds;
	$mindate = date("Y-m-d 23:59:59",$mintime);
	$mintime2 = strtotime($enddate)-14*$onedayseconds;
	$mindate2 = date("Y-m-d 23:59:59",$mintime2);
	$nexttime = strtotime($enddate)+7*$onedayseconds;
	$plot = 1;
	$title = $strings['between']." ".dayMmYear(strtotime($mindate),0)." ".$strings['and']." ".dayMmYear(strtotime($enddate),0);
} else {
	$interval = 'day';
	$mintime = strtotime($enddate)-$onedayseconds;
	$mindate = date("Y-m-d 23:59:59",$mintime);
	$mintime2 = strtotime($enddate)-2*$onedayseconds;
	$mindate2 = date("Y-m-d 23:59:59",$mintime2);
	$nexttime = strtotime($enddate)+$onedayseconds;
	$plot = 0;
	$title = $strings['on']." ".dayMmYear(strtotime($enddate),0);
}

echo "<table class=\"form\" align=\"center\">
<tr><td>
<form name=\"previousperiod\" action=\"index.php?mode=stats&amp;sid=$sid&amp;lang=$lang\" method=\"post\">
<input type=\"hidden\" name=\"show\" value=\"period\">
<input type=\"hidden\" name=\"interval\" value=\"$interval\">
<input type=\"hidden\" name=\"day\" value=\"".date("d",$mintime)."\">
<input type=\"hidden\" name=\"month\" value=\"".date("m",$mintime)."\">
<input type=\"hidden\" name=\"year\" value=\"".date("Y",$mintime)."\">
<input type=\"image\" src=\"./Img/System/back.png\" height=\"24\" width=\"24\" border=\"0\" ALT=\"".$strings['Previous']."\">
</form>
</td><td>
<form name=\"dateselect\" action=\"index.php?mode=stats&amp;sid=$sid&amp;lang=$lang\" method=\"post\">
<input type=\"hidden\" name=\"show\" value=\"period\">
<table class=\"basic\" align=\"center\">
<tr>
<td valign=\"middle\">
<select name=\"interval\">";
if ($interval == 'month') {
	echo "<option value=\"month\" selected>".$strings['Monthlystatistics']."</option>";
	echo "<option value=\"week\">".$strings['Weeklystatistics']."</option>";
	echo "<option value=\"day\">".$strings['Dailystatistics']."</option>";
} elseif($interval == 'week') {
	echo "<option value=\"month\">".$strings['Monthlystatistics']."</option>";
	echo "<option value=\"week\" selected>".$strings['Weeklystatistics']."</option>";
	echo "<option value=\"day\">".$strings['Dailystatistics']."</option>";
} else {
	echo "<option value=\"month\">".$strings['Monthlystatistics']."</option>";
	echo "<option value=\"week\">".$strings['Weeklystatistics']."</option>";
	echo "<option value=\"day\" selected>".$strings['Dailystatistics']."</option>";
}
echo "</select></td>
<td valign=\"middle\"> ".$strings['endingon']." </td>\n<td valign=\"middle\">
<select name=\"day\">";
for ($i = 1; $i <= 31; $i++) {
	if ($i == $endday) {
		echo "<Option value=$i selected>$i</Option>";
	} else {
		echo "<Option value=$i>$i</Option>";
	}
}
echo "</select></td>
<td valign=\"middle\"><SELECT NAME=\"month\">";
$monthname = array ("", $strings['january'], $strings['february'], $strings['march'], $strings['april'], $strings['may'], $strings['june'], $strings['july'], $strings['august'], $strings['september'], $strings['october'], $strings['november'], $strings['december']);
for ($i = 1; $i <= 12; $i++) {
	if ($i == $endmonth) {
		echo "<Option value=$i selected>".$monthname[$i]."</Option>";
	} else {
		echo "<Option value=$i>".$monthname[$i]."</Option>";
	}
}
echo "</SELECT></td>
<td valign=\"middle\"><select name=\"year\">";
for ($i = $firstyear; $i <= $lastyear; $i++) {
	if ($i == $endyear) {
		echo "<Option value=$i selected>$i</Option>";
	} else {
		echo "<Option value=$i>$i</Option>";
	}
}
echo "</select></td>
<td valign=\"middle\"><input type=\"submit\" value=\" ".$strings['Ok']."\"></td></tr>
</table></form></td>
<td><form name=\"previousperiod\" action=\"index.php?mode=stats&amp;sid=$sid&amp;lang=$lang\" method=\"post\">
<input type=\"hidden\" name=\"show\" value=\"period\">
<input type=\"hidden\" name=\"interval\" value=\"$interval\">
<input type=\"hidden\" name=\"day\" value=\"".date("d",$nexttime)."\">
<input type=\"hidden\" name=\"month\" value=\"".date("m",$nexttime)."\">
<input type=\"hidden\" name=\"year\" value=\"".date("Y",$nexttime)."\">
<input type=\"image\" src=\"./Img/System/forward.png\" height=\"24\" width=\"24\" border=\"0\" ALT=\"".$strings['Next']."\">
</form></td>
</tr></table>\n";

// Fetching number of access per day
// Creating a table to fill up daily accesses with 0's (in case there
// is nothing in the database
$mindatetime = strtotime($mindate)+$onedayseconds;
$maxdatetime = strtotime($enddate);
$start = $maxdatetime;
$accestable = array();
$uniqIPtable = array();
for ($start = $maxdatetime; $start >= $mindatetime; $start-=86400) {
	$accestable[date("Y-m-d",$start)] = 0;
	$uniqIPtable[date("Y-m-d",$start)] = 0;
}
$totalAccess = 0;
$req = "SELECT date, count FROM ${table}_acces WHERE date>'$mindate' AND date<='$enddate' and label=0";
$res = mysql_query($req,$c);
while($row = mysql_fetch_object($res)) {
	$date = $row->date;
	$count = $row->count;
	$accestable[$date] = $count;
	$totalAccess += $count;
}
$req = "SELECT date, count FROM ${table}_uniq  WHERE label=0 AND date>'$mindate' AND date<='$enddate'";
$res = mysql_query($req,$c);
$totalUniq = 0;
while($row = mysql_fetch_object($res)) {
	$date = $row->date;
	$count = $row->count;
	$uniqIPtable[$date] = $count;
	$totalUniq += $count;
}
// If interval is more than one day, we do a plot
if ($plot == 1) {
	$string = "<?php  \n\$date_data = array(";
	$max = 0;
	$i = 0;
	for ($start = $mindatetime; $start <= $maxdatetime; $start+=86400) {
		$count = $accestable[date("Y-m-d",$start)];
		$date =date("Y-m-d",$start);
		if ($interval == 'week') {
			$day = "plot-day-short-".date("w",strtotime("$date"));
			$label = $strings[$day];
		} else {
			if (date("w",strtotime("$date")) == 1) {
				$label = $date;
			} else {
				$label = "";
			}
		}
		if ($count > $max) {$max=$count;}
		$i += 1;
		if ($i > 1) {
			$string .= "\n,array(\"$label\",$count)";
		} else {
			$string .= "\narray(\"$label\",$count)";
		}
	}
	$string .= "\n);\n\$maxY = $max;\n\$plottype=\"bars\";";
	$string .= "\n\$ylabel = \"".$strings['plot-Pageviews']."\";";
	$string .= "\n\$date_data2 = array(";
	$maxY = $max;
	$max = 0;
	$i = 0;
	for ($start = $mindatetime; $start <= $maxdatetime; $start+=86400) {
		$count = $uniqIPtable[date("Y-m-d",$start)];
		$date =date("Y-m-d",$start);
		$label = "";
		if ($count > $max) {$max=$count;}
		$i += 1;
		if ($i > 1) {
			$string .= "\n,array(\"$label\",$count)";
		} else {
			$string .= "\narray(\"$label\",$count)";
		}
	}
	$string .= "\n);\n\$twoplots=1;\n\$maxY2 = $max;\n\$plottype2=\"lines\";";
	$string .= "\n\$ylabel2 = \"".$strings['plot-Uniquevisitors']."\";";
	$string .= "\$start=$start;\n\$end=$maxdatetime;";
	$string .= "\n\$width = 500;";
	$string .= "\n\$height = 250;";
	$string .= "\n?>";
	$temp = fopen ("$tmpdirectory/tmp.$timecall.txt.php", 'w');
	fwrite($temp, $string);
	fclose($temp);
	if (($i>0) && ($maxY>0)) {
		echo "<table class=\"stat\">
<tr class=\"title\"><td>".$strings['History']." $title</td></tr>
<tr><td align=\"center\"><img src=\"./plotStatBar.php?file=tmp.$timecall.txt.php\" width=\"500\" height=\"250\" alt=\"".$strings['Timeintervalplot']."\"></tr>
</table>\n";
	} else {
		echo "<table class=\"stat\">
<tr class=\"title\"><td>".$strings['History']." $title</td></tr>
<tr><td align=\"center\">&nbsp;<br>".$strings['Nothingyet']."<br>&nbsp;</td></tr>
</table>\n";
	}
}

echo "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"3\">".$strings['Sillystats']." $title</td></tr>
<tr class=\"data odd\"><td>".$strings['Totalnumberofpageviews']."</td><td>".pageviews($totalAccess)."</td><td>".visitors($totalUniq)."</td></tr>\n";
if ($interval!='day') {
	$av = 1.0*$totalAccess*86400/($maxdatetime-$mindatetime+86400);
	$av2 = 1.0*$totalUniq*86400/($maxdatetime-$mindatetime+86400);
	echo "<tr class=\"data even\"><td>".$strings['Average']."</td><td>".pageviewsperday($av)."</td><td>".visitorsperday($av2)."</td></tr>\n";
	asort($accestable);
	reset($accestable);
	list($key, $val) = each($accestable);
	echo "<tr class=\"data odd\"><td colspan=\"3\">".$strings['Quietday']." ".dayMmYear(strtotime($key),2)." (".pageviews($val).")</td></tr>\n";
	arsort($accestable);
	reset($accestable);
	list($key, $val) = each($accestable);
	echo "<tr class=\"data even\"><td colspan=\"3\">".$strings['Busyday']." ".dayMmYear(strtotime($key),2)." (".pageviews($val).")</td></tr>\n";
	$even = "even";
	$odd = "odd";
} else {
	$even = "odd";
	$odd = "even";
}
$req = "SELECT COUNT(*) FROM ${table}_keyword WHERE first>'$mindate' and first<'$afterdate'";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$count=$row[0];
if ($count=='') { $count = 0; }
echo "<tr class=\"data $odd\"><td colspan=\"2\">".$strings['Numberofnewkeywords']."</td><td>".format_float($count)."</td></tr>\n";
$req = "SELECT COUNT(*) FROM ${table}_referrer WHERE first>'$mindate' and first<'$afterdate'";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$count=$row[0];
if ($count=='') { $count = 0; }
echo "<tr class=\"data $even\"><td colspan=\"2\">".$strings['Numberofnewreferrers']."</td><td>".format_float($count)."</td></tr>\n";
echo "</table>\n";

// Pie plot
// get the most popular pages
$req = "SELECT label,SUM(count) as count FROM ${table}_acces WHERE date>'$mindate' AND date<='$enddate' AND label>0 GROUP BY label ORDER BY count DESC ";
$res = mysql_query($req,$c);
$nPages = 0;
$totalAccess = 0;
$countPage = array();
$countPage2 = array();
$uniqPage = array();
while($row = mysql_fetch_object($res)) {
	$pageid[$nPages] = $row->label;
	$countPage[$row->label] = $row->count;
	$page[$row->label] = shortenCenter(pagename($c,$table,$pageid[$nPages]));
	$pageL[$row->label] = shortenCenter(pagename($c,$table,$pageid[$nPages]), 50);
	$totalAccess += $row->count;
	$nPages += 1; 
}
arsort($countPage);
if ($totalAccess > 0) {
	$string = "<?php  \n\$date_data = array(";
	$max = min($nPages,6);
	$j = 0;
	while ($j < $max) {
		list($key, $val) = each($countPage);
		$pageidplot[$j] = $key;
		$tt = $val;
		if ($j>0) {
			$string .= ",$tt";
		} else {
			$string .= "array(\"\",$tt";
		}
		$j += 1;
	}
	$other = 0;
	while (list($key, $val) = each($countPage)) {
		$other += $val;
	}
	if ($other > 0) {
		$string .= ",$other";
	}
	$string .= ")";
	// Removed calls to translitarate (not needed, we now have UTF in plots
	// $legende = "\"".shortencenter(translitarate($page[$pageidplot[0]]),24)."\"";
	$legende = "\"".shortencenter($page[$pageidplot[0]],24)."\"";
	for ($j=1;$j<$max;$j++) {
		// $tmpleg = shortencenter(translitarate($page[$pageidplot[$j]]),24);
		$tmpleg = shortencenter($page[$pageidplot[$j]],24);
		$legende .= ",\"$tmpleg\"";
	}
	if ($other > 0) {
		$legende .= ",\"".$strings['plot-Other']."\"";
	}
	$string .= "\n);\n\$plottype=\"pie\";";
	$string .= "\n\$legende = array($legende);";
	$string .= "\n\$width = 500;";
	$string .= "\n\$height = 200;";
	$string .= "\n?>";
	$temp = fopen ("$tmpdirectory/tmp2.$timecall.txt.php", 'w');
	fwrite($temp, $string);
	fclose($temp);
	echo "<table class=\"stat\">
<tr class=\"title\"><td>".$strings['Popularpages']." $title</td></tr>
<tr><td align=\"center\"><img src=\"./plotStatPie.php?file=tmp2.$timecall.txt.php\" width=\"500\" height=\"200\" alt=\"".$strings['Popularpages']." $title\"></td></tr>
</table>\n"; 
} else {
	echo "<table class=\"stat\">
<tr class=\"title\"><td>".$strings['Popularpages']." $title</td></tr>
<tr><td align=\"center\">&nbsp;<br>".$strings['Nothingyet']."<br>&nbsp;</td></tr>
</table>\n"; 
}

// New popular keywords during this period
echo "<table class=\"stat\">\n";
echo "<tr class=\"title\"><td colspan=\"3\">".$strings['Newpopularkeywords']." $title</td></tr>\n";
echo "<tr class=\"caption\"><td width=\"46%\">".$strings['Page']."</td><td width=\"46%\">".$strings['Keyword']."</td><td>".$strings['Hits']."</td></tr>\n";
$req = "SELECT page,keyword,SUM(count) as c,engine FROM ${table}_keyword WHERE first>'$mindate' and first<'$afterdate' GROUP BY LOWER(CONCAT(engine, ': ', keyword)) ORDER BY c DESC LIMIT 0,$ntop";
$res = mysql_query($req,$c);
$n = 0;
while ($row = mysql_fetch_object($res)) {
	$n += 1;
	$key = mb_strtolower(htmlentities($row->keyword, ENT_NOQUOTES, 'UTF-8'), 'UTF-8');
	$engine = $row->engine;
	$count = $row->c;
	$pageid2 = $row->page;
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	$link = linksforpage ($sid, shortenPage(pagename($c,$table,$pageid2),1), $pageid2);
	echo "<tr class=\"data $even\"><td>$link</td><td>$engine: $key</td><td>".hits($count)."</td></tr>\n";
}
if ($n == 0) {
	echo "<tr><td colspan=\"3\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n";
}
echo "</table>\n";

// New and popular Referers
echo "<table class=\"stat\">\n";
echo "<tr class=\"title\"><td colspan=\"3\">".$strings['Newreferrers']." $title</td></tr>\n";
echo "<tr class=\"caption\"><td width=\"46%\">".$strings['Page']."</td><td width=\"46%\">".$strings['Referrer']."</td><td>".$strings['Hits']."</td></tr>\n";
$req = "SELECT address,count,page FROM ${table}_referrer WHERE first>'$mindate' and first<'$afterdate'  ORDER BY count DESC LIMIT 0,$ntop";
$res = mysql_query($req,$c);
$n = 0;
while ($row = mysql_fetch_object($res)) {
	$n += 1;
	$ref = $row->address;
	$count = $row->count;
	$pageid2 = $row->page;
	$link = linksforpage ($sid, shortenPage(pagename($c,$table,$pageid2),1), $pageid2);
	$linkref = urlLink($ref,2);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "<tr class=\"data $even\"><td>$link</td><td>$linkref</td><td>".hits($count)."</td></tr>\n";
}
if ($n == 0) {
	echo "<tr><td colspan=\"3\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n";
}
echo "</table>\n";

// Page statistics
// Getting the uniq visitors
$req = "SELECT label,SUM(count) as count FROM ${table}_uniq WHERE date>'$mindate' AND date<='$enddate' AND label>0 GROUP BY label ORDER BY count DESC";
$res = mysql_query($req,$c);
while($row = mysql_fetch_object($res)) {
	//$thispageid[$nPages] = $row->label;
	$uniqPage[$row->label] = $row->count;
}
// Getting the uniq visitors in previous interval
$req = "SELECT label,SUM(count) as count FROM ${table}_uniq WHERE date>'$mindate2' AND date<='$mindate' AND label>0 GROUP BY label ORDER BY count DESC";
$res = mysql_query($req,$c);
while($row = mysql_fetch_object($res)) {
	//$thispageid[$nPages] = $row->label;
	$uniqPage2[$row->label] = $row->count;
}
// Getting hits for previous interval
$req = "SELECT label,SUM(count) as count FROM ${table}_acces WHERE date>'$mindate2' AND date<='$mindate' AND label>0 GROUP BY label ORDER BY count DESC ";
$res = mysql_query($req,$c);
while($row = mysql_fetch_object($res)) {
	$countPage2[$row->label] = $row->count;
}
foreach($countPage as $i=>$tt) {
	if (!isset($countPage[$i])) $countPage[$i]=0;
	if (!isset($countPage2[$i])) $countPage2[$i]=0;
	if (!isset($uniqPage[$i])) $uniqPage[$i]=0;
	if (!isset($uniqPage2[$i])) $uniqPage2[$i]=0;
	if (!isset($uniqIPtable[$i])) $uniqIPtable[$i]=0;

	if (($countPage[$i]+$countPage2[$i])>0) {
		$pcCount[$i] = intval(100.*($countPage[$i]-$countPage2[$i])/($countPage[$i]+$countPage2[$i]));
	}
	if (($uniqPage[$i]+$uniqIPtable[$i])>0) {
		$pcUniq[$i] = intval(100.*($uniqPage[$i]-$uniqPage2[$i])/($uniqPage[$i]+$uniqIPtable[$i]));
	}
}


// Preparing the table

$txtPage = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=period&amp;interval=$interval&amp;enddate=$enddatecall&amp;lang=$lang&amp;sort=name", $strings['Sortby']." ".$strings['Name'], "#tablePages");
$txtHits = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=period&amp;interval=$interval&amp;enddate=$enddatecall&amp;lang=$lang&amp;sort=hits", $strings['Sortby']." ".$strings['Hits'], "#tablePages");
$txtHitsPc = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=period&amp;interval=$interval&amp;enddate=$enddatecall&amp;lang=$lang&amp;sort=hitspc", $strings['Sortby']." %","#tablePages");
$txtVisits = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=period&amp;interval=$interval&amp;enddate=$enddatecall&amp;lang=$lang&amp;sort=visits", $strings['Sortby']."   ".$strings['Visits'],"#tablePages");
$txtVisitsPc = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=period&amp;interval=$interval&amp;enddate=$enddatecall&amp;lang=$lang&amp;sort=visitspc", $strings['Sortby']." %","#tablePages");

echo "<A name=\"tablePages\"></A><table class=\"stat\">
<tr class=\"title\"><td colspan=\"7\">".$strings['Individualpagestatistics']." $title</td></tr>\n";
echo "<tr class=\"caption\"><td>".$strings['Rank']."</td><td>".$strings['Page']."</td><td width=\"110px\">".$strings['Graph']."</td><td width=\"12%\" colspan=\"2\">".$strings['Hits']."</td><td width=\"12%\" colspan=\"2\">".$strings['Visits']."</td></tr>
<tr class=\"caption\"><td>&nbsp;</td><td>$txtPage</td><td width=\"110px\">&nbsp;</td><td>$txtHits</td><td>$txtHitsPc</td><td>$txtVisits</td><td>$txtVisitsPc</td></tr>\n";
//echo "<tr><td>".$strings['Total']."</td><td>$totalAccess ".$strings['hits']. "</td></tr>\n";
$max = 0;
if (isset($countPage[0])) $max = $countPage[0];
if ($sort == "name") {
	$key = $pageL;
} else if ($sort == "visits") {
	$key = $uniqPage;
} else if ($sort == "visitspc") {
	$key = $pcUniq;
} else if ($sort == "hitspc") {
	$key = $pcCount;
} else {
	$key = $countPage;
}
if ($order == "desc") {
	asort($key);
} else {
	arsort($key);
}
$j = 0;
$n = 0;
if ($nPages>0) { 
	$max = max($countPage);
	foreach ($key as $i=>$tt) {
		$width = max(intval(140*$countPage[$i]/$max),1);
		if ($uniqPage[$i] == "") $uniqPage[$i]=0;
		if ($countPage[$i] == "") $countPage[$i]=0;
		$j += +1;
		if (($countPage[$i] != 0) || ($uniqPage[$i] != 0)) {
			$n+=1;
			if (isset($pcCount[$i])) {
				$txtPcC = incrdecr($pcCount[$i],"%");
			} else {
				$txtPcC = incrdecr(0,"%");
			}
			if (isset($pcUniq[$i])) {
				$txtPcU = incrdecr($pcUniq[$i],"%");
			} else {
				$txtPcU = incrdecr(0,"%");
			}
			$link = linksforpage ($sid, shortenPage(pagename($c,$table,$i),2), $i);
			if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
			echo "<tr class=\"data $even\"><td align=\"center\">$j</td><td>$link</td><td width=\"150px\"><img src=\"Img/System/bar.gif\" alt=\"$countPage[$i] ".$strings['hits']."\" width=\"$width\" height=\"10\" border=\"1\"></td><td align=\"center\">".format_float($countPage[$i])."</td><td>$txtPcC</td><td align=\"center\">".format_float($uniqPage[$i])."</td><td>$txtPcU</td></tr>\n";
		}
	}
} else {
	echo "<tr><td align=\"center\" colspan=\"7\">".$strings['Nothingyet']."</td></tr>\n";
}
echo "</table>\n";
}
?>
