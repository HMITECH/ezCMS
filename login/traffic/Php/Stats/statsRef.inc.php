<?php
$DEBUG = 0;

/***************************************************************************
phpTrafficA @soft.ZoneO.net
Copyright (C) 2004-2008 ZoneO-soft, Butchu (email: "butchu" with the domain "zoneo.net")

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

More Info About The Licence At http://www.gnu.org/copyleft/gpl.html
****************************************************************************/

/**/
/*	Function to follow a referrer link
/*    Tracks the fact that the link was followed if you are logged-in
/* Parameters
/*    $c: connection to the database
/*    $table: table
/*    $sid: sid of the site being tracked
/*    $refid: referrer ID
/**/
function followRef($c, $table, $sid, $refId) {
global $DEMO;
$isLoggedIn = isloggedin ($c);
$sql = "SELECT address,visited FROM ${table}_referrer WHERE id=$refId";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$ref = $row->address;
$visited = $row->visited;
if ((!$DEMO) && (!$visited)) {
	$sql = "UPDATE `${table}_referrer` SET visited=1 WHERE id=$refId";
	$res = mysql_query($sql,$c);
}
@mysql_close ($c);
header("Location: $ref");
die();
}

/*********************************************************************************/
/* Function: refLink
/* Role: prepares a link for a referrer, that will be tracked by phpTrafficA
/* Parameters:
/*   - $sid: ID of the site being tracked
/*   - $url: the url you need to link to
/*   - $refID: the ID of the referrer
/*   - $short: 1 for a short URL, 2 for an average one, 3 for a long one, default values:
/*        0: 20 chars
/*        1: 40 chars
/*        2: 60 chars
/*        3: 80 chars
/*        4: 100 chars
/*        ...
/*   - $extrapar: extra parameter for the link (css class...)
/* Output:
/*   - string with a link
/* Created: 09/2009
/*********************************************************************************/
function refLink($sid, $url, $refID, $short=1, $extrapar="") {
// echo "len is $short<br>";
$link = "./index.php?mode=followref&amp;sid=$sid&amp;id=$refID";
$url=cleanURL($url);
$urlS = shortenURL($url,$short);
return "<a href=\"$link\" target=\"_new\" title=\"$url\" rel=\"nofollow\" $extrapar>$urlS</a>";
}



