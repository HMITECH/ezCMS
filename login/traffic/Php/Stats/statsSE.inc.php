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
/* Function: SEPlot
/* Role: plots the different search engines used to acess a given page
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $sid
/*   - $showpage (id or 'all')
/* Returns:
/*   - the string with the plot
/* Create: 09/2005
/*********************************************************************************/
function SEPlot($c,$table, $sid, $showpage) {
global $strings;
global $tmpdirectory;
$timecall = time();
if ($showpage=='all') {
	$req = "SELECT engine,SUM(count) as count FROM ${table}_keyword GROUP BY engine ORDER BY count DESC";
} else {
	$req = "SELECT engine,SUM(count) as count FROM ${table}_keyword WHERE page=$showpage GROUP BY engine ORDER BY count DESC";
}
$res = mysql_query($req,$c);
$temp = fopen ("$tmpdirectory/tmp.$timecall.txt.php", 'w');
fwrite($temp, "<?php  \n\$date_data = array(");
$countother = 0;
$i=0;
$max = 0;
while($row = mysql_fetch_object($res)) {
	$i += 1;
	if ($i>9) {
		$countother += $row->count;
	} else {
		$engine = shorten($row->engine,6);
		$count = $row->count;
		if ($count > $max) {$max=$count;}
		if ($i > 1) {
			fwrite($temp, "\n,array(\"$engine\",$count)");
		} else {
			fwrite($temp, "\narray(\"$engine\",$count)");
		}
	}
}
if ($countother > $max) {$max=$countother;}
fwrite($temp, "\n,array(\"".$strings['plot-Other']."\",$countother)");
fwrite($temp, "\n);\n\$maxY = $max;\n\$plottype=\"bars\";");
fwrite($temp, "\n\$ylabel = \"".$strings['plot-Nsearch']."\";");
fwrite($temp, "\n\$yscale=\"qdrt\";");
fwrite($temp, "\n?>");
fclose($temp);
if ($i>0) {
	$str = "<img src=\"./plotStatBar.php?file=tmp.$timecall.txt.php\" alt=\"".$strings['Nsearch']."\">";
} else {
	$str = "&nbsp;<br>".$strings['Nothingyet']."<br>&nbsp;";
}
return $str;
}

/*********************************************************************************/
/* Function: kwdHistoryPlot
/* Role: plots a history of the number of new keywords per day for a given page
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $sid
/*   - $showpage (id or 'all')
/* Returns:
/*   - the string with the plot
/* Create: 09/2005
/*********************************************************************************/
function kwdHistoryPlot($c,$table, $sid, $showpage) {
global $strings;
global $tmpdirectory;
$timecall = time();
$start = 100000000000;
$end = 0;
$maxcount = 0;
if ($showpage=='all') {
	$req = "SELECT DATE_FORMAT(first, '%Y-%m-%d') AS Date, COUNT(*) AS num FROM ${table}_keyword  GROUP BY Date ORDER BY first ASC";
} else {
	$req = "SELECT DATE_FORMAT(first, '%Y-%m-%d') AS Date, COUNT(*) AS num FROM ${table}_keyword WHERE page=$showpage GROUP BY Date ORDER BY first ASC";
}
$res = mysql_query($req,$c);
while ($row = mysql_fetch_object($res)) {
	$day = $row->Date;
	$time = intval(100*floor(strtotime($day)/100));
	$count = $row->num;
	//echo "<br>day is $day, time is $time, count is $count";
	if ($time<$start) {$start=$time;}
	if ($time>$end) {$end=$time;}
	if ($count>$maxcount) {$maxcount = $count;}
	$count_array[$time] = $count;
}
$average = array();
if ((($start+2679000) != $end)&&$maxcount>0) {
	$day = $start;
	while ($day<=$end) {
		if (!array_key_exists($day, $count_array)) $count_array[$day] = 0;
		$day = strtotime("+1 day",$day);
	}
	$day = strtotime("+3 days", $start);
	$endloop =strtotime("-3 days", $end);
	while ($day<=$endloop) {
		for ($i=-3;$i<=3;$i++) {
			if (!isset($count_array[$day+$i*86400])) $count_array[$day+$i*86400] = 0;
		}
		$average[$day] = 1.0*($count_array[$day-3*86400] + $count_array[$day-2*86400]
			+ $count_array[$day-86400] + $count_array[$day]
			+ $count_array[$day+86400] + $count_array[$day+2*86400]
			+ $count_array[$day+3*86400]) / 7.0;
		$day = strtotime("+1 day",$day);
	}
	$temp = fopen ("$tmpdirectory/tmp2.$timecall.txt.php", 'w');
	fwrite($temp, "<?php  \n\$date_data = array(");
	$day = $start;
	$i = 0;
	while ($day<$end) {
		if ($i>0) {
			fwrite($temp,"\n,array(\"\",$day");
		} else {
			fwrite($temp,"\narray(\"\",$day");
		}
		if (array_key_exists($day, $average)) {
			fwrite($temp,",$count_array[$day],$average[$day])");
		} else {
			fwrite($temp,",$count_array[$day])");
		}
		$day = strtotime("+1 day",$day);
		$i += 1;
	}
	fwrite($temp, "\n);\n\$start=$start;\n\$end=$end;\n\$maxY=$maxcount;\n\$plottype=\"lines\";");
	fwrite($temp, "\n\$width = 600;");
	fwrite($temp, "\n\$height = 300;");
	fwrite($temp, "\n\$legende = array(\"".$strings['plot-Dailycount']."\",\"".$strings['plot-Weeklyaverage']."\");");
	fwrite($temp, "\n\$ylabel = \"".$strings['plot-Newkeywordsperday']."\";");
	fwrite($temp, "\n?>");
	fclose($temp);
	$str = "<img src=\"./plotStat.php?file=tmp2.$timecall.txt.php\" alt=\"".$strings['Newkeywordsperday']."\">";
} else {
	$str = "&nbsp;<br>".$strings['Nothingyet']."<BR>&nbsp;";
}
return $str;
}

