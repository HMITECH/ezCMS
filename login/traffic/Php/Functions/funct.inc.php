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



/******************************************************************************
/* Function: cleanRefIPKwdPath
/* Role: clean-up of referrer, keyword, IP list, and path tables
/* Parameters:
/*   - $c: connection to the database
/* Output:
/* Created 09/2009 from similar function in Php/UI/setup.inc.php
******************************************************************************/
function cleanRefIPKwdPath($c) {
global $sites;
global $DEMO;
if (!$DEMO) {
	$twomonthago = date("Y-m-d H:i:s",strtotime("-2 month"));
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
			// print("SQL: $req<br>");
			$result = mysql_query($req,$c);
			$req = "OPTIMIZE TABLE $thisone";
			$result = mysql_query($req,$c);
		}
	}
	reset($sites);
}
}

/******************************************************************************
/* Function: cleanAccess
/* Role: clean-up of access table
/* Parameters:
/*   - $c: connection to the database
/* Output:
/* Created 09/2009 from similar function in Php/UI/setup.inc.php
******************************************************************************/
function cleanAccess($c) {
global $sites;
global $DEMO;
if (!$DEMO) {
	$twomonthagobis = date("Y-m-d",strtotime("-2 month"));
	reset($sites);
	while ($bar=each($sites)) {
		$sid = $bar[0];
		$table = $bar[1]['table'];
		$domain= $bar[1]['site'];
		$req = "DELETE FROM ${table}_acces WHERE date<'$twomonthagobis' AND label>0";
		// print("SQL: $req<br>");
 		$result = mysql_query($req,$c);
		$req = "DELETE FROM ${table}_uniq WHERE date<'$twomonthagobis' AND label>0";
		// print("SQL: $req<br>");
	 	$result = mysql_query($req,$c);
		$req = "OPTIMIZE TABLE ${table}_acces";
	 	$result = mysql_query($req,$c);
		$req = "OPTIMIZE TABLE ${table}_uniq";
 		$result = mysql_query($req,$c);
	}
	reset($sites);
}
}

/******************************************************************************
/* Function: performAutoClean
/* Role: check if auto-clean of database is activated. If so, do it if necessary
/* Parameters:
/*   - $c: connection to the database
/* Output:
/* Created 09/2009
******************************************************************************/
function performAutoClean($c) {
global $autoclean;
global $config_table;
$next = strtotime($autoclean['lastCleanAccess']) + $autoclean['cleanAccessInt']*86400;
$now = time();
$nowTxt = date("Y-m-d",$now);
if (($autoclean['cleanAccess']) && ($next<$now)) {
	// echo "Autoclean Access<br>";
	cleanAccess($c);
	$sql = "UPDATE $config_table SET value=\"$nowTxt\" WHERE variable LIKE \"lastCleanAccess\"";
	$res = mysql_query($sql,$c);
}
$next = strtotime($autoclean['lastCleanRefIPKwdPath']) + $autoclean['cleanRefIPKwdPathInt']*86400;
if  (($autoclean['cleanRefIPKwdPath']) && ($next<$now)) {
	// echo "Autoclean the rest<br>";
	cleanRefIPKwdPath($c);
	$sql = "UPDATE $config_table SET value=\"$nowTxt\" WHERE variable LIKE \"lastCleanRefIPKwdPath\"";
	$res = mysql_query($sql,$c);
}
}

/*********************************************************************************
/* Function: isIPbanned
/* Role: make sure an IP address is not banned
/* Parameters:
/*   - $c: connection to the database
/*   - $config_table: root name for config table
/*   - $long: long IP address
/* Output:
/*   - true if banned, false otherwise
/* Created 09/2009 out of phpTrafficA_bannedIP in log_function
**********************************************************************************/
function isIPbanned($c, $long) {
global $config_table;
if ($long == -1 || $long === FALSE) return true;
$sql = "SELECT count(*) as count FROM `${config_table}_ipban` WHERE (($long>=ip) AND ($long<=(ip+`range`)))";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$count = $row->count;
if ($count >= 1) return true;
return false;
}

/*********************************************************************************
/* Function: registrationCheck
/* Role: check for registration, check for update availability if registered
/* Parameters:
/*   - $c: connection to the database
/* Output:
/*   - returns "Registered or unregitered", message if upgrade is available
/* Created 03/2008 /********************************************************************************/
function registrationCheck($c,$lookforupdate) {
global $registration;
global $strings;
global $config_table;
global $lang;

if ($registration['registered'] == '0') {
	return 0;
} else {
	if ($lookforupdate) {
		$today = strtotime(date("Y-m-d"));
		if ($registration['lastchecked'] == "") {
			$lastchecked = 0;
		} else {
			$lastchecked = strtotime($registration['lastchecked']);
		}
		if ($today > $lastchecked) {
			$test = file("http://data.phpTrafficA.com/last.php");
			$line = explode("|", $test[0]);
			$last = intval($line[0]);
			$sql = "UPDATE $config_table SET value='$last' WHERE variable 'latestupdate'";
			$result = mysql_query ($sql,$c);
			$sql = "UPDATE $config_table SET value='".date("Y-m-d")."' WHERE variable LIKE 'lastchecked'";
		} else {
			$last = $registration['latestupdate'];
		}
		if($last >  $registration['currentupdate']) {
			echo "<div class=\"announce\">".$strings['UpdateAvailable']."<br><a href=\"./index.php?mode=update&lang=$lang&todo=testupdate&version=$last\">".$strings['InstallUpdate']."</a></div>\n";
		}
		if (trim($registration['registrationexpires'])=="") {
			$end = time();
		} else {
			$end = strtotime($registration['registrationexpires']);
		}
		$remaining = intval((time()-$end)/8400);
		if ($remaining < 30) {
			echo "<div class=\"announce\">".$strings['regRemaining'].": $remaining<br><a href=\"http://www.phpTrafficA.com/register.php\">Renew today!</a></div>";
		}
	}
	return 1;
}
}

/*********************************************************************************
/* Function: get_sites
/* Role: reads the sites table
/* Parameters:
/*   - $c: connection to the database
/* Output:
/*   - array with sites informations
/*    array(
	"id1"=> array("table" =>"table", "site"=>"address", "public"=>0/1, "trim"=>0/1, "crawler"=>0/1, "counter"=>0/1, "timediff"=> ),
	"id2"=> array("table" =>"table", "site"=>"address", "public"=>0/1, "trim"=>0/1, "crawler"=>0/1, "counter"=>0/1, "timediff"=> )
	)
/* Created 09/2007 to replace the file 'sites.php'
**********************************************************************************/
function get_sites($c) {
global $config_table;
$sql = "SELECT * FROM ${config_table}_sites";
$res = mysql_query ($sql,$c);
$sites = array();
while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$sites = $sites + array($row["id"] => $row );
}
mysql_free_result($res);
return $sites;
}