function echoReferer($c,$table, $site,$sid,$sort,$order,$start,$pageid="all") {
global $DEBUG;
global $strings;
global $lang;
global $display;
global $sites;

$isLoggedIn = isloggedin ($c);
$nperpage = $display['ntoplong'];
$timeForNew = $display['referrerNewDuration']*604800;
$daysForNew =  $display['referrerNewDuration']*7;
$timeserver = time() + 3600*$sites[$sid]['timediff'];
$dateserver = date("Y-m-d H:i:s",$timeserver);

// Check if need to show only new referrers
$onlynew = 0;
if (isset($_POST["onlynew"])) $onlynew = 1;
if (isset($_GET["onlynew"])) $onlynew = $_GET["onlynew"];

// Restriction on page for keyword search
if ($pageid=="all") {
	if ($onlynew) {
		$where = " WHERE visited=0 AND (first + INTERVAL $daysForNew DAY)> '$dateserver' ";
	} else {
		$where = "";
	}
	$ncol = 6;
} else {
	if ($onlynew) {
		$where = " WHERE page=$pageid AND visited=0 AND (first + INTERVAL $daysForNew DAY)> '$dateserver'";
	} else {
		$where = " WHERE page=$pageid ";
	}
	$ncol = 5;
}
if ($isLoggedIn) $ncol += 1; // One more to be able to ban referrers

//
if ($sort == "") {
	$sort = "hits";
	$order = "asc";
}
if ($start == "") $start = 1;

// Title
if ($pageid=='all') {
	$title = $strings['forthewholesite'];
} else {
	$pagename = pagename($c,$table,$pageid);
	$title = $strings['for']." $pagename";
}

// Page navigation
// echo formnavpages($c,$table,$pageid,"index.php?mode=stats&amp;sid=$sid&amp;show=ref&amp;sort=hits&amp;start=1&amp;lang=$lang",$strings['Statisticsfor'], true);
$select = selectnavpages($c, $table, $pageid);
$url = "index.php?mode=stats&amp;sid=$sid&amp;show=ref&amp;sort=hits&amp;start=1&amp;lang=$lang";
if ($onlynew) { $checked = "checked"; } else { $checked = ""; }
$chBoxNew = "<input type=\"checkbox\" name=\"onlynew\" value=\"1\" $checked>";
$nav = "<div align=\"center\"><form name=\"pageselect\" action=\"$url\" method=\"post\"><table class=\"form\">
<tr><td valign=\"middle\" width=\"200px\">".$strings['Statisticsfor']."</td>
<td valign=\"middle\" width=\"200px\">$select</td>
<td valign=\"middle\" rowspan=\"2\"width=\"50px\"><input type=\"submit\" value=\"".$strings['Ok']."\"></td></tr>
<tr><td colspan=\"2\">$chBoxNew ".$strings['showOnlyNew']."</td>
</table></form>\n</div>\n";
echo $nav;


// If referrer search, there is a second restriction
if (isset($_GET['searchref'])) {
	$searchref = $_GET['searchref'];
	if ($pageid == 'all') {
		$where = "WHERE address LIKE '%$searchref%'";
	} else {
		$where = " WHERE page=$pageid AND address LIKE '%$searchref%'";
	}
	$searchURL = "&amp;searchref=$searchref";
	$title .= ": ".$strings['searchresultsfor']." '$searchref'";
} else {
	$searchURL = "";
	$searchref = "";
}

// Sorting things
if ($sort == "hits") {
	$sortstring = "ORDER BY count";
} else if ($sort == "added") {
	$sortstring = "ORDER BY first";
} else if ($sort == "latest") {
	$sortstring = "ORDER BY last";
} else if ($sort == "refs") {
	$sortstring = "ORDER BY address";
}
if ($order=="desc") {
	$sortstring .= " ASC";
} else {
	$sortstring .= " DESC";
}
$f = $nperpage * ($start - 1);
$limits =  "LIMIT $f,$nperpage";
$sql = "SELECT id,first,last,page,address,count,visited FROM ${table}_referrer $where $sortstring $limits";

// Arrows for sorting choice
$arrowsLatest = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=ref&amp;sort=latest&amp;start=1&amp;pageid=$pageid&amp;onlynew=$onlynew&amp;lang=$lang$searchURL", $strings['Sortby']." ".$strings['Latesthit'], '');
$arrowsFirst = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=ref&amp;sort=added&amp;start=1&amp;pageid=$pageid&amp;onlynew=$onlynew&amp;lang=$lang$searchURL", $strings['Sortby']." ".$strings['Latests'], '');
$arrowsHits = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=ref&amp;sort=hits&amp;start=1&amp;pageid=$pageid&amp;onlynew=$onlynew&amp;lang=$lang$searchURL", $strings['Sortby']." ".$strings['Numberofhits'], '');
$arrowsRefs = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=ref&amp;sort=refs&amp;start=1&amp;pageid=$pageid&amp;onlynew=$onlynew&amp;lang=$lang$searchURL", $strings['Sortby']." ".$strings['Numberofhits'], '');

// Start for Numbering
$index = $nperpage * ($start - 1);

// Table with referrers list
echo "\n<table class=\"stat\">
<tr class=\"title\"><td colspan=\"$ncol\">".$strings['Topreferrers']." $title</td></tr>";
echo "<tr class=\"caption\"><td>&nbsp;</td>";
if ($pageid=="all") echo "<td>".$strings['Page']."</td>";
if ($isLoggedIn) { $colRef = 2; } else { $colRef = 1;}
echo "<td>".$strings['Firsttime']."</td><td>".$strings['Latesthit']."</td><td colspan=\"$colRef\">".$strings['Referrer']."</td><td>".$strings['Hits']."</td></tr>\n";
echo "<tr class=\"caption\"><td>&nbsp;</td>";
if ($pageid=="all") echo "<td>&nbsp;</td>";
echo "<td>$arrowsFirst</td><td>$arrowsLatest</td><td colspan=\"$colRef\">$arrowsRefs</td><td>$arrowsHits</td></tr>\n";

$res = mysql_query($sql,$c);
$n = 0;
$today = strtotime(date("M j, Y", $timeserver));
while ($row = mysql_fetch_object($res)) {
	$index += 1;
	$n += 1;
	$refid = $row->id;
	$ref = $row->address;
	$count = $row->count;
	$thispageid = $row->page;
	$visited = $row->visited;
	$datefirst = strtotime($row->first);
	if ($datefirst>$today) {
		$first = timetoday($datefirst,0);
	} else {
		$first = dayMmYear($datefirst,0);
	}
	$date = strtotime($row->last);
	if ($date>$today) {
		$last = timetoday($date,0);
	} else {
		$last = dayMmYear($date,0);
	}
	if ($pageid=="all") {
		$linkpage = linksforpage ($sid, shortenPage(pagename($c,$table,$thispageid),1), $thispageid);
	}
	$refText = refLink($sid,$ref,$refid,3,"class=\"basic\"");
	if (($visited == 0) && ((time()-$datefirst)<$timeForNew)) $refText .= " <font color=\"#FF0000\">-- ".$strings['new!']."</font>";
	if ($isLoggedIn) {
		$banText = "<td><a href=\"javascript:banRef($sid, $refid)\">".$strings['ban']."</a></td>";
	} else {
		$banText = "";
	}
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "\n<tr class=\"data av $even\"><td>".format_float($index,0)."</td>";
	if ($pageid=="all") echo "<td>$linkpage</td>";
	echo "<td>$first</td><td>$last</td><td>$refText</td>$banText<td>".hits($count)."</td></tr>\n";
}
if ($n == 0) {
	echo "\n<tr><td colspan=\"$ncol\"><div align=\"center\">".$strings['Nothingyet']."</div></td></tr>\n";
} else {
	$req = "SELECT COUNT(*) as count FROM ${table}_referrer $where;";
	$res = mysql_query($req,$c);
	$row = mysql_fetch_object($res);
	$nref = $row->count;
	$npages = intval($nref/$nperpage)+1;
	echo "<tr><td colspan=\"$ncol\"><div align=\"center\"><strong>".$strings['Page'].":</strong> ";
	echo navPage("./index.php?mode=stats&amp;sid=$sid&amp;show=ref&amp;sort=$sort&amp;order=$order&amp;pageid=$pageid&amp;onlynew=$onlynew&amp;lang=$lang$searchURL&amp;start=", $npages, $start);
	echo "</div></td></tr>\n";
}
echo "</table>\n";

// Search tables
echo "<form method='get' action='index.php'>
<input type='hidden' name='mode' value='stats'>
<input type='hidden' name='sid' value='$sid'>
<input type='hidden' name='show' value='ref'>
<input type='hidden' name='sort' value='hits'>
<input type='hidden' name='start' value='1'>
<input type='hidden' name='pageid' value='$pageid'>
<input type='hidden' name='lang' value='$lang'>
<table class=\"stat\">
<tr class=\"top\"><td>".$strings['Referrersearch']."</td>
<td align=\"center\"><input type='text' name='searchref' value='$searchref' size='40'></td>
<td><input type='submit' value='Search'></td></tr>
</table></form>";
}

?>