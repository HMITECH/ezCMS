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

// Functions for full path analysis: entry pages, exit pages, path...
// Everything except visitor retention, visit duration, and path on latest visitors

/***********************************************************************
/* Function: entryExit
/* Role: prepares a table with entry, exit, and magnet pages
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $site
/*   - $sid
/*   - $sort: can be "path" or "visits" to sort by number of path or number of visits
/* Output:
/*   - Echos whatever it finds
/* Created: 11/2005
/* 11/2006: removed database connection function (moved earlier in the code)
************************************************************************/
function entryExit($c, $table, $site, $sid,$sort) {
global $strings;
global $lang;
$ntop = 10;

$sql = "SELECT SUM(count) as count, COUNT(id) as diff FROM ${table}_path";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$totalpath = $row->diff;
$totalvisits = $row->count;

if ($totalpath > 0) {
	if ($sort == "path") {
		$order = "diff";
	} else {
		$order = "count";
	}
	// Entry pages
	$str = "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"3\">".$strings['Entrypages']."</td></tr><tr class=\"caption\"><td>".$strings['Page']."</td><td width=\"20%\">".$strings['Npaths']."</td><td width=\"20%\">".$strings['Count']."</td></tr>\n";
	$sql = "SELECT entry,SUM(count) as count, COUNT(id) as diff FROM ${table}_path GROUP BY entry ORDER BY $order DESC LIMIT 0,$ntop;";
	$res = mysql_query($sql,$c);
	$n = 0;
	while($row = mysql_fetch_object($res)) {
		$n += 1;
		$pageid = $row->entry;
		$count = $row->count;
		$npath = $row->diff;
		$pcpath = number_format(100.0*$npath/$totalpath,0);
		$pcvisits = number_format(100.0*$count/$totalvisits,0);
		$pagename = shortenPage(pagename($c,$table,$pageid));
		if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
		$str .= "<tr class=\"data $even\"><td>".linksforpage($sid, $pagename, $pageid)."</td><td>".sprintf($strings["pcOfPaths"],$pcpath)."</td><td>".sprintf($strings["pcOfVisits"],$pcvisits)."</td></tr>\n";
	}
	$str .= "</table>\n";
	echo $str;
	// Exit pages
	$str = "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"3\">".$strings['Exitpages']."</td></tr><tr class=\"caption\"><td>".$strings['Page']."</td><td width=\"20%\">".$strings['Npaths']."</td><td width=\"20%\">".$strings['Count']."</td></tr>\n";
	$sql = "SELECT ${table}_path.exit,SUM(count) as count, COUNT(id) as diff FROM ${table}_path GROUP BY ${table}_path.exit ORDER BY $order DESC LIMIT 0,$ntop;";
	$res = mysql_query($sql,$c);
	$n = 0;
	while($row = mysql_fetch_object($res)) {
		$n += 1;
		$pageid = $row->exit;
		$npath = $row->diff;
		$count = $row->count;
		$pagename = shortenPage(pagename($c,$table,$pageid));
		$pcpath = number_format(100.0*$npath/$totalpath,0);
		$pcvisits = number_format(100.0*$count/$totalvisits,0);
		if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
		$str .= "<tr class=\"data $even\"><td>".linksforpage($sid, $pagename, $pageid)."</td><td>".sprintf($strings["pcOfPaths"],$pcpath)."</td><td>".sprintf($strings["pcOfVisits"],$pcvisits)."</td></tr>\n";
	}
	$str .= "</table>\n";
	echo $str;
	// Magnet pages 
	$str = "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"3\">".$strings['Magnetpages']."</td></tr>\n<tr class=\"caption\"><td>".$strings['Page']."</td><td width=\"20%\">".$strings['Nvisits']."</td><td width=\"20%\">".$strings['Efficiency']."</td></tr>\n";
	$sql = "SELECT entry,SUM(count) as count, SUM(length*count) as eff,SUM(length*count)/SUM(count) as av FROM ${table}_path GROUP BY entry ORDER BY eff DESC LIMIT 0,$ntop;";
	$res = mysql_query($sql,$c);
	$n = 0;
	while($row = mysql_fetch_object($res)) {
		$n += 1;
		$pageid = $row->entry;
		$av = $row->av;
		$count = $row->count;
		$bigtotal = $row->eff;
		$pagename = shortenPage(pagename($c,$table,$pageid));
		if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
		$str .= "<tr class=\"data $even\"><td>".linksforpage($sid, $pagename, $pageid)."</td><td>".visits($count)."</td><td>".clickspervisits($av)."</td></tr>\n";
	}
	$str .= "</table>\n";
	echo $str;
} else {
	$str = "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"3\">".$strings['Entrypages']."</td></tr><tr class=\"caption\"><td>".$strings['Page']."</td><td>".$strings['Npaths']."</td><td>".$strings['Count']."</td></tr>\n<tr><td colspan=\"3\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n</table>\n";
	$str .= "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"3\">".$strings['Exitpages']."</td></tr><tr class=\"caption\"><td>".$strings['Page']."</td><td>".$strings['Npaths']."</td><td>".$strings['Count']."</td></tr>\n<tr><td colspan=\"3\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n</table>\n";
	$str .= "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"3\">".$strings['Magnetpages']."</td></tr>\n<tr class=\"caption\"><td>".$strings['Entrypage']."</td><td>".$strings['Nvisits']."</td><td>".$strings['Efficiency']."</td></tr>\n<tr><td colspan=\"3\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n</table>\n";
	echo $str;
}
}