/*********************************************************************************
/* Function: get_url_title
/* Role: get the title of the page corresponding to a URL
/* Parameters:
/*   - $url
/* Output:
/*   - page title
/* Created 11/2006
/* Source: php website
**********************************************************************************/
function get_url_title($url, $timeout = 2) {
$url = parse_url($url);
if(!in_array($url['scheme'],array('','http'))) return;
$fp = fsockopen ($url['host'], ($url['port'] > 0 ? $url['port'] : 80), $errno, $errstr, $timeout);
if (!$fp) {
	return;
} else {
	fputs ($fp, "GET /".$url['path'].($url['query'] ? '?'.$url['query'] : '')." HTTP/1.0\r\nHost: ".$url['host']."\r\n\r\n");
	$d = '';
	while (!feof($fp)) {
		$d .= fgets ($fp,2048);
		if(preg_match('~(</head>|<body>|(<title>\s*(.*?)\s*</title>))~i', $d, $m)) break;
	}
	fclose ($fp);
	return $m[3];
}
}

/*********************************************************************************/
/* Function: read_config
/* Role: Read configuration table, but only options used for display (ntop, and so on) and version number
/* Parameters:
/*   - $c: connection to the database
/* Output:
/*   - Version number
/* Created 11/2006
/*********************************************************************************/
function read_config($c) {
global $config_table;
global $display;
global $registration;
global $autoclean;

// get configuration (browser, OS list, search engines definitions)
$sql = "SELECT * FROM $config_table";
$result = mysql_query ($sql,$c);
$config = array("x" =>"x");
while($row = mysql_fetch_array($result)) {
	$config = $config + array($row["variable"] => $row["value"] );
}
mysql_free_result ($result);
// Getting what we really whant
$display['ntop'] = $config['ntop'];
$display['ntoplong'] = $config['ntoplong'];
$display['stringLengthsFactor'] = $config['stringLengthsFactor'];
$display['referrerNewDuration'] = $config['referrerNewDuration'];
$registration['registered'] = $config['registered'];
$registration['lastchecked'] = $config['lastchecked'];
$registration['currentupdate'] = $config['currentupdate'];
$registration['loginupdate'] = $config['loginupdate'];
$registration['passwordupdate'] = $config['passwordupdate'];
$registration['latestupdate'] = $config['latestupdate'];
$autoclean['cleanRefIPKwdPath'] = $config['cleanRefIPKwdPath'];
$autoclean['cleanRefIPKwdPathInt'] = $config['cleanRefIPKwdPathInt'];
$autoclean['cleanAccess'] = $config['cleanAccess'];
$autoclean['cleanAccessInt'] = $config['cleanAccessInt'];
$autoclean['lastCleanRefIPKwdPath'] = $config['lastCleanRefIPKwdPath'];
$autoclean['lastCleanAccess'] = $config['lastCleanAccess'];
if (isset($config['version'])) return $config['version'];
return 1.0;
}

/*********************************************************************************/
/* Function: incrdecr
/* Role: Returns a string with the arrows for increasing or decreasing quantities
/* Parameters:
/*   - $n: number
/*   - $txt: text to add after the number (%...)
/* Output:
/* Created 11/2006
/*********************************************************************************/
function incrdecr($n,$txt) {

if ($n<0) {
	$str = "<table class=\"basic\"><tr><td><IMG src=\"Img/System/decr.gif\" width=\"10\" height=\"14\" align=\"left\" border=\"0\" alt=\"$n$txt\"></td><td>".format_float($n)."%</td></tr></table>";
} else {
	$str = "<table class=\"basic\"><tr><td><IMG src=\"Img/System/incr.gif\" width=\"10\" height=\"14\" align=\"left\" border=\"0\" alt=\"$n$txt\"></td><td>+".format_float($n)."%</td></tr></table>";
}
return $str;
}

/*********************************************************************************/
/* Function: bartext
/* Role: Creates a bar with a text, provide the bar relative length, in pc
/* Parameters:
/*   - $width
/*   - $max: maximum width
/*   - $text
/* Output:
/*   - the string to print out
/* Create: 11/2006
/*********************************************************************************/

function bartext($width, $max, $text, $maxwidth=40) {
if ($max != 0) {
	$width = max(intval($maxwidth*$width/$max),1);
} else {
	$width = $maxwidth;
}
$tdw = $maxwidth +5;
$txtPage = "<table class=\"basic\"><tr><td width=\"${tdw}px\"><img src=\"Img/System/bar.gif\" alt=\"$text\" width=\"$width\" height=\"10\" border=\"1\"></td><td>$text</td></tr></table>";
return $txtPage;
}


/*********************************************************************************/
/* Function: linksTableUp
/* Role: Creates legend for column table with a small up arrow to sort it in a given direction
/* Parameters:
/*   - $url: url to call
/*   - $namelink: to display as image title
/*   - $name: column name
/* Output:
/*   - the string to print out
/* Create: 11/2006
/*********************************************************************************/

function linksTableUp($url, $namelink, $name) {
$txtPage = "<table class=\"basic\"><tr><td><a href=\"$url\" class=\"img\"><IMG src=\"Img/System/up.gif\" alt=\"$namelink\" title=\"$namelink\" width=\"9\" height=\"9\" border=\"0\"></a></td><td>$name</td></tr></table>";
return $txtPage;
}

/*********************************************************************************/
/* Function: linksTableUpDown
/* Role: Creates legend for column table with small arrows to sort it in a given direction
/* Parameters:
/*   - $url: url to call, order=asc or order=desc will be added to the URL
/*   - $namelink: to display as image title
/*   - $name: column name
/*   - $extra: to add after the link
/* Output:
/*   - the string to print out
/* Create: 11/2006
/*********************************************************************************/

function linksTableUpDown($url, $namelink, $name, $extra = "") {
$txtPage = "<table class=\"basic\"><tr><td><a href=\"$url&amp;order=asc$extra\" class=\"img\"><IMG src=\"Img/System/up.gif\" alt=\"$namelink\" title=\"$namelink\" width=\"9\" height=\"9\" border=\"0\"></a><a href=\"$url&amp;order=desc$extra\" class=\"img\"><IMG src=\"Img/System/down.gif\" alt=\"$namelink\" title=\"$namelink\" width=\"9\" height=\"9\" border=\"0\"></a></td><td>$name</td></tr></table>";
return $txtPage;
}

