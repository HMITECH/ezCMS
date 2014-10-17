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
/* Function: table_exists
/* Role: test if a table exists in the database
/* Parameters: 
/*   - $c: connection to a database
/*   - $table: the table to test
/* Output:
/*   - true if it exists, false otherwise
/* Created 11/2007
/*********************************************************************************/
function table_exists($c, $table) {
global $strings;
$query = "show table status like '$table'";
$sql = @mysql_query($query,$c);
if (!$sql)  {
	echo "<br>".$strings['errorwithdb']." ".__LINE__.": ".mysql_error();
	echo "\n<br>".$strings['SQLquerywas'].": $query\n";
	return;
}
$table_exists = mysql_num_rows($sql) == 1;
return $table_exists;
}

/*********************************************************************************/
/* Function: doRemovePage
/* Role: actually remove page from the database
/* Parameters:
/*   - $c: connection to a database
/*   - $sid: site ID
/*   - $pageid: page ID for the page to remove
/* Output:
/* Created 3/2008
/*********************************************************************************/
function doRemovePage($c, $sid, $pageid) {
global $sites;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
} else {
	$table = $sites[$sid]['table'];
	$sql = "DELETE FROM ${table}_pages WHERE id=$pageid";
	$res = mysql_query($sql,$c);
	$sql = "DELETE FROM ${table}_acces WHERE label=$pageid";
	$res = mysql_query($sql,$c);
	$sql = "DELETE FROM ${table}_uniq WHERE label=$pageid";
	$res = mysql_query($sql,$c);
	$sql = "DELETE FROM ${table}_keyword WHERE page=$pageid";
	$res = mysql_query($sql,$c);
	$sql = "DELETE FROM ${table}_referrer WHERE page=$pageid";
	$res = mysql_query($sql,$c);
	$sql = "DELETE FROM ${table}_path WHERE INSTR(path,'|$pageid|')>0";
	$res = mysql_query($sql,$c);
}
}

/*********************************************************************************/
/* Function: confirmremovepage
/* Role: show a dialog to confirm that a page should really be remove from the database
/* Parameters:
/*   - $c: connection to a database
/*   - $sid: site ID
/*   - $pageid: page ID for the page to remove
/*   - $loginfailed: message in case of failed password
/* Output:
/* Created 3/2008
/*********************************************************************************/

function confirmRemovePage($c, $sid, $pageid, $loginfailed) {
global $sites;
global $strings;
global $lang;
$domain = $sites[$sid]['site'];
$table = $sites[$sid]['table'];
$sql = "SELECT name, (ref+se+internal+other+old) as count FROM ${table}_pages WHERE id=$pageid";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$name = $row->name;
$count = $row->count;
if ($name == "") $name=0;
if ($count == "") $count=0;
$sql = "SELECT count(*) as nlines, SUM(count) as total FROM ${table}_keyword WHERE page=$pageid GROUP BY page";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$nkwds = $row->nlines;
$nsearch = $row->total;
if ($nkwds == "") $nkwds=0;
if ($nsearch == "") $nsearch=0;
$sql = "SELECT count(*) as nlines, SUM(count) as total FROM ${table}_referrer WHERE page=$pageid GROUP BY page";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$nrefs = $row->nlines;
$nrefcount = $row->total;
if ($nrefs == "") $nrefs=0;
if ($nrefcount == "") $nrefcount=0;
$sql = "SELECT SUM(count) as count, COUNT(id) as diff FROM ${table}_path WHERE INSTR(path,'|$pageid|')>0";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$npath = $row->diff;
$npathcount = $row->count;
if ($npath == "") $npath=0;
if ($npathcount == "") $npathcount=0;
echo "<form action=\"./index.php\" method=\"post\">
<table>
<tr class=\"toprow\">
<td>".$strings['Youselected'].":<br>
<blockquote>".$strings['RemovePage']."</blockquote>
".$strings['for']."<br>
<blockquote>".$strings['Page'].": $name<br>
".$strings['Domain'].": $domain<br>
".$strings['Hits'].": $count<br>
".$strings['nPageKwd'].": $nkwds<br>
".$strings['nPageSch'].": $nsearch<br>
".$strings['nPageRef'].": $nrefs<br>
".$strings['nPageRefAccess'].": $nrefcount<br>
".$strings['nPagePath'].": $npath<br>
".$strings['nPagePathUsed'].": $npathcount
</blockquote>
".$strings['RemovePageDef']."<br>
<blockquote>".$strings['nogoingback']."</blockquote>
<table class=\"form\" align=\"center\">
<tr><td>&nbsp;&nbsp;".$strings['Password']."&nbsp;&nbsp;</td><td><input type=\"password\" name=\"testpasswd\" maxlength=\"50\">&nbsp;&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"2\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"reallyremovepage\"><input type=\"hidden\" name=\"sid\" value=\"$sid\"><input type=\"hidden\" name=\"pageid\" value=\"$pageid\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr>";
if ($loginfailed) {
	echo "<tr><td colspan=\"2\" align=\"center\"><i>".$strings['IncorrectPassword']."</i></td></tr>";
}
echo "</table>
</table></form>\n";
}
/*********************************************************************************/
/* Function: doRemoveSeveralPage
/* Role: actually remove several pages from the database
/* Parameters:
/*   - $c: connection to a database
/*   - $sid: site ID
/*   - $pageidStr: string with page ID to remove, sepatated with "|"
/* Output:
/* Created 3/2009
/*********************************************************************************/
function doRemoveSeveralPage($c, $sid, $pageidStr) {
global $sites;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
} else {
	$idArray = explode("|", $pageidStr);
	foreach ($idArray as $pageid) doRemovePage($c, $sid, $pageid);
}
}


/*********************************************************************************/
/* Function: confirmremoveSeveralpage
/* Role: show a dialog to confirm that pages that match a string should really be remove from the database
/* Parameters:
/*   - $c: connection to a database
/*   - $sid: site ID
/*   - $pagematch: string to match in page name
/*   - $loginfailed: message in case of failed password
/* Output:
/* Created 3/2009
/*********************************************************************************/

function confirmRemoveSeveralPage($c, $sid, $pagematch, $loginfailed) {
global $sites;
global $strings;
global $lang;
$domain = $sites[$sid]['site'];
$table = $sites[$sid]['table'];
// Locating page names
$idArray = array();
echo "<table>
<tr class=\"toprow\">
<td>".$strings['Youselected'].":<br>
<blockquote>".$strings['RemovePage']."</blockquote>
".$strings['for']."<br>
<blockquote><ul>";
$sql = "select name, (ref+se+internal+other+old) as count, id from ${table}_pages where name like '%$pagematch%'";
$res = mysql_query($sql,$c);
$totalHits = 0;
while ($row = mysql_fetch_object($res)) {
	$idArray[] = $row->id;
	$name = $row->name;
	$count = $row->count;
	if ($count == "") $count=0;
	$totalHits += $count;
	echo "<li>$name - ".hits($count)."</li>";
}
echo "</ul></blockquote>\n";
$totalRefs = 0;
$totalKwd = 0;
$totalPath = 0;
foreach ($idArray as $pageid) {
	$sql = "SELECT count(*) as nlines, SUM(count) as total FROM ${table}_keyword WHERE page=$pageid GROUP BY page";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$nkwds = $row->nlines;
	$nsearch = $row->total;
	if ($nkwds == "") $nkwds=0;
	if ($nsearch == "") $nsearch=0;
	$totalKwd += $nkwds;
	$sql = "SELECT count(*) as nlines, SUM(count) as total FROM ${table}_referrer WHERE page=$pageid GROUP BY page";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$nrefs = $row->nlines;
	$nrefcount = $row->total;
	if ($nrefs == "") $nrefs=0;
	if ($nrefcount == "") $nrefcount=0;
	$totalRefs += $nrefs;
	$sql = "SELECT SUM(count) as count, COUNT(id) as diff FROM ${table}_path WHERE INSTR(path,'|$pageid|')>0";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$npath = $row->diff;
	$npathcount = $row->count;
	if ($npath == "") $npath=0;
	if ($npathcount == "") $npathcount=0;
	$totalPath += $npath;
}
echo "<blockquote>".$strings['Domain'].": $domain<br>
".$strings['Hits'].": $totalHits<br>
".$strings['nPageKwd'].": $totalKwd<br>
".$strings['nPageRef'].": $totalRefs<br>
".$strings['nPagePath'].": $totalPath<br>
</blockquote>
".$strings['RemovePageDef']."<br>
<blockquote>".$strings['nogoingback']."</blockquote>";

$strPages = implode("|",$idArray);
echo "<form action=\"./index.php\" method=\"post\">
<table class=\"form\" align=\"center\">
<tr><td>&nbsp;&nbsp;".$strings['Password']."&nbsp;&nbsp;</td><td><input type=\"password\" name=\"testpasswd\" maxlength=\"50\">&nbsp;&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"2\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"reallyremoveseveralpage\"><input type=\"hidden\" name=\"sid\" value=\"$sid\"><input type=\"hidden\" name=\"pageid\" value=\"$strPages\"><input type=\"hidden\" name=\"pagematch\" value=\"$pagematch\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr>";
if ($loginfailed) {
	echo "<tr><td colspan=\"2\" align=\"center\"><i>".$strings['IncorrectPassword']."</i></td></tr>";
}
echo "</table></form>
</td></tr></table>\n";
}


/*********************************************************************************/
/* Function: choosePageToRemove
/* Role: show a dialog with a list of pages with an option to completely remove them from the database
/* Parameters:
/*   - $c: connection to a database
/* Output:
/*   - nothing
/* Created 3/2008
/*********************************************************************************/
function choosePageToRemove($c) {
global $config_table;
global $strings;
global $sites;
global $lang;

echo "<div id=\"text\">\n";
echo "<h1>".$strings['titleRemovePage']."</h1>\n";
echo $strings['blablaRemovePage']."\n";
while ($bar=each($sites)) {
	$id = $bar[0];
	$table = $bar[1]['table'];
	$site = $bar[1]['site'];
	echo "<h2>$site (sid: $id)</h2>\n";
	echo "<ul><li>".$strings['Numberofpagestracked'].": ".countpages($c,$table);
	echo "</li>\n<li>".$strings['Diskusage'].": ".diskUsageTable ($c,$table);
	echo "</li></ul>\n";
	if (countpages($c,$table)>0) {
		echo "<table class=\"removepage\" align=\"center\"><tr><td valign=\"middle\" align=\"left\">".$strings['Remove']."</td></tr>\n";
		echo "<tr><td valign=\"middle\"><form action=\"index.php\" method=\"post\">";
		echo "<table><tr><td><SELECT NAME=\"pageid\">";
		$sql = "SELECT name,id, (ref+se+internal+other+old) as count FROM ${table}_pages ORDER BY name ASC";
		$res = mysql_query($sql,$c);
		while ($row = mysql_fetch_object($res)) {
			$name = $row->name;
			$thispagename = shortencenter($name,50);
			$count = $row->count;
			$thispageid = $row->id;
			echo "<option value=$thispageid>$thispagename: $count ".$strings['hits']."</option>";
		}
		echo "</select></td><td class=\"submit\">&nbsp;<input type=\"hidden\" name=\"sid\" value=\"$id\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"show\" value=\"testremovepage\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['RemovePage']."\"></td></tr></table></form>\n";
		//
		echo "<tr><td valign=\"middle\" align=\"left\">".$strings['RemovePagesMatch']."</td></tr>\n";
		echo "<tr><td valign=\"middle\"><form action=\"index.php\" method=\"post\">";
		echo "<table><tr><td><input name=\"pagematch\" class=\"txt\">";
		echo "</td><td class=\"submit\">&nbsp;<input type=\"hidden\" name=\"sid\" value=\"$id\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"show\" value=\"testremoveseveralpage\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['RemovePage']."\"></td></tr></table></form>\n";


		echo "</td></tr></table>";
	}
}
echo "</div>\n";
}