/***********************************************************************
/* Function pathDesigner
/* Role: for given path, finds out where visitors came from, and where
/*       they went
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $site
/*   - $sid
/*   - $pathid: the path, with each element separated by a |, eg, |43|32|32|,
/*      number 0 indicate a entry (an exit) from (to) the outside world
/* Output:
/*   - Echos whatever it finds
/* Created: 02/2005
/* 11/2006: removed database connection function (moved earlier in the code)
***********************************************************************/
function pathDesigner($c,$table, $site, $sid, $pathid) {
global $strings;
global $lang;

//
// Cleaning up the path, check if we want to path to start or finish here
//
$wantfirst = FALSE;
$wantlast = FALSE;
$patharray = explode("|",$pathid);
if ($patharray[0] == 0) {
	$wantfirst = TRUE;
	array_shift($patharray);
}
if ($patharray[count($patharray)-1] == 0) {
	$wantlast = TRUE;
	array_pop($patharray);
}
$pathidstr = "|".implode("|",$patharray)."|";
$length = strlen($pathidstr);
$extrasql = "";
if ($wantfirst) $extrasql .= " AND INSTR(path,'$pathidstr')=1 ";
if ($wantlast) $extrasql .= " AND RIGHT(path,$length)='$pathidstr' ";

//
// Main properties: number of path and this kind of things
// Total number of path and visits in database
//
$sql = "SELECT SUM(count) as visits, COUNT(id) as paths FROM ${table}_path";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$totalpath = $row->paths;
$totalvisits = $row->visits;
// Total number of path and visits that match the desired one
$sql = "SELECT SUM(count) as count, COUNT(id) as diff FROM ${table}_path WHERE INSTR(path,'$pathidstr')>0 $extrasql";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$totalpathpage = $row->diff;
$totalvisitspage = $row->count;
// Got to find how many start with this path
$sql = "SELECT SUM(count) as count, COUNT(id) as diff FROM ${table}_path WHERE INSTR(path,'$pathidstr')=1 $extrasql";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$totalpathstart = $row->diff;
$totalvisitsstart = $row->count;
// Got to find the path that finish this way...
$sql = "SELECT SUM(count) as count, COUNT(id) as diff FROM ${table}_path WHERE RIGHT(path,$length)='$pathidstr' $extrasql";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$totalpathend = $row->diff;
$totalvisitsend = $row->count;

//
// If  there was no path recorded, we stop here with a little word...
//
if ($totalvisitspage < 1) {
	$pagename = shortenPage(pagename($c,$table,$pageid));
	$str = "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"2\">Path analysis</td></tr>\n<tr class=\"pathdesign\"><td>&nbsp;<br>".$strings['Nopaththroughthepage']." $pagename ....<br>&nbsp;</td></tr>\n</table>";
	echo $str;
	return;
}

//
// Starting the table
//
$pc = format_float(100.0*$totalvisitspage/$totalvisits,1);
$str = "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"3\">".$strings['Pathanalysis']."</td></tr>
<tr class=\"pathdesign\"><td colspan=\"2\">&nbsp;<br>".$strings['Numberofvisitsanalysed'].": ".format_float($totalvisits)."<br>".$strings['Visitorswhousedapathlikethis'].": ".format_float($totalvisitspage)." ($pc%)<br>&nbsp;</td><td colspan=\"1\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=studypath&amp;pathid=$pathid&amp;lang=$lang\" class=\"basic\">".$strings['Studyvisitorsflowalongthispath']."</a></td></tr>\n<tr class=\"pathdesign\" ><td align=\"left\">";
if (!$wantfirst) $str .= "<div class=\"leftpath\">";

//
// Entry from outside world
//
$pc = format_float(100.0*$totalvisitsstart/$totalvisitspage,1);
$total = $totalvisitsstart;
$str .= "<table><tr><td colspan=\"2\"><b>".$strings['Outsideworld']."</b><br> ".visitors($totalvisitsstart)." ($pc%)</td></tr><tr><td colspan=\"2\">";
if ($totalvisitsstart > 0) {
	$str .= "<b>".$strings['Keywords']."</b>: ";
	$req = "SELECT keyword FROM ${table}_keyword WHERE page=".$patharray[0]." GROUP BY keyword ORDER BY count DESC LIMIT 0,5;";
	$res = mysql_query($req,$c);
	while ($row = mysql_fetch_object($res)) {
		$key = $row->keyword;
		$str .=" $key, ";
	}
	$str .= "...<br><b>".$strings['Referrers']."</b>: ";
	$req = "SELECT address,count as count FROM ${table}_referrer WHERE page=".$patharray[0]." ORDER BY count DESC LIMIT 0,5;";
	$res = mysql_query($req,$c);
	while ($row = mysql_fetch_object($res)) {
		$str .= urlLink($row->address,0,"class=\"basic\"").", ";
	}
	$str .= "...";
} 
$str .= "</td></tr>";
$link0 = "<a href=\"index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;lang=$lang&amp;pathid=".$patharray[0]."\" class=\"basic\">".$strings['Moreinfo']."</a>";
$link1 = "";
if ((!$wantfirst) &&  ($totalvisitsstart>0)) $link1 = "<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;lang=$lang&amp;pathid=0|$pathid\" class=\"basic\">".$strings['Addtopath']."</a>";
$str .= "<tr><td>$link0</td><td>$link1</td></tr></table>\n";

//
// Getting what is before the path, in general...
//
$sql = "SELECT SUBSTRING_INDEX(SUBSTRING(path,1,INSTR(path,'$pathidstr')-1),'|',-1) as previous, SUM(count) as total FROM ${table}_path WHERE INSTR(path,'$pathidstr')>1 $extrasql GROUP BY previous ORDER BY total DESC LIMIT 0,7";
$res = mysql_query($sql,$c);
while($row = mysql_fetch_object($res)) {
	$previous = $row->previous;
	$thistotal = $row->total;
	$total += $thistotal;
	$pc = format_float(100.0*$thistotal/$totalvisitspage,1);
	$pagename = shortenPage(pagename($c,$table,$previous),1);
	$link1 = "<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;lang=$lang&amp;pathid=$previous|$pathid\" class=\"basic\">".$strings['Addtopath']."</a>";
	$link2 = "<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;lang=$lang&amp;pathid=$previous\" class=\"basic\">".$strings['Centerhere']."</a>";
	$str .= "<table><tr><td colspan=\"2\">$pagename<br>".visitors($thistotal)." ($pc%)</td></tr><tr><td>$link1</td><td>$link2</td></tr></table>\n";
}
if (!$wantfirst) {
	$rest = $totalvisitspage - $total;
	$pc = format_float(100.0*$rest/$totalvisitspage,1);
	$str .= "<table><tr><td><b>".$strings['Other']."</b><br>".visitors($rest)." ($pc%)</td></tr></table></div>\n</td>\n";
} else {
	$str .= "</td>\n";
}
$str .= "<td align=\"center\">";

//
// Putting the path itself
//
foreach ($patharray as $page) {
	$pagename = shortenPage(pagename($c,$table,$page),2);
	$link2 = "<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;pathid=$page&amp;lang=$lang\" class=\"basic\">".$strings['Centerhere']."</a>";
	$str .= "<div class=\"postit\">$pagename<br>$link2</div>";
}
if (!$wantlast) {
	$str .= "</td>\n<td align=\"right\"><div class=\"rightpath\">";
} else {
	$str .= "</td>\n<td align=\"right\">";
}

//
// Exit to outside world
//
$pc = format_float(100.0*$totalvisitsend/$totalvisitspage,1);
$total = $totalvisitsend;
if ($wantlast) {
$str .= "<table><tr><td><b>".$strings['Outsideworld']."</b><br> $totalvisitsend ".$strings['visitors']."</td></tr></table>\n";
} else {
	if ($totalvisitsend > 0) {
		$link1 = "<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;lang=$lang&amp;pathid=$pathid|0\" class=\"basic\">".$strings['Addtopath']."</a>";
	} else {
		$link1 = "";
	}
	$str .= "<table><tr><td><b>".$strings['Outsideworld']."</b><br>".visitors($totalvisitsend)." ($pc%)<br>$link1</td></tr></table>\n";
}

//
// Getting what was after
//
$sql = "SELECT SUBSTRING_INDEX(SUBSTRING(path,INSTR(path,'$pathidstr')+$length),'|',1) as next, SUM(count) as total FROM ${table}_path WHERE INSTR(path,'$pathidstr')>0 AND RIGHT(path,$length) != '$pathidstr' $extrasql GROUP BY next ORDER BY total DESC LIMIT 0,7";
$res = mysql_query($sql,$c);
while($row = mysql_fetch_object($res)) {
	$next = $row->next;
	$thistotal = $row->total;
	$total += $thistotal;
	$pc = format_float(100.0*$thistotal/$totalvisitspage,1);
	$pagename = shortenPage(pagename($c,$table,$next),1);
	$link1 = "<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;lang=$lang&amp;pathid=$pathid|$next\" class=\"basic\">".$strings['Addtopath']."</a>";
	$link2 = "<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;lang=$lang&amp;pathid=$next\" class=\"basic\">".$strings['Centerhere']."</a>";
	$str .= "<table><tr><td colspan=\"2\">$pagename<br>".visitors($thistotal)." ($pc%)</td></tr><tr><td>$link1</td><td>$link2</td></tr></table>\n";
}
if (!$wantlast) {
	$rest = $totalvisitspage - $total;
	$pc = format_float(100.0*$rest/$totalvisitspage,1);
	$str .= "<table><tr><td><b>".$strings['Other']."</b><br>".visitors($rest)." ($pc%)</td></tr></table></div>\n";
}
$str .= "</td></tr>\n</table>\n";
echo $str;
}