function linksUpDown($url, $namelink, $extra = "") {
$txtPage = "<a href=\"$url&amp;order=asc$extra\" class=\"img\"><IMG src=\"Img/System/up.gif\" alt=\"$namelink\" title=\"$namelink\" width=\"9\" height=\"9\" border=\"0\"></a><a href=\"$url&amp;order=desc$extra\" class=\"img\"><IMG src=\"Img/System/down.gif\" alt=\"$namelink\" title=\"$namelink\" width=\"9\" height=\"9\" border=\"0\"></a>";
return $txtPage;
}

/*********************************************************************************/
/* Function: simplePageLink
/* Role: Creates link to statistics for a page
/* Parameters:
/*   - $c
/*   - $table
/*   - $sid
/*   - $lang
/*   - $id: page id
/*   - $short: 1 if you want a short string, 2 if you want an average string, 3 for a long one
/* Output:
/*   - the string to print out
/* Create: 01/2007
/*********************************************************************************/
function simplePageLink($c, $table, $sid, $lang, $id, $short=1) {
	global $strings;
	if ($short == 1) {
		$l = 30;
	} else if ($short == 2) {
		$l = 40;
	} else if ($short == 3) {
		$l = 50;
	}
	$name = pagename($c,$table,$id);
	$page = shortenCenter($name,$l);
	return "<a href=\"index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;pageid=$id&amp;lang=$lang\" title=\"".$strings['Generalstatsfor']." $name\">$page</a>";
}

/*********************************************************************************/
/* Function: linksforpage
/* Role: Creates a objects with links to various kind of stats for a given page
/* Parameters:
/*   - $sid
/*   - $pagename
/*   - $pageid
/* Output:
/*   - the string to print out
/* Create: 12/2005
/*********************************************************************************/
function linksforpage ($sid, $pagename, $pageid) {
	global $strings;
	global $lang;
	// Links with popup menus. Have been removed (too heavy)
// 	$divlink = "<div class=\"link\">
// 	".$strings['Statsfor']." $pagename
// <ul>
// <li>- <a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;pageid=$pageid&amp;lang=$lang\">".$strings['generalstats']."</a></li>
// <li>- <a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;pageid=$pageid&amp;lang=$lang\">".$strings['searchenginestats']."</a></li>
// <li>- <a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=ref&amp;pageid=$pageid&amp;lang=$lang\">".$strings['referrerstats']."</a></li>
// <li>- <a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathdesign&amp;pathid=$pageid&amp;lang=$lang\">".$strings['pathanalysis']."</a></li>
// </ul></div>";
// 	$str = "<div name=\"pagelink\" class=\"pagelink\" id=\"pagelink\"><span>$pagename$divlink</span></div>";
	$str = "<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;pageid=$pageid&amp;lang=$lang\" title=\"".$strings['Generalstatsfor']." $pagename\">$pagename</a>";
	return $str;
}

/*********************************************************************************/
/* Function: pageRank
/* Role: gets the google page rank of a page
/* Parameters:
/*   - $url: the URL
/* Output:
/*   - google page rank
/* Create: 11/2005
/*********************************************************************************/

define('GOOGLE_MAGIC', 0xE6359A60);

//unsigned shift right 
function zeroFill($a, $b) { 
$z = hexdec(80000000);
//echo $z;
if ($z & $a) { 
	$a = ($a>>1); 
	$a &= (~$z); 
	$a |= 0x40000000; 
	$a = ($a>>($b-1)); 
} else { 
	$a = ($a>>$b); 
} 
return $a; 
} 

function mix($a,$b,$c) { 
	$a -= $b; $a -= $c; $a ^= (zeroFill($c,13)); 
	$b -= $c; $b -= $a; $b ^= ($a<<8); 
	$c -= $a; $c -= $b; $c ^= (zeroFill($b,13)); 
	$a -= $b; $a -= $c; $a ^= (zeroFill($c,12)); 
	$b -= $c; $b -= $a; $b ^= ($a<<16); 
	$c -= $a; $c -= $b; $c ^= (zeroFill($b,5)); 
	$a -= $b; $a -= $c; $a ^= (zeroFill($c,3));   
	$b -= $c; $b -= $a; $b ^= ($a<<10); 
	$c -= $a; $c -= $b; $c ^= (zeroFill($b,15)); 
	return array($a,$b,$c); 
} 

function GoogleCH($url, $length=null, $init=GOOGLE_MAGIC) { 
if(is_null($length)) { 
	$length = sizeof($url); 
} 
$a = $b = 0x9E3779B9; 
$c = $init; 
$k = 0; 
$len = $length; 
while($len >= 12) { 
	$a += ($url[$k+0] +($url[$k+1]<<8) +($url[$k+2]<<16) +($url[$k+3]<<24)); 
	$b += ($url[$k+4] +($url[$k+5]<<8) +($url[$k+6]<<16) +($url[$k+7]<<24)); 
	$c += ($url[$k+8] +($url[$k+9]<<8) +($url[$k+10]<<16)+($url[$k+11]<<24)); 
	$mix = mix($a,$b,$c); 
	$a = $mix[0]; $b = $mix[1]; $c = $mix[2]; 
	$k += 12; 
	$len -= 12; 
} 
$c += $length; 
switch($len) {              /* all the case statements fall through */ 
	case 11: $c+=($url[$k+10]<<24); 
	case 10: $c+=($url[$k+9]<<16); 
	case 9 : $c+=($url[$k+8]<<8); 
		/* the first byte of c is reserved for the length */ 
	case 8 : $b+=($url[$k+7]<<24); 
	case 7 : $b+=($url[$k+6]<<16); 
	case 6 : $b+=($url[$k+5]<<8); 
	case 5 : $b+=($url[$k+4]); 
	case 4 : $a+=($url[$k+3]<<24); 
	case 3 : $a+=($url[$k+2]<<16); 
	case 2 : $a+=($url[$k+1]<<8); 
	case 1 : $a+=($url[$k+0]); 
	/* case 0: nothing left to add */ 
} 
$mix = mix($a,$b,$c); 
//echo $mix[0];
/*-------------------------------------------- report the result */ 
return $mix[2]; 
} 

//converts a string into an array of integers containing the numeric value of the char 
function strord($string) { 
for($i=0;$i<strlen($string);$i++) { 
		$result[$i] = ord($string{$i}); 
} 
return $result; 
} 