/*********************************************************************************/
/* Function: systemtest
/* Role: run a test of the system to make sure phpTrafficA can run on this platform
/*   to be used when the system changed to make sure it is still ok to run phpTrafficA
/* Parameters:
/*   - $c: connection to a database
/* Output:
/*   - nothing
/* Created 11/2007
/*********************************************************************************/
function systemtest($c) {
global $config_table;
global $strings;
global $tmpdirectory;
global $sites;
global $ip2c;

echo "<div id=\"text\">\n";
// Database
echo "\n<P><b>".$strings['Database']."</b>";
echo "<br>- ".$strings['MySQLversion'].": ";
$mysql_version = mysql_get_server_info();
echo "$mysql_version\n";

// Options version
echo "<br>- ".$strings['testConfigTable']." (<code>${config_table}</code>): ";
if (table_exists($c, $config_table)) {
	echo "<font color='#35BA4D'>".$strings['pass']."</font>.\n";
} else {
	echo "<font color='#FF0000'>".$strings['failed']."</font>.\n";
	echo " ".$strings['tablemissing']."\n";
}
// Site list
echo "<br>- ".$strings['testSites']." (<code>${config_table}_sites</code>): ";
if (table_exists($c, "${config_table}_sites")) {
	echo "<font color='#35BA4D'>".$strings['pass']."</font>.\n";
} else {
	echo "<font color='#FF0000'>".$strings['failed']."</font>.\n";
	echo " ".$strings['tablemissing']."\n";
}
// IP Ban
echo "<br>- ".$strings['testIPban']." (<code>${config_table}_ipban</code>): ";
if (table_exists($c, "${config_table}_ipban")) {
	echo "<font color='#35BA4D'>".$strings['pass']."</font>.\n";
} else {
	echo "<font color='#FF0000'>".$strings['failed']."</font>.\n";
	echo " ".$strings['tablemissing']."\n";
}

// PHP Server
echo "\n<P><b>".$strings['PHPserver']."</b>";
echo "<br>- ".$strings['PHPversion'].": ".phpversion()."\n";
echo "<br>- ".$strings['CheckGD'].": ";
if ((!extension_loaded('gd')) || (!function_exists('gd_info'))) {
    echo "<font color='#FF0000'>".$strings['failed']."</font>.\n";
} else {
	echo "<font color='#35BA4D'>".$strings['pass']."</font>.\n";
	$gd_info = gd_info();
	echo "<br>- ".$strings['GDversion'].": ".$gd_info['GD Version']."\n";
	echo "<br>- ".$strings['CheckFreeType'].": ";
	if ($gd_info['FreeType Support']) {
		echo "<font color='#35BA4D'>".$strings['pass']."</font>.\n";
	} else {
		echo "<font color='#FF0000'>".$strings['failed']."</font>.\n";
	}
}

// Temporary files
echo "</P><P><b>".$strings['filesystemtest']."</b>\n";
echo "<br>- ".$strings['tmpdiris'].": $tmpdirectory\n";
echo "<br>- ".$strings['testcreatetmpfile'].": ";
$file = "$tmpdirectory/test.txt";
$ok = true;
if (touch($file)) {
	echo "<font color='#35BA4D'>".$strings['pass']."</font>.";
} else {
	$ok = false;
	echo "<font color='#FF0000'>".$strings['failed']."</font>.";
}
echo "\n<br>- ".$strings['testdeletetmpfile'].": ";
if (unlink($file)) {
	echo "<font color='#35BA4D'>".$strings['pass']."</font>.";
} else {
	$ok = false;
	echo "<font color='#FF0000'>".$strings['failed']."</font>.";
}
echo "\n<br>- ".$strings['endfilesystemtest']." ".$strings['Status'].": ";
if ($ok) {
	echo "<font color='#35BA4D'>".$strings['pass']."</font>.</P>";
} else {
	echo "<font color='#FF0000'>".$strings['failed']."</font>.</P>";
}

// File with sql tables definitions
echo "</P><P><b>".$strings['sqlDefTest']."</b>\n";
$table = "";
$utf = "";
$file = "Php/Functions/sqlTables.sql.php";
echo "<br>- ".$strings['filetested'].": $file\n";
echo "<br>- ".$strings['testfileexist'].": ";
$ok = true;
if (!is_file($file)) {
	$ok = false;
	echo "<font color='#FF0000'>".$strings['failed']."</font>.";
} else {
	echo "<font color='#35BA4D'>".$strings['pass']."</font>.";
	echo "<br>- ".$strings['testreadingfile'].": ";
	include ("Php/Functions/sqlTables.sql.php");
	$sqllist = explode("\n", $sql);
	if (count($sqllist) != $nsql) {
		$ok = false;
		echo "<font color='#FF0000'>".$strings['failed']."</font>.";
		echo $strings['nLinesExpected'].": $nsql. ".$strings['nLinesFound'].": ".count($sqllist).".";
		echo "<br>- <font color='#FF0000'>".$strings['testTransfer']."</font>";
	} else {
		echo "<font color='#35BA4D'>".$strings['pass']."</font>.";
	}
}

// Testing site list
echo "</P>\n<P><b>".$strings['Sitelist']."</b>\n";
$extension = array("_retention", "_hour", "_day", "_uniq", "_path", "_referrer", "_keyword", "_browser", "_os", "_country", "_host", "_acces", "_pages", "_iplist");
while ($bar=each($sites)) {
	$id = $bar[0];
	$table = $bar[1]['table'];
	$site = $bar[1]['site'];
	echo "\n<br>- $site (sid: $id): ";
	$test = true;
	reset($extension);
	foreach ($extension as $ext) {
		if (!table_exists($c, "${table}${ext}")) {
			echo "\n<br>&nbsp;&nbsp;+ ".$strings['Testfortable']." <code>${table}${ext}</code> <font color='#FF0000'>".$strings['failed']."</font>. ".$strings['Tableismissing']."\n";
			$test = false;
		}
	}
	if ($test) {
		echo "\n<br>&nbsp;&nbsp;+ ".$strings['Tablesfor']." $site: <font color='#35BA4D'>".$strings['pass']."</font>.";
	}
	echo "\n<br>&nbsp;&nbsp;+ ".$strings['Numberofpagestracked'].": ".countpages($c,$table);
	echo "\n<br>&nbsp;&nbsp;+ ".$strings['Diskusage'].": ".diskUsageTable ($c,$table);
}

// IP to country database
echo "</P>\n<P><b>".$strings['IPtocountrytest']."</b>\n";
echo "\n<br>- ".$strings['testIP'];
$ok = true;
if ($ok) {
	echo " <font color='#35BA4D'>".$strings['pass']."</font>.";
} else {
	echo " <font color='#FF0000'>".$strings['failed']."</font>.";
}
echo "</div>\n";
}



/*********************************************************************************/
/* Function: echoOSWB
/* Role: displays the list of web browsers and OS known in an editable form
/* Parameters: 
/*   - $c: connection to a database
/* Output:
/*   - nothing
/*********************************************************************************/
function echoOSWB($c) {
global $config_table;
global $strings;
global $lang;
$sql = "SELECT * FROM $config_table";
$result = mysql_query ($sql,$c);
$config = array("x" =>"x");
while($row = mysql_fetch_array($result)) {
  $config = $config + array($row["variable"] => $row["value"] );
}
mysql_free_result ($result);
echo "<form action=\"index.php\" method=\"post\">
<table  align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['Configuration']."</td></tr>
<tr><td>".$strings['OSlist']."<a href=\"javascript:help('oslist')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\"><textarea cols=\"60\" rows=\"15\" name=\"os_id\">
$config[os_list]
</textarea></td>
<td rowspan=\"2\" align=\"center\" class=\"bleft\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updateoswb\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr> 
<tr><td>".$strings['Browserlist']."<a href=\"javascript:help('wblist')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\"><textarea cols=\"60\" rows=\"15\" name=\"wb_id\">
$config[browser_list]
</textarea></td></tr>
</table>
</form>\n";
}

/*********************************************************************************/
/* Function: echoSEBL
/* Role: displays the list of search engines and the referer blacklist an 
/*       editable form
/* Parameters: 
/*   - $c: connection to a database
/* Output:
/*   - nothing
/*********************************************************************************/
function echoSEBL($c) {
global $config_table;
global $strings;
global $lang;
$sql = "SELECT * FROM $config_table";
$result = mysql_query ($sql,$c);
$config = array("x" =>"x");
while($row = mysql_fetch_array($result)) {
  $config = $config + array($row["variable"] => $row["value"] );
}
mysql_free_result ($result);
echo "<form action=\"index.php\" method=\"post\">
<table  class=\"stat\" align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['Configuration']."</td></tr>
<tr><td>".$strings['Searchengines']."<a href=\"javascript:help('selist')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\"><textarea cols=\"60\" rows=\"15\" name=\"se_id\">
$config[search_engines]
</textarea></td>
<td rowspan=\"2\" align=\"center\" class=\"bleft\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updatesebl\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr> 
<tr><td>".$strings['Refererblacklist']."<a href=\"javascript:help('blist')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\"><textarea cols=\"60\" rows=\"15\" name=\"bl_id\">
$config[blacklist]
</textarea></td></tr>  
</table>
</form>\n";
}