/***********************************************************************
/* Function studyPath
/* Role: studies the visitors flow along a given path
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $site
/*   - $sid
/*   - $pathid: the path, with each element separated by a |, eg, |43|32|32|,
/*      number 0 indicate a entry (an exit) from (to) the outside world
/* Output:
/*   - Echos whatever it finds
/* Created: 11/2005
/* 11/2006: removed database connection function (moved earlier in the code)
************************************************************************/
function studyPath($c, $table, $site, $sid, $pathid) {
global $strings;
// Arrow images properties
$widthTD = 250;
$heightTD = 30;
$widthLR = 30;
$heightLR = 150;
//
$wantfirst = FALSE;
$wantlast = FALSE;
$patharray = explode("|",$pathid);
if ($patharray[0] == 0) {
	$wantfirst = TRUE;
	array_shift($patharray);
}
if ($patharray[count($patharray)-1] == 0) {
	$wantlast = TRUE;
	array_pop($patharray);
}
$extrasql = "";
if ($wantfirst) $extrasql .= " AND INSTR(path,'$path')=1 ";
// if ($wantlast) $extrasql .= " AND RIGHT(path,$length)='$pathidstr' ";

$str = "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"6\">".$strings['Visitorsflow']."</td></tr>\n";
reset($patharray);
if ($wantfirst) {
	// Origin of the paths = search engines, referrers...
	// First step: how do we get there, and how many people
	$id = $patharray[0];
	$path = "|$id|";
	// Start here
	$length = strlen($path);
	$sql = "SELECT SUM(count) as count FROM ${table}_path WHERE SUBSTRING(path,1,$length) = '$path'";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$start = $row->count;
	$totalvisits = $start;
	$w1 = max(intval($widthTD * $start / $totalvisits),6);
	$pc1 = format_float($start/$totalvisits*100.,1);
	$str .= "<tr class=\"vflow\"><td colspan=\"2\">&nbsp;</td><td colspan=\"2\"><div  class=\"postit\">".$strings['searchenginesreferrersbookmarks']."<br>".visitors($start)." ($pc1 %)</div></td><td colspan=\"2\">&nbsp;</td></tr>\n<tr class=\"vflow\"><td colspan=\"2\">&nbsp;</td><td align=\"center\" colspan=\"2\"><img src=\"Img/System/arrowD.jpg\" width=\"$w1\" height=\"$heightTD\" alt=\"".visitors($start)."\"></td><td colspan=\"2\">&nbsp;</td></tr>\n";
	// number of visits that stop here
	$length = strlen($path);
	$sql = "SELECT SUM(count) as count FROM ${table}_path WHERE INSTR(path,'$path')>0 and SUBSTRING_INDEX(SUBSTRING(path,INSTR(path,'$path')+$length),'|',1)='' $extrasql";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$stop = $row->count;
	// Number of visits that come here 
	$come = $totalvisits;
	$oldid = $id;
} else {
	// First step: how do we get there, and how many people
	$id = $patharray[0];
	$path = "|$id|";
	// Start here
	$sql = "SELECT SUM(count) as count FROM ${table}_path WHERE INSTR(path,'$path')=1";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$start = $row->count;
	// Started somewhere else
	$sql = "SELECT SUM(count) as count FROM ${table}_path WHERE INSTR(path,'$path')>1";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$elsewhere = $row->count;
	$totalvisits = $start + $elsewhere;
	$w1 = max(intval($widthTD * $start / $totalvisits),6);
	$w2 = max(intval($widthTD * $elsewhere / $totalvisits),6);
	$pc1 = format_float($start/$totalvisits*100.,1);
	$pc2 = format_float($elsewhere/$totalvisits*100.,1);
	$str .= "<tr class=\"vflow\"><td colspan=\"2\">&nbsp;</td><td><div class=\"postit\">".$strings['searchenginesreferrersbookmarks']."<br>".visitors($start)." ($pc1 %)</div></td><td><div class=\"postit\">".$strings['Within']." $site<br>".visitors($elsewhere)." ($pc2 %)</div></td><td colspan=\"2\">&nbsp;</td></tr>\n<tr class=\"vflow\"><td colspan=\"2\">&nbsp;</td><td align=\"center\"><img src=\"Img/System/arrowD.jpg\" width=\"$w1\" height=\"$heightTD\" alt=\"".visitors($start)."\"></td><td align=\"center\"><img src=\"Img/System/arrowD.jpg\" width=\"$w2\" height=\"$heightTD\" alt=\"".visitors($elsewhere)."\"></td><td colspan=\"2\">&nbsp;</td></tr>\n";
	// number of visits that stop here
	$length = strlen($path);
	$sql = "SELECT SUM(count) as count FROM ${table}_path WHERE INSTR(path,'$path')>0 and SUBSTRING_INDEX(SUBSTRING(path,INSTR(path,'$path')+$length),'|',1)='' $extrasql";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$stop = $row->count;
	// Number of visits that come here 
	$come = $totalvisits;
	$oldid = $id;
}
while ($id = next($patharray)) {
	$h1 = max(intval($heightLR * $stop / $totalvisits),6);
	$pc1 = format_float($stop/$totalvisits*100.,1);
	$str .= "<tr class=\"vflow\"><td><div class=\"postit\">".$strings['Endofvisit']."<br>".visitors($stop)." ($pc1 %)</div></td><td valign=\"middle\" align=\"right\"><img src=\"Img/System/arrowL.jpg\" width=\"$widthLR\" height=\"$h1\" alt=\"".visitors($stop)."\"></td>";
	$pagename = shortenPage(pagename($c, $table, $oldid),2);
	$pc1 = format_float($come/$totalvisits*100.,1);
	$str .= "<td colspan=\"2\"><div class=\"postit\">$pagename<br>".visitors($come)." ($pc1 %)</div></td>";
	$comeold = $come;
	$path = "${path}$id|";
	// Number of visits that make it to the next step
	$sql = "SELECT SUM(count) as count FROM ${table}_path WHERE INSTR(path,'$path')>0 $extrasql";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$come = $row->count;
	// Number of visits that went somewhere else
	$elsewhere = $comeold - $stop - $come;
	$h2 = max(intval($heightLR * $elsewhere / $totalvisits),6);
	$pc1 = format_float($elsewhere/$totalvisits*100.,1);
	$str .= "<td valign=\"middle\" align=\"left\"><img src=\"Img/System/arrowR.jpg\" width=\"$widthLR\" height=\"$h2\" alt=\"".visitors($elsewhere)."\"></td><td><div class=\"postit\">".$strings['Somewhereelseon']." $site<br>".visitors($elsewhere)." ($pc1 %)</div></td></tr>\n";
	// number of visits that stop here
	$length = strlen($path);
	$sql = "SELECT SUM(count) as count FROM ${table}_path WHERE INSTR(path,'$path')>0 and SUBSTRING_INDEX(SUBSTRING(path,INSTR(path,'$path')+$length),'|',1)='' $extrasql";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$stop = $row->count;
	//
	$w1 = max(intval($widthTD * $come / $totalvisits),6);
	$str .= "<tr class=\"vflow\"><td colspan=\"2\">&nbsp;</td><td align=\"center\"  colspan=\"2\"><img src=\"Img/System/arrowD.jpg\" width=\"$w1\" height=\"$heightTD\" alt=\"".visitors($come)."\"></td><td colspan=\"2\">&nbsp;</td></tr>\n";
	$oldid = $id;
}
// GOT TO DO THE END OF THE PATH...
$pagename = shortenPage(pagename($c, $table, $oldid),2);
$pc1 = format_float($come/$totalvisits*100.,1);
$str .= "<tr class=\"vflow\"><td colspan=\"2\">&nbsp;</td><td  colspan=\"2\"><div class=\"postit\">$pagename<br>".visitors($come)." ($pc1 %)</div></td><td colspan=\"2\">&nbsp;</td></tr>\n";
$comeold = $come;
$elsewhere = $comeold - $stop;
$w1 = max(intval($widthTD * $stop / $totalvisits),6);
$w2 = max(intval($widthTD * $elsewhere / $totalvisits),6);
$pc1 = format_float($stop/$totalvisits*100.,1);
$pc2 = format_float($elsewhere/$totalvisits*100.,1);
$str .= "<tr class=\"vflow\"><td colspan=\"2\">&nbsp;</td><td align=\"center\"><img src=\"Img/System/arrowD.jpg\" width=\"$w1\" height=\"$heightTD\" alt=\"".visitors($stop)."\"></td><td align=\"center\"><img src=\"Img/System/arrowD.jpg\" width=\"$w2\" height=\"$heightTD\"  alt=\"".visitors($elsewhere)."\"></td><td colspan=\"2\">&nbsp;</td></tr>\n<tr class=\"vflow\"><td colspan=\"2\">&nbsp;</td><td><div class=\"postit\">".$strings['Endofvisit']."<br>".visitors($stop)." ($pc1 %)</div></td><td><div class=\"postit\">".$strings['Stayon']." $site<br>".visitors($elsewhere)." ($pc2 %)</div></td><td colspan=\"2\">&nbsp;</td></tr>\n";
//
$str .= "</table>\n";
echo $str;
}