function getpageRank($url) {
$pagerank = 0;
$ch = "6" . GoogleCH(strord("info:" . $url)); 
$fp = fsockopen("www.google.com", 80, $errno, $errstr, 30);
if (!$fp) {
	return 0;;
} else {
	$out = "GET /search?client=navclient-auto&ch=". $ch .  "&features=Rank&q=info:" . $url . " HTTP/1.1\r\n";
	$out .= "Host: www.google.com\r\n";
	$out .= "Connection: Close\r\n\r\n";
	fwrite($fp, $out);
	while (!feof($fp)) {
		$data = fgets($fp, 128);
		$pos = strpos($data, "Rank_");
		if($pos === false){} else{
			$pagerank = substr($data, $pos + 9);
		}
	}
	fclose($fp);
}
return $pagerank;
}

function pageRank($c,$table,$site,$pageid) {
global $pageRankArray;
global $cachepagenames, $cachepagenamesset;
global $tmpdirectory;
if (is_array($pageRankArray)) {   // We have a cache of page rank values
	//  echo "We have a cache of page ranks!<br>";
	if (array_key_exists($pageid, $pageRankArray)) {
		return $pageRankArray[$pageid];
	}
	return "&nbsp;";
} else {  // Check if we have a cache in tmp directory
	$dir = "$tmpdirectory";
	$list = ls($dir,"pr$table*.php");
	if (count($list) > 0) { // There is one, we load it
		include("$dir/".$list[0]);
		//  echo "Loading page ranks from file!<br>";
		return $pageRankArray[$pageid];
	} else { // We have to build a cache...
		//  echo "Building cache for page ranks!<br>";
		setcachepagename($c,$table);
		$textPr = "<?php  \n\$pageRankArray = array(\n";
		$i = 0;
		foreach ($cachepagenames as $id => $url) {
			$pageRankArray[$id] = getpageRank($site."/".$url);
			if ($i==0) { 
				$textPr.= $id."=>".$pageRankArray[$id]."\n"; 
			} else {
				$textPr.= ",".$id."=>".$pageRankArray[$id]."\n"; 
			}
			$i += 1;
		}
		$textPr .= ");\n?>\n";
		$temp = fopen ("$tmpdirectory/pr$table.".time().".php", 'w');
		fwrite($temp, $textPr);
		fclose($temp);
		return $pageRankArray[$pageid];
	}
}
return 0;
}

/*********************************************************************************/
/* Function: navPage
/* Role: echos a navigation to change pages
/* Parameters:
/*   - $url: the first part of the URL
/*   - $npages: number of pages
/*   - $page: current page
/* Output:
/*   - the navigation
/* Create: 09/2005
/*********************************************************************************/
function navPage($url, $npages, $page) {
	$str = "";
	for ($i=1;$i<=min(4,$npages);$i++) {
		if ($page == $i) {
			$str .= "<strong>$i</strong> ";
		} else {
			$str .= "<a href=\"${url}$i\" class=\"basic\">$i</a> ";
		}
	}
	if ($page > 9) $str .= " ... ";
	for ($i=max(5,$page-4);$i<=min($npages,$page+4);$i++) {
		if ($page == $i) {
			$str .= "<strong>$i</strong> ";
		} else {
			$str .= "<a href=\"${url}$i\" class=\"basic\">$i</a> ";
		}
	}
	if (($page < $npages -8)&&($npages>4)) $str .= " ... ";
	for ($i=max(($npages-3),$page+5);$i<=$npages;$i++) {
		if ($page == $i) {
			$str .= "<strong>$i</strong> ";
		} else {
			$str .= "<a href=\"${url}$i\" class=\"basic\">$i</a> ";
		}
	}
	return $str;
}

/*********************************************************************************/
/* Function: getmicrotime
/* Role: time with milliseconds
/* Parameters:
/* Output:
/*   - returns the time
/* Source: php website
/*********************************************************************************/
 if (!function_exists('getmicrotime')) {
	function getmicrotime()  {
		global $DEBUG;
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec); 
	}
}
/*********************************************************************************/
/* Function: cleanURL
/* Role: cleans up URL's so we pass the W3C test. Mostly: replace & with $amp;
/* Parameters: 
/*   - $var: the URL to clean
/* Output:
/*   - the URL
/*********************************************************************************/
function cleanURL ($var) {
	$var=preg_replace("/&/","&amp;",$var);
	$var=preg_replace("/&amp;amp;/","&amp;",$var);
	$var=preg_replace("/</","&#060;",$var);
	$var=preg_replace("/>/","&#062;",$var);
	return $var;
}

/*********************************************************************************/
/* Function: shorten
/* Role: Shorten a string 
/* Parameters: 
/*   - $var: the string to shorten
/*   - $len: number of characters
/* Output:
/*   - the shorten string
/* Source: php website
/*********************************************************************************/
function shorten ($var, $len = 30) {
  if (empty ($var)) {
    return "";
  }
  if (strlen ($var) < $len) {
    return $var;
  }
  return substr ($var, 0, $len);
}

/*********************************************************************************/
/* Function: shortenURL
/* Role: Shorten a string, add spaces before dots, and add dots if it did shorten 
/* Parameters: 
/*   - $var: the string to shorten
/*   - $len: function to set number of characters: 1 for a short URL, 2 for an average one, 3 for a long one, default values:
/*        0: 20 chars
/*        1: 40 chars
/*        2: 60 chars
/*        3: 80 chars
/*        4: 100 chars
/* Output:
/*   - the shorten string
/*********************************************************************************/
function shortenURL ($var, $len = 3) {
global $display;
$len = 20+$display['stringLengthsFactor']*$len;
// echo "len is $len<br>";
$varS = shorten($var, $len);
if ($varS != $var) {
	$varS = str_replace(".", ".&#8203;", $varS);
	$varS = str_replace("?", "?&#8203;", $varS);
	$varS = str_replace("&amp;", "&amp;&#8203;", $varS);
	$varS .= "..."; 
} else {
	$varS = str_replace(".", ".&#8203;", $varS);
	$varS = str_replace("?", "?&#8203;", $varS);
	$varS = str_replace("&amp;", "&amp;&#8203;", $varS);
}
return $varS;
}

