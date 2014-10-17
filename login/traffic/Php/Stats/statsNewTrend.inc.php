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
/* Function: newTrend
/* Role: tries to figure out new trend over a time period
/* Parameters: 
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $sid: the site ID
/*   - $interval: time interval, in days
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 06/2004
/* 11/2006: removed database connection function (moved earlier in the code)
/*********************************************************************************/

function echoNewTrend($c,$table, $site, $sid, $interval) {
global $DEBUG;
global $strings;
global $lang;
global $display;

// Number of pages, keywords and referrers to list in the tables
$ntop = $display['ntop'];

echo "<table class=\"form\">
<tr><td align=\"center\" colspan=\"3\">".$strings['Intervalofanalysis']."</td></tr>
<tr><td align=\"center\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=new&amp;interval=7&amp;lang=$lang\">7 ".$strings['days']."</a></td>
<td align=\"center\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=new&amp;interval=30&amp;lang=$lang\">1 ".$strings['month']."</a></td>
<td align=\"center\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=new&amp;interval=120&amp;lang=$lang\">3 ".$strings['months']."</a></td></tr>
</table>";

$today = date("Y-m-d");
$first = date("Y-m-d", strtotime("-$interval days"));
$two = 2*$interval;
$second = date("Y-m-d", strtotime("-$two days"));
$three = 3*$interval;
$third = date("Y-m-d", strtotime("-$three days"));

// Pages on the rise and decline

// Fetching access data for this interval
$req = "SELECT label,SUM(count) as count FROM ${table}_acces WHERE date>='$first' and label>0 GROUP BY label";
$res = mysql_query($req,$c);
$n = 0;
while ($row = mysql_fetch_object($res)) {
	$count[$row->label] = $row->count;
	$n += 1;
}
// Access data for previous interval
if ($n > 0) {
	$req = "SELECT label,SUM(count) as count FROM ${table}_acces WHERE date>='$second' and date<'$first' and label>0 GROUP BY label";
	$res = mysql_query($req,$c);
	$twoT = array();
	while ($row = mysql_fetch_object($res)) {
		$twoT[$row->label] = $row->count;
	}
	// Calculating daily average (need to do separatly because days with no access are not in the database...
	$req = "SELECT id,added FROM ${table}_pages";
	$res = mysql_query($req,$c);
	while ($row = mysql_fetch_object($res)) {
		$added[$row->id] = $row->added;
	}
	while (list($key, $thiscount) = each($count)) {
		//echo "<br>For pageid $key, it was added on $added[$key], we have a count of $thiscount";
		if ($first>$added[$key]) {
			$count[$key] = $thiscount/($interval+1);
		} else {
			$thisinterval = (strtotime($today)-strtotime($added[$key])+86400.)/86400.;
			$count[$key] = $thiscount/$thisinterval;
		}
		//echo " and the average becomes $count[$key].\n";
	}
	reset($count);
	while (list($key, $thiscount) = each($twoT)) {
		if ($second>$added[$key]) {
			$twoT[$key] = $thiscount/($interval+1);
		} else {
			$thisinterval = (strtotime($first)-strtotime($added[$key])+86400.)/86400.;
			$twoT[$key] = $thiscount/$thisinterval;
		}
	}
	reset($twoT);
	// Calculating gain and losses
	while (list($key, $thiscount) = each($count)) {
		if (isset($twoT[$key])) {
			$gain[$key] = $thiscount - $twoT[$key];
			$loss[$key] = $twoT[$key]-$thiscount;
		} else {
			$gain[$key] = $thiscount;
			$loss[$key] = 0;
		}
	}
	arsort($gain);
	reset($gain);
	$max = min($ntop,$n);
	// Pages on the rise
	echo "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"4\">".$strings['Pagesontherise']."</td></tr>
<tr class=\"caption\"><td>&nbsp;</td><td width=\"15%\">".sprintf($strings["LastXXdays"],$interval)."</td><td width=\"15%\">".sprintf($strings["PreviousXXdays"],$interval)."</td><td width=\"15%\">".$strings['Gain']."</td></tr>\n";
	for ($i=0;$i<$max;$i++) {
		list($thisid, $thiscount) = each($gain);
		$link = linksforpage($sid, shortenPage(pagename($c,$table,$thisid),2), $thisid);
		if(!isset($count[$thisid])) $count[$thisid]=0;
		if(!isset($twoT[$thisid])) $twoT[$thisid]=0;
		$av = format_float($count[$thisid],1);
		$av2 = format_float($twoT[$thisid],1);
		$av3 = $count[$thisid]-$twoT[$thisid];
		$av3T = format_float($av3,1);
		if ($av3 > 0) {
			if (($i % 2) == 0) { $even = "odd";} else {$even="even";}
			echo "\n<tr class=\"data $even\"><td>$link</td><td align=\"center\">$av</td><td align=\"center\">$av2</td><td align=\"center\">$av3T</td></tr>\n";
		}
	}
	echo "</table>\n";
	// Pages on the decline
	echo "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"4\">".$strings['Pagesonthedecline']."</td></tr>
<tr class=\"caption\"><td>&nbsp;</td><td width=\"15%\">".sprintf($strings["LastXXdays"],$interval)."</td><td width=\"15%\">".sprintf($strings["PreviousXXdays"],$interval)."</td><td width=\"15%\">".$strings['Loss']."</td></tr>\n";
	arsort($loss);
	reset($loss);
	for ($i=0;$i<$max;$i++) {
		list($thisidL, $thiscount) = each($loss);
		$linkL = linksforpage($sid, shortenPage(pagename($c,$table,$thisidL),2), $thisidL);
		if (!isset($count[$thisidL])) $count[$thisidL]=0;
		if (!isset($twoT[$thisidL])) $twoT[$thisidL]=0;
		$avL = format_float($count[$thisidL],1);
		$av2L = format_float($twoT[$thisidL],1);
		$av3N = $twoT[$thisidL]-$count[$thisidL];
		$av3L = format_float($av3N,1);
		if ($av3N>0) {
			if (($i % 2) == 0) { $even = "odd";} else {$even="even";}
			echo "\n<tr class=\"data $even\"><td>$linkL</td><td align=\"center\">$avL</td><td align=\"center\">$av2L</td><td align=\"center\">$av3L</td></tr>\n";
		}
	}
	echo "</table>\n";
} else {
	echo "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"4\">".$strings['Pagesontherise']."</td></tr>
<tr class=\"caption\"><td>&nbsp;</td><td width=\"15%\">".sprintf($strings["LastXXdays"],$interval)."</td><td width=\"15%\">".sprintf($strings["PreviousXXdays"],$interval)."</td><td width=\"15%\">".$strings['Gain']."</td></tr>\n";
	echo "<tr><td colspan=\"4\" align=\"center\">".$strings['Nothingyet']."</td></tr>";
	echo "</table>\n";
	echo "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"4\">".$strings['Pagesonthedecline']."</td></tr>
<tr class=\"caption\"><td>&nbsp;</td><td width=\"15%\">".sprintf($strings["LastXXdays"],$interval)."</td><td width=\"15%\">".sprintf($strings["PreviousXXdays"],$interval)."</td><td width=\"15%\">".$strings['Loss']."</td></tr>\n";
	echo "<tr><td colspan=\"4\" align=\"center\">".$strings['Nothingyet']."</td></tr>";
	echo "</table>\n";
}

// Pages with a large number of new keywords
$req = "SELECT page,COUNT(keyword) AS key_num FROM ${table}_keyword  WHERE first>='$first' GROUP BY page";
$res = mysql_query($req,$c);
$newkeys = array();
$nkeys = 0;
while ($row = mysql_fetch_object($res)) {
	$newkeys[$row->page] = $row->key_num;
	$nkeys += 1;
}
// Pages with a large number of new referrers
$req = "SELECT page,COUNT(address) AS key_num FROM ${table}_referrer  WHERE first>='$first' GROUP BY page";
$res = mysql_query($req,$c);
$newrefs = array();
$nrefs = 0;
while ($row = mysql_fetch_object($res)) {
	$newrefs[$row->page] = $row->key_num;
	$nrefs += 1;
}
arsort($newkeys);
arsort($newrefs);
echo "\n<table class=\"stat\">
<tr class=\"title\"><td colspan=\"4\">".$strings['Pageswithnewkeywordsandreferrers']."</td></tr>
<tr class=\"caption\"><td colspan=\"2\" width=\"50%\">".$strings["NewKwds"]."</td><td class=\"bleft\" colspan=\"2\">".$strings["NewRefs"]."</td></tr>";
if (($nkeys*$nrefs)>0) {
	for ($i=0;$i<$ntop;$i++) {
		if (list($thisid, $thiscount) = each($newkeys)) {
			$newk = 1;
			$link = linksforpage($sid, shortenPage(pagename($c,$table,$thisid),1), $thisid);
			$counttxt =  format_float($thiscount);
			$string="<td>$link</td><td>$counttxt</td>";
		} else {
			$newk=0;
			$string="<td>&nbsp;</td><td>&nbsp;</td>";
		}
		if (list($thisid, $thiscount) = each($newrefs)) {
			$newr = 1;
			$link = linksforpage($sid, shortenPage(pagename($c,$table,$thisid),1), $thisid);
			$counttxt =  format_float($thiscount);
			$string2="<td class=\"bleft\">$link</td><td>$counttxt</td>";
		} else {
			$newr=0;
			$string2="<td class=\"bleft\">&nbsp;</td><td>&nbsp;</td>";
		}
		if ($newk || $newr) {
			if (($i % 2) == 0) { $even = "odd";} else {$even="even";}
			echo "\n<tr class=\"data $even\">".$string.$string2."</tr>";
		}
	}
} else {
echo "<tr><td colspan=\"4\" align=\"center\">".$strings['Nothingyet']."</td></tr>";
}
echo "\n</table>";

// New popular keywords during this period
echo "<table class=\"stat\">";
echo "<tr class=\"title\"><td colspan=\"3\">".sprintf($strings["NewinlastXXdays"], $interval)."</td></tr>";
echo "\n<tr class=\"caption\"><td colspan=\"3\">".$strings['Keywords']."</td></tr>";
$req = "SELECT page,keyword,SUM(count) as c,engine FROM ${table}_keyword WHERE first>='$first' GROUP BY LOWER(CONCAT(engine, ': ', keyword)) ORDER BY count DESC LIMIT 0,$ntop";
$res = mysql_query($req,$c);
$n = 0;
while ($row = mysql_fetch_object($res)) {
	$n += 1;
	$key = mb_strtolower(htmlentities ($row->keyword, ENT_NOQUOTES, 'UTF-8'), 'UTF-8');
	$engine = $row->engine;
	$count = $row->c;
	$pageid2 = $row->page;
	$link = linksforpage($sid, shortenPage(pagename($c,$table,$pageid2),1), $pageid2);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "\n<tr class=\"data $even\"><td>$link</td><td>$engine: $key</td><td>".hits($count)."</td></tr>";
}
if ($n == 0) {
	echo "\n<tr><td colspan=\"3\" align=\"center\">".$strings['Nothingyet']."</td></tr>";
}
// New popular referrers during this period
echo "\n<tr class=\"caption\"><td colspan=\"3\" class=\"btop\">".$strings['Referrers']."</td></tr>";
$req = "SELECT page,address,count FROM ${table}_referrer WHERE first>='$first' ORDER BY count DESC LIMIT 0,$ntop";
$res = mysql_query($req,$c);
$n = 0;
while ($row = mysql_fetch_object($res)) {
	$n += 1;
	$ref = $row->address;
	$count = $row->count;
	$pageid2 = $row->page;
	$linkref = urlLink($ref,2);
	$link = linksforpage($sid, shortenPage(pagename($c,$table,$pageid2),1), $pageid2);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "\n<tr class=\"data $even\"><td>$link</td><td>$linkref</td><td>".hits($count)."</td></tr>";
}
if ($n == 0) {
	echo "\n<tr><td colspan=\"3\"><div align=\"center\">".$strings['Nothingyet']."</div></td></tr>";
}
echo "\n</table>\n";
}

?>
