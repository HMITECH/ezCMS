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
/* Function: forecast
/* Role: creates a forecast for today...
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $sid: site id
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 11/2006
/*********************************************************************************/

function forecast ($c,$table, $site, $sid) {
$time = time();
$fulltime = date("G:i:s", $time);
$hour = date("G", $time);
$minutes = date("i", $time);
// Get number of visitors before and after
$req = "SELECT SUM(count) as count FROM ${table}_hour WHERE value<$hour";
$res = mysql_query($req,$c);
$row = mysql_fetch_object($res);
$before = $row->count;
$req = "SELECT count FROM ${table}_hour WHERE value=$hour";
$res = mysql_query($req,$c);
$row = mysql_fetch_object($res);
$now = $row->count;
$req = "SELECT SUM(count) as count FROM ${table}_hour WHERE value>$hour";
$res = mysql_query($req,$c);
$row = mysql_fetch_object($res);
$after = $row->count;
// Average number of visitors and hits last week
$today = date("Y-m-d");
$sevendays = date("Y-m-d",strtotime("-1 week"));
$req = "SELECT SUM(count) as count FROM ${table}_acces WHERE date>'$sevendays' AND date <'$today' AND label=0";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$totalhitsweek =$row['count'];
if ($totalhitsweek=='') $totalhitsweek=0;
$avhits = $totalhitsweek/6.0;
$req = "SELECT SUM(count) as count FROM ${table}_uniq WHERE date>'$sevendays' AND date <'$today' AND label=0";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$totalvisitsweek=$row['count'];
if ($totalvisitsweek=='') $totalvisitsweek=0;
$avvisits = $totalvisitsweek/6.0;
// Number of hits and visits for today
$hitstoday = nToday($c,$table, $site);
$visitstoday = vToday($c,$table, $site) + vOnline($table);
// Forecasts for today
$totalhits = $before+$now+$after;
$pcbefore = 100.*($before + $now*$minutes/60.)/$totalhits;
$pcafter = 100.-$pcbefore;
$pcaftertxt = intval($pcafter);
$hitstogo = intval(100.*$hitstoday/$pcbefore)-$hitstoday;
$visitstogo = intval(100.*$visitstoday/$pcbefore)-$visitstoday;
$string = "It is $fulltime. Usually, you get $pcaftertxt% of you visits after $fulltime. You already got $hitstoday hits and $visitstoday visitors. Therefore, you should expect $hitstogo and $visitstogo more hits and visitors.";
echo $string;
}

/*********************************************************************************/
/* Function: echoMain
/* Role: echoMain stats results, such as number access/day, best day, best page,
/*    new keywords and so on
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 06/2004
/* 11/2006: removed database connection function (moved earlier in the code)
/*********************************************************************************/