/*********************************************************************************/
/* Function: shortenPage
/* Role: Shorten a page, and add dots if it did shorten 
/* Parameters: 
/*   - $var: the string to shorten
/*   - $len: function to set number of characters: 1 for a short URL, 2 for an average one, 3 for a long one, default values:
/*        0: 20 chars
/*        1: 40 chars
/*        2: 60 chars
/*        3: 80 chars
/*        4: 100 chars
/* Output:
/*   - the shorten string
/*********************************************************************************/
function shortenPage ($var, $len = 3) {
global $display;
$len = 20+$display['stringLengthsFactor']*$len;
// echo "len is $len<br>";
$varS = shorten($var,$len);
if ($varS != $var) { 
	$varS .= "...";
}
return $varS;
}

/*********************************************************************************/
/* Function: shortencenter
/* Role: Shorten a string from the center (remove chars at the center)
/* Parameters: 
/*   - $var: the string to shorten
/*   - $len: number of characters
/* Output:
/*   - the shorten string
/* Created: 06/2004
/*********************************************************************************/
function shortencenter ($var, $len = 30) {
  if (empty ($var)) {
    return "";
  }
  if (strlen ($var) < $len) {
    return $var;
  }
  $len = floor($len/2)-1;
  return  substr($var,0,$len)."...".substr ($var,strlen($var)-$len,$len);
}

/*********************************************************************************/
/* Function: urlLink
/* Role: prepares a link for a referrer, or something like it...
/* Parameters:
/*   - $url: the url you need to link to
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
/* Created: 01/2007
/*********************************************************************************/
function urlLink($url, $short=1, $extrapar="") {
// echo "len is $short<br>";
$url=cleanURL($url);
$urlS = shortenURL($url,$short);
return "<a href=\"$url\" target=\"_new\" title=\"$url\" rel=\"nofollow\" $extrapar>$urlS</a>";
}

/*********************************************************************************/
/* Function: nToday
/* Role: number of access to a site today
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $timetoday: day on the server, in unixtime
/* Output:
/*   - number of access
/* Created: 06/2004
/* Changed (pulled out connection to db): 10/2005
/* Changed 04/2006 for new format of table (label=0 has total for one day)
/* Changed 01/2008 for time differences
/*********************************************************************************/
function nToday($c,$table, $site, $timetoday) {
  $today = date("Y-m-d", $timetoday);
  $thismonth = date("Y-m-01", $timetoday);
  // get total access today WHERE date='$today'
  $req = "SELECT SUM(count) as count FROM ${table}_acces WHERE date='$today' AND label=0";
  $res = mysql_query($req,$c);
  $count=mysql_fetch_array($res);
  if ($count['count'] == "") {
    $c = 0;
  } else {
    $c = $count['count'];
  }
  return $c;
}



/*********************************************************************************/
/* Function: vToday
/* Role: number of unique visitors to a site today
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $timetoday: day on the server, in unixtime
/* Output:
/*   - number of access
/* Created: 06/2006 after suggestion from  Martynas Majeris
/* Changed 01/2008 for time differences
/*********************************************************************************/
function vToday($c,$table, $site, $timetoday) {
  $today = date("Y-m-d", $timetoday);
  $thismonth = date("Y-m-01", $timetoday);
  // get total access today WHERE date='$today'
  $req = "SELECT SUM(count) as count FROM ${table}_uniq WHERE date='$today' AND label=0";
  $res = mysql_query($req,$c);
  $count=mysql_fetch_array($res);
  if ($count['count'] == "") {
    $c = 0;
  } else {
    $c = $count['count'];
  }
  return $c;
}

/*********************************************************************************/
/* Function: nYesterday
/* Role: number of access to a site yesterday
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $timetoday: day on the server, in unixtime
/* Output:
/*   - number of access
/* Created: 06/2006 after suggestion from  Martynas Majeris
/* Changed 01/2008 for time differences
/*********************************************************************************/
function nYesterday($c,$table, $site, $timetoday) {
  $y = date("Y", $timetoday);
  $m = date("m", $timetoday);
  $d = date("d", $timetoday);
  $d--;
  if ($d == 0) {
    $m--;
    if ($m == 0) {
      $y--;
      $m = 12;
    }
    $d = date("t", mktime(0, 0, 0, $m, 1, $y));
  }
  $today = date("Y-m-d", mktime(0, 0, 0, $m, $d, $y));
  $thismonth = date("Y-m-01");
  // get total access today WHERE date='$today'
  $req = "SELECT SUM(count) as count FROM ${table}_acces WHERE date='$today' AND label=0";
  $res = mysql_query($req,$c);
  $count=mysql_fetch_array($res);
  if ($count['count'] == "") {
    $c = 0;
  } else {
    $c = $count['count'];
  }
  return $c;
}

/*********************************************************************************/
/* Function: vYesterday
/* Role: number of unique visitors to a site yesterday
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $timetoday: day on the server, in unixtime
/* Output:
/*   - number of access
/* Created: 06/2006 after suggestion from  Martynas Majeris
/* Changed 01/2008 for time differences
/*********************************************************************************/
function vYesterday($c,$table, $site, $timetoday) {
  $y = date("Y", $timetoday);
  $m = date("m", $timetoday);
  $d = date("d", $timetoday);
  $d--;
  if ($d == 0) {
    $m--;
    if ($m == 0) {
      $y--;
      $m = 12;
    }
    $d = date("t", mktime(0, 0, 0, $m, 1, $y));
  }
  $today = date("Y-m-d", mktime(0, 0, 0, $m, $d, $y));
  $thismonth = date("Y-m-01");
  // get total access today WHERE date='$today'
  $req = "SELECT SUM(count) as count FROM ${table}_uniq WHERE date='$today' AND label=0";
  $res = mysql_query($req,$c);
  $count=mysql_fetch_array($res);
  if ($count['count'] == "") {
    $c = 0;
  } else {
    $c = $count['count'];
  }
  return $c;
}


/*********************************************************************************/
/* Function: firstDay
/* Role: first day recorded
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/* Output:
/*   - the first day, Y-m-d
/* Created: 02/2005
/* Changed (pulled out connection to db): 10/2005
/*********************************************************************************/
function firstDay($c,$table) {
  $req = "SELECT date FROM ${table}_acces ORDER BY date ASC LIMIT 0,1;";
  $res = mysql_query($req,$c);
  $first=mysql_fetch_object($res);
  return $first->date;
}

/*********************************************************************************/
/* Function: lastDay
/* Role: last day recorded
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/* Output:
/*   - the last day, Y-m-d
/* Created: 02/2005
/* Changed (pulled out connection to db): 10/2005
/*********************************************************************************/
function lastDay($c,$table) {
  $req = "SELECT date FROM ${table}_acces ORDER BY date DESC LIMIT 0,1;";
  $res = mysql_query($req,$c);
  $first=mysql_fetch_object($res);
  return $first->date;
}