/***********************************************************************
/* Function mainPath
/* Role: main portal to path analysis
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $site
/*   - $sid
/* Output:
/*   - Echos whatever it finds
/* Created: 02/2006
/* 11/2006: removed database connection function (moved earlier in the code)
************************************************************************/
function mainPath($c,$table, $domain, $sid) {
global $strings;
global $lang;

$str = "<table class=\"stat\">\n<tr class=\"title\"><td colspan=\"2\">".$strings['Visitorbehavior']."</td></tr>\n<tr class=\"caption\"><td colspan=\"2\">".$strings['Maininformation']."</td></tr>\n";
$sql = "SELECT SUM(count) as count, COUNT(id) as diff FROM ${table}_path";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$totalpath = $row->diff;
$totalvisits = $row->count;
$av = 0.0;
if ($totalpath>0) $av = $totalvisits/$totalpath;
$str .= "<tr><td>".$strings['Numberofvisitsanalysed']."</td><td>".format_float($totalvisits)."</td></tr>\n";
$str .= "<tr><td>".$strings['Numberofpath']."</td><td>".format_float($totalpath,0)."</td></tr>\n";
$str .= "<tr><td>".$strings['NvisitsONpaths']."</td><td>".format_float($av,1)."</td></tr>\n";
$sql = "SELECT AVG(length) as av FROM ${table}_path";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$av = $row->av;
$str .= "<tr><td>".$strings['Averagenumberofhitsperpath']."</td><td>".format_float($av,1)."</td></tr>\n";
$sql = "SELECT count, length FROM ${table}_path ORDER BY length DESC LIMIT 0,1";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$maxlength = $row->length;
$maxlengthcount = $row->count;
$times = $strings['time'];
if ($maxlengthcount > 1) $times = $strings['times'];
$str .= "<tr><td colspan=\"2\">".$strings['Longestpath'].": ".hits($maxlength).". ".$strings['Pathusedby']." ".visitors($maxlengthcount).".</td></tr>\n";
$str .= "</table>\n";
echo $str;

$str = "<div align=\"center\"><form name=\"hitselect\" action=\"./index.php?mode=stats&amp;sid=$sid&amp;show=commonpath&amp;lang=$lang\" method=\"post\"><table class=\"form\"><tr><td>".$strings['Showcommonpathwithmorethan']."</td><td><select name=\"nhits\"><option value=\"1\">1</option><option value=\"3\" selected>3</option><option value=\"5\">5</option><option value=\"10\">10</option><option value=\"20\">20</option><option value=\"50\">50</option></select></td><td>".$strings['hits']."</td><td width=\"50px\" align=\"center\"><input type=\"submit\" value=\"".$strings['Ok']."\"></td></tr></table></form></div>\n";
echo $str;

echo formnavpages($c,$table, "none", "./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;lang=$lang", $strings['Showpathgoingthrough'], false);
}