/*********************************************************************************/
/* Function: showOptions
/* Role: dialog to set various options such as
/*  - display options (number of items in a top list, colors...)
/*  - number of hosts in latest hosts table
/*  - search engines are referrers or not
/* Parameters:
/*   - $c: connection to a database
/* Output:
/*   - nothing
/* Created 11/2006 from displayoption and echoSaveHosts
/* Changed 01/2007: added option to set visit cut-off time
/*********************************************************************************/
function showOptions($c) {
global $config_table;
global $strings;
global $lang;
$sql = "SELECT * FROM $config_table";
$result = mysql_query ($sql,$c);
$config = array("x" =>"x");
while($row = mysql_fetch_array($result)) {
  $config = $config + array($row["variable"] => $row["value"] );
}
mysql_free_result ($result);
// Display options
echo "<form action=\"index.php\" method=\"post\">
<table align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['Displayoptions']."</td></tr>
<tr><td>".$strings['defNTop']."</td><td class=\"padv\" width=\"15%\"><input size=\"5\" name=\"ntop\" value=\"".$config['ntop']."\"></td><td align=\"center\" rowspan=\"2\" class=\"bleft\" width=\"15%\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updatedisplay\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr>
<tr><td>".$strings['defNTopLong']."</td><td class=\"padv\"><input size=\"5\" name=\"ntoplong\" value=\"".$config['ntoplong']."\"></td></tr>
</table>
</form>\n";
// Number of lines in latest hosts
echo "<form action=\"index.php\" method=\"post\">
<table align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['Numberoflinesinlatesthosts']."</td></tr>
<tr><td>".$strings['Numberoflinesinlatesthosts']."<a href=\"javascript:help('savehost')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\" width=\"15%\"><input size=\"5\" name=\"save_hosts\" value=\"$config[save_host]\"></td><td align=\"center\" width=\"15%\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updatesh\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr> 
</table>
</form>\n";
// Search engines referrers or not?
echo "<form action=\"index.php\" method=\"post\">
<table align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['SearchEnginesAreReferrers']."</td></tr>
<tr><td>".$strings['SearchEnginesAreReferrers']."<a href=\"javascript:help('sereferrers')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\" width=\"15%\"><select name=\"serefs\">";
if ($config['seref']) {
	echo "<option value=\"1\" selected>".$strings['Yes']."</option>";
	echo "<option value=\"0\">".$strings['No']."</option>";
} else {
	echo "<option value=\"1\">".$strings['Yes']."</option>";
	echo "<option value=\"0\" selected>".$strings['No']."</option>";
}
echo "</select></td><td align=\"center\" width=\"15%\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updateserefs\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr> 
</table>
</form>\n";
// Visit cut-off time
echo "<form action=\"index.php\" method=\"post\">
<table align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['VisitCutOffTime']."</td></tr>
<tr><td>".$strings['VisitCutOffTime']."<a href=\"javascript:help('visitcutoff')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\" width=\"15%\">
<input size=\"5\" name=\"visitcutoff\" value=\"$config[visitcutoff]\">
</td><td align=\"center\" width=\"15%\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updatevisitcutoff\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr> 
</table>
</form>\n";
// String length
echo "<form action=\"index.php\" method=\"post\">
<table align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['URLTrimFactor']."</td></tr>
<tr><td>".$strings['URLTrimFactorLong']."<a href=\"javascript:help('URLTrimFactor')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\" width=\"15%\">
<input size=\"5\" name=\"URLTrimFactor\" value=\"$config[stringLengthsFactor]\">
</td><td align=\"center\" width=\"15%\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updateURLTrim\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr> 
</table>
</form>\n";
// Referrer marked as new duration
echo "<form action=\"index.php\" method=\"post\">
<table align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['referrerNewDuration']."</td></tr>
<tr><td>".$strings['referrerNewDurationLong']."<a href=\"javascript:help('referrerNewDuration')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\" width=\"15%\">
<input size=\"5\" name=\"referrerNewDuration\" value=\"$config[referrerNewDuration]\">
</td><td align=\"center\" width=\"15%\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updateReferrerNew\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr> 
</table>
</form>\n";
// Options for auto-clean of referrer, keywords, IP list, and path tables
echo "<form action=\"index.php\" method=\"post\">
<table align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['autoCleanRefKwdIPPath']."</td></tr>
<tr><td>".$strings['doAutoCleanRKIP']."<a href=\"javascript:help('autoCleanRKIP')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\" width=\"15%\">
<select name=\"autoclean\">";
if ($config['cleanRefIPKwdPath']) {
	echo "<option value=\"1\" selected>".$strings['Yes']."</option>";
	echo "<option value=\"0\">".$strings['No']."</option>";
} else {
	echo "<option value=\"1\">".$strings['Yes']."</option>";
	echo "<option value=\"0\" selected>".$strings['No']."</option>";
}
echo "</select>
</td><td align=\"center\" width=\"15%\" rowspan=\"2\" class=\"bleft\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updateCleanRKIP\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr>
<tr><td>".$strings['timeAutoClean']."</td><td><input size=\"5\" name=\"time\" value=\"$config[cleanRefIPKwdPathInt]\"></td></tr>
</table>
</form>\n";
// Options for auto-clean of access tables
echo "<form action=\"index.php\" method=\"post\">
<table align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['autoCleanAccess']."</td></tr>
<tr><td>".$strings['doAutoCleanAccess']."<a href=\"javascript:help('autoCleanAccess')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td class=\"padv\" width=\"15%\">
<select name=\"autoclean\">";
if ($config['cleanAccess']) {
	echo "<option value=\"1\" selected>".$strings['Yes']."</option>";
	echo "<option value=\"0\">".$strings['No']."</option>";
} else {
	echo "<option value=\"1\">".$strings['Yes']."</option>";
	echo "<option value=\"0\" selected>".$strings['No']."</option>";
}
echo "</select>
</td><td align=\"center\" width=\"15%\" rowspan=\"2\" class=\"bleft\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updateCleanAccess\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr>
<tr><td>".$strings['timeAutoClean']."</td><td><input size=\"5\" name=\"time\" value=\"$config[cleanAccessInt]\"></td></tr>
</table>
</form>\n";
}

/*********************************************************************************/
/* Function: echositelist
/* Role: displays the list of known sites, a form to change their properties, 
/*       and a form to track a new domain
/* Parameters: 
/*   - $c: connection to a database
/* Output:
/*   - nothing
/*********************************************************************************/
function echositelist($c) {
global $sites;
global $DEMO;
global $strings;
global $lang;
echo "<table align=\"center\">
<tr class=\"title\"><td colspan=\"10\">".$strings['Existingdatabases']."</td></tr>
<tr class=\"caption\"><td>".$strings['Domain']."</td><td>".$strings['Table']."</td><td>ID</td><td>".$strings['Public']."<a href=\"javascript:help('public')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></a></td><td>".$strings['TrimURL']."<a href=\"javascript:help('trim')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td>".$strings['Countbots']."<a href=\"javascript:help('countbots')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td>".$strings['Counter']."<a href=\"javascript:help('counter')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td>".$strings['Time']."</td><td>".$strings['timediff']."<a href=\"javascript:help('timediff')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td>&nbsp;</td></tr>\n";
while ($bar=each($sites)) {
	$id = $bar[0];
	$table = $bar[1]['table'];
	$site = $bar[1]['site'];
	$public = $bar[1]['public'];
	$trim = $bar[1]['trim'];
	$crawlercount = $bar[1]['crawler'];
	$counter = $bar[1]['counter'];
	$timediff = $bar[1]['timediff'];
	$tabletxt = $table;
	if ($DEMO) $tabletxt = "****";
	echo "<form name=\"site$id\" action=\"./index.php\" method=\"post\">";
	echo "<tr><td>$site</td><td>$tabletxt</td><td>$id</td><td align=\"center\"><select name=\"public\">";
	if ($public) {
		echo "<option value=\"1\" selected>".$strings['Public']."</option>";
		echo "<option value=\"0\">".$strings['Private']."</option>";
	} else {
		echo "<option value=\"1\">".$strings['Public']."</option>";
		echo "<option value=\"0\" selected>".$strings['Private']."</option>";
	}
	echo "</select></td>";
	echo "<td align=\"center\"><select name=\"trim\">";
	if ($trim) {
		echo "<option value=\"1\" selected>".$strings['Yes']."</option>";
		echo "<option value=\"0\">".$strings['No']."</option>";
	} else {
		echo "<option value=\"1\">".$strings['Yes']."</option>";
		echo "<option value=\"0\" selected>".$strings['No']."</option>";
	}
	echo "</select></td>";
	echo "<td align=\"center\"><select name=\"crawler\">";
	if ($crawlercount) {
		echo "<option value=\"1\" selected>".$strings['Yes']."</option>";
		echo "<option value=\"0\">".$strings['No']."</option>";
	} else {
		echo "<option value=\"1\">".$strings['Yes']."</option>";
		echo "<option value=\"0\" selected>".$strings['No']."</option>";
	}
	echo "</select></td>";
	echo "<td align=\"center\"><select name=\"counter\">";
	if ($counter) {
		echo "<option value=\"1\" selected>".$strings['Yes']."</option>";
		echo "<option value=\"0\">".$strings['No']."</option>";
	} else {
		echo "<option value=\"1\">".$strings['Yes']."</option>";
		echo "<option value=\"0\" selected>".$strings['No']."</option>";
	}
	echo "</select></td>";
	$timeserver = date("H:i", time()+$timediff*3600);
	echo "<td align=\"center\">$timeserver</td>";
	echo "<td align=\"center\"><input name=\"timediff\" size=\"2\" value=\"$timediff\"></td>";
	echo "<td align=\"center\" class=\"padv\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"updatesite\"><input type=\"hidden\" name=\"sid\" value=\"$id\"><input type=\"submit\" value=\"".$strings['Change']."\"></td>";
	echo "</tr></form>\n";
}
echo "</table>\n";
echo "<form action=\"./index.php\"  method=\"post\">
<table align=\"center\">
<tr class=\"title\"><td colspan=\"5\">".$strings['Trackanewdomain']."</td>
<tr><td>".$strings['Domainaddress']."<a href=\"javascript:help('domain')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td colspan=\"3\" class=\"padv\"><input size=\"40\" name=\"domain\" value=\"\"></td>
<td rowspan=\"4\" align=\"center\" class=\"bleft\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"hidden\" name=\"todo\" value=\"adddomain\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr>
<tr><td>".$strings['Tablename']."<a href=\"javascript:help('table')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td colspan=\"3\" class=\"padv\"><input size=\"40\" name=\"table\" value=\"\"></td></tr>
<tr><td class=\"padv\" colspan=\"4\">
<table class=\"basic\" width=\"100%\"><tr><td>".$strings['Public']."<a href=\"javascript:help('public')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td><select name=\"public\"><option value=\"1\" selected>".$strings['Public']."</option><option value=\"0\">".$strings['Private']."</option></select></td><td>".$strings['TrimURL']."<a href=\"javascript:help('trim')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td><select name=\"trim\"><option value=\"1\" selected>".$strings['Yes']."</option><option value=\"0\">".$strings['No']."</option></select></td><td>".$strings['Countbots']."<a href=\"javascript:help('countbots')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td><select name=\"crawler\"><option value=\"1\" selected>".$strings['Yes']."</option><option value=\"0\">".$strings['No']."</option></select></td><td>".$strings['Counter']."<a href=\"javascript:help('counter')\" class=\"img\"> <IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></td><td><select name=\"counter\"><option value=\"1\" selected>".$strings['Yes']."</option><option value=\"0\" selected>".$strings['No']."</option></select></td></tr></table></td></tr>
<tr><td class=\"padv\">".$strings['timediff']." (".$strings['hours'].") <a href=\"javascript:help('timediff')\" class=\"img\"><IMG src=\"Img/System/help.png\" width=\"12\" height=\"12\" align=\"top\" border=\"0\" alt=\"".$strings['Helpme']."\" title=\"".$strings['Helpme']."\"></a></td><td class=\"padv\" colspan=\"3\"><input size=\"4\" name=\"timediff\" value=\"0\"></td></tr>
</table>
</form>";
}