/*********************************************************************************/
/* Function: nThisMonth
/* Role: number of access to a site this month
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $timetoday: day on the server, in unixtime
/* Output:
/*   - number of access
/* Created: 06/2004
/* Changed (pulled out connection to db): 10/2005
/* Changed 04/2006 for new format of table (label=0 has total for one day)
/* Changed 01/2008 for time differences
/*********************************************************************************/
function nThisMonth($c,$table, $site, $timetoday) {
$today = date("Y-m-d", $timetoday);
$thismonth = date("Y-m-01", $timetoday);
$req = "SELECT SUM(count) as count FROM ${table}_acces WHERE date>='$thismonth' AND date<='$today' AND label=0";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
if ($count['count'] == "") {
	$c = 0;
} else {
	$c = $count['count'];
}
return $c;
}

/*********************************************************************************/
/* Function: vThisMonth
/* Role: number of unique visitors to a site this month
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $timetoday: day on the server, in unixtime
/* Output:
/*   - number of access
/* Created: 06/2006 after suggestion from  Martynas Majeris
/* Changed 01/2008 for time differences
/*********************************************************************************/
function vThisMonth($c,$table, $site, $timetoday) {
$today = date("Y-m-d", $timetoday);
$thismonth = date("Y-m-01", $timetoday);
$req = "SELECT SUM(count) as count FROM ${table}_uniq WHERE date>='$thismonth' AND date<='$today' AND label=0";
$res = mysql_query($req,$c);
$count=mysql_fetch_array($res);
if ($count['count'] == "") {
	$c = 0;
} else {
	$c = $count['count'];
}
return $c;
}

/*********************************************************************************/
/* Function: nTotal
/* Role: total number of access to a site
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/* Output:
/*   - number of access
/* Created: 02/2005
/* Changed (pulled out connection to db): 10/2005
/* Changed 04/2006 for new format of table (label=0 has total for one day)
/* Changed 04/2009 to use page table instead of access table (in case numbers where changed by the user)
/*********************************************************************************/
function nTotal($c,$table, $site) {
  $req = "SELECT (SUM(se)+SUM(ref)+SUM(other)+SUM(internal)+SUM(old)) as count FROM ${table}_pages";
  $res = mysql_query($req,$c);
  $count=mysql_fetch_array($res);
  if ($count['count'] == "") {
    $c = 0;
  } else {
    $c = $count['count'];
  }
  return $c;
}

/*********************************************************************************/
/* Function: vTotal
/* Role: total number of unique visitors to a site
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/* Output:
/*   - number of access
/* Created: 06/2006 after suggestion from  Martynas Majeris
/*********************************************************************************/
function vTotal($c,$table, $site) {
  $req = "SELECT SUM(count) as count FROM ${table}_uniq where label=0";
  $res = mysql_query($req,$c);
  $count=mysql_fetch_array($res);
  if ($count['count'] == "") {
    $c = 0;
  } else {
    $c = $count['count'];
  }
  return $c;
}

/*********************************************************************************/
/* Function: vOnline
/* Role: number of unique visitors visiting a site at this time
/* Parameters:
/*   - $table: base name for sql tables
/* Output:
/*   - number of visitors
/* Created: 11/2006
/*********************************************************************************/
function vOnline($table) {
global $path;
global $tmpdirectory;
$tmpfile = "$path/$tmpdirectory/ipbased.$table.dat";
if (!file_exists($tmpfile)) {
	touch($tmpfile);
}
$stats = file("$tmpfile");
$count = 0;
foreach($stats as $log) {
	$count += 1;
}
return $count;
}

/*********************************************************************************/
/* Function: dropSQLStructure
/* Role: drops the SQL structures of a group of tables, used in the application design...
/* Parameters:
/*   - $c: connection to database
/*   - $table: base name for sql tables
/* Output:
/*   -
/* Created: 06/2004
/* Changed (pulled out connection to db): 10/2005
/* Changed 11/2005: two more tables (uniq and path)
/*********************************************************************************/
function dropSQLStructure($c,$table) {
$tables[] = $table."_pages";
$tables[] = $table."_acces";
$tables[] = $table."_host";
$tables[] = $table."_country";
$tables[] = $table."_hour";
$tables[] = $table."_day";
$tables[] = $table."_os";
$tables[] = $table."_browser";
$tables[] = $table."_keyword";
$tables[] = $table."_referrer";
$tables[] = $table."_retention";
$tables[] = $table."_uniq";
$tables[] = $table."_path";
foreach ($tables as $thetable) {
	$sql = "show create table $thetable";
	$describe=mysql_query($sql,$c);
	while ($ligne=mysql_fetch_array($describe)){
		//echo "<br>$ligne";
		$str = str_replace("$table", "\${table}", $ligne[1]);
		echo "<br>$str;<br>";
	}
}
}

/*********************************************************************************/
/* Function: diskUsage
/* Role: drops the detailed disk usage
/* Parameters: 
/*   - $c: connection to database
/* Output:
/* Created: 06/2004
/* Modified 03/2005 because of new format of array "sites"
/* Changed (pulled out connection to db): 10/2005
/* Changed 11/2005: two more tables (uniq and path)
/*********************************************************************************/
function diskUsage ($c) {
global $sites;
$extension = array("_retention", "_hour", "_day", "_uniq", "_path", "_referrer", "_keyword", "_browser", "_os", "_country", "_host", "_acces", "_pages");
echo "<table class='simple'>
<tr><td class='title' colspan='3'>Existing databases</td></tr>
<tr><td class='caption'>Domain</td><td class='caption'>Table</td><td class='caption'>Disk usage</td></tr>";
while ($bar=each($sites)) {
	$id = $bar[0];
	$table = $bar[1][table];
	$site = $bar[1][site];
	echo "<tr><td>$site</td><td>$table</td><td>";
	reset($extension);
	foreach ($extension as $ext) {
		$thetable = "$table$ext";
		$result = mysql_query ("show table status like '$thetable'",$c);
		$row = mysql_fetch_array($result);
		$number_line = $row['Rows'];      // Get number of lines in tables
		$table_size = number_format(($row['Data_length'] + $row['Index_length'])/1024,1);
		echo "$thetable: $number_line rows, $table_size kb<br>";
	}
	echo "</td></tr>";
	unset($tables);
}
echo "</table>";
}