function echoMainStats($c,$table, $site, $sid, $toplot) {
global $DEBUG;
global $strings;
global $lang;
global $tmpdirectory;
global $display;
global $sites;

// Number of pages, keywords and referrers to list in the tables
$ntop = $display['ntop'];
// Time at which the routine was called (used in giving names for temporary files)
$timecall = time();

$timeserver = $timecall + 3600*$sites[$sid]['timediff'];
$today = date("Y-m-d", $timeserver);
$sevendays = date("Y-m-d",$timeserver-7*86400);
$onemonth = date("Y-m-d",$timeserver-30*86400);
$thismonth = date("Y-m-01", $timeserver);


// Summary plot
if ($toplot == "month") {
	$req = "SELECT COUNT(DISTINCT(DATE_FORMAT(date,'%Y-%m-01'))) as nmonth FROM ${table}_acces WHERE label=0";
	$res = mysql_query($req,$c);
	$row = mysql_fetch_object($res);
	$nLines = $row->nmonth;
	$labelskip = floor($nLines/15)+1;
	$req = "SELECT DATE_FORMAT(date,'%Y-%m-01') as month, SUM(count) as count FROM ${table}_acces WHERE label=0 GROUP BY month ORDER BY month ASC;";
} else {
	$req = "SELECT COUNT(date) as nlines FROM ${table}_acces WHERE label=0";
	$res = mysql_query($req,$c);
	$row = mysql_fetch_object($res);
	$nLines = $row->nlines;
	$req = "SELECT date as month,count FROM ${table}_acces WHERE label=0 ORDER BY month ASC;";
}
$res = mysql_query($req,$c); 
$string = "<?php  \n\$date_data = array(";
$countline=0;
$start = 100000000000000;
$end = 0;
$maxcount= 0;
$line = $nLines;
while($row = mysql_fetch_object($res)) {
	$line -= 1;
	$date = $row->month;
	$count = $row->count;
	$time = strtotime($date);
	$dataUniq[$time] = 0;
	if ($time<$start) {$start = $time;}
	if ($time>$end) {$end = $time;}
	if ($count>$maxcount) {$maxcount=$count;}
	if (date("j",$time) == 1) {
		$monthN = date("n", $time);
		$yearN = date("y", $time);
		$label = $strings['plot-month-short-'.$monthN]." $yearN";
	} else {
		$label = "";
	}
	if (($toplot == "month") && ($line%$labelskip != 0)) $label = '';
	if ($countline>0) {
		if ($toplot == "month") {
			$string .= "\n,array(\"$label\",$count)";
		} else {
			$string .= "\n,array(\"$label\",$time,$count)";
		}
	} else {
		if ($toplot == "month") {
			$string .= "\narray(\"$label\",$count)"; 
		} else {
			$string .= "\narray(\"$label\",$time,$count)"; 
		}
	}
	$countline += 1;
}
if ($toplot == "month") {
	$plottype = "bars";
} else {
	$plottype = "thinbarline";
}
$string .= "\n);\n\$start=$start;\n\$end=$end;\n\$maxY=$maxcount;\n\$plottype=\"$plottype\";";
$string .= "\n\$width = 600;";
$string .= "\n\$height = 300;";
$string .= "\n\$ylabel = \"".$strings['plot-Pageviews']."\";";
// Pulling uniq IP visits
if ($toplot == "month") {
	$req = "SELECT DATE_FORMAT(date,'%Y-%m-01') as month,SUM(count) as count FROM ${table}_uniq WHERE label=0 GROUP BY month ORDER BY month ASC;";
} else {
	$req = "SELECT date as month, count FROM ${table}_uniq WHERE label=0 ORDER BY month ASC;";
}
$res = mysql_query($req,$c); 
$countline=0;
$startUniq = 100000000000000;
$endUniq = 0;
$maxcount= 0;
$dataUniq = array();
$nLines = 0;
while($row = mysql_fetch_object($res)) {
	$date = $row->month;
	$count = $row->count;
	$time = strtotime($date);
	if ($time<$startUniq) {$startUniq = $time;}
	if ($time>$endUniq) {$endUniq = $time;}
	if ($count>$maxcount) {$maxcount=$count;}
	$dataUniq[$time] = $count;
	$nLines += 1;
}
if ($start < $end) {
	if ($toplot == "month") {$incr = "month";} else {$incr = "day";}
	$string .= "\n\$date_data2 = array(";
	$line = $nLines + 1;
	for ($t=$start;$t<=$end;$t = strtotime("+1 $incr",$t)) {
		$line -= 1;
		if (!isset($dataUniq[$t])) {
			$count = 0;
		} else {
			$count = $dataUniq[$t];
		}
		$thislabel = $t;
		if ($countline>0) {
			$string .= "\n,array(\"\",\"$thislabel\",$count)";
		} else {
			$string .= "\narray(\"\",\"$thislabel\",$count)"; 
		}
		$countline += 1;
	}
	$string .= "\n);\n\$twoplots=1;\n\$maxY2=$maxcount;\n\$plottype2=\"lines\";";
	$string .= "\n\$ylabel2 = \"".$strings['plot-Uniquevisitors']."\";\n\$angleX = 90;";
}
$string .= "\n?>";
$temp = fopen ("$tmpdirectory/tmp.$timecall.txt.php", 'w');
fwrite($temp, $string);
fclose($temp);
if (($start != $end)&&($countline!=0)) {
	if ($toplot == "month") {
		$title = $strings['Monthlyvisits'];
		$tablenav = "<div align=\"right\">".$strings['Groupby'].": <a href=\"./index.php?mode=stats&amp;sid=$sid&amp;toplot=day&amp;lang=$lang\" class=\"basic\">".$strings['day']."</a> -- ".$strings['month']."</div>";
		$plot = "plotStatBar";
	} else {
		$title = $strings['Dailyvisits'];
		$tablenav = "<div align=\"right\">".$strings['Groupby'].": ".$strings['day']." -- <a href=\"./index.php?mode=stats&amp;sid=$sid&amp;toplot=month&amp;lang=$lang\" class=\"basic\">".$strings['month']."</a></div>";
		$plot = "plotStat";
	}
	echo "<table class=\"stat\">
<tr class=\"title\"><td>$title</td></tr>
<tr><td align=\"center\"><img src=\"./$plot.php?file=tmp.$timecall.txt.php\" alt=\"$title\">$tablenav</td></tr>
</table>\n"; 
} else {
	echo "<table class=\"stat\">
<tr class=\"title\"><td>".$strings['Dailyvisits']."</td></tr>
<tr><td align=\"center\">&nbsp;<br>".$strings['Nothingyet']."<br>&nbsp;</td></tr>
</table>\n"; 
}

// Summary table
echo "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"3\">".$strings['Visitsstatistics']."</td></tr>";
// get total access today WHERE date='$today'
$req = "SELECT count FROM ${table}_acces WHERE date='$today' AND label=0";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$count=$row['count'];
$req = "SELECT count FROM ${table}_uniq WHERE date='$today' AND label=0";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$count2=$row['count'];
if ($count2=='') $count2=0;
$today = dayMmYear ($timeserver,1);
echo "\n<tr><td>".$strings['Today']." $today</td><td>".pageviews($count) ."</td><td>".visitors($count2)."</td></tr>";
// get total access last seven days
$req = "SELECT SUM(count) as count FROM ${table}_acces WHERE date>'$sevendays' AND label=0";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$count=$row['count'];
$req = "SELECT SUM(count) as count FROM ${table}_uniq WHERE date>'$sevendays' and label=0";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$count2=$row['count'];
if ($count2=='') $count2=0;
echo "\n<tr><td>".$strings['Lastsevendays']."</td><td>".pageviewsperday($count/7,0)."</td><td>".visitorsperday($count2/7,0)."</td></tr>";
// get total access this month
$req = "SELECT SUM(count) as count FROM ${table}_acces WHERE date>='$thismonth' AND label=0";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$count=$row['count'];
if ($count=='') $count=0;
$req = "SELECT SUM(count) as count FROM ${table}_uniq WHERE date>='$thismonth' and label=0";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$count2=$row['count'];
if ($count2=='') $count2=0;
$duration = ceil(($timeserver-strtotime($thismonth))/86400);
if ($duration == 0) $duration = 1;
echo "\n<tr><td>".$strings['Thismonth']."</td><td>".pageviewsperday($count/$duration,0)."</td><td>".visitorsperday($count2/$duration,0)."</td></tr>";
// Average
$time = $timeserver;
$req = "SELECT SUM(count) as count FROM ${table}_acces WHERE label=0";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$total = $count['count'];
if ($total=='') $total=0;
$req = "SELECT added FROM ${table}_pages ORDER BY added ASC LIMIT 0,1";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$firsttext = $count['added'];
if ($count['added'] == '') {
	$first = 0;
} else {
	$first = strtotime($count['added']);
}
$av = 1.0*$total*86400/($time-$first+86400);
$req = "SELECT SUM(count) as count FROM ${table}_uniq WHERE label=0";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$total2 = $count['count'];
if ($total2=='') $total2=0;
$req = "SELECT date FROM ${table}_uniq WHERE label=0 ORDER BY date ASC LIMIT 0,1";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$firsttext = $count['date'];
if ($count['date'] == '') {
	$first = 0;
} else {
	$first = strtotime($count['date']);
}
$av2 = 1.0*$total2*86400/($time-$first+86400);
echo "\n<tr><td>".$strings['Sincebeginingofrecord']."</td><td>".pageviewsperday($av,0)."</td><td>".visitorsperday($av2,0)."</td></tr>";
// Total, recalculating (if the user manually changed the page table, the numbers in access table might not be correct)
$req = "SELECT (SUM(se)+SUM(ref)+SUM(other)+SUM(internal)+SUM(old)) as count FROM ${table}_pages";
$res = mysql_query($req,$c);
$res=mysql_fetch_array($res);
if ($res['count'] == "") { $count = 0; } else { $count = $res['count']; }
echo "\n<tr><td colspan=\"2\">".$strings['Totalnumberofpageviews']."</td><td>".format_float($count)."</td></tr>";
// Search engines and referers, new style. In table_pages we have some idea for pages created after version 1.2, it is the exact number. For the others, we need a correction.
$req = "SELECT SUM(ref) as ref, SUM(se) as se, SUM(internal) as internal, SUM(other) as other, SUM(old) as old FROM ${table}_pages";
$res = mysql_query($req,$c);
$row =mysql_fetch_object($res);
$fromRef = $row->ref;
$fromSE = $row->se;
$fromInternal = $row->internal;
$fromOther = $row->other;
$fromOld = $row->old;
// Here is the correction
$thistotal = $fromRef + $fromSE + $fromInternal + $fromOther;
if ($thistotal > 0) {
	$fromSECorrected = intval($fromSE * $total / $thistotal);
	$fromRefCorrected = intval($fromRef * $total / $thistotal);
	$fromInternalCorrected =  intval($fromInternal * $total / $thistotal);
	$fromOtherCorrected =  intval($fromOther * $total / $thistotal);
} else {
	$fromSECorrected = 0;
	$fromRefCorrected = 0;
	$fromInternalCorrected = 0;
	$fromOtherCorrected = 0;
}
if ($total > 0) {
	$fromSEpc = 100.0*$fromSECorrected/$total;
	$fromRefpc = 100.0*$fromRefCorrected/$total;
	$fromInternalpc = 100.0*$fromInternalCorrected/$total;
	$fromOtherpc = 100.0*$fromOtherCorrected/$total;
} else {
	$fromSEpc = 0;
	$fromRefpc = 0;
	$fromInternalpc = 0;
	$fromOtherpc = 0;
}
echo "\n<tr><td colspan=\"2\">".$strings['Totalvisitsengines']."</td><td>".format_float($fromSECorrected)." (".format_float($fromSEpc)." %)</td></tr>";
// Referers
echo "\n<tr><td colspan=\"2\">".$strings['Totalvisitsreferrers']."</td><td>".format_float($fromRefCorrected)." (".format_float($fromRefpc)." %)</td></tr>";
// Best day
$req = "SELECT date FROM ${table}_acces WHERE label=0 ORDER BY date ASC LIMIT 0,1";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$firsttext = $count['date'];
$req = "SELECT date, count FROM ${table}_acces WHERE (label=0 AND date>'$firsttext') ORDER BY count DESC LIMIT 0,1;";
$res = mysql_query($req,$c);
$i=0;
$row = mysql_fetch_array($res);
$bestday = $row['date'];
$bestcount = $row['count'];
if ($total>0) {$howmuch = number_format(1.0*$bestcount/$av,1);} else $howmuch=0.0;
if ($bestday == '') {
	$bestday = dayMmYear(0,2);
} else {
	$bestday = dayMmYear(strtotime($bestday),2);
}
echo "\n<tr><td colspan=\"3\">".$strings['Busyday']." $bestday (".pageviews($bestcount).")</td></tr>";
// worst day
$req = "SELECT date, count FROM ${table}_acces WHERE (label=0 AND date>'$firsttext') ORDER BY count ASC LIMIT 0,1;";
$res = mysql_query($req,$c);
$row = mysql_fetch_array($res);
$bestday = $row['date'];
$bestcount = $row['count'];
if ($total>0) {$howmuch = number_format(1.0*$bestcount/$av,1);} else $howmuch=0.0;
if ($bestday == '') {
	$bestday = dayMmYear(0,2);
} else {
	$bestday = dayMmYear(strtotime($bestday),2);
}
echo "\n<tr><td colspan=\"3\">".$strings['Quietday']." $bestday (".pageviews($bestcount).")</td></tr>";
echo "\n</table>";

}
?>