/*********************************************************************************/
/* Function: tableSE
/* Role:
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $sid
/*   - $pageid (id or 'all')
/*   - $sort
/*   - $start: table page number
/*   - $lowercase: 1 if case-insensitive, 0 otherwise
/*   - $groupengines: 1 if group search engines
/* Returns:
/*   - the string with the plot
/* Create: 09/2005
/*********************************************************************************/
function tableSE ($c,$table,$sid,$title,$pageid,$sort,$order,$start,$lowercase,$groupengines) {
global $strings;
global $lang;
global $display;
global $sites;

$nperpage = $display['ntoplong'];
$ncol = 3;

// Preparing WHERE options
$where = "";
$extralink = "";
$sortstring = "";
if ($pageid!="all") $where = " page=$pageid ";
// Search tables
if (isset($_POST['searchSE'])) {
	$str =  mb_strtolower($_POST['searchSE'],  "UTF-8");
	if ($where != "") $where .= " AND ";
	$where .= " LOWER(engine) LIKE '%$str%' ";
	$extralink .= "&amp;searchSE=$str";
}
if (isset($_POST['searchKW'])) {
	$str =  mb_strtolower($_POST['searchKW'],  "UTF-8");
	if ($where != "") $where .= " AND ";
	$where .= " LOWER(keyword) LIKE '%$str%' ";
	$extralink .= "&amp;searchKW=$str";
}
if (isset($_GET['searchSE'])) {
	$str =  mb_strtolower($_GET['searchSE'],  "UTF-8");
	if ($where != "") $where .= " AND ";
	$where .= " LOWER(engine) LIKE '%$str%' ";
	$extralink .= "&amp;searchSE=$str";
}
if (isset($_GET['searchKW'])) {
	$str =  mb_strtolower($_GET['searchKW'],  "UTF-8");
	if ($where != "") $where .= " AND ";
	$where .= " LOWER(keyword) LIKE '%$str%' ";
	$extralink .= "&amp;searchKW=$str";
}
if ($where != "") $where = "WHERE $where";
// Reste
$toreturn = "";
$f = $nperpage * ($start - 1);
// Sorting options
if ($order=="desc") {
	$sortstring .= " ASC";
} else {
	$sortstring .= " DESC";
}
if ($lowercase && $groupengines) {
	$grouplower = "GROUP BY LOWER(keyword)";
	$kwdString = "LOWER(keyword)";
	$sumcount = "SUM(count) as c";
} else if ($lowercase && (!$groupengines)) {
	$grouplower = "GROUP BY LOWER(CONCAT(engine, ': ', keyword))";
	$kwdString = "LOWER(CONCAT(engine, ': ', keyword))";
	$sumcount = "SUM(count) as c";
} else if ((!$lowercase) && ($groupengines)) {
	// cast as binary necessary to force case sensitive
	$grouplower = "GROUP BY CAST(keyword AS binary)";
	$kwdString = "CAST(keyword AS binary)";
	$sumcount = "SUM(count) as c";
} else {
	// cast as binary necessary to force case sensitive
	$grouplower = "GROUP BY CAST(CONCAT(engine, ': ', keyword) AS binary)";
	$kwdString = "CAST(CONCAT(engine, ': ', keyword) AS binary)";
	$sumcount = "SUM(count) as c";
}
if ($sort == "latest") {
	$req = "SELECT MIN(first) as f, MAX(last) as l, engine, keyword, $sumcount FROM ${table}_keyword $where $grouplower ORDER BY first $sortstring LIMIT $f,$nperpage";
} else if ($sort == "latesthit") {
	$req = "SELECT MIN(first) as f, MAX(last) as l, engine, keyword, $sumcount FROM ${table}_keyword $where $grouplower ORDER BY last $sortstring LIMIT $f,$nperpage";
} else if ($sort == "key") {
	$req = "SELECT MIN(first) as f, MAX(last) as l, engine, keyword, $sumcount FROM ${table}_keyword $where $grouplower ORDER BY $kwdString $sortstring LIMIT $f,$nperpage";
} else { // hits
	$req = "SELECT MIN(first) as f, MAX(last) as l, engine, keyword, $sumcount FROM ${table}_keyword $where $grouplower ORDER BY c $sortstring LIMIT $f,$nperpage";
}

// Arrows for sorting choice
$arrowsLatest = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;sort=latest&amp;start=1&amp;pageid=$pageid&amp;lang=$lang&amp;casesensitive=$lowercase&amp;groupengine=$groupengines", $strings['Sortby']." ".$strings['Firsttime'], '');
$arrowsLatesthit = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;sort=latesthit&amp;start=1&amp;pageid=$pageid&amp;lang=$lang&amp;casesensitive=$lowercase&amp;groupengine=$groupengines", $strings['Sortby']." ".$strings['Latesthit'], '');
$arrowsHits = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;sort=hits&amp;start=1&amp;pageid=$pageid&amp;lang=$lang&amp;casesensitive=$lowercase&amp;groupengine=$groupengines", $strings['Sortby']." ".$strings['Numberofhits'], '');
$arrowsKey = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;sort=key&amp;start=1&amp;pageid=$pageid&amp;lang=$lang&amp;casesensitive=$lowercase&amp;groupengine=$groupengines", $strings['Sortby']." ".$strings['Keyword'], '');

// table itself
$toreturn .= "<tr class=\"caption\">";
$toreturn .= "<td>".$strings['Firsttime']."</td>";
$toreturn .= "<td>".$strings['Latesthit']."</td>";
$toreturn .= "<td>".$strings['Keyword']."</td>";
$toreturn .= "<td>".$strings['Hits']."</td></tr>";
$toreturn .= "<tr class=\"caption\">";
$toreturn .= "<td align=\"center\">$arrowsLatest</td>";
$toreturn .= "<td align=\"center\">$arrowsLatesthit</td>";
$toreturn .= "<td align=\"center\">$arrowsKey</td>";
$toreturn .= "<td align=\"center\">$arrowsHits</td></tr>";
//echo "SQL is $req";
$res = mysql_query($req,$c);
$n = 0;
$timeserver = time() + 3600*$sites[$sid]['timediff'];
$today = strtotime(date("M j, Y", $timeserver));
while ($row = mysql_fetch_object($res)) {
	$n += 1;
	$count = $row->c;
	$date = strtotime($row->f);
	if ($date>$today) {
		$first = timetoday($date,0);
	} else {
		$first = dayMmYear($date,0);
	}
	$date = strtotime($row->l);
	if ($date>$today) {
		$last = timetoday($date,0);
	} else {
		$last = dayMmYear($date,0);
	}
	// $keyword = htmlentities ($row->keyword, ENT_NOQUOTES, 'UTF-8');
	// $txt = $keyword;
	$engine = $row->engine;
	$keyword = htmlentities ($row->keyword, ENT_NOQUOTES, 'UTF-8');
	if ($lowercase) {
		$keyword =  mb_strtolower($keyword, 'UTF-8');
	}
	if ($groupengines) {
		$txt = "$keyword";
	} else {
		$txt = "$engine: $keyword";
	}
	if (($count == 1)&&($sort!="latest")) $txt .= " <font color=\"#FF0000\">-- ".$strings['new!']."</font>";
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	$toreturn .= "\n<tr class=\"data $even\">";
	$toreturn .= "<td class='av'>$first</td>";
	$toreturn .= "<td class='av'>$last</td>";
	$toreturn .= "<td class='av'>$txt</td>";
	$toreturn .= "<td class='av'>".hits($count)."</td></tr>\n";
}
if ($n == 0) {
	$toreturn .= "<tr><td colspan=\"4\"><div align=\"center\">".$strings['Nothingyet']."</div></td></tr>\n";
} else {
	$req = "SELECT DISTINCT($kwdString) as total FROM ${table}_keyword $where";
	$res = mysql_query($req,$c);
	$nref = mysql_num_rows($res);
	$npages = intval($nref/$nperpage)+1;
	$toreturn .= "<tr><td colspan=\"4\"><div align=\"center\"><strong>".$strings['Page'].":</strong> ";
	$toreturn .= navPage("./index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;sort=$sort&amp;order=$order&amp;casesensitive=$lowercase&amp;groupengine=$groupengines&amp;pageid=$pageid&amp;lang=$lang$extralink&amp;start=", $npages, $start);
	$toreturn .= "</div></td></tr>\n";
	$toreturn .= "<tr><td align=\"center\" colspan=\"4\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=seplot&amp;pageid=$pageid&amp;lang=$lang\" class=\"basic\">".$strings['Searchengines']."</a> -- <a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=kwdplot&amp;pageid=$pageid&amp;lang=$lang\" class=\"basic\">".$strings['NewkeywordsPday']."</a></td></tr>\n";
}

return $toreturn;
}