/*********************************************************************************/
/* Function: updateSEBL
/* Role: update the list of search engines and referer blacklist
/* Parameters: 
/*   - $c: connection to a database
/*   - $se_id: new configuration for search engines
/*   - $bl_id: new configuration for referer blacklist
/* Output:
/*   - nothing
/*********************************************************************************/
function updateSEBL($c,$se_id, $bl_id) {
global $config_table;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['SavingEBBL']."<br>".$strings['notindemo']."</div>\n";
} else {
	$se_id = str_replace("\r\n", "\n", $se_id);
	$se_id = str_replace("\n\r", "\n", $se_id);
	$se_id = preg_replace('/\n\n+/', "\n", $se_id);
	$se_id = preg_replace('~^(\n*)(.*?)(\n*)$~m', "\\2", $se_id);
	$bl_id = str_replace("\r\n", "\n", $bl_id);
	$bl_id = str_replace("\n\r", "\n", $bl_id);
	$bl_id = preg_replace('/\n\n+/', "\n", $bl_id);
	$bl_id = preg_replace('~^(\n*)(.*?)(\n*)$~m', "\\2", $bl_id);
	$sql[0] = "UPDATE $config_table SET value='$se_id' WHERE variable LIKE 'search_engines'"; 
	$sql[1] = "UPDATE $config_table SET value='$bl_id' WHERE variable LIKE 'blacklist'";
	echo "<div class=\"error\">".$strings['SavingEBBL'];
	for ($i=0;$i<2;$i++) {
		$result = mysql_query ($sql[$i],$c);
	}
	echo "<br>".$strings['Done']."</div>";
}
}

/*********************************************************************************/
/* Function: updateSERefs
/* Role: update the option weather SE are referrers or not
/* Parameters: 
/*   - $c: connection to a database
/*   - $serefs
/* Output:
/*   - nothing
/*********************************************************************************/
function updateSERefs($c,$serefs) {
global $config_table;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['SearchEnginesAreReferrers']."<br>".$strings['notindemo']."</div>\n";
} else {
	$sql = "UPDATE $config_table SET value='$serefs' WHERE variable LIKE 'seref'";
	$result = mysql_query ($sql,$c);
	echo "<div class=\"error\">".$strings['SearchEnginesAreReferrers']." - ".$strings['Saved']."</div>\n";
}
}

/*********************************************************************************/
/* Function: updateURLTrim
/* Role: update the factor used to trim URL
/* Parameters: 
/*   - $c: connection to a database
/*   - $stringLengthsFactor
/* Output:
/*   - nothing
/*********************************************************************************/
function updateURLTrim($c, $stringLengthsFactor) {
global $config_table;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['SavingSTLF']."<br>".$strings['notindemo']."</div>\n";
} else {
	$sql = "UPDATE $config_table SET value='$stringLengthsFactor' WHERE variable LIKE 'stringLengthsFactor'";
	$result = mysql_query ($sql,$c);
	echo "<div class=\"error\">".$strings['URLTrimFactor']." - ".$strings['Saved']."</div>\n";
}
}

/*********************************************************************************/
/* Function: updateCleanRKIP
/* Role: update settings for auto clean of  referrer, keyword, IP list, and path tables
/* Parameters: 
/*   - $c: connection to a database
/*   - $autoclean: do it?
/*   - $ndays: interval, in days
/* Output:
/*   - nothing
/*********************************************************************************/
function updateCleanRKIP($c, $autoclean, $ndays) {
global $config_table;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['SaveOption']."<br>".$strings['notindemo']."</div>\n";
} else {
	$sql = "UPDATE $config_table SET value='$autoclean' WHERE variable LIKE 'cleanRefIPKwdPath'";
	$result = mysql_query ($sql,$c);
	$sql = "UPDATE $config_table SET value='$ndays' WHERE variable LIKE 'cleanRefIPKwdPathInt'";
	$result = mysql_query ($sql,$c);
	echo "<div class=\"error\">".$strings['autoCleanRefKwdIPPath']." - ".$strings['Saved']."</div>\n";
}
}

/*********************************************************************************/
/* Function: updateCleanAccess
/* Role: update settings for auto clean of access tables
/* Parameters: 
/*   - $c: connection to a database
/*   - $autoclean: do it?
/*   - $ndays: interval, in days
/* Output:
/*   - nothing
/*********************************************************************************/
function updateCleanAccess($c, $autoclean, $ndays) {
global $config_table;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['SaveOption']."<br>".$strings['notindemo']."</div>\n";
} else {
	$sql = "UPDATE $config_table SET value='$autoclean' WHERE variable LIKE 'cleanAccess'";
	$result = mysql_query ($sql,$c);
	$sql = "UPDATE $config_table SET value='$ndays' WHERE variable LIKE 'cleanAccessInt'";
	$result = mysql_query ($sql,$c);
	echo "<div class=\"error\">".$strings['autoCleanAccess']." - ".$strings['Saved']."</div>\n";
}
}

/*********************************************************************************/
/* Function: updateReferrerNewTime
/* Role: update the time to keep referrers marked as new
/* Parameters: 
/*   - $c: connection to a database
/*   - $nWeeks
/* Output:
/*   - nothing
/*********************************************************************************/
function updateReferrerNewTime($c, $nWeeks) {
global $config_table;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['SaveOption']."<br>".$strings['notindemo']."</div>\n";
} else {
	$sql = "UPDATE $config_table SET value='$nWeeks' WHERE variable LIKE 'referrerNewDuration'";
	$result = mysql_query ($sql,$c);
	echo "<div class=\"error\">".$strings['referrerNewDuration']." - ".$strings['Saved']."</div>\n";
}
}

/*********************************************************************************/
/* Function: updateVisitCutOff
/* Role: update the visit cut-off time
/* Parameters: 
/*   - $c: connection to a database
/*   - $visitcutoff
/* Output:
/*   - nothing
/*********************************************************************************/
function updateVisitCutOff($c,$visitcutoff) {
global $config_table;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['SavingNH']."<br>".$strings['notindemo']."</div>\n";
} else {
	$sql = "UPDATE $config_table SET value='$visitcutoff' WHERE variable LIKE 'visitcutoff'";
	$result = mysql_query ($sql,$c);
	echo "<div class=\"error\">".$strings['VisitCutOffTime']." - ".$strings['Saved']."</div>\n";
}
}

/*********************************************************************************/
/* Function: updatedisplay
/* Role: update the display parameters
/* Parameters: 
/*   - $c: connection to a database
/*   - $ntop
/*   - $ntoplong
/* Output:
/*   - nothing
/*********************************************************************************/
function updatedisplay($c,$ntop,$ntoplong) {
global $config_table;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['SavingNH']."<br>".$strings['notindemo']."</div>\n";
} else {
	$sql = "UPDATE $config_table SET value='$ntop' WHERE variable LIKE 'ntop'";
	$result = mysql_query ($sql,$c);
	$sql = "UPDATE $config_table SET value='$ntoplong' WHERE variable LIKE 'ntoplong'";
	$result = mysql_query ($sql,$c);
	echo "<div class=\"error\">".$strings['Displayoptions']." - ".$strings['Saved']."</div>\n";
}
}

/*********************************************************************************/
/* Function: updateSaveHosts
/* Role: update the number of hosts to be saved in the latest hosts table
/* Parameters: 
/*   - $c: connection to a database
/*   - $save_hosts: new number
/* Output:
/*   - nothing
/*********************************************************************************/
function updateSaveHosts($c,$save_hosts) {
global $config_table;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['SavingNH']."<br>".$strings['notindemo']."</div>\n";
} else {
	$sql = "UPDATE $config_table SET value='$save_hosts' WHERE variable LIKE 'save_host'";
	echo "<div class=\"error\">".$strings['Numberoflinesinlatesthosts'];
	$result = mysql_query ($sql,$c);
	echo " - ".$strings['Saved']."</div>\n";
}
}

/*********************************************************************************/
/* Function: updateOSWb
/* Role: update the list of OS and web browsers
/* Parameters: 
/*   - $c: connection to a database
/*   - $os_id: new list of OS
/*   - $wb_id: new list of web browsers
/* Output:
/*   - nothing
/*********************************************************************************/
function updateOSWb($c,$os_id, $wb_id) {
global $config_table;
global $DEMO;
global $strings;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['SavingOSWL']."<br>".$strings['notindemo']."</div>\n";
} else {
	$os_id = str_replace("\r\n", "\n", $os_id); // Simplify new line chars
	$os_id = str_replace("\n\r", "\n", $os_id); // Simplify new line chars
	$os_id = preg_replace('/\n\n+/', "\n", $os_id); // Remove subsequent new line chars
	$os_id = preg_replace('~^(\n*)(.*?)(\n*)$~m', "\\2", $os_id); // Remove new line chars at the end of the string
	$wb_id = str_replace("\r\n", "\n", $wb_id);
	$wb_id = str_replace("\n\r", "\n", $wb_id);
	$wb_id = preg_replace('/\n\n+/', "\n", $wb_id);
	$wb_id = preg_replace('~^(\n*)(.*?)(\n*)$~m', "\\2", $wb_id);
	$sql[0] = "UPDATE $config_table SET value='$os_id' WHERE variable LIKE 'os_list'"; 
	$sql[1] = "UPDATE $config_table SET value='$wb_id' WHERE variable LIKE 'browser_list'";
	echo "<div class=\"error\">".$strings['SavingOSWL'];
	for ($i=0;$i<2;$i++) {
		$result = mysql_query ($sql[$i],$c);
	}
	echo "<br>".$strings['Done']."</div>\n";
	}
}

/*********************************************************************************/
/* Function: adddomain
/* Role: add a new domain to track
/* Parameters: 
/*   - $c: connection to a database
/*   - $domain: domain URL
/*   - $table: root for SQL table names
/*   - $public: stats public or private (1/0)
/*   - $trim: trim URL? (1/0)
/*   - $countbots: count bots? (1/0)
/*   - $counter: counter? (1/0)
/* Output:
/*   - nothing
/*********************************************************************************/
function adddomain($c, $domain, $table, $public, $trim, $countbots, $counter, $timediff) {
global $sites;
global $DEMO;
global $strings;
global $config_table;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['Trackanewdomain']."<br>".$strings['notindemo']."</div>\n";
} else {
	$ok = 1;
	if (($domain=="")||($table=="")) {
	echo "<div class=\"error\">".$strings['addressandtableempty']."</div>\n";
	return;
	}
	// Clean up the table and domain names (remove all spaces)	
	$table = str_replace(" ", "", $table);
	$domain = str_replace(" ", "", $domain);
	$domain = str_replace("http://", "", $domain);
	$domain = str_replace("//", "/", $domain);
	$pos = strrpos($domain, '/');
	if ($pos == (strlen($domain)-1)) $domain = substr($domain,0,strlen($domain)-1);
	while ($bar=each($sites)) {
		$thisid = $bar[0];
		$thistable = $bar[1]['table'];
		$thissite = $bar[1]['site'];
		if (($thissite == $domain)|| ($thistable == $table)) {
			echo "<div class=\"error\">".$strings['domaintablealreadyuse']."</div>\n";
			return;
		}
	}
	reset($sites);
	echo "<div class=\"error\">";
	// Create an ID
	$test = true;
	while ($test) {
		$newID = rand (10000, 1000000);
		if (!array_key_exists($newID, $sites)) $test = false;
	}
	// Update the database
	$public=intval($public);
	$trim=intval($trim);
	$countbots=intval($countbots);
	$counter=intval($counter);
	$timediff=intval($timediff);
	$sql = "INSERT INTO `${config_table}_sites` VALUES ('$newID', '$table', '$domain', '$public', '$trim', '$countbots', '$counter', '$timediff')";
	if (!mysql_query($sql,$c)) {
		echo ("Error with database at line 445 in setup.inc.php: ".mysql_error()."<br>Was working on $sql</div>\n");
		return;
	}
	// Create database tables for this site
	create_db($c,$table);
	echo "</div>\n";
	// Re-read site list
	$sites = get_sites($c);
}
}