/*********************************************************************************/
/* Function: diskUsageTable
/* Role: returns the total disk usage for a set of tables
/* Parameters: 
/*   - $c: connection to database
/*  - $table: table root
/* Output:
*   - disk usage in kb
/* Created: 06/2004
/* Changed (pulled out connection to db): 10/2005
/* Changed 11/2005: two more tables (uniq and path)
/*********************************************************************************/
function diskUsageTable ($c,$table) {
$extension = array("_retention", "_hour", "_day", "_uniq", "_path", "_referrer", "_keyword", "_browser", "_os", "_country", "_host", "_acces", "_pages", "_iplist");
$table_size = 0;
foreach ($extension as $ext) {
	$thetable = "$table$ext";
	$result = mysql_query ("show table status like '$thetable'",$c);
	$row = mysql_fetch_array($result);
	// $number_line = $row['Rows'];      // Get number of lines in tables
	$table_size += ($row['Data_length'] + $row['Index_length']);
}
$table_size = size_hum_read($table_size);
return $table_size;
}

/*********************************************************************************/
/* Function: getTimeEn
/* Role: returns a time interval in a more readable format
/* Parameters: 
/*   - $originalDate: time to convert
/* Output: the duration, in english
/* Created 09/2005. Inspired strongly by the php website
/*********************************************************************************/
function getTimeEn($elapsedTime, $roundTo=0) {
global $strings;
if($elapsedTime==1) {
	// One second
	$elapsedString = $elapsedTime." ".$strings['seconds-short'];
} else if($elapsedTime<(60*2)) {
	// Seconds
	$elapsedString = $elapsedTime . " ".$strings['seconds-short'];
} else if($elapsedTime<(60*60*2)) {
	// Minutes
	$elapsedString = round($elapsedTime/60, $roundTo) . " ".$strings['minutes-short'];
} else if($elapsedTime<(60*60*24*2)) {
	// Hours
	$elapsedString = round($elapsedTime/60/60, $roundTo) . " ".$strings['hours-short'];
} else if($elapsedTime<(60*60*24*7*2)) {
	// Days
	$elapsedString = round($elapsedTime/60/60/24, $roundTo) . " " .$strings['days-short'];
} else if($elapsedTime<(60*60*24*30*2)) {
	// Weeks
	$elapsedString = round($elapsedTime/60/60/24/7, $roundTo) . " ".$strings['weeks-short'];
} else if($elapsedTime<(60*60*24*365*2)) {
	// Months
	$elapsedString = round($elapsedTime/60/60/24/12, $roundTo) . " ".$strings['months-short'];
} else {
	// Years
	$elapsedString = round($elapsedTime/60/60/24/365, $roundTo) . " ".$string['years'];
}

return $elapsedString;
}
 
/*********************************************************************************/
/* Function: ls
/* ls(dir,pattern) return file list in "dir" folder matching "pattern"
/* ls("path","module.php?") search into "path" folder for module.php3, module.php4, ...
/* ls("images/","*.jpg") search into "images" folder for JPG images
/* Created: 09/2005
/* Source: php website
/*********************************************************************************/
 
function ls($__dir="./",$__pattern="*.*") {
  settype($__dir,"string");
  settype($__pattern,"string");
  $__ls=array();
  $__regexp=preg_quote($__pattern,"/");
  $__regexp=preg_replace("/[\\x5C][\x2A]/",".*",$__regexp);
  $__regexp=preg_replace("/[\\x5C][\x3F]/",".", $__regexp);
  if(is_dir($__dir))
   if(($__dir_h=@opendir($__dir))!==FALSE) {
    while(($__file=readdir($__dir_h))!==FALSE)
    if(preg_match("/^".$__regexp."$/",$__file))
      array_push($__ls,$__file);
 
    closedir($__dir_h);
    sort($__ls,SORT_STRING);
   }
  return $__ls;
 } 

/*********************************************************************************/
/* Function: lsexclude
/* ls(dir,pattern,exclude) return file list in "dir" folder matching "pattern" and that do not match "exclude"
/* Created: 10/2006
/*********************************************************************************/
 
function lsexclude($__dir="./",$__pattern="*.*", $exclude) {
	$list = ls($__dir,$__pattern);
	$list2 = array();
	//$__regexp=preg_quote($exclude,"/");
	$__regexp=$exclude;
	$__regexp=preg_replace("/[\\x5C][\x2A]/",".*",$__regexp);
	$__regexp=preg_replace("/[\\x5C][\x3F]/",".", $__regexp);
	foreach ($list as $item) {
		if(!preg_match("/^".$__regexp."$/",$item))
			array_push($list2,$item);
	}
	return $list2;
}

/*********************************************************************************/
/* Function: tmpClean
/* Role: removes all files from tmp directory
/* Parameters:
/*   - how long to keep the files (in seconds)
/* Output:
/* Created: 09/2005
/* 11/2005: added section to delete page rank cache
/*********************************************************************************/
function tmpClean($limit = 36000)  {
	global $tmpdirectory;
	$limit = time()-$limit;
	$dir = $tmpdirectory;
	$list = ls($dir,'tmp*.txt.php');
	foreach ($list as $file) { 
		$pos = strpos ($file,".");
		$time = substr($file, $pos+1);
		$pos = strpos ($time,".");
		$time = substr($time, 0,$pos);
		if (($time>1000)&&($time<$limit)) {
			unlink("$dir/".$file);
		}
	}
	$list = ls($dir,'pr*.php');
	foreach ($list as $file) { 
		$pos = strpos ($file,".");
		$time = substr($file, $pos+1);
		$pos = strpos ($time,".");
		$time = substr($time, 0,$pos);
		if (($time>1000)&&($time<$limit)) {
			unlink("$dir/".$file);
		}
	}
}

/*********************************************************************************/
/* Function: countpages
/* Role: count the number of pages tracked
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/* Output:
/*   - number of pages
/* Created: 11/2007
/*********************************************************************************/
function countpages($c,$table)  {
$sql = "SELECT count(*) as count FROM ${table}_pages";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
return $row->count;
}

/*********************************************************************************/
/* Function: setcachepagename
/* Role: sets a gobal variable with cachepagename[pageid] = page name
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/* Output:
/*   - 
/* Created: 09/2005
/*********************************************************************************/
function setcachepagename($c,$table)  {
global $cachepagenames, $cachepagenamesset;
if (!$cachepagenamesset) {
	$cachepagenames = array();
	$sql = "SELECT id,name FROM ${table}_pages ORDER BY name ASC";
	$res = mysql_query($sql,$c);
	while($row = mysql_fetch_object($res)) {
		$cachepagenames[$row->id] = $row->name;
	}
	$cachepagenamesset = true;
}
}

