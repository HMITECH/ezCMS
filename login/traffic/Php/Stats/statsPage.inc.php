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
/* Function: echoPage
/* Role: stats results, such as number access/day, best day,
/*    new keywords and so on for a single page
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $pageid: id of the page in the database
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 06/2004
/* 04/2006: fixed a problem with date calculations in uniq IP plot
/* 11/2006: removed database connection function (moved earlier in the code)
/*********************************************************************************/
function echoPage($c,$table, $site, $pageid,$sid) {
global $strings;
global $lang;
global $tmpdirectory;
global $sites;
$ntop = 10;
$timecall = time();

$timeserver = time() + 3600*$sites[$sid]['timediff'];
$today = date("Y-m-d", $timeserver);
$sevendays = date("Y-m-d",$timeserver-7*86400);
$onemonth = date("Y-m-d",$timeserver-30*86400);
$thismonth = date("Y-m-01", $timeserver);

// page name
$pagename = shortenPage(pagename($c,$table,$pageid),3);
// Navigation
$nav = formnavpages($c, $table, $pageid, "index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;lang=$lang", $strings['Statisticsfor'], false);
echo $nav;

// Summary plot
$req = "SELECT date,count FROM ${table}_acces WHERE label=$pageid ORDER BY date ASC";
$res = mysql_query($req,$c);
$string = "<?php  \n\$date_data = array(";
$countline=0;
$start = 100000000000000;
$end = $timeserver;
$maxcount= 0;
while($row = mysql_fetch_object($res)) {
	$date = $row->date;
	$count = $row->count;
	$time = strtotime($date);
	if ($time<$start) {$start = $time;}
	if ($count>$maxcount) {$maxcount=$count;}
	if (date("j",$time) == 1) {
		$label = date("M-y",$time);
	} else {
		$label = "";
	}
	if ($countline > 0) {
		$string .= "\n,array(\"$label\",$time,$count)";
	} else {
		$string .= "\narray(\"$label\",$time,$count)";
	}
	$countline += 1;
}
$string .= "\n);\n\$start=$start;\n\$end=$end;\n\$maxY=$maxcount;\n\$plottype=\"thinbarline\";\n\$width = 600;\n\$height = 250;\n\$ylabel = \"".$strings['plot-Pageviews']."\";";
// Pulling uniq IP visits
$req = "SELECT date,count FROM ${table}_uniq WHERE label=$pageid ORDER BY date ASC";
$res = mysql_query($req,$c); 
$countline=0;
$start = 100000000000000;
$end = 0;
$maxcount= 0;
$dataUniq = array();
while($row = mysql_fetch_object($res)) {
	$date = $row->date;
	$count = $row->count;
	$time = strtotime($date);
	if ($time<$start) {$start = $time;}
	if ($time>$end) {$end = $time;}
	if ($count>$maxcount) {$maxcount=$count;}
	$dataUniq[$date] = $count;
}
if ($start < $end) {
	$string .= "\n\$date_data2 = array(";
	for ($t=$start;$t<=$end;$t = strtotime("+1 day", $t)) {
		$date = date("Y-m-d",$t);
		if (!isset($dataUniq[$date])) {
			$count = 0;
		} else {
			$count = $dataUniq[$date];
		}
		if ($countline>0) {
			$string .= "\n,array(\"\",$t,$count)";
		} else {
			$string .= "\narray(\"\",$t,$count)"; 
		}
		$countline += 1;
	}
	$string .= "\n);\n\$twoplots=1;\n\$maxY2=$maxcount;\n\$plottype2=\"lines\";";
	$string .= "\n\$ylabel2 = \"".$strings['plot-Uniquevisitors']."\";";
}
$string .= "\n?>";
$temp = fopen ("$tmpdirectory/tmp.$timecall.txt.php", 'w');
fwrite($temp, $string);
fclose($temp);
echo "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"3\">".$strings['Dailyvisitsto']." $pagename</td></tr>
<tr><td colspan=\"3\" align=\"center\">";
if ($maxcount == 0) {
	echo "&nbsp;<br>".$strings['Nothingyet']."<br>&nbsp;";
} else {
	echo "<img src=\"./plotStat.php?file=tmp.$timecall.txt.php\" alt=\"".$strings['Dailyvisitsto']." $pagename\">";
}
echo "</td></tr>\n";

// get total access today 
$req = "SELECT count FROM ${table}_acces WHERE date='$today' AND label=$pageid";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$ctoday=$row['count'];
if ($ctoday=='') {$ctoday=0;};
$req = "SELECT count FROM ${table}_uniq WHERE date='$today' AND label=$pageid";
$res = mysql_query($req,$c);
$row=mysql_fetch_array($res);
$ctoday2=$row['count'];
if ($ctoday2=='') {$ctoday2=0;};
echo "<tr class=\"data even\"><td>".$strings['Today']." $today</td><td>".pageviews($ctoday)."</td><td>".visitors($ctoday2)."</td></tr>\n";
// Last week
$enddate = $sevendays;
$req = "SELECT SUM(count) as count FROM ${table}_acces WHERE label=$pageid AND date>='$enddate'";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$week = $count['count'];
if ($week == '') $week=0;
$avweek = ceil($week*86400/(time()-strtotime("-1 week")));
$req = "SELECT SUM(count) as count FROM ${table}_uniq WHERE label=$pageid AND date>='$enddate'";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$week2 = $count['count'];
if ($week2 == '') $week2=0;
$avweek2 = ceil($week2/7.);
echo "<tr class=\"data odd\"><td>".$strings['Lastweek']."</td><td>".pageviewsperday($avweek)."</td><td>".visitorsperday($avweek2)."</td></tr>\n";
// Last month
$enddate = $onemonth;
$req = "SELECT SUM(count) as count FROM ${table}_acces WHERE label=$pageid AND date>='$enddate'";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$month = $count['count'];
if ($month == '') $month=0;
$avmonth = ceil($month/30.);
$req = "SELECT SUM(count) as count FROM ${table}_uniq WHERE label=$pageid AND date>='$enddate'";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$month2 = $count['count'];
if ($month2 == '') $month2=0;
$avmonth2 = ceil($month2/30.);
echo "<tr class=\"data even\"><td>".$strings['Last30days']."</td><td>".pageviewsperday($avmonth)."</td><td>".visitorsperday($avmonth2)."</td></tr>\n";
// Since begining
$req = "SELECT (ref+se+internal+other+old) as count FROM ${table}_pages WHERE id=$pageid";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$total = $count['count'];
if ($total=='') $total=0;
$req = "SELECT added FROM ${table}_pages WHERE id=$pageid";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$first = strtotime($count['added']);
$av = ceil($total*86400/($timeserver-$first+86400));
$req = "SELECT SUM(count) as count FROM ${table}_uniq WHERE label=$pageid";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
$total2 = $count['count'];
if ($total2=='') $total2=0;
$req = "SELECT date FROM ${table}_uniq WHERE label=$pageid ORDER BY date ASC LIMIT 0,1";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
if ($count['date'] != "") {
	$first2 = strtotime($count['date']);
} else {
	$first2 = strtotime(0);
}
$av2 = ceil($total2*86400/($timeserver-$first2+86400));
echo "<tr class=\"data odd\"><td>".$strings['Sincebeginingofrecord']."</td><td>".pageviewsperday($av)."</td><td>".visitorsperday($av2)."</td></tr>\n";
echo "<tr class=\"data even\"><td colspan=\"2\">".$strings['Total']."</td><td>".hits($total)."&nbsp;</td></tr>\n";
// Visits from search engines, in table_pages we have some idea for pages created after version 1.2, it is the exact number. For the others, we need a correction
$req = "SELECT ref,se,internal,other FROM ${table}_pages WHERE id=$pageid";
$res = mysql_query($req,$c);
$row =mysql_fetch_object($res);
$fromRef = $row->ref;
$fromSE = $row->se;
$fromInternal = $row->internal;
$fromOther = $row->other;
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
$fromSEpc = number_format(100.0*$fromSECorrected/$total,0);
$fromRefpc = number_format(100.0*$fromRefCorrected/$total,0);
$fromInternalpc = number_format(100.0*$fromInternalCorrected/$total,0);
$fromOtherpc = number_format(100.0*$fromOtherCorrected/$total,0);
echo "<tr class=\"data odd\"><td colspan=\"2\">".$strings['Visitsengines']."</td><td>".format_float($fromSECorrected)." ($fromSEpc %)</td></tr>\n";
echo "<tr class=\"data even\"><td colspan=\"2\">".$strings['Visitsreferrers']."</td><td>".format_float($fromRefCorrected)." ($fromRefpc %)</td></tr>\n";
echo "<tr class=\"data odd\"><td colspan=\"2\">".$strings['Visitswithinwebsite']."</td><td>".format_float($fromInternalCorrected)." ($fromInternalpc %)</td></tr>\n";
echo "<tr class=\"data even\"><td colspan=\"2\">".$strings['Othervisits']."</td><td>".format_float($fromOtherCorrected )." ($fromOtherpc %)</td></tr>\n";
// busiest day
$req = "SELECT date,count FROM ${table}_acces WHERE label=$pageid ORDER BY count DESC LIMIT 0,1;";
$res = mysql_query($req,$c);
$i=0;
$row = mysql_fetch_array($res);
if (isset($row['date'])) {
	$bestday = dayMmYear(strtotime($row['date']));
	$bestcount = $row['count'];
	echo "<tr class=\"data odd\"><td colspan=\"3\">".$strings['Busyday']." $bestday (".pageviews($bestcount).")</td></tr>\n";
} else {
	$bestday = $strings["Nothingyet"];
	echo "<tr class=\"data odd\"><td colspan=\"3\">".$strings['Busyday']." $bestday</td></tr>\n";
}
// Most boring day
$req = "SELECT date,count FROM ${table}_acces WHERE label=$pageid  ORDER BY count ASC LIMIT 0,1;";
$res = mysql_query($req,$c);
$row = mysql_fetch_array($res);
if (isset($row['date'])) {
	$bestday = dayMmYear(strtotime($row['date']));
	$bestcount = $row['count'];
	echo "<tr class=\"data even\"><td colspan=\"3\">".$strings['Quietday']." $bestday (".pageviews($bestcount).")</td></tr>\n";
} else {
	$bestday = $strings["Nothingyet"];
	echo "<tr class=\"data even\"><td colspan=\"3\">".$strings['Quietday']." $bestday</td></tr>\n";
}
echo "</table>\n";

// Access to this page
echo "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"4\">".$strings['Accessto']." $pagename</td></tr>\n";
if (($total > 0) && ($fromSE+$fromRef+$fromInternal+$fromOther > 0)) {
	if (!isset($kwds)) $kwds = 0;
	if (!isset($refs)) $refs = 0;
	$rest = $total-$kwds-$refs;
	$datastr = "";
	$legendArr = array();
	if ($fromSE > 0) { 
		$datastr .= ",$fromSE";
		$legendArr[] = "\"".$strings['plot-Searchengines']."\"";
	}
	if ($fromRef > 0) { 
		$datastr .= ",$fromRef";
		$legendArr[] = "\"".$strings['plot-Referrers']."\"";
	}
	if ($fromInternal > 0) { 
		$datastr .= ",$fromInternal";
		$legendArr[] = "\"".$strings['plot-Internal']."\"";
	}
	if ($fromOther > 0) { 
		$datastr .= ",$fromOther";
		$legendArr[] = "\"".$strings['plot-Other']."\"";
	}
	$legendStr = implode(",",$legendArr);
	$string = "<?php  \n\$date_data = array(array(\"\"$datastr));\n\$plottype=\"pie\";\n\$legende = array($legendStr);\n\$width = 500;\n\$height = 200;\n?>";
	$temp = fopen ("$tmpdirectory/tmp2.$timecall.txt.php", 'w');
	fwrite($temp,$string);
	fclose($temp);
	echo "<tr><td colspan=\"4\" align=\"center\"><img src=\"./plotStatPie.php?file=tmp2.$timecall.txt.php\" width=\"500\" height=\"200\" alt=\"".$strings['Accessto']." $pagename\"></td></tr>\n";
}
$req = "SELECT engine,keyword,SUM(count) as c FROM ${table}_keyword WHERE page=$pageid GROUP BY LOWER(keyword) ORDER BY c DESC LIMIT 0,$ntop;";
$res = mysql_query($req,$c);
$n = 0;
$key = array();
while ($row = mysql_fetch_object($res)) {
	$key[$n] = mb_strtolower(htmlentities ($row->keyword, ENT_NOQUOTES, 'UTF-8'), 'UTF-8');
	$engine[$n] = $row->engine;
	$count[$n] = $row->c;
	 $n += 1;
}
$req = "SELECT address,count as count FROM ${table}_referrer WHERE page=$pageid ORDER BY count DESC LIMIT 0,$ntop;";
$res = mysql_query($req,$c);
$n=0;
$ref=array();
while ($row = mysql_fetch_object($res)) {
	$ref[$n] = $row->address;
	$countR[$n] = $row->count;
	$n += 1;
}  
echo "<tr class=\"caption\"><td colspan=\"2\" class=\"btop\" width=\"50%\">".$strings['Keywords']."</td><td class=\"bleft btop\" colspan=\"2\">".$strings['Referrers']."</td></tr>\n";
for ($n=0;$n<$ntop;$n++) {
	if (array_key_exists($n,$key) || array_key_exists($n,$ref)) {
		if (($n % 2) == 0) { $even = "odd";} else {$even="even";}
		echo "<tr class=\"data $even\">";
		if (array_key_exists($n,$key)) {
			$txt = shortenPage("$engine[$n]: $key[$n]",1);
			echo "<td>$txt</td><td>".hits($count[$n])."</td>";
		} else {
			echo "<td>&nbsp;</td><td>&nbsp;</td>";
		}
		if (array_key_exists($n,$ref)) {
			$refC = cleanURL($ref[$n]);
			$refS = shortenURL($refC,1);
			if (!isset($extra)) $extra="";
			echo "<td class=\"bleft\"><a href=\"$refC\" target=_new>$refS$extra</a></td><td>".hits($countR[$n])."</td>";
		} else {
			echo "<td class=\"bleft\">&nbsp;</td><td>&nbsp;</td>";
		}
		echo "</tr>\n";
	}
}
echo "<tr><td colspan=\"2\" align=\"center\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;pageid=$pageid&amp;lang=$lang\" title=\"".$strings['Searchenginestatsfor']." $pagename\">".$strings['Fulllist']."</a></td><td colspan=\"2\" align=\"center\" class=\"bleft\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=ref&amp;start=1&amp;sort=hits&amp;pageid=$pageid&amp;lang=$lang\" title=\"".$strings['Referrerstatsfor']." $pagename\">".$strings['Fulllist']."</a></td></tr>\n";

// Path analysis, what was before, what was after

$str = "<tr class=\"caption\"><td colspan=\"2\" class=\"btop\">".$strings['Beforethispage']."</td><td colspan=\"2\" class=\"btop bleft\">".$strings['Afterthispage']."</td></tr>\n";

// Total number of path and visits that match the desired one
$pathidstr = "|$pageid|";
$length = strlen($pathidstr);
$sql = "SELECT SUM(count) as count, COUNT(id) as diff FROM ${table}_path WHERE INSTR(path,'$pathidstr')>0";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$totalpathpage = $row->diff;
$totalvisitspage = $row->count;
// Got to find how many start with this path
$sql = "SELECT SUM(count) as count, COUNT(id) as diff FROM ${table}_path WHERE INSTR(path,'$pathidstr')=1";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$totalpathstart = $row->diff;
$totalvisitsstart = $row->count;
// Got to find the path that finish this way...
$sql = "SELECT SUM(count) as count, COUNT(id) as diff FROM ${table}_path WHERE RIGHT(path,$length)='$pathidstr'";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$totalpathend = $row->diff;
$totalvisitsend = $row->count;

// Getting what is before the path, in general...
if (!isset($extrasql)) $extrasql="";
$sql = "SELECT SUBSTRING_INDEX(SUBSTRING(path,1,INSTR(path,'$pathidstr')-1),'|',-1) as previous, SUM(count) as total FROM ${table}_path WHERE INSTR(path,'$pathidstr')>1 $extrasql GROUP BY previous ORDER BY total DESC LIMIT 0,7";
$res = mysql_query($sql,$c);
$nafter = 0;
while($row = mysql_fetch_object($res)) {
	$previous = $row->previous;
	$thistotal = $row->total;
	$pcAfter[] = 100.0*$thistotal/$totalvisitspage;
	$pagenameAfter[] = shortenPage(pagename($c,$table,$previous),1);
	$nafter += 1;
}
// Getting what was after
$sql = "SELECT SUBSTRING_INDEX(SUBSTRING(path,INSTR(path,'$pathidstr')+$length),'|',1) as next, SUM(count) as total FROM ${table}_path WHERE INSTR(path,'$pathidstr')>0 AND RIGHT(path,$length) != '$pathidstr' GROUP BY next ORDER BY total DESC LIMIT 0,7";
$res = mysql_query($sql,$c);
$nbefore = 0;
while($row = mysql_fetch_object($res)) {
	$next = $row->next;
	$thistotal = $row->total;
	$pcBefore[] = 100.0*$thistotal/$totalvisitspage;
	$pagenameBefore[] = shortenPage(pagename($c,$table,$next),1);
	$nbefore += 1;
}
// Preparing the table
$max = max($nbefore, $nafter);
if ($max > 0) {
	$pcstart = number_format(100.0*$totalvisitsstart/$totalvisitspage,1);
	$pcend = number_format(100.0*$totalvisitsend/$totalvisitspage,1);
	$str .= "<tr class=\"data odd\"><td>".$strings['Outsideworld']."</td><td>$pcstart %</td><td class=\"bleft\">".$strings['Outsideworld']."</td><td>$pcend %</tr>";
	for ($i=0; $i<$max; $i+=1) {
		if (($i % 2) == 0) { $even = "even";} else {$even="odd";}
		if ($i<$nafter) {
			$str .= "<tr class=\"data $even\"><td>".$pagenameAfter[$i]."</td><td>".format_float($pcAfter[$i],1)." %</td>";
		} else {
			$str .= "<tr class=\"data $even\"><td>&nbsp;</td><td>&nbsp;</td>";
		}
		if ($i<$nbefore) {
			$str .= "<td class=\"bleft\">".$pagenameBefore[$i]."</td><td>".format_float($pcBefore[$i],1)." %</td></tr>\n";
		} else {
			$str .= "<td class=\"bleft\">&nbsp;</td><td>&nbsp;</td></tr>\n";
		}
		
	}
	$str .= "<tr><td colspan=\"4\" align=\"center\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;pathid=$pageid&amp;lang=$lang\">".$strings['Pathanalysisfor']." $pagename</a></td></tr>";
} else {
	$str .= "<tr><td colspan=\"4\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n";
}
echo $str;

echo "</table>\n";
}