/*********************************************************************************/
/* Function: echoEngine
/* Role: echos search engines and keyword stats
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $site
/*   - $sid
/*   - $showpage (id or 'all')
/*   - $sort: what kind of sorting for the table
/*   - $start: page number
/* Returns:
/*   - nothing, but echos what it finds
/* Created: 2004
/* 11/2006: removed database connection function (moved earlier in the code)
/*********************************************************************************/
function echoEngine($c,$table, $site, $sid, $showpage, $sort, $order, $start) {
global $DEBUG;
global $strings;
global $lang;

// Options
if (isset($_GET["casesensitive"])) {
	$lowercase = $_GET["casesensitive"];
} else if (!isset($_POST["casesensitive"])) {
	$lowercase = 1;
} else {
	$lowercase = 0;
}
if (isset($_GET["groupengine"])) {
	$groupengines = $_GET["groupengine"];
} else if (isset($_POST["groupengine"])) {
	$groupengines = 1;
} else {
	$groupengines = 0;
}

// Title
if ($showpage=='all') {
	$title = $strings['Searchenginesstats']." ".$strings['forthewholesite'];
} else {
	$pagename = pagename($c,$table,$showpage);
	$title =$strings['Searchenginesstats']." ". $strings['for']." $pagename";
}

// Navigation
$select = selectnavpages($c, $table, $showpage);
$url = "index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;lang=$lang";
if (!$lowercase) { $checked = "checked"; } else { $checked = ""; }
$chBoxCase = "<input type=\"checkbox\" name=\"casesensitive\" value=\"1\" $checked>";
if ($groupengines) { $checked = "checked"; } else { $checked = ""; }
$chBoxEngine = "<input type=\"checkbox\" name=\"groupengine\" value=\"1\" $checked>";

$nav = "<div align=\"center\"><form name=\"pageselect\" action=\"$url\" method=\"post\"><table class=\"form\">
<tr><td valign=\"middle\" width=\"200px\">".$strings['Statisticsfor']."</td>
<td valign=\"middle\" width=\"200px\">$select</td>
<td valign=\"middle\" rowspan=\"2\"width=\"50px\"><input type=\"submit\" value=\"".$strings['Ok']."\"></td></tr>
<tr><td>$chBoxCase ".$strings['caseSensitiveKwd']."</td>
<td>$chBoxEngine ".$strings['GroupEngines']."</td></tr>
</table></form>\n</div>\n";
echo $nav;

// Table
echo "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"4\">$title</td></tr>\n";
echo tableSE ($c,$table,$sid, $title,$showpage,$sort, $order, $start,$lowercase,$groupengines);
echo "</table>";

// Search tables
if (isset($_POST['searchSE'])) { $searchSE = $_POST['searchSE']; } else if (isset($_GET['searchSE'])) { $searchSE = $_GET['searchSE']; } else { $searchSE = ""; }
if (isset($_POST['searchKW'])) { $searchKW = $_POST['searchKW']; } else if (isset($_GET['searchKW'])) { $searchKW = $_GET['searchKW']; } else { $searchKW = ""; }
echo "<form method='post' action='index.php'>
<input type='hidden' name='mode' value='stats'>
<input type='hidden' name='sid' value='$sid'>
<input type='hidden' name='show' value='key'>
<input type='hidden' name='sort' value='hits'>
<input type='hidden' name='start' value='1'>
<input type='hidden' name='pageid' value='$showpage'>
<input type='hidden' name='lang' value='$lang'>
<table class=\"stat\">
<tr class=\"top\"><td rowspan=\"2\">".$strings['Keywordsearch']."</td>
<td align=\"right\">".$strings['Searchengines']."</td>
<td align=\"left\"><input type='text' name='searchSE' value='$searchSE' size='40'></td>
<td rowspan=\"2\"><input type='submit' value='Search'></td></tr>
<tr>
<td align=\"right\" class=\"noborder\">".$strings['Keywords']."</td>
<td align=\"left\" class=\"noborder\"><input type='text' name='searchKW' value='$searchKW' size='40'></td>
</tr>
</table></form>";

}