/*********************************************************************************/
/* Function: pagename
/* Role: returns the name of a page, based on it's id
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $pageid
/* Output:
/*   - pagename
/* Created: 09/2005
/*********************************************************************************/
function pagename($c,$table,$pageid)  {
global $cachepagenames, $cachepagenamesset;
if (!$cachepagenamesset) setcachepagename($c,$table);
return $cachepagenames[$pageid];
}


/*********************************************************************************/
/* Function: valid_pathid
/* Role: Make sure that the pathid is valid
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $pageid
/* Output:
/*   - pageid or 0
/* Created: 07/2007 to avoid sql injections with this parameter
/********************************************************************************/
function valid_pathid($c,$table,$pathid)  {
global $cachepagenames, $cachepagenamesset;
if (!$cachepagenamesset) setcachepagename($c,$table);
$patharray = explode("|",$pathid);
$test = true;
foreach ($patharray as $key => $val) {
	$val = intval($val);
	$patharray[$key] = $val;
	if (!array_key_exists($val, $cachepagenames) && ($val !=0 )) $test = false;
}
$pathid = implode("|", $patharray);
if ($test) return $pathid;
return 0;
}


/*********************************************************************************/
/* Function: valid_pageid
/* Role: Make sure that the pageid is valid
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $pageid
/* Output:
/*   - pageid or 0
/* Created: 06/2007 to avoid sql with this parameter
/********************************************************************************/
function valid_pageid($c,$table,$pageid)  {
global $cachepagenames, $cachepagenamesset;
if (!$cachepagenamesset) setcachepagename($c,$table);
$pageid = intval($pageid);
if (array_key_exists($pageid, $cachepagenames)) return $pageid;
return 0;
}

/*********************************************************************************/
/* Function: formnavpages
/* Role: returns a navigation form to choose a page or the whole site
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $showpage: the selected page
/*   - $url: the url to call in the form
/*   - $title: title to put in front of the form
/*   - $includeall: if true, 'all pages' is a possible choice
/* Output:
/*   - a string with the form
/* Created: 09/2005
/*********************************************************************************/
function formnavpages($c,$table,$showpage,$url,$title, $includeall=true)  {
global $cachepagenames, $cachepagenamesset;
global $strings;
if (!$cachepagenamesset) setcachepagename($c,$table);
$str = "<div align=\"center\"><form name=\"pageselect\" action=\"$url\" method=\"post\"><table class=\"form\">
<tr><td valign=\"middle\" width=\"150px\">$title</td>
<td valign=\"middle\" width=\"200px\"><SELECT NAME=\"pageid\">";
if ($includeall) {
	if ($showpage=='all') {
		$str .= "<option value=\"all\" selected>".$strings['allpages']."</option>";
	} else {
		$str .= "<option value=\"all\">all pages</option>";
	}
}
foreach ($cachepagenames as $thispageid=>$pagename) {
	$thispagename = shortenPage($pagename,2);
	if ($showpage==$thispageid) {
		$str .= "<option value=$thispageid selected>$thispagename</option>";
	} else {
		$str .= "<option value=$thispageid>$thispagename</option>";
	}
}
$str .= "</select></td>
<td valign=\"middle\" width=\"50px\"><input type=\"submit\" value=\"".$strings['Ok']."\"></td>
</tr></table></form>\n</div>\n";
return $str;
}

/*********************************************************************************/
/* Function: selectnavpages
/* Role: returns a portion of form to choose a page or the whole site
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/*   - $showpage: the selected page
/*   - $includeall: if true, 'all pages' is a possible choice
/* Output:
/*   - a string with the select portion of form
/* Created: 10/2007 out of formnavpages
/*********************************************************************************/
function selectnavpages($c, $table, $showpage, $includeall=true)  {
global $cachepagenames, $cachepagenamesset;
global $strings;
if (!$cachepagenamesset) setcachepagename($c,$table);
$str = "<SELECT NAME=\"pageid\">";
if ($includeall) {
	if ($showpage=='all') {
		$str .= "<option value=\"all\" selected>".$strings['allpages']."</option>";
	} else {
		$str .= "<option value=\"all\">all pages</option>";
	}
}
foreach ($cachepagenames as $thispageid=>$pagename) {
	$thispagename = shortenPage($pagename,2);
	if ($showpage==$thispageid) {
		$str .= "<option value=$thispageid selected>$thispagename</option>";
	} else {
		$str .= "<option value=$thispageid>$thispagename</option>";
	}
}
$str .= "</select>\n";
return $str;
}


/*********************************************************************************/
/* Function: create_db
/* Role: creates tables in database to track a new site
/* Parameters:
/*   - $c: connection to the database
/*   - $table
/* Output:
/* Created: 2004
/* Changed (pulled out connection to db): 10/2005
/* Changed 11/2005: two more tables (uniq and path)
/*********************************************************************************/

function create_db($c,$table) {
global $strings;
echo "<div align=\"left\">
<br>".$strings['Creatingtables'];
echo "<ul>";
$utf = "";
$mysql_version = mysql_get_server_info();
$pos = strpos($mysql_version, '.');
$pos = strpos($mysql_version, '.',$pos+1);
$mysql_version_major = substr($mysql_version, 0, $pos);
if ($mysql_version_major > 4.1) {
	$utf = "character set utf8 collate utf8_unicode_ci";
}
include ("Php/Functions/sqlTables.sql.php");
$sqllist = explode("\n", $sql);
foreach($sqllist as $doit) {
	$test = preg_split("/[\s]+/", $doit);
	if (strtoupper($test[0]) == "CREATE") {
		echo "<li>".$test[2]."</li>\n";
	}
	if (!mysql_query($doit,$c)) 
		die("<br>".$strings['Errorwithdatabase'].": ".mysql_error()."<br>".$strings['Wasworkingon']." $doit.");
}
echo "</ul>
<br>".$strings['Donewithcreationoftables']."</div>\n";
}

/*********************************************************************************/
/* Function: size_hum_read
/* Role: Returns a size in bytes, kilobytes, megabytes, or whatever is best
/* Parameters:
/*   - $size
/* Created: 04/2006 (taken on php Web site)
/*********************************************************************************/
function size_hum_read($size) {
$i=0;
$iec = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
while (($size/1024)>1) {
	$size=$size/1024;
	$i++;
}
return substr($size,0,strpos($size,'.')+2)."&nbsp;".$iec[$i];
}

?>