/*********************************************************************************/
/* Function: updatedomain
/* Role: update domain properties
/* Parameters:
/*   - $c: connection to db
/*   - $sid: domain ID
/*   - $public: stats public or private (1/0)
/*   - $trim: trim URL? (1/0)
/*   - $countbots: count bots? (1/0)
/*   - $counter: counter? (1/0)
/*   - $timediff: time difference between server and site
/* Output:
/*   - nothing
/*********************************************************************************/
function updatedomain($c, $sid, $public, $trim, $countbots, $counter, $timediff) {
global $sites, $config_table, $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
} else {
	$public=intval($public);
	$trim=intval($trim);
	$countbots=intval($countbots);
	$counter=intval($counter);
	$timediff=intval($timediff);
	$sql = "UPDATE ${config_table}_sites SET public=$public, trim=$trim, crawler=$countbots, counter=$counter, timediff=$timediff WHERE id=$sid";
	$res = mysql_query ($sql,$c);
	$sites = get_sites($c);
}
}

/*********************************************************************************/
/* Function: testcleanup
/* Role: simulation of DB cleanup and form to fo ahead
/* Parameters: 
/*   - $c: connection to Db
/* Output:
/*   - nothing
/* Created: 05/2006
/*********************************************************************************/
function testcleanup($c) {
global $sites;
global $strings;
global $lang;
$twomonthago = date("Y-m-d H:i:s",strtotime("-2 month"));
$fourmonthago = date("Y-m-d H:i:s",strtotime("-4 month"));
$twomonthagobis = date("Y-m-d",strtotime("-2 month"));
$totalKwd = 0;
$totalRef = 0;
$totalIP = 0;
$totalPath = 0;
$totalAcces = 0;
$totalDeleteRef1 = 0;
$totalDeleteRef2 = 0;
$totalDeleteKwd1 = 0;
$totalDeleteKwd2 = 0;
$totalDeleteIP1 = 0;
$totalDeleteIP2 = 0;
$totalDeletePath1 = 0;
$totalDeletePath2 = 0;
$totalDeleteAcces = 0;
echo "<table align=\"center\">
<tr class=\"title\"><td colspan=\"6\">".$strings['Databaseinformation']."</td></tr>
<tr class=\"caption\"><td rowspan=\"2\">".$strings['Domain']."</td><td colspan=5\">".$strings['Numberoflinesindatabase']."</td></tr>
<tr class=\"caption\"><td>".$strings['Keywords']."</td><td>".$strings['Referrers']."</td><td>".$strings['IPaddress']."</td><td>".$strings['Paths']."</td><td>".$strings['Access']."</td></tr>\n";
reset($sites);
while ($bar=each($sites)) {
	$sid = $bar[0];
	$table = $bar[1]['table'];
	$domain= $bar[1]['site'];
	// Check the database for possibilities
	// Number of referers that can be cleaned up  
	$req = "SELECT COUNT(*)  as count FROM ${table}_referrer";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nrefs[$sid] = $count['count'];
	if ($nrefs[$sid]=='') $nrefs[$sid]=0;
	$totalRef += $nrefs[$sid];
	$req = "SELECT COUNT(*)  as count FROM ${table}_referrer WHERE count=1 AND last<\"$twomonthago\"";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nrefdelete[$sid] = $count['count'];
	if ($nrefdelete[$sid]=='') $nrefdelete[$sid]=0;
	$req = "SELECT COUNT(*)  as count FROM ${table}_referrer WHERE count=2 AND last<\"$fourmonthago\"";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nrefdelete2[$sid] = $count['count'];
	if ($nrefdelete2=='') $nrefdelete2[$sid]=0;
	if ($nrefs[$sid] != 0) {
		$pcRef1[$sid] = intval(100.*$nrefdelete[$sid]/$nrefs[$sid]);
		$pcRef2[$sid] = intval(100.*$nrefdelete2[$sid]/$nrefs[$sid]);
	} else {
		$pcRef1[$sid] = 0;
		$pcRef2[$sid] = 0;
	}
	$pcRef[$sid] = $pcRef1[$sid] + $pcRef2[$sid];
	$totalDeleteRef1 += $nrefdelete[$sid];
	$totalDeleteRef2 += $nrefdelete2[$sid];
	// Number of keyword entries that can be cleaned up
	$req = "SELECT COUNT(*)  as count FROM ${table}_keyword";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nkwd[$sid] = $count['count'];
	if ($nkwd[$sid]=='') $nkwd[$sid]=0;
	$totalKwd += $nkwd[$sid];
	$req = "SELECT COUNT(*)  as count FROM ${table}_keyword WHERE  count=1 AND last<'$twomonthago'";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nkwddelete[$sid] = $count['count'];
	if ($nkwddelete[$sid]=='') $nkwddelete[$sid]=0;
	$req = "SELECT COUNT(*)  as count FROM ${table}_keyword WHERE count=2 AND last<\"$fourmonthago\"";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nkwddelete2[$sid] = $count['count'];
	if ($nkwddelete2[$sid]=='') $nkwddelete2[$sid]=0;
	if ($nkwd[$sid] != 0) {
		$pcKwd1[$sid] = intval(100.*$nkwddelete[$sid]/$nkwd[$sid]);
		$pcKwd2[$sid] = intval(100.*$nkwddelete2[$sid]/$nkwd[$sid]);
	} else {
		$pcKwd1[$sid] = 0;
		$pcKwd2[$sid] = 0;
	}
	$pcKwd[$sid] = $pcKwd1[$sid] + $pcKwd2[$sid];
	$totalDeleteKwd1 += $nkwddelete[$sid];
	$totalDeleteKwd2 += $nkwddelete2[$sid];

	// Number of IP addresses that can be cleaned up  
	$req = "SELECT COUNT(*)  as count FROM ${table}_iplist";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nIP[$sid] = $count['count'];
	if ($nIP[$sid]=='') $nIP[$sid]=0;
	$totalIP += $nIP[$sid];
	$req = "SELECT COUNT(*)  as count FROM ${table}_iplist WHERE count=1 AND last<\"$twomonthago\"";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nIPdelete[$sid] = $count['count'];
	if ($nIPdelete[$sid]=='') $nIPdelete[$sid]=0;
	$req = "SELECT COUNT(*)  as count FROM ${table}_iplist WHERE count=2 AND last<\"$fourmonthago\"";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nIPdelete2[$sid] = $count['count'];
	if ($nIPdelete2[$sid]=='') $nIPdelete2[$sid]=0;
	if ($nIP[$sid] != 0) {
		$pcIP1[$sid] = intval(100.*$nIPdelete[$sid]/$nIP[$sid]);
		$pcIP2[$sid] = intval(100.*$nIPdelete2[$sid]/$nIP[$sid]);
	} else {
		$pcIP1[$sid] = 0;
		$pcIP2[$sid] = 0;
	}
	$pcIP[$sid] = $pcIP1[$sid] + $pcIP2[$sid];
	$totalDeleteIP1 += $nIPdelete[$sid];
	$totalDeleteIP2 += $nIPdelete2[$sid];

	// Number of path that can be cleaned up  
	$req = "SELECT COUNT(*)  as count FROM ${table}_path";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nPath[$sid] = $count['count'];
	if ($nPath[$sid]=='') $nPath[$sid]=0;
	$totalPath += $nPath[$sid];
	$req = "SELECT COUNT(*)  as count FROM ${table}_path WHERE count=1 AND last<\"$twomonthago\"";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nPathdelete[$sid] = $count['count'];
	if ($nPathdelete[$sid]=='') $nPathdelete[$sid]=0;
	$req = "SELECT COUNT(*)  as count FROM ${table}_path WHERE count=2 AND last<\"$fourmonthago\"";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$nPathdelete2[$sid] = $count['count'];
	if ($nPathdelete2[$sid]=='') $nPathdelete2[$sid]=0;
	if ($nPath[$sid] != 0) {
		$pcPath1[$sid] = intval(100.*$nPathdelete[$sid]/$nPath[$sid]);
		$pcPath2[$sid] = intval(100.*$nPathdelete2[$sid]/$nPath[$sid]);
	} else {
		$pcPath1[$sid] = 0;
		$pcPath2[$sid] = 0;
	}
	$pcPath[$sid] = $pcPath1[$sid] + $pcPath2[$sid];
	$totalDeletePath1 += $nPathdelete[$sid];
	$totalDeletePath2 += $nPathdelete2[$sid];

	// Access table
	$req = "SELECT COUNT(*)  as count FROM ${table}_acces";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$naccess[$sid] = $count['count'];
	if ($naccess[$sid]=='') $naccess[$sid]=0;
	$req = "SELECT COUNT(*)  as count FROM ${table}_uniq";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$naccess[$sid] += $count['count'];
	$req = "SELECT COUNT(*)  as count FROM ${table}_acces WHERE date<'$twomonthagobis' AND label>0";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$naccessdelete[$sid] = $count['count'];
	if ($naccessdelete[$sid]=='') $naccessdelete[$sid]=0;
	$req = "SELECT COUNT(*)  as count FROM ${table}_uniq WHERE date<'$twomonthagobis' AND label>0";
	$res = mysql_query($req,$c);
	$count=mysql_fetch_array($res);
	$naccessdelete[$sid] += $count['count'];
	$totalAcces += $naccess[$sid];
	$totalDeleteAcces += $naccessdelete[$sid];

	// Size of the tables
	$sql = "SHOW TABLE STATUS LIKE '${table}_keyword'";
 	$result = mysql_query($sql); 
	$row = mysql_fetch_array($result);
 	$sizeKwd = size_hum_read($row['Data_length']+$row['Index_length']);
	$sql = "SHOW TABLE STATUS LIKE '${table}_referrer'";
 	$result = mysql_query($sql); 
	$row = mysql_fetch_array($result);
 	$sizeRef = size_hum_read($row['Data_length']+$row['Index_length']);
	$sql = "SHOW TABLE STATUS LIKE '${table}_iplist'";
 	$result = mysql_query($sql); 
	$row = mysql_fetch_array($result);
 	$sizeIP = size_hum_read($row['Data_length']+$row['Index_length']);
	$sql = "SHOW TABLE STATUS LIKE '${table}_path'";
 	$result = mysql_query($sql); 
	$row = mysql_fetch_array($result);
 	$sizePath = size_hum_read($row['Data_length']+$row['Index_length']);
	$sql = "SHOW TABLE STATUS LIKE '${table}_acces'";
 	$result = mysql_query($sql); 
	$row = mysql_fetch_array($result);
 	$tmp = ($row['Data_length']+$row['Index_length']);
	$sql = "SHOW TABLE STATUS LIKE '${table}_uniq'";
 	$result = mysql_query($sql); 
	$row = mysql_fetch_array($result);
 	$sizeAcces = size_hum_read($row['Data_length']+$row['Index_length']+$tmp);

	// Table
	echo "<tr><td>$domain</td><td>".$nkwd[$sid]." ($sizeKwd)</td><td>".$nrefs[$sid]." ($sizeRef)</td><td>".$nIP[$sid]." ($sizeIP)</td><td>".$nPath[$sid]." ($sizePath)</td><td>".$naccess[$sid]." ($sizeAcces)</td></tr>\n";
}
if ($totalKwd != 0) {
	$pcKwd1 = intval(100.*$totalDeleteKwd1/$totalKwd);
	$pcKwd2 = intval(100.*$totalDeleteKwd2/$totalKwd);
} else {
	$pcKwd1 = 0;
	$pcKwd2 = 0;
}
$pcKwd = $pcKwd1 + $pcKwd2;
if ($totalRef != 0) {
	$pcRef1 = intval(100.*$totalDeleteRef1/$totalRef);
	$pcRef2 = intval(100.*$totalDeleteRef2/$totalRef);
} else {
	$pcRef1 = 0;
	$pcRef2 = 0;
}
$pcRef = $pcRef1 + $pcRef2;
if ($totalIP != 0) {
	$pcIP1 = intval(100.*$totalDeleteIP1/$totalIP);
	$pcIP2 = intval(100.*$totalDeleteIP2/$totalIP);
} else {
	$pcIP1 = 0;
	$pcIP2 = 0;
}
$pcIP = $pcIP1 + $pcIP2;
if ($totalPath != 0) {
	$pcPath1 = intval(100.*$totalDeletePath1/$totalPath);
	$pcPath2 = intval(100.*$totalDeletePath2/$totalPath);
} else {
	$pcPath1 = 0;
	$pcPath2 = 0;
}
$pcPath = $pcPath1 + $pcPath2;
$totalDeleteKwd = $totalDeleteKwd1 + $totalDeleteKwd2;
$totalDeleteRef = $totalDeleteRef1 + $totalDeleteRef2;
$totalDeleteIP = $totalDeleteIP1 + $totalDeleteIP2;
$totalDeletePath = $totalDeletePath1 + $totalDeletePath2;
if ($totalAcces != 0) {
	$pcAcces = intval(100.*$totalDeleteAcces/$totalAcces);
} else {
	$pcAcces = 0;
}
echo "<table align=\"center\">
<tr class=\"title\"><td colspan=\"7\">".$strings['Cleanupopportunities']."</td></tr>
<tr class=\"caption\"><td rowspan=\"2\">&nbsp;</td><td colspan=6\">".$strings['Numberoflinestobedeleted']."</td></tr>
<tr class=\"caption\"><td>".$strings['Keywords']."</td><td>".$strings['Referrers']."</td><td>".$strings['IPaddress']."</td><td>".$strings['Paths']."</td><td>".$strings['Access']."</td><td>&nbsp;</td></tr>
<form name=\"easyclean\" action=\"./index.php\" method=\"post\">
<tr><td>".$strings['Simplecleanup']."</td><td>$totalDeleteKwd1 ($pcKwd1 %)</td><td>$totalDeleteRef1 ($pcRef1 %)</td><td>$totalDeleteIP1 ($pcIP1 %)</td><td>$totalDeletePath1 ($pcPath1 %)</td><td>0 (0 %)</td><td align=\"center\" class=\"padv\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"show\" value=\"doeasyclean\"><input type=\"hidden\" name=\"sid\" value=\"all\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Cleanup']."\"></td></tr></form>
<form name=\"hardclean\" action=\"./index.php\" method=\"post\">
<tr><td>".$strings['Aggressivecleanup']."</td><td>$totalDeleteKwd ($pcKwd %)</td><td>$totalDeleteRef ($pcRef %)</td><td>$totalDeleteIP ($pcIP %)</td><td>$totalDeletePath ($pcPath %)</td><td>0 (0 %)</td><td align=\"center\" class=\"padv\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"show\" value=\"dohardclean\"><input type=\"hidden\" name=\"sid\" value=\"all\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Cleanup']."\"></td></tr></form>
<form name=\"accesclean\" action=\"./index.php\" method=\"post\">
<tr><td>".$strings['Cleanupacces']."</td><td>0 (0 %)</td><td>0 (0 %)</td><td>0 (0 %)</td><td>0 (0 %)</td><td>$totalDeleteAcces ($pcAcces %)</td><td align=\"center\" class=\"padv\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"show\" value=\"doaccesclean\"><input type=\"hidden\" name=\"sid\" value=\"all\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Cleanup']."\"></td></tr></form>
<form name=\"fullclean\" action=\"./index.php\" method=\"post\">
</table>\n";
echo "<table align=\"center\">
<tr class=\"title\"><td>".$strings['Definitions']."</td></tr>
<tr><td>".$strings['simplecleandef']."</td></tr>
<tr><td>".$strings['aggressivecleandef']."</td></tr>
<tr><td>".$strings['accesscleandef']."</td></tr>
<tr><td><FONT color=\"#cf0000\"><strong>".$strings['nogoingback']."</strong></FONT></td></tr>
</table>\n";
}