/*********************************************************************************/
/* Function: echoKwdPlot
/* Role: echos a plot of the number of new keywords per day
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $site
/*   - $sid
/*   - $showpage (id or 'all')
/* Returns:
/*   - nothing, but echos what it finds
/* Created: 10/2007 out of echoEngine
/*********************************************************************************/
function echoKwdPlot($c,$table, $site, $sid, $showpage) {
global $strings;
global $lang;

$nav = formnavpages($c, $table, $showpage, "index.php?mode=stats&amp;sid=$sid&amp;show=kwdplot&amp;lang=$lang", $strings['Statisticsfor']);
echo $nav;
if ($showpage=='all') {
	$title = $strings['Newkeywordsperday']." ".$strings['forthewholesite'];
} else {
	$title = $strings['Newkeywordsperday']." ".$strings['for']." ".pagename($c,$table,$showpage);
}
echo "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"2\">$title</td></tr>\n";
echo "<tr><td colspan=\"2\">&nbsp;<div align=\"center\">";
echo kwdHistoryPlot($c,$table, $sid, $showpage);
echo "</td></tr>\n";
echo "<tr><td align=\"center\" width=\"50%\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=seplot&amp;pageid=$showpage&amp;lang=$lang\">".$strings['Searchengines']."</a></td><td align=\"center\" class=\"bleft\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;pageid=$showpage&amp;sort=kwd&amp;start=1&amp;lang=$lang\">".$strings['Keywords']."</a></td></tr>\n";
echo "</table>\n";
}

