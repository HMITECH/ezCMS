<?php
/*********************************************************************************/
/* Function: echoTopXX
/* Role: displays a list of top pages, top referrers, top keywords...
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 09/2007, from the former echoMainStats function
/*********************************************************************************/

function echoTopXX($c,$table, $site, $sid) {
global $strings;
global $lang;
global $display;
$ntop = $display['ntop'];

// TOP XX Pages
echo "<table class=\"stat\">\n";
echo "<tr class=\"title\"><td colspan=\"2\">".sprintf($strings["topPages"],$ntop)."</td></tr>\n";
$req = "SELECT (se+ref+other+internal+old) as count, id FROM ${table}_pages ORDER BY count DESC LIMIT 0,$ntop";
$res = mysql_query($req,$c);
$n=0;
while ($row = mysql_fetch_object($res)) {
	$n+=1;
	$pageid = $row->id;
	$count = $row->count;
	// $link = simplePageLink($c, $table, $sid, $lang, $row->id, 3);
	$pagename = shortenPage(pagename($c,$table,$row->id),3);
	$link = linksforpage ($sid, $pagename, $row->id);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "\n<tr class=\"data $even\"><td>$link</td><td width=\"25%\">".pageviews($count)."</td></tr>\n";
}
if ($n==0) echo "<tr><td colspan=\"2\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n";
echo "</table>\n";


// Popular pages for search engines
echo "\n<table class=\"stat\">
<tr class=\"title\"><td colspan=\"2\">".sprintf($strings["topPagesSE"],$ntop)."</td></tr>\n";
$req = "SELECT id, (se*(0.+old+se+ref+other+internal)/(GREATEST(se+ref+other+internal,1))) as seC FROM ${table}_pages ORDER BY seC DESC LIMIT 0,$ntop";
$res = mysql_query($req,$c);
$n = 0;
while ($row = mysql_fetch_object($res)) {
	$countSE = intval($row->seC);
	$n += 1;
	$pagename = shortenPage(pagename($c,$table,$row->id),3);
	$link = linksforpage ($sid, $pagename, $row->id);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "\n<tr class=\"data $even\"><td>$link</td><td width=\"25%\">".hits($countSE)."</td></tr>\n";
}
if ($n==0) echo "<tr><td colspan=\"2\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n";
echo "</table>\n";

echo "\n<table class=\"stat\">
<tr class=\"title\"><td colspan=\"2\">".sprintf($strings["topPagesRef"],$ntop)."</td></tr>\n";
$req = "SELECT id, (ref*(0.+old+se+ref+other+internal)/(GREATEST(se+ref+other+internal,1))) as refC FROM ${table}_pages ORDER BY refC DESC LIMIT 0,$ntop";
$res = mysql_query($req,$c);
$n = 0;
while ($row = mysql_fetch_object($res)) {
	$countR = intval($row->refC);
	$n += 1;
	$pagename = shortenPage(pagename($c,$table,$row->id),3);
	$link = linksforpage ($sid, $pagename, $row->id);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "\n<tr class=\"data $even\"><td>$link</td><td width=\"25%\">".hits($countR)."</td></tr>\n";
}
if ($n==0) echo "<tr><td colspan=\"2\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n";
echo "</table>\n";

// Top keywords
echo "\n<table class=\"stat\">
<tr class=\"title\"><td colspan=\"2\">".sprintf($strings["topKwds"],$ntop)."</td></tr>\n";
$req = "SELECT keyword,SUM(count) as count FROM ${table}_keyword GROUP BY LOWER(keyword) ORDER BY count DESC LIMIT 0,$ntop";
$res = mysql_query($req,$c);
$n = 0;
while ($row = mysql_fetch_object($res)) {
	$key = mb_strtolower(htmlentities($row->keyword, ENT_NOQUOTES,'UTF-8'),'UTF-8');
	$countSE = $row->count;
	$n += 1;
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "\n<tr class=\"data $even\"><td>$key</td><td width=\"25%\">".hits($countSE)."</td></tr>\n";
}
if ($n==0) echo "<tr><td colspan=\"2\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n";
echo "</table>\n";


// Top referrers
echo "\n<table class=\"stat\">
<tr class=\"title\"><td colspan=\"2\">".sprintf($strings["topRefs"],$ntop)."</td></tr>\n";
$req = "SELECT address,SUM(count) as count FROM ${table}_referrer GROUP BY address ORDER BY count DESC LIMIT 0,$ntop";
$res = mysql_query($req,$c);
$n=0;
while ($row = mysql_fetch_object($res)) {
	$ref = $row->address;
	$countR = $row->count;
	$n += 1;
	$link = urlLink($ref,3);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	echo "\n<tr class=\"data $even\"><td>$link</td><td width=\"25%\">".hits($countR)."</td></tr>\n";

}
if ($n==0) echo "<tr><td colspan=\"2\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n";
echo "</table>\n";

}
?>