/*********************************************************************************/
/* Access clean functions 
/*
/* Created: 07/2006
/*********************************************************************************/

function finalcheckaccessclean($c,$loginfailed) {
global $sites;
global $strings;
global $lang;
echo "<form name=\"accesclean\" action=\"./index.php\" method=\"post\">
<table><tr class=\"toprow\"><td>".$strings['Youselected'].":<br>\n<blockquote>".$strings['Cleanupacces']."</blockquote>\n".$strings['accesscleandef']."<br><blockquote>\n".$strings['nogoingback']."</blockquote>
<table class=\"form\" align=\"center\">
<tr><td>&nbsp;&nbsp;".$strings['Password']."&nbsp;&nbsp;</td><td><input type=\"password\" name=\"testpasswd\" maxlength=\"50\">&nbsp;&nbsp;</td></tr>
<tr><td colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"reallydoaccesclean\"><input type=\"hidden\" name=\"sid\" value=\"all\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Cleanup']."\"></td></tr>";
if ($loginfailed) {
	echo "<tr><td colspan=\"2\" align=\"center\"><i>".$strings['IncorrectPassword']."</i></td></tr>";
}
echo "</table>
</td></tr>
</table></form>\n";
}

function reallydoaccesclean($c) {
global $sites;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
} else {
	$twomonthago = date("Y-m-d H:i:s",strtotime("-2 month"));
	$fourmonthago = date("Y-m-d H:i:s",strtotime("-4 month"));
	$twomonthagobis = date("Y-m-d",strtotime("-2 month"));
	reset($sites);
	while ($bar=each($sites)) {
		$sid = $bar[0];
		$table = $bar[1]['table'];
		$domain= $bar[1]['site'];
		$req = "DELETE FROM ${table}_acces WHERE date<'$twomonthagobis' AND label>0";
 		$result = mysql_query($req,$c);
		$req = "DELETE FROM ${table}_uniq WHERE date<'$twomonthagobis' AND label>0";
	 	$result = mysql_query($req,$c);
		$req = "OPTIMIZE TABLE ${table}_acces";
	 	$result = mysql_query($req,$c);
		$req = "OPTIMIZE TABLE ${table}_uniq";
 		$result = mysql_query($req,$c);
	}
}
}

/*********************************************************************************/
/* Simple clean functions 
/* 
/* Created: 07/2006
/*********************************************************************************/

function finalcheckeasyclean($c,$loginfailed) {
global $sites;
global $strings;
global $lang;
echo "<form name=\"easyclean\" action=\"./index.php\" method=\"post\">
<table><tr class=\"toprow\"><td>".$strings['Youselected'].":<br>\n<blockquote>".$strings['Simplecleanup']."</blockquote>\n".$strings['simplecleandef']."<br><blockquote>\n".$strings['nogoingback']."</blockquote>
<table class=\"form\" align=\"center\">
<tr><td>&nbsp;&nbsp;".$strings['Password']."&nbsp;&nbsp;</td><td><input type=\"password\" name=\"testpasswd\" maxlength=\"50\">&nbsp;&nbsp;</td></tr>
<tr><td colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"reallydoeasyclean\"><input type=\"hidden\" name=\"sid\" value=\"all\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Cleanup']."\"></td></tr>";
if ($loginfailed) {
	echo "<tr><td colspan=\"2\" align=\"center\"><i>".$strings['IncorrectPassword']."</i></td></tr>";
}
echo "</table>
</table></form>\n";
}

function reallydoeasyclean($c) {
global $sites;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
} else {
	$twomonthago = date("Y-m-d H:i:s",strtotime("-2 month"));
	$fourmonthago = date("Y-m-d H:i:s",strtotime("-4 month"));
	$twomonthagobis = date("Y-m-d",strtotime("-2 month"));
	$tablelist = array("keyword", "referrer", "iplist", "path");
	reset($sites);
	while ($bar=each($sites)) {
		$sid = $bar[0];
		$table = $bar[1]['table'];
		$domain= $bar[1]['site'];
		reset($tablelist);
		foreach ($tablelist as $thistable) {
			$thisone = "${table}_${thistable}";
			$req = "DELETE FROM $thisone WHERE  count=1 AND last<'$twomonthago'";
			$result = mysql_query($req,$c);
			$req = "OPTIMIZE TABLE $thisone";
			$result = mysql_query($req,$c);
		}
	}
}
}

/*********************************************************************************/
/* Aggressive clean functions 
/*
/* Created: 07/2006
/*********************************************************************************/

function finalcheckhardclean($c,$loginfailed) {
global $sites;
global $strings;
global $lang;
echo "<form name=\"easyclean\" action=\"./index.php\" method=\"post\">
<table><tr class=\"toprow\"><td>".$strings['Youselected'].":<br>\n<blockquote>".$strings['Aggressivecleanup']."</blockquote>\n".$strings['aggressivecleandef']."<br><blockquote>\n".$strings['nogoingback']."</blockquote>
<table class=\"form\" align=\"center\">
<tr><td>&nbsp;&nbsp;".$strings['Password']."&nbsp;&nbsp;</td><td><input type=\"password\" name=\"testpasswd\" maxlength=\"50\">&nbsp;&nbsp;</td></tr>
<tr><td colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"reallydohardclean\"><input type=\"hidden\" name=\"sid\" value=\"all\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Cleanup']."\"></td></tr>";
if ($loginfailed) {
	echo "<tr><td colspan=\"2\" align=\"center\"><i>".$strings['IncorrectPassword']."</i></td></tr>";
}
echo "</table>
</table></form>\n";
}