/*********************************************************************************/
/* Function: echoEngineSEPlot
/* Role: echos a plot of search engines (N. search vs. Search engines
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $site
/*   - $sid
/*   - $showpage (id or 'all')
/* Returns:
/*   - nothing, but echos what it finds
/* Created: 10/2007 out of echoEngine
/*********************************************************************************/
function echoEngineSEPlot($c,$table, $site, $sid, $showpage) {
global $strings;
global $lang;

$nav = formnavpages($c, $table, $showpage, "index.php?mode=stats&amp;sid=$sid&amp;show=seplot&amp;lang=$lang", $strings['Statisticsfor']);
echo $nav;
if ($showpage=='all') {
	$title = $strings['Searchengineplot']." ".$strings['forthewholesite'];
} else {
	$title = $strings['Searchengineplot']." ".$strings['for']." ".pagename($c,$table,$showpage);
}
echo "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"2\">$title</td></tr>\n";
echo "<tr><td colspan=\"2\">&nbsp;<div align=\"center\">";
echo SEPlot($c,$table, $sid, $showpage);
echo "</td></tr>\n";
echo "<tr><td align=\"center\" width=\"50%\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=kwdplot&amp;pageid=$showpage&amp;lang=$lang\">".$strings['NewkeywordsPday']."</a></td><td align=\"center\" class=\"bleft\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;pageid=$showpage&amp;sort=kwd&amp;start=1&amp;lang=$lang\">".$strings['Keywords']."</a></td></tr>\n";
echo "</table>\n";
}

?>