/***********************************************************************
/* Function commonPath
/* Role: show common path with more than a given number of hits
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $site
/*   - $sid
/*   - $nhits
/* Output:
/*   - Echos whatever it finds
/* Created: 02/2006
/* 11/2006: removed database connection function (moved earlier in the code)
************************************************************************/
function commonPath($c,$table, $domain, $sid, $nhits) {
global $strings;
global $lang;

$str = "<table class=\"stat\">\n<tr class=\"title\"><td>".$strings['Commonpathwithmorethan']." ".hits($nhits)."</td></tr>";
$sql = "SELECT path,count FROM ${table}_path WHERE length>=$nhits ORDER BY count desc limit 0,5";
$res = mysql_query($sql,$c);
$countpath = 0;
$css = "";
while($row = mysql_fetch_object($res)) {
	$path = $row->path;
	$count = $row->count;
	$patharray = explode("|",$path);
	array_shift($patharray);
	array_pop($patharray);
	if ($countpath>0) $css = " class=\"btop\"";
	$str .= "<tr class=\"caption\"><td $css>".$strings['Pathusedby']." ".visitors($count)."</td></tr>\n";
	$str .= "<tr class=\"pathdesign\"><td>";
	$countpath +=1;
	$old = 0;
	$ntmp = 0;
	foreach ($patharray as $page) {
		if ($old != $page) {
			if ($old != 0) {
				$textn = "";
				if ($ntmp>1) $textn = " (".hits($ntmp).")" ;
				$pagename = shortenPage(pagename($c,$table,$old));
				$link = "<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;lang=$lang&amp;pathid=$page\" class=\"basic\">".$strings['Centerhere']."</a>";
				$str .= "<div class=\"postit\">$pagename$textn<br><div align=\"center\">$link</div></div>";
			}
			$ntmp = 0;
			$old = $page;
		} 
		$ntmp += 1;
	}
	$textn = "";
	if ($ntmp>1) $textn = " (".hits($ntmp).")" ;
	$pagename = shortenPage(pagename($c,$table,$old));
	$link = "<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;lang=$lang&amp;pathid=$page\" class=\"basic\">".$strings['Centerhere']."</a>";
	$str .= "<div class=\"postit\">$pagename$textn<br><div align=\"center\">$link</div></div>";
	$str .= "\n";
	$pathid = "0|".implode("|", $patharray);
	$str .= "<div class=\"clearer\">&nbsp;</div><div align=\"right\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=studypath&amp;lang=$lang&amp;pathid=$pathid\" class=\"basic\">".$strings['Studyvisitorsflowalongthispath']."</a></div></td></tr>";
}
$str .= "</table>";
echo $str;
}
?>