function reallydohardclean($c) {
global $sites;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
} else {
	$twomonthago = date("Y-m-d H:i:s",strtotime("-2 month"));
	$fourmonthago = date("Y-m-d H:i:s",strtotime("-4 month"));
	$twomonthagobis = date("Y-m-d",strtotime("-2 month"));
	$tablelist = array("keyword", "referrer", "iplist", "path");
	reset($sites);
	while ($bar=each($sites)) {
		$sid = $bar[0];
		$table = $bar[1]['table'];
		$domain= $bar[1]['site'];
		reset($tablelist);
		foreach ($tablelist as $thistable) {
			$thisone = "${table}_${thistable}";
			$req = "DELETE FROM $thisone WHERE  count=1 AND last<'$twomonthago'";
			$result = mysql_query($req,$c);
			$req = "DELETE FROM $thisone WHERE count=2 AND last<\"$fourmonthago\"";
			$result = mysql_query($req,$c);
			$req = "OPTIMIZE TABLE $thisone";
			$result = mysql_query($req,$c);
		}
	}
}
}


/*********************************************************************************/
/* Purge DB for one domain functions
/*
/* Created: 07/2006
/*********************************************************************************/

function testclearsite($c) {
global $sites;
global $DEMO;
global $strings;
global $lang;
echo "<table align=\"center\">
<tr class=\"title\"><td colspan=\"5\">".$strings['DeleteOrEmptyDatabases']."</td></tr>
<tr class=\"caption\"><td>".$strings['Domain']."</td><td>".$strings['Table']."</td><td>ID</td><td>".$strings['Erasedata']."</td><td>".$strings['Deletesite']."</td></tr>\n";
reset($sites);
while ($bar=each($sites)) {
	$id = $bar[0];
	$table = $bar[1]['table'];
	$site = $bar[1]['site'];
	$public = $bar[1]['public'];
	$trim = $bar[1]['trim'];
	$tabletxt = $table;
	if ($DEMO) $tabletxt = "****";
	echo "<tr><td>$site</td><td>$tabletxt</td><td>$id</td>";
	echo "<td align=\"center\" class=\"padv\"><form name=\"erasedata$id\" action=\"./index.php\" method=\"post\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"show\" value=\"erasedata\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"hidden\" name=\"sid\" value=\"$id\"><input type=\"submit\" value=\"".$strings['Submit']."\"></form></td>";
	echo "<td align=\"center\" class=\"padv\"><form name=\"delete$id\" action=\"./index.php\" method=\"post\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"show\" value=\"deletesite\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"hidden\" name=\"sid\" value=\"$id\"><input type=\"submit\" value=\"".$strings['Submit']."\"></form></td>";
	echo "</tr>\n";
}
echo "</table>\n";
}

function confirmclearsite($c,$sid, $loginfailed) {
global $sites;
global $strings;
global $lang;
$domain = $sites[$sid]['site'];
echo "<form name=\"easyclean\" action=\"./index.php\" method=\"post\">
<table><tr class=\"toprow\"><td>".$strings['Youselected'].":<br>\n<blockquote>".$strings['Erasedata']."</blockquote>\n".$strings['for']."\n<br>\n<blockquote>$domain</blockquote>\n ".$strings['ErasedataDef']."<br><blockquote>\n".$strings['nogoingback']."</blockquote>
<table class=\"form\" align=\"center\">
<tr><td>&nbsp;&nbsp;".$strings['Password']."&nbsp;&nbsp;</td><td><input type=\"password\" name=\"testpasswd\" maxlength=\"50\">&nbsp;&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"2\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"reallycleardata\"><input type=\"hidden\" name=\"sid\" value=\"$sid\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr>";
if ($loginfailed) {
	echo "<tr><td colspan=\"2\" align=\"center\"><i>".$strings['IncorrectPassword']."</i></td></tr>";
}
echo "</table>
</table></form>\n";
}

function reallycleardata($c, $sid) {
global $sites;
global $strings;
global $DEMO;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
} else {
	$table = $sites[$sid]['table'];
	$ext = array("_acces", "_browser", "_country", "_host", "_keyword", "_os", "_pages", "_path", "_referrer", "_retention", "_uniq");
	while (list($i,$tableend)=each($ext)) {
		$sql= "TRUNCATE TABLE $table$tableend";
		$res = mysql_query($sql,$c);
	}
	$sql = "UPDATE `${table}_hour` SET count=0";
	$res = mysql_query($sql,$c);
	$sql = "UPDATE `${table}_day` SET count=0";
	$res = mysql_query($sql,$c);
}
}

/*********************************************************************************/
/* Delete DB for one domain functions
/*
/* Created: 07/2006
/*********************************************************************************/

function confirmdeletesite($c,$sid, $loginfailed) {
global $sites;
global $strings;
global $lang;
$domain = $sites[$sid]['site'];
echo "<form name=\"easyclean\" action=\"./index.php\" method=\"post\">
<table><tr class=\"toprow\"><td>".$strings['Youselected'].":<br>\n<blockquote>".$strings['Removesite']."</blockquote>\n".$strings['for']."\n<br>\n<blockquote>$domain</blockquote>\n".$strings['RemovesiteDef']."<br><blockquote>\n".$strings['nogoingback']."</blockquote>
<table class=\"form\" align=\"center\">
<tr><td>&nbsp;&nbsp;".$strings['Password']."&nbsp;&nbsp;</td><td><input type=\"password\" name=\"testpasswd\" maxlength=\"50\">&nbsp;&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"2\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"reallydeletesite\"><input type=\"hidden\" name=\"sid\" value=\"$sid\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td></tr>";
if ($loginfailed) {
	echo "<tr><td colspan=\"2\" align=\"center\"><i>".$strings['IncorrectPassword']."</i></td></tr>";
}
echo "</table>
</table></form>\n";
}

function reallydeletesite($c, $sid) {
global $sites;
global $strings;
global $DEMO;
global $config_table;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
} else {
	// Delete the SQL tables
	$table = $sites[$sid]['table'];
	$ext = array("_acces", "_browser", "_country", "_host", "_keyword", "_os", "_pages", "_path", "_referrer", "_retention", "_uniq", "_hour", "_day", "_resolution");
	while (list($i,$tableend)=each($ext)) {
		$sql= "DROP TABLE $table$tableend";
		$res = mysql_query($sql,$c);
	}
	// Update site list
	$sql = "DELETE FROM ${config_table}_sites WHERE id=$sid";
	$res = mysql_query($sql,$c);
	// Re-read site list
	$sites = get_sites($c);
}
}

/*********************************************************************************/
/* IP ban functions
/*
/* Created: 10/2007
/*********************************************************************************/

function banIP($c) {
global $DEMO;
global $strings;
global $config_table;

if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
	return;
}

if (isset($_POST['oneIP'])) { // Form submitted for one IP
	$long = ip2long($_POST['ip']);
	$ip = $_POST['ip'];
	$range = 0;
} else if (isset($_POST['IPrange'])) { // Form submitted for IP range
	$long = ip2long($_POST['ipstart']);
	$ip = $_POST['ipstart'];
	$range = ip2long($_POST['ipend'])-$long;
} else if (isset($_GET['ip'])) { //IP banned from latest visitors
	$long = ip2long($_GET['ip']);
	$ip = $_GET['ip'];
	$range = 0;
}
if ((long2ip($long) != trim($ip)) || $range < 0) {
	echo "<div class=\"error\">".$strings['errorwithIP']."</div>\n";
	return;
}
$ipend = $long + $range;
$sql = "SELECT * FROM `${config_table}_ipban` WHERE (ip<=$long) AND ((ip+`range`)>=$ipend)";
$res = mysql_query($sql, $c);
if (mysql_num_rows($res) > 0) {
	echo "<div class=\"error\">".$strings['IPalreadybanned']."</div>\n";
	return;
}
$time = date("Y-m-d");
$sql = "INSERT INTO `${config_table}_ipban` SET `ip`='$long', `range`='$range', `date`='$time', `last`='$time', `count`='0'";
$res = mysql_query($sql, $c);
}

function removeBanIP($c) {
global $DEMO;
global $strings;
global $config_table;
if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
	return;
}
$id = $_GET['id'];
$sql = "DELETE FROM `${config_table}_ipban` WHERE id=$id";
$res = mysql_query($sql, $c);
}

function showBanIPTable($c) {
global $strings;
global $config_table;
global $ip2c;
global $lang;
include ("Php/Functions/funct.country.inc.php");

$str = "<table  align=\"center\">
<tr class=\"title\"><td colspan=\"5\">".$strings['ListOfBannedIP']."</td></tr>
<tr class=\"caption\"><td>IP</td><td>".$strings['Blockedsince']."</td><td>".$strings['LastBlocked']."</td><td>".$strings['NumberOfTimeBlocked']."</td><td>&nbsp;</td></tr>\n";
$sql = "SELECT * FROM `${config_table}_ipban` ORDER BY id ASC";
$res = mysql_query($sql, $c);
$n = 0;
while ($row = mysql_fetch_object($res)) {
	$n+=1;
	$ip = long2ip($row->ip);
	$ipend = long2ip($row->ip+$row->range);
	$count = $row->count;
	$date = $row->date;
	$last = $row->last;
	$id = $row->id;
	$country = ip2Country($ip,$ip2c);
	if (whoislink($country) == FALSE) {
		$whoislink = "no whois link";
	} else {
		$whoislink = "<a href=\"".whoislink($country)."$ip\"  target=\"blank\" class=\"basic\">whois</a>";
	}
	$country = countryname($country);
	$maplink = "<a href=\"http://soft.zoneo.net/phpTrafficA/mapIP.php?ip=$ip\" target=\"blank\" class=\"basic\">map</a>";
	$unblocklink = "<a href=\"index.php?mode=setup&amp;todo=removebanip&amp;id=$id&amp;lang=$lang\" class=\"basic\">".$strings['unblock']."</a>";
	$str .= "<tr><td>$ip";
	if ($ipend != $ip) $str .= " - $ipend";
	$str .= " ($country, $whoislink, $maplink)</td><td align=\"center\">$date</td><td align=\"center\">$last</td><td align=\"center\">$count</td><td>$unblocklink</td></tr>\n";
}
if ($n==0) $str .= "<tr><td colspan=\"4\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n";
$str .= "</table>\n";
echo $str;

$str = "<form action=\"./index.php\" method=\"post\">
<table class=\"form\" align=\"center\"><tr class=\"title\"><td colspan=\"5\">".$strings['AddIPBan']."</td></tr>

<tr><td>".$strings['singleIP']."</td><td colspan=\"3\"><input type=\"text\" name=\"ip\" maxlength=\"50\"></td><td><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"banip\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\" name=\"oneIP\"></td></tr>

<tr><td>".$strings['IPrangefrom']."</td><td><input type=\"text\" name=\"ipstart\" maxlength=\"50\"></td><td>".$strings['IPrangeto']."</td><td><input type=\"text\" name=\"ipend\" maxlength=\"50\"></td><td><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"banip\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\" name=\"IPrange\"></td></tr>

</table>
</form>\n";


echo $str;

}

/*********************************************************************************/
/* Functions to change administrator password
/*
/* Created: 9/2009
/*********************************************************************************/

function changeAdminPwd($c, $error="") {
global $strings;
global $config_table;
global $lang;
global $isLoggedIn;

if (!$isLoggedIn) {
	echo "<div class=\"error\">".$strings['failed']."</div>\n";
	return;
}

$str = "<script type=\"text/javascript\">
function validates(theForm) {
if ((theForm.newpwd1.value == \"\") || (theForm.newpwd1.value.length < 6)){
	alert(\"".$strings['PwdTooShort']."\");
	return false;
}
if (theForm.newpwd1.value != theForm.newpwd2.value){ 
	alert(\"".$strings['PwdNotMatch']."\");
	return false;
}
}
</script>\n";

if ($error != "") $str .= "<div class=\"error\">$error</div>\n";

$str .= "<form name=\"newpwd\" action=\"./index.php\" method=\"post\"  OnSubmit=\"return validates(this);\">
<table  align=\"center\">
<tr class=\"title\"><td colspan=\"3\">".$strings['ChgAdminPwd']."</td></tr>
<tr><td>".$strings['OldPwd']."</td><td class=\"padv\"><input type=\"password\" name=\"oldpwd\" maxlength=\"50\"></td>
<td rowspan=\"3\" class=\"bleft\" align=\"center\"><input type=\"hidden\" name=\"mode\" value=\"setup\"><input type=\"hidden\" name=\"todo\" value=\"dochgadminpwd\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"submit\" value=\"".$strings['Submit']."\"></td>
</tr>
<tr><td>".$strings['NewPwd']."</td><td class=\"padv\"><input type=\"password\" name=\"newpwd1\" maxlength=\"50\"></td></tr>
<tr><td>".$strings['NewPwd']."</td><td class=\"padv\"><input type=\"password\" name=\"newpwd2\" maxlength=\"50\"></td></tr>
</table>
</form>\n";
echo $str;
}

function doChangePwd($c, $old, $new1, $new2) {
global $strings;
global $config_table;
global $lang;
global $isLoggedIn;
global $DEMO;

if ($DEMO) {
	echo "<div class=\"error\">".$strings['notindemo']."</div>\n";
	return;
}
if (!$isLoggedIn) {
	echo "<div class=\"error\">".$strings['failed']."</div>\n";
	return;
}
if ($new1 != $new2) {
	echo "<div class=\"error\">".$strings['PwdNotMatch']."</div>\n";
	return;
}
if (! testadminpwd($c, $old)) {
	echo "<div class=\"error\">".$strings['IncorrectPassword']."</div>\n";
	return;
}
$md5 = md5($new1);
$doit = "UPDATE $config_table SET value='$md5' WHERE variable='adminpassword'";
if (!mysql_query($doit,$c)) {
	echo "<div class=\"error\">Problem with database in setup at line [".__LINE__."]: ".mysql_error()."</div>\n";
	return;
}
echo "<div class=\"error\">".$strings['PwdUpdated']."<br>".$strings['LogInAgain']."</div>\n";
}

/*********************************************************************************/
/* Main setup function
/*********************************************************************************/

function setup($c) {
global $sites;
global $server, $user, $password, $base;
global $strings;
global $lang;

$loginfailed = 0;
if (isset($_POST["todo"])) {
	$todo = $_POST["todo"];
} else if (isset($_GET["todo"])) {
	$todo = $_GET["todo"];
} else {
	$todo = "";
}
if (isset($_POST["show"])) {
	$show = $_POST["show"];
} else if (isset($_GET["show"])) {
	$show = $_GET["show"];
} else {
	$show = "";
}

$setupitems = array(
$strings['Sitelist'] => "./index.php?mode=setup&amp;show=sites&amp;lang=$lang",
$strings['Options'] => "./index.php?mode=setup&amp;show=options&amp;lang=$lang",
$strings['IPban'] => "index.php?mode=setup&amp;show=baniptable&amp;lang=$lang",
$strings['OSbrowserslist'] => "./index.php?mode=setup&amp;show=oswb&amp;lang=$lang",
$strings['Searchengineslist'] => "index.php?mode=setup&amp;show=sebl&amp;lang=$lang",
$strings['Databasecleanup'] => "index.php?mode=setup&amp;show=testcleanup&amp;lang=$lang",
$strings['Removesite'] => "index.php?mode=setup&amp;show=testclearsite&amp;lang=$lang",
$strings['RemovePage'] => "./index.php?mode=setup&amp;show=removepage&amp;lang=$lang",
$strings['SystemTest'] => "./index.php?mode=setup&amp;show=systemtest&amp;lang=$lang",
$strings['AdminPwd'] => "./index.php?mode=setup&amp;show=adminpwd&amp;lang=$lang");

echo "<div id=\"setupmenu\">";
$i = 0;
foreach ($setupitems as $item => $link) {
	$item = str_replace(" ", "&nbsp;", $item);
	echo "<a href=\"$link\">$item</a>";
	if ($i == 3) { echo "<br>"; $i=-1; }
	$i += 1;
}
echo "</div>";

echo "<div class=\"clearer\">&nbsp;</div>
<div id=\"setup\">\n";

switch ($todo) {
	case "adddomain":
		adddomain($c, $_POST["domain"], $_POST["table"], $_POST["public"], $_POST["trim"], $_POST["crawler"], $_POST["counter"], $_POST["timediff"]);
		break;
	case "updateoswb":
		updateOSWb($c,$_POST['os_id'], $_POST['wb_id']);
		$show = "oswb";
		break;
	case "updatesebl":
		updateSEBL($c,$_POST['se_id'], $_POST['bl_id']);
		$show = "sebl";
		break;
	case "updatesh":
		updateSaveHosts($c,$_POST['save_hosts']);
		$show = "options";
		break;
	case "updatedisplay":
		updateDisplay($c, $_POST["ntop"], $_POST["ntoplong"]);
		$show = "options";
		break;
	case "updateserefs":
		updateSERefs($c, $_POST["serefs"]);
		$show = "options";
		break;
	case "updatevisitcutoff":
		updateVisitCutOff($c, $_POST['visitcutoff']);
		$show = "options";
		break;
	case "updateURLTrim":
		updateURLTrim($c, $_POST['URLTrimFactor']);
		$show = "options";
		break;
	case "updateReferrerNew":
		updateReferrerNewTime($c, $_POST['referrerNewDuration']);
		$show = "options";
		break;
	case "updateCleanRKIP":
		updateCleanRKIP($c, $_POST['autoclean'], $_POST['time']);
		$show = "options";
		break;
	case "updateCleanAccess":
		updateCleanAccess($c, $_POST['autoclean'], $_POST['time']);
		$show = "options";
		break;
	case "updatesite":
		updatedomain($c, $_POST["sid"], $_POST["public"], $_POST["trim"], $_POST["crawler"], $_POST["counter"], $_POST["timediff"]);
		break;
	case "reallydoaccesclean":
		if (testadminpwd($c,$_POST['testpasswd'])) {
			reallydoaccesclean($c);
			$show = "testcleanup";
		} else {
			$loginfailed = 1;
			$show = "doaccesclean";
		}
		break;
	case "reallydoeasyclean":
		if (testadminpwd($c,$_POST['testpasswd'])) {
			reallydoeasyclean($c);
			$show = "testcleanup";
		} else {
			$loginfailed = 1;
			$show = "doeasyclean";
		}
		break;
	case "reallydohardclean":
		if (testadminpwd($c,$_POST['testpasswd'])) {
			reallydohardclean($c);
			$show = "testcleanup";
		} else {
			$loginfailed = 1;
			$show = "dohardclean";
		}
		break;
	case "reallycleardata":
		if (testadminpwd($c,$_POST['testpasswd'])) {
			reallycleardata($c, $_POST["sid"]);
		} else {
			$loginfailed = 1;
			$show = "erasedata";
		}
		break;
	case "reallydeletesite":
		if (testadminpwd($c,$_POST['testpasswd'])) {
			reallydeletesite($c, $_POST["sid"]);
		} else {
			$loginfailed = 1;
			$show = "deletesite";
		}
		break;
	case "banip":
		banIP($c);
		$show = "baniptable";
		break;
	case "removebanip":
		removeBanIP($c);
		$show = "baniptable";
		 break;
	case "reallyremovepage":
		if (testadminpwd($c,$_POST['testpasswd'])) {
			doRemovePage($c, $_POST["sid"], $_POST["pageid"]);
			$show = "removepage";
		} else {
			$loginfailed = 1;
			$show = "testremovepage";
		}
		break;
	case "reallyremoveseveralpage":
		if (testadminpwd($c,$_POST['testpasswd'])) {
			doRemoveSeveralPage($c, $_POST["sid"], $_POST["pageid"]);
			$show = "removepage";
		} else {
			$loginfailed = 1;
			$show = "testremoveseveralpage";
		}
		break;
	case "dochgadminpwd":
		doChangePwd($c, $_POST['oldpwd'], $_POST['newpwd1'], $_POST['newpwd2']);
		$show = "adminpwd";
		break;
}

switch ($show) {
	case "oswb":
		echoOSWB($c);
		break;
	case "sebl":
		echoSEBL($c);
		break;
	case "testcleanup":
		testcleanup($c);
		break;
	case "doaccesclean":
		finalcheckaccessclean($c, $loginfailed);
		break;
	case "doeasyclean":
		finalcheckeasyclean($c, $loginfailed);
		break;
	case "dohardclean":
		finalcheckhardclean($c, $loginfailed);
		break;
	case "testclearsite":
		testclearsite($c);
		break;
	case "erasedata":
		confirmclearsite($c,$_POST['sid'], $loginfailed);
		break;
	case "deletesite":
		confirmdeletesite($c,$_POST['sid'], $loginfailed);
		break;
	case "options":
		showOptions($c);
		break;
	case "baniptable":
		showBanIPTable($c);
		break;
	case "systemtest":
		systemtest($c);
		break;
	case "removepage":
		choosePageToRemove($c);
		break;
	case "testremovepage":
		confirmRemovePage($c, $_POST['sid'], $_POST['pageid'], $loginfailed);
		break;
	case "testremoveseveralpage":
		confirmRemoveSeveralPage($c, $_POST['sid'], $_POST['pagematch'], $loginfailed);
		break;
	case "getpagetitle":
		$sid = 746210;
		$table = $sites[$sid]['table'];
		$domain = $sites[$sid]['site'];
		$sql = "SELECT name FROM ${table}_pages ORDER BY name ASC";
		$res = mysql_query($sql,$c);
		while ($row = mysql_fetch_object($res)) {
			$name = $row->name;
			$url = "http://$domain/$name";
			$title = get_url_title($url);
			echo "$name: $title<br>";
		}
		break;
	case "adminpwd":
		changeAdminPwd($c);
		break;
	default:
		echositelist($c);
		break;
}
echo "</div>\n";
}