/*********************************************************************************/
/* Function: echoPageList
/* Role: list pages in different sort orders
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $sort: how do you want to sort it?
/*   - $order: 'asc' or 'desc'
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 09/2005
/* 11/2006: removed database connection function (moved earlier in the code)
/*********************************************************************************/
function echoPageList($c,$table, $site, $sid, $sort, $order) {
global $strings;
global $lang;
global $sites;
$ntop = 10;

$txtPage = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;lang=$lang&amp;sort=name", $strings['Sortby']." ".$strings['Name'], '');
$txtAge = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;sort=age&amp;lang=$lang", $strings['Sortby']." ".$strings['Ndays'], '');
$txtHits = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;sort=hits&amp;lang=$lang", $strings['Sortby']." ".$strings['Nhits'], '');
$txtAverage = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;sort=av&amp;lang=$lang", $strings['Sortby']." ".$strings['Average'], '');
$txtMagnet = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;sort=magnet&amp;lang=$lang", $strings['Sortby']." ".$strings['Magnetindex'], '');
$txtBounce = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;sort=bounce&amp;lang=$lang", $strings['Sortby']." ".$strings['Bouncerate'], '');

echo "<table class=\"stat\">
<tr class=\"caption\"><td class=\"small\">".$strings['Page']."</td><td class=\"small\" width=\"8%\">".$strings['Ndays']."</td><td class=\"small\" width=\"8%\">".$strings['Nhits']."</td><td class=\"small\" width=\"8%\">".$strings['Average']."</td><td class=\"small\" width=\"8%\">".$strings['Magnetindex']."<a href=\"javascript:help('magnetindex')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"small\" width=\"8%\">".$strings['Bouncerate']."<a href=\"javascript:help('bouncerate')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td></tr>
<tr class=\"caption\"><td class=\"small\">$txtPage</td><td class=\"small\" width=\"8%\">$txtAge</td><td class=\"small\" width=\"8%\">$txtHits</td><td class=\"small\" width=\"8%\">$txtAverage</td><td class=\"small\" width=\"8%\">$txtMagnet</td><td class=\"small\" width=\"8%\">$txtBounce</td></tr>\n";

$sql = "SELECT id,name,added FROM ${table}_pages ORDER BY name ASC";
$res = mysql_query($sql,$c);
$npages=0;
$timeserver = time()+3600*$sites[$sid]['timediff'];
while($row = mysql_fetch_object($res)) {
	$pagename[$row->id] = shortenPage($row->name,3);
	$pageid[$row->id] = $row->id;
	// Age
	$age[$row->id] = ceil(($timeserver-strtotime($row->added))/(86400));
	// Total number of access
	$req2 = "SELECT ref+se+internal+other+old as count FROM ${table}_pages WHERE id=$row->id;";
	$res2 = mysql_query($req2,$c);
	$count=mysql_fetch_array($res2);
	$total[$row->id] = $count['count'];
	if ($total[$row->id]=='') $total[$row->id]=0;
	// Average
	$average[$row->id] = $total[$row->id]/$age[$row->id];
	//
	$npages += 1;
}

// Pulling out number of uniq visitors to each page
$req = "SELECT label, SUM(count) as count, MIN(date) as start FROM ${table}_uniq group by label";
$res = mysql_query($req,$c);
while($row = mysql_fetch_object($res)) {
	$uniq[$row->label] = $row->count;
	// $ageuniq[$row->label] = ($timeserver-strtotime($row->start))/86400;
	// if ($ageuniq[$row->label] == "") $ageuniq[$row->label]=0;
	if ($uniq[$row->label] == "") $uniq[$row->label]=0;
	//echo $row->label.": ".$ageuniq[$row->label]."<br>";
}

// Pulling out path information for the page (Magnet and bounce index)
// Total number of hits in path database (will be used for normalization)
$sql = "SELECT SUM(length*count) as count, count(*) as npath FROM ${table}_path";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$totalHits = $row->count;
$npath = $row->npath;
// Magnet pages
$sql = "SELECT entry, SUM(length*count) as magnet, SUM(count) as entryfactor FROM ${table}_path GROUP BY entry";
$res = mysql_query($sql,$c);
while($row = mysql_fetch_object($res)) {
	if (!isset($age[$row->entry])) $age[$row->entry]=0;
	if ($age[$row->entry] == 0) {
		$magnet[$row->entry] = 0;
	} else {
		$magnet[$row->entry] = 1.*log(1+$row->magnet/$age[$row->entry],10);
	}
	$entryfactor[$row->entry] = $row->entryfactor;
}
// Bounce effect
$sql = "SELECT entry, SUM(count) as count FROM ${table}_path WHERE length=1 GROUP BY entry";
$res = mysql_query($sql,$c);
while($row = mysql_fetch_object($res)) {
	$entryonehit[$row->entry] = $row->count;
}
$sql = "SELECT entry, SUM(count) as count FROM ${table}_path WHERE length>1 GROUP BY entry";
$res = mysql_query($sql,$c);
while($row = mysql_fetch_object($res)) {
	$entrymorehit[$row->entry] = $row->count;
}
foreach ($pageid as $id) {
	if (!isset($entryonehit[$id])) $entryonehit[$id] = 0;
	if (!isset($entrymorehit[$id])) $entrymorehit[$id] = 0;
	$sum = 0 + $entryonehit[$id] +  $entrymorehit[$id];
	if ($sum == 0) {
		$bounce[$id] = 0;
	} else {
		$bounce[$id] = 100.*$entryonehit[$id]/$sum;
	}
}


if ($npages > 0) {
	if ($sort == "age") {
		$keys = $age;
	} else if ($sort == "hits") {
		$keys = $total;
	} else if ($sort == "av") {
		$keys = $average;
	} else if ($sort == "bounce") {
		$keys = $bounce;
	} else if ($sort == "magnet") {
		$keys = $magnet;
	} else {
		$keys = $pagename;
	}
	if ($order=="asc") {
		arsort($keys);
	} else {
		asort($keys);
	}
	$n = 0;
	while (list($key, $val) = each($keys)) {
		if ($key != "") {
			$n += 1;
			$av = format_float($average[$key],2);
			if (isset($magnet[$key])) {
				$magnets = format_float($magnet[$key],1);
			} else {
				$magnets = format_float(0,1);
			}
			$bounces = format_float($bounce[$key],0);
			if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
			echo "<tr class=\"small data $even\"><td>".linksforpage ($sid, $pagename[$key], $pageid[$key])."</td>
<td align=\"center\">".format_float($age[$key])."</td><td align=\"center\">".format_float($total[$key])."</td><td align=\"center\">$av</td><td align=\"center\">$magnets</td><td align=\"center\">$bounces</td></tr>\n"; 
		}
	}
} else {
	echo "<tr><td align=\"center\" colspan=\"8\">".$strings['Nothingyet']."</td></tr>\n";
}
echo "</table>\n";
}
?>
