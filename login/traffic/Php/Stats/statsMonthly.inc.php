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
/* Function: echoProfile
/* Role: stats results on visitors profile (mobile users, windows computers...)
/* Parameters: 
/*   - $table: base name for sql tables
/*   - $site: id of the website
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 09/2009
/*********************************************************************************/
function echoProfile($c,$table, $site,$toplot, $sort) {
  echoMonthly($c,$table,$site,"profile",$toplot, $sort);
}

/*********************************************************************************/
/* Function: echoWindows
/* Role: show windows version as a function of time
/* Parameters: 
/*   - $table: base name for sql tables
/*   - $site: id of the website
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 09/2009
/*********************************************************************************/
function echoWindows($c,$table, $site,$toplot, $sort) {
  echoMonthly($c,$table,$site,"windows",$toplot, $sort);
}

/*********************************************************************************/
/* Function: echoResolution
/* Role: stats results on screen resolution
/* Parameters: 
/*   - $table: base name for sql tables
/*   - $site: id of the website
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 09/2008
/*********************************************************************************/
function echoResolution($c,$table, $site,$toplot, $sort) {
  echoMonthly($c,$table,$site,"resolution",$toplot, $sort);
}


/*********************************************************************************/
/* Function: echoOS
/* Role: stats results on operating system
/* Parameters: 
/*   - $table: base name for sql tables
/*   - $site: id of the website
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 06/2004
/*********************************************************************************/
function echoOS($c,$table, $site,$toplot, $sort) {
  echoMonthly($c,$table,$site,"os",$toplot, $sort);
}

/*********************************************************************************/
/* Function: echoOS
/* Role: stats results on web browsers
/* Parameters: 
/*   - $table: base name for sql tables
/*   - $site: id of the website
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 06/2004
/*********************************************************************************/
function echoBrowser($c,$table, $site,$toplot, $sort) {
  echoMonthly($c,$table,$site,"browser",$toplot, $sort);
}

/*********************************************************************************/
/* Function: echoOS
/* Role: stats results on operating systems
/* Parameters: 
/*   - $table: base name for sql tables
/*   - $site: id of the website
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 06/2004
/*********************************************************************************/
function echoCountry($c,$table, $site,$toplot, $sort) {
  echoMonthly($c,$table,$site,"country",$toplot, $sort);
}

/*********************************************************************************/
/* Function: browserImg
/* Role: returns the logo of a browser 
/* Parameters: 
/*   - $c: browser name
/* Output:
/*   - string, with image link to the browser logo
/* Created: 11/2006
/*********************************************************************************/
function browserImg($c, $extratxt = "") {
	$img="Img/Browser/noimage.gif";
	$strings = array("msie", "firefox", "gecko", "konqueror", "safari", "ns", "opera", "crawler", "google", "lynx","galeon","sony", "icab", "blazer", "sunplex","omniweb", "netfront", "nokia","chrome", "blackberry");
	$images = array("Img/Browser/ie.gif", "Img/Browser/firefox.gif", "Img/Browser/mozilla.gif", "Img/Browser/konqueror.gif", "Img/Browser/safari.gif", "Img/Browser/netscape.gif", "Img/Browser/opera.gif", "Img/Browser/crawler.gif", "Img/Browser/crawler.gif","Img/Browser/lynx.gif","Img/Browser/galeon.gif", "Img/OS/playstation.gif", "Img/Browser/iCab.gif", "Img/OS/palm.gif", "Img/OS/sun.gif", "Img/Browser/omniweb.gif", "Img/Browser/netfront.gif", "Img/OS/nokia.jpg", "Img/Browser/chrome.gif", "Img/Browser/blackberry.gif");
	$nTop = count($images);
	for ($cpt = 0; $cpt < $nTop ; $cpt++) {
		if (strpos(strtolower($c),$strings[$cpt]) !== FALSE) $img=$images[$cpt];
	}
	if(is_file($img)) {
		list($width, $height, $type, $attr) = getimagesize($img);
		$width = intval($width*14/$height);
		$string = "<center><img src=\"$img\"  width=\"$width\" height=\"14\" alt=\"$c\" title=\"$c $extratxt\"></center>";
	} else {
		$string = "$c";
	}
	return $string;
}

/*********************************************************************************/
/* Function: browserImgName
/* Role: returns the logo and name of a browser 
/* Parameters: 
/*   - $c: browser name
/* Output:
/*   - string, with image link to the browser logo and its name
/* Created: 05/2005
/*********************************************************************************/
function browserImgName($c) {
	$img="Img/Browser/noimage.gif";
	$strings = array("msie", "firefox", "gecko", "konqueror", "safari", "ns", "opera", "crawler", "google", "lynx", "galeon", "sony", "icab", "blazer", "sunplex", "omniweb", "netfront", "nokia", "chrome", "blackberry");
	$images = array("Img/Browser/ie.gif", "Img/Browser/firefox.gif", "Img/Browser/mozilla.gif", "Img/Browser/konqueror.gif", "Img/Browser/safari.gif", "Img/Browser/netscape.gif", "Img/Browser/opera.gif", "Img/Browser/crawler.gif", "Img/Browser/crawler.gif","Img/Browser/lynx.gif","Img/Browser/galeon.gif", "Img/OS/playstation.gif", "Img/Browser/iCab.gif", "Img/OS/palm.gif", "Img/OS/sun.gif", "Img/Browser/omniweb.gif", "Img/Browser/netfront.gif", "Img/OS/nokia.jpg", "Img/Browser/chrome.gif", "Img/Browser/blackberry.gif");
	$nTop = count($images);
	for ($cpt = 0; $cpt < $nTop ; $cpt++) {
		if (strpos(strtolower($c), $strings[$cpt]) !== FALSE) $img=$images[$cpt];
	}
	if(is_file($img)) {
		$string = "<center><img src=\"$img\" alt=\"$c\"></center></td><td>$c";
	} else {
		$string = "</td><td>$c";
	}
	return $string;
}

/*********************************************************************************/
/* Function: osImg
/* Role: returns the logo of an OS
/* Parameters: 
/*   - $c: os name
/* Output:
/*   - string, with image link to the os logo
/* Created: 11/2006
/*********************************************************************************/
function osImg($c, $extratxt = "") {
	$img="Img/OS/noimage.gif";
	$strings = array("win", "linux", "mac", "sun", "bsd", "sgi", "hp", "crawler", "google","palm", "ericsson", "sony","iphone", "symbian", "nokia", "blackberry", "android");
	$images = array("Img/OS/windows.gif", "Img/OS/linux.gif", "Img/OS/osx.gif", "Img/OS/sun.gif", "Img/OS/bsd.gif", "Img/OS/sgi.gif", "Img/OS/hp.gif", "Img/OS/crawler.gif", "Img/OS/crawler.gif","Img/OS/palm.gif", "Img/OS/sonyericsson.gif", "Img/OS/playstation.gif", "Img/OS/iPhone.gif", "Img/OS/symbian.gif", "Img/OS/nokia.jpg", "Img/OS/blackberry.jpg", "Img/OS/android.gif");
	$nTop = count($strings);
	for ($cpt = 0; $cpt < $nTop ; $cpt++) {
		if (strpos(strtolower($c), $strings[$cpt]) !== FALSE) { $img=$images[$cpt]; break; }
	}
	if(is_file($img)) {
		list($width, $height, $type, $attr) = getimagesize($img);
		$width = intval($width*14/$height);
		$string = "<center><img src=\"$img\"  height=\"14\" width=\"$width\" alt=\"$c\" title=\"$c $extratxt\"></center>";
	} else {
		$string = "$c";
	}
	return $string;
}

/*********************************************************************************/
/* Function: osImgName
/* Role: returns the logo and name of an OS
/* Parameters: 
/*   - $c: os name
/* Output:
/*   - string, with image link to the os logo and its name
/* Created: 05/2005
/*********************************************************************************/
function osImgName($c) {
	$img="Img/OS/noimage.gif";
	$strings = array("win", "linux", "mac", "sun", "bsd", "sgi", "hp", "crawler", "google","palm", "ericsson", "sony","iphone", "symbian", "nokia", "blackberry", "android");
	$images = array("Img/OS/windows.gif", "Img/OS/linux.gif", "Img/OS/osx.gif", "Img/OS/sun.gif", "Img/OS/bsd.gif", "Img/OS/sgi.gif", "Img/OS/hp.gif", "Img/OS/crawler.gif", "Img/OS/crawler.gif","Img/OS/palm.gif", "Img/OS/sonyericsson.gif", "Img/OS/playstation.gif", "Img/OS/iPhone.gif", "Img/OS/symbian.gif", "Img/OS/nokia.jpg", "Img/OS/blackberry.jpg", "Img/OS/android.gif");
	$nTop = count($strings);
	for ($cpt = 0; $cpt < $nTop ; $cpt++) {
		if (strpos(strtolower($c),$strings[$cpt]) !== FALSE) { $img=$images[$cpt]; break; }
	}
	if(is_file($img)) {
		$string = "<center><img src=\"$img\" alt=\"$c\"></center></td><td>$c";
	} else {
		$string = "</td><td>$c";
	}
	return $string;
}

/*********************************************************************************/
/* Function: countryFlag
/* Role: converts the 2 chars string into flag 
/* Parameters: 
/*   - $c: 2 chars country string
/* Output:
/*   - string, with image link to the flag
/* Created: 11/2006
/*********************************************************************************/
function countryFlag($c) {
  global $ip2c;
  $ty=strtolower($c);
  $ty=str_replace("\n","",$ty);
  $rz = countryName($ty);
  $img="Img/Flags/$ty.png";
  if(is_file($img)) {
	list($width, $height, $type, $attr) = getimagesize($img);
	$width = intval($width*12/$height);
    $string = "<center><img src=\"$img\" height=\"12\" width=\"$width\" alt=\"$rz\" title=\"$rz\"></center>";
  } else {
    $string = "$rz";
  }
  return $string;
}

/*********************************************************************************/
/* Function: countryNameFlag
/* Role: converts the 2 chars string into full country name 
/*    and flag 
/* Parameters: 
/*   - $c: 2 chars country string
/* Output:
/*   - string, with image link to the flag and full country name
/* Source: ip2c, http://hot-things.net
/* Created: 06/2004
/* Changed 05/2005 (added </td><td> between the image and the country name)
/*********************************************************************************/
function countryNameFlag($c) {
  global $ip2c;
  $ty=strtolower($c);
  $ty=str_replace("\n","",$ty);
  $rz = countryName($ty);
  $img="Img/Flags/$ty.png";
  if(is_file($img)) {
		list($width, $height, $type, $attr) = getimagesize($img);
		$string = "<center><img src=\"$img\" alt=\"$rz\" class=\"flag\" $attr></center></td><td>$rz";
  } else {
    $string = "</td><td>$rz";
  }
  return $string;
}

/*********************************************************************************/
/* Function: plotHistory
/* Role: returns a string with a link to a plot with the evolution of the mosy 
/*    popular qty with time 
/* Parameters: 
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $what: can be "os", "browser", "country"
/* Output:
/*   - a link to the plot
/* Created: 09/2005
/*********************************************************************************/
function plotHistory($c,$table, $what) {
global $strings;
global $tmpdirectory;

if ($what == "profile") {
	$tableext = 'os';
	$extraWhereSQL = "";
	$extraWhereSQLAnd = "";
} else if ($what == "windows") {
	$tableext = 'os';
	$extraWhereSQL = "WHERE (LOWER(label) LIKE '%win%')";
	$extraWhereSQLAnd = "AND (LOWER(label) LIKE '%win%')";
} else {
	$tableext = $what;
	$extraWhereSQL = "";
	$extraWhereSQLAnd = "";
}

// Get the monthly data (total)
// Pull number of counts for each month
$req = "SELECT date,SUM(count) as count FROM ${table}_$tableext $extraWhereSQL GROUP BY date;";
$res = mysql_query($req,$c);
$nMonth = 0;
$date = array();
$total = array();
$bigTotal = 0;
while($row = mysql_fetch_object($res)) {
	$date[$nMonth] = $row->date;
	$total[$nMonth] = $row->count;
	$bigTotal += $row->count;
	$nMonth += 1;
}

if ($what != "profile") {
	// We get the nplot most common qties (country, OS, browser out of the db,
	// then plot the history for them
	$nplot = 8;
	// Getting the qties to plot, the most common ones in the last 6 months
	$limitmonth = date("Y-m-01", time()-86400*6*30);
	$req = "SELECT label,SUM(count) as count FROM ${table}_$tableext WHERE date>'$limitmonth' $extraWhereSQLAnd GROUP BY label ORDER BY count DESC LIMIT 0,$nplot;";
	$res = mysql_query($req,$c);
	$qty = array();
	$count = array();
	while($row = mysql_fetch_object($res)) {
		$qty[] = $row->label;
		$count[] = $row->count;
	}
} else {
	// Testing if any of Windows, unix, mac computers, or crawlers or mobile devices should be plotted
	$sqlTestArr = array(
		"(LOWER(label) LIKE '%win%') AND (label NOT LIKE 'Win CE')",
		"(LOWER(label) LIKE '%mac%')",
		"((LOWER(label) LIKE '%linux%') OR (LOWER(label) LIKE '%sun%') OR (LOWER(label) LIKE '%unix%') OR (LOWER(label) LIKE '%osf%') OR (LOWER(label) LIKE '%irix%') or (LOWER(label) LIKE '%bsd%'))",
		"((LOWER(label) LIKE '%crawler%') OR (LOWER(label) LIKE '%google%'))",
		"(LOWER(label) LIKE '%???%')");
	$labelTestArr = array(
		$strings['Windowscomputers'], $strings['Applecomputers'], $strings['Unixcomputers'], $strings['Crawlers'], '???');
	$nplot = 0;
	$toplot = array();
	$qty = array();
	$count = array();
	$thistotal = 0;
	foreach ($sqlTestArr as $i=>$sqlwhere) {
		$req = "SELECT SUM(count) as count FROM ${table}_$tableext WHERE $sqlwhere";
		$res = mysql_query($req,$c);
		$row = mysql_fetch_object($res);
		$thistotal += $row->count;
		if ($row->count > 0) { $toplot[$i] = 1; $nplot += 1; } else { $toplot[$i] = 0; }
	}
	$plotMobiles = 0;
	if ($thistotal < $bigTotal) { $plotMobiles = 1; $nplot += 1; }
}

// For each of the qty to plot, get the monthly data
$datecount = array();
if ($what != "profile") {
	for ($j=0;$j<$nplot;$j++) {
		$req = "SELECT date,count FROM ${table}_$tableext WHERE label='$qty[$j]';";
		$res = mysql_query($req,$c);
		while($row = mysql_fetch_object($res)) {
			$i = array_search($row->date, $date); 
			$datecount[$i][$j] = $row->count;
		}
	}
} else {
	foreach ($sqlTestArr as $i=>$sqlwhere) {
		if ($toplot[$i]) {
			$qty[$i] = $labelTestArr[$i];
			$req = "SELECT date,sum(count) as count FROM ${table}_$tableext WHERE $sqlwhere GROUP BY date";
			$res = mysql_query($req,$c);
			while($row = mysql_fetch_object($res)) {
				$k = array_search($row->date, $date);
				$datecount[$k][$i] = $row->count;
			}
		}
	}
	if ($plotMobiles) {
		$qty[$nplot-1] = $strings['Mobiledevices'];
		foreach($date as $i=>$thisdate) {
			$datecount[$i][$nplot-1] = $total[$i]-array_sum($datecount[$i]);
		}
	}
}

// Plot the history
$start = 100000000000;
$end = 0;
$maxcount = 0;
$string = "<?php  \n\$date_data = array(";
$max = count ($qty);
for ($i=0;$i<$nMonth;$i++) {
	$time = strtotime($date[$i]);
	if ($time<$start) {$start=$time;}
	if ($time>$end) {$end=$time;}
	if ($i>0) {
		$string .= "\n,array(\"\",$time";
	} else {
		$string .= "\narray(\"\",$time";
	}
	$other = 100.;
	for ($j=0;$j<$max;$j++) {
		if (!isset($datecount[$i][$j])) $datecount[$i][$j] = 0;
		$tt = intval(1000.*$datecount[$i][$j]/$total[$i])/10;
		if ($tt == "") $tt = 0;
		$other -= $tt;
		if ($tt>$maxcount) {$maxcount = $tt;}
		$string .= ",$tt";
	}
	if ($other>$maxcount) {$maxcount=$tt;}
	$string .= ",$other)";
}
$legende = "\"$qty[0]\"";
for ($j=0;$j<$max;$j++) {
	if ($what == "country") {
		if (function_exists('countryName-plot')) {
			$txt = shorten(countryName-plot($qty[$j]),12);
		} else {
			$txt = shorten(countryName($qty[$j]),12);
		}
	} else {
			$txt = $qty[$j];
	}
	if ($j==0) {
		$legende = "\"$txt\"";
	} else {
		$legende .= ",\"$txt\"";
	}
}
$legende .= ",\"".$strings['plot-Other']."\"";
$string .= "\n);\n\$start=$start;\n\$end=$end;\n\$maxY=$maxcount;\n\$plottype=\"lines\";"; 
$string .= "\n\$legende = array($legende);\n\$width = 600;\n\$height = 300;\n\$ylabel = \"".$strings['pcoftotal']."\";\n?>";
if ((($start+2679000) != $end)&&$maxcount>0) {
	$timecall = time();
	$temp = fopen ("$tmpdirectory/tmp.$timecall.txt.php", 'w');
	fwrite($temp, $string);
	fclose($temp);
	return "<img src=\"./plotStat.php?file=tmp.$timecall.txt.php\" alt=\"".$strings['History']."\">"; 
} else {
	return $strings['Nothingyet'];
}
}

/*********************************************************************************/
/* Function: plotThisMonth
/* Role: returns a string with a link to a plot with the plot for this month
/* Parameters: 
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $sid: sid of the site
/*   - $what: can be "os", "browser", "country"
/* Output:
/*   - a link to the plot
/* Created: 09/2005
/*********************************************************************************/
function plotThisMonth($c, $sid, $table, $what) {
global $strings;
global $tmpdirectory;
global $sites;
$nplot = 6;
$timeserver = time() + 3600*$sites[$sid]['timediff'];
$timecall = time();
$thismonth = date("Y-m-01", $timeserver);
if ($what == "profile") {
	$thistable = 'os';
} else if  ($what == "windows") {
	$thistable = 'os';
	$extraWhereSQL = "WHERE (LOWER(label) LIKE '%win%')";
	$extraWhereSQLAnd = "AND (LOWER(label) LIKE '%win%')";
} else {
	$thistable = $what;
	$extraWhereSQL = "";
	$extraWhereSQLAnd = "";
}

if ($what != "profile") {
	// get the most popular QTY
	$req = "SELECT label,count FROM ${table}_$thistable WHERE date='$thismonth' $extraWhereSQLAnd ORDER BY count DESC LIMIT 0,$nplot;";
	$res = mysql_query($req,$c);
	$thismonthNQty = 0;
	$totalthismonth = 0;
	while($row = mysql_fetch_object($res)) {
		$thismonthqty[$thismonthNQty] = $row->label;
		$thismonthcount[$thismonthNQty] = $row->count;
		$thismonthNQty += 1;
		$totalthismonth -= $row->count;
	}
} else {
	$thismonthNQty = 0;
	$totalthismonth = 0;
	$sqlArr = array(
		"(LOWER(label) LIKE '%win%') AND (label NOT LIKE 'Win CE')",
		"(LOWER(label) LIKE '%mac%')",
		"((LOWER(label) LIKE '%linux%') OR (LOWER(label) LIKE '%sun%') OR (LOWER(label) LIKE '%unix%') OR (LOWER(label) LIKE '%osf%') OR (LOWER(label) LIKE '%irix%') or (LOWER(label) LIKE '%bsd%'))",
		"((LOWER(label) LIKE '%crawler%') OR (LOWER(label) LIKE '%google%'))",
		"(LOWER(label) LIKE '%???%')");
	$labelArr = array($strings['Windowscomputers'], $strings['Applecomputers'], $strings['Unixcomputers'], $strings['Crawlers'], '???');
	foreach($sqlArr as $i=>$thissql) {
		$sqlReq = "SELECT sum(count) as count FROM ${table}_os WHERE date='$thismonth' AND  $thissql";
		$res = mysql_query($sqlReq,$c);
		$row = mysql_fetch_object($res);
		if ($row->count > 0) {
			$thismonthqty[$thismonthNQty] = $labelArr[$i];
			$thismonthcount[$thismonthNQty] = $row->count;
			$totalthismonth -= $row->count;
			$thismonthNQty += 1;
		}
	}
	$req = "SELECT sum(count) as count FROM ${table}_os WHERE date='$thismonth'";
	$res = mysql_query($req,$c);
	$row = mysql_fetch_object($res);
	if ($row->count+$totalthismonth > 0) {
		$thismonthqty[$thismonthNQty] = $strings['Mobiledevices'];
		$thismonthcount[$thismonthNQty] = $row->count+$totalthismonth;
		$totalthismonth -= $row->count;
		$thismonthNQty += 1;
	}
}
if ($totalthismonth < 0) {
	if ($what != 'profile') {
		$req = "SELECT SUM(count) as count FROM ${table}_$thistable WHERE date='$thismonth' $extraWhereSQLAnd GROUP BY date;";
		$res = mysql_query($req,$c);
		$row = mysql_fetch_array($res);
		$total=$row['count'];
	} else {
		$total=-$totalthismonth;
	}
	$string = "<?php  \n\$date_data = array(";
	$max = min($thismonthNQty,$nplot);
	$order = 0;
	for ($j=0;$j<$max;$j++) {
		$tt = $thismonthcount[$j];
		// Do not include if this is 0
		if ($tt > 0) {
			if ($what == "country") {
				if (function_exists('countryName-plot')) {
					$txt = shorten(countryName-plot($thismonthqty[$j]),12);
				} else {
					$txt = shorten(countryName($thismonthqty[$j]),12);
				}
			} else {
				$txt = $thismonthqty[$j];
			}
			if ($order>0) {
				$string .= ",$tt";
				$legende .= ",\"$txt\"";
			} else {
				$string .= "array('',$tt";
				$legende = "\"$txt\"";
			}
			$order += 1;
		}
	}
	$other = $totalthismonth + $total;
	if ($other > 0) {
		$string .= ",$other";
		$legende .= ",\"".$strings['plot-Other']."\"";
	}
	$string .= "\n));\n\$plottype=\"pie\";\n\$legende = array($legende);\n\$width = 500;\n\$height = 200;\n?>";
	$temp = fopen ("$tmpdirectory/tmp2.$timecall.txt.php", 'w');
	fwrite($temp, $string);
	fclose($temp);
	return "<img src=\"./plotStatPie.php?file=tmp2.$timecall.txt.php\" alt=\"".$strings['Thismonth']."\">";
} 
return "&nbsp;<br>".$strings['Nothingyet']."<br>&nbsp;";
}

/*********************************************************************************/
/* Function: tableHistory
/* Role: returns a string with a table with numbers
/* Parameters: 
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $sid: site id
/*   - $what: can be "os", "browser", "country"
/*   - $link: link to the page without sorting information
/*   - $sort: can be "thismonth", "lastmonth", or "total"
/*   - $full: true if you want a full list, false for a list limited to the one 
/*      that are more than 1% of the total.
/* Output:
/*   - a link to the plot
/* Created: 09/2005
/* Changed: 11/2006 to add option to change the sort order
/*********************************************************************************/
function tableHistory($c,$table, $sid, $what, $link, $sort,$full=false) {
global $strings;
global $lang;
global $sites;

if ($what == 'windows') {
	$thistable = 'os';
	$extraWhereSQL = "WHERE (LOWER(label) LIKE '%win%')";
	$extraWhereSQLAnd = "AND (LOWER(label) LIKE '%win%')";
} else {
	$thistable = $what;
	$extraWhereSQL = "";
	$extraWhereSQLAnd = "";
}

$txtThisMonth = linksTableUp("$link&amp;sort=thismonth", $strings['Sortby'], $strings['Thismonth']);
$txtLastMonth = linksTableUp("$link&amp;sort=lastmonth", $strings['Sortby'], $strings['Lastmonth']);
$txtTotal = linksTableUp("$link&amp;sort=total", $strings['Sortby'], $strings['Total']);

$string = "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"5\">".$strings['Recentstatistics']."</td></tr>
<tr class=\"caption\"><td colspan=\"2\">&nbsp;</td><td>$txtThisMonth</td><td>$txtLastMonth</td><td>$txtTotal</td></tr>
<tr><td colspan=\"2\">".$strings['Totalnumberofaccess']."</td>";
// Getting the info out of the database, we'll do it in two steps: total numbers, and numbers for this month, last month, and two month ago...
$timeserver = time() + 3600*$sites[$sid]['timediff'];
$thismonth = date("Y-m-01", $timeserver);
$lastmonth = date("Y-m-01",strtotime("-1 month", $timeserver));
if ($thismonth == $lastmonth) $lastmonth = date("Y-m-01",strtotime("-1 month -5 days", $timeserver));
$req = "SELECT date, SUM(count) as count FROM ${table}_$thistable $extraWhereSQL GROUP BY date";
$total = array();
$bigTotal = 0;
$res = mysql_query($req,$c);
while($row = mysql_fetch_object($res)) {
	$total[$row->date] = $row->count;
	$bigTotal += $row->count;
}

// Extracting data from db, depending on sorting order
if ($sort == "thismonth") {
	if (!$full) {
		$limit = intval($total[$thismonth]/100);
	} else {
		$limit = 0;
	}
	// Number for thismonth, the month before and the two month ago...
	$req = "SELECT date,label,count FROM ${table}_$thistable WHERE (date='$thismonth' OR date='$lastmonth') $extraWhereSQLAnd";
	$res = mysql_query($req,$c);
	$arrayMonthly = array();
	$arraySort = array();
	while($row = mysql_fetch_object($res)) {
		$arrayMonthly[$row->label][$row->date]=$row->count;
		if ($arrayMonthly[$row->label][$thismonth]>$limit) {
			$arraySort[$row->label] = $arrayMonthly[$row->label][$thismonth];
		}
	}
	arsort($arraySort);
	// Total number, grouped by label
	$req = "SELECT label, SUM(count) as bigcount FROM ${table}_$thistable $extraWhereSQL GROUP BY label ORDER BY bigcount DESC";
	$res = mysql_query($req,$c);
	$arrayTotal = array();
	while(($row = mysql_fetch_object($res))) {
		if ($arraySort[$row->label]>0) {
			$arrayTotal[$row->label] = $row->bigcount;
		}
	}
} else if ($sort == "lastmonth") {
	if (!$full) {
		$limit = intval($total[$lastmonth]/100);
	} else {
		$limit = 0;
	}
	// Number for thismonth, the month before and the two month ago...
	$req = "SELECT date,label,count FROM ${table}_$thistable WHERE (date='$thismonth' OR date='$lastmonth') $extraWhereSQLAnd";
	$res = mysql_query($req,$c);
	$arrayMonthly = array();
	$arraySort = array();
	while($row = mysql_fetch_object($res)) {
		$arrayMonthly[$row->label][$row->date]=$row->count;
		if ($arrayMonthly[$row->label][$lastmonth]>$limit) {
			$arraySort[$row->label] = $arrayMonthly[$row->label][$lastmonth];
		}
	}
	arsort($arraySort);
	// Total number, grouped by label
	$req = "SELECT label, SUM(count) as bigcount FROM ${table}_$thistable $extraWhereSQL GROUP BY label ORDER BY bigcount DESC";
	$res = mysql_query($req,$c);
	$arrayTotal = array();
	while(($row = mysql_fetch_object($res))) {
		if ($arraySort[$row->label]>0) {
			$arrayTotal[$row->label] = $row->bigcount;
		}
	}
} else { // sort on total number
	if (!$full) {
		$limit = intval($bigTotal/100);
	} else {
		$limit = 0;
	}
	// Total number, grouped by label
	$req = "SELECT label, SUM(count) as bigcount FROM ${table}_$thistable $extraWhereSQL GROUP BY label ORDER BY bigcount DESC";
	$res = mysql_query($req,$c);
	$arrayTotal = array();
	$arraySort = array();
	while(($row = mysql_fetch_object($res))) {
		if ($row->bigcount > $limit) {
			$arrayTotal[$row->label] = $row->bigcount;
			$arraySort[$row->label] = 1;
		}
	}
	// Number for thismonth, the month before and the two month ago...
	$req = "SELECT date,label,count FROM ${table}_$thistable WHERE (date='$thismonth' OR date='$lastmonth') $extraWhereSQLAnd";
	$res = mysql_query($req,$c);
	$arrayMonthly = array();
	while($row = mysql_fetch_object($res)) {
		if (isset($arraySort[$row->label])) {
			if ($arraySort[$row->label]>0) {
				$arrayMonthly[$row->label][$row->date]=$row->count;
			}
		}
	}
}

$toPull[0] = $thismonth;
$toPull[1] = $lastmonth;
$counted[0] = 0;
$counted[1] = 0;
$counted[2] = 0;
$counted[3] = 0;
$max[$thismonth] = 0;
$max[$lastmonth] = 0;
foreach ($arraySort as $key=>$doit) {
	if (isset($arrayMonthly[$key][$thismonth])) {
		if ($arrayMonthly[$key][$thismonth] > $max[$thismonth]) $max[$thismonth] = $arrayMonthly[$key][$thismonth];
	}
	if (isset($arrayMonthly[$key][$lastmonth])) {
		if ($arrayMonthly[$key][$lastmonth] > $max[$lastmonth]) $max[$lastmonth] = $arrayMonthly[$key][$lastmonth];
	}
}
if ($total[$thismonth]>0) {
	$max[$thismonth] = 100.*$max[$thismonth]/$total[$thismonth];
} else {
	$max[$thismonth]=0;
}
if ($total[$lastmonth]>0) {
	$max[$lastmonth] = 100.*$max[$lastmonth]/$total[$lastmonth];
} else {
	$max[$lastmonth]=0;
}
for ($i=0;$i<2;$i++) {
	$n = $total[$toPull[$i]];
	if ($n == "") $n = 0;
	$string .= "<td>".format_float($n,0)."</td>";
}
$string .= "<td>".format_float($bigTotal,0)."</td></tr>\n";
$nline = 1;
foreach ($arraySort as $key=>$doit) {
	$nline += 1;
	if (($nline % 2) == 0) { $even = "even";} else {$even="odd";}
	if ($what == "country") {
		$string .= "\n<tr class=\"data $even\"><td>".countryNameFlag($key)."</td>";
	} else if ($what=="browser") {
		$string .= "\n<tr class=\"data $even\"><td>".browserImgName($key)."</td>";
	} else if ($what=="os") {
		$string .= "\n<tr class=\"data $even\"><td>".osImgName($key)."</td>";
	} else {
		$string .= "\n<tr class=\"data $even\"><td colspan=\"2\">$key</td>";
	}
	for ($i=0;$i<2;$i++) {
		if (isset($arrayMonthly[$key][$toPull[$i]])) {
			$n = $arrayMonthly[$key][$toPull[$i]];
		} else {
			$n = 0;
		}
		$counted[$i] += $n;
		if ($n == "") $n = 0;
		if ($total[$toPull[$i]] == 0) {
			$pctxt = format_float(0.0,1);
			$pc=0.0;
		} else {
			$pc = 100*$n/$total[$toPull[$i]];
			$pctxt = format_float($pc,1);
		}
		$ntxt = format_float($n, 0);
		$string .= "<td>".bartext($pc, $max[$toPull[$i]], "$ntxt - $pctxt%")."</td>";
	}
	if (isset($arrayTotal[$key])) {
		$n = $arrayTotal[$key];
	} else {
		$n = 0;
	}
	$counted[3] += $n;
	$pc = 100*$n/$bigTotal;
	$pctxt = format_float($pc,1);
	$maxtot = 100*max($arrayTotal)/$bigTotal;
	$ntxt = format_float($n,0);
	$string .= "<td>".bartext($pc, $maxtot,"$ntxt - $pctxt%")."</td>";
}
if (!$full) {
	$string .= "\n<tr><td colspan=\"2\">".$strings['Other']."</td>";
	for ($i=0;$i<2;$i++) {
		$n = $total[$toPull[$i]]-$counted[$i];
		if ($total[$toPull[$i]] == 0.0) {$pc = 0.0;} else {$pc = 100*$n/$total[$toPull[$i]];}
		$pctxt = format_float($pc,1);
		$ntxt = format_float($n,0);
		$string .= "<td>".bartext($pc, $max[$toPull[$i]],"$ntxt - $pctxt%")."</td>";
	}
	$n = $bigTotal-$counted[3];
	if ($bigTotal == 0.0) {
		$pc = 0.0;
	} else {
		$pc = 100*$n/$bigTotal;
	}
	$pctxt = format_float($pc,1);
	$ntxt = format_float($n,0);
	$string .= "<td>".bartext($pc, $maxtot,"$ntxt - $pctxt%")."</td>\n";
	$string .= "<tr><td colspan=\"5\" align=\"center\"><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=$what&amp;toplot=fulllist&amp;lang=$lang\">".$strings['Fulllist']."</a></td></tr>\n";
}
$string .= "</table>\n";
return $string;
}

function tableProfileHistory($c,$table, $sid) {
global $strings;
global $lang;
global $sites;

$txtThisMonth = $strings['Thismonth'];
$txtLastMonth = $strings['Lastmonth'];
$txtTotal = $strings['Total'];

$string = "<table class=\"stat\">
<tr class=\"title\"><td colspan=\"4\">".$strings['Recentstatistics']."</td></tr>
<tr class=\"caption\"><td colspan=\"1\">&nbsp;</td><td>$txtThisMonth</td><td>$txtLastMonth</td><td>$txtTotal</td></tr>\n";

// Total number of visitors for each month
$timeserver = time() + 3600*$sites[$sid]['timediff'];
$thismonth = date("Y-m-01", $timeserver);
$lastmonth = date("Y-m-01",strtotime("-1 month", $timeserver));
if ($thismonth == $lastmonth) $lastmonth = date("Y-m-01",strtotime("-1 month -5 days", $timeserver));
$req = "SELECT date, SUM(count) as count FROM ${table}_os GROUP BY date;";
$total = array();
$bigTotal = 0;
$res = mysql_query($req,$c);
while($row = mysql_fetch_object($res)) {
	$total[$row->date] = $row->count;
	$bigTotal += $row->count;
}

// Extracting data from db, depending on sorting order
$sqlTestArr = array(
		"(LOWER(label) LIKE '%win%') AND (label NOT LIKE 'Win CE')",
		"(LOWER(label) LIKE '%mac%')",
		"((LOWER(label) LIKE '%linux%') OR (LOWER(label) LIKE '%sun%') OR (LOWER(label) LIKE '%unix%') OR (LOWER(label) LIKE '%osf%') OR (LOWER(label) LIKE '%irix%') or (LOWER(label) LIKE '%bsd%'))",
		"((LOWER(label) LIKE '%crawler%') OR (LOWER(label) LIKE '%google%'))",
		"(LOWER(label) LIKE '%???%')");
$labelTestArr = array(
		$strings['Windowscomputers'], $strings['Applecomputers'], $strings['Unixcomputers'], $strings['Crawlers'], '???',$strings['Mobiledevices']);
$nlines = count($labelTestArr);

// Number for thismonth, the month before and the two month ago...
$arrayMonthly = array();
foreach ($sqlTestArr as $i=>$sqlwhere) {
	$req = "SELECT sum(count) as count FROM ${table}_os WHERE date='$thismonth' AND $sqlwhere";
	$res = mysql_query($req,$c);
	$row = mysql_fetch_object($res);
	$arrayMonthly[$thismonth][$i]=$row->count;
	$req = "SELECT sum(count) as count FROM ${table}_os WHERE date='$lastmonth' AND $sqlwhere";
	$res = mysql_query($req,$c);
	$row = mysql_fetch_object($res);
	$arrayMonthly[$lastmonth][$i] = $row->count;
}
$arrayMonthly[$thismonth][$nlines-1] = $total[$thismonth]-array_sum($arrayMonthly[$thismonth]);
$arrayMonthly[$thismonth][$nlines-1] = $total[$lastmonth]-array_sum($arrayMonthly[$lastmonth]);

// Total number, grouped by label
$arrayTotal = array();
foreach ($sqlTestArr as $i=>$sqlwhere) {
	$req = "SELECT SUM(count) as bigcount FROM ${table}_os WHERE $sqlwhere";
	$res = mysql_query($req,$c);
	$row = mysql_fetch_object($res);
	$arrayTotal[$i] = $row->bigcount;
}
$arrayTotal[$nlines-1] = $bigTotal-array_sum($arrayTotal);
$max[$thismonth] = max($arrayMonthly[$thismonth]);
$max[$lastmonth] = max($arrayMonthly[$lastmonth]);
$max['total'] = max($arrayTotal);

// Preparing the table
if ($total[$thismonth] == 0) $total[$thismonth] = 1; // Avoid division by 0
if ($total[$lastmonth] == 0) $total[$lastmonth] = 1; // Avoid division by 0
if ($bigTotal == 0) $bigTotal = 1; // Avoid division by 0
for ($i = 0; $i<$nlines; $i++) {
	if (($i % 2) == 0) { $even = "even";} else {$even="odd";}
	if (isset($arrayMonthly[$thismonth][$i])) {
		$pcThisMonth = 100.*$arrayMonthly[$thismonth][$i]/$total[$thismonth];
	} else {
		$pcThisMonth = 0.0;
	}
	$pcThisMonthTxt = format_float($pcThisMonth,1);
	if (isset($arrayMonthly[$lastmonth][$i])) {
		$pcLastMonth = 100.*$arrayMonthly[$lastmonth][$i]/$total[$lastmonth];
	} else {
		$pcLastMonth = 0.0;
	}
	$pcLastMonthTxt = format_float($pcLastMonth,1);
	$pcTotal = 100.*$arrayTotal[$i]/$bigTotal;
	$pcTotalTxt = format_float($pcTotal,1);
	if (isset($arrayMonthly[$thismonth][$i])) {
		$nThisMonthTxt = format_float($arrayMonthly[$thismonth][$i],0);
	} else {
		$nThisMonthTxt = "0";
	}
	if (isset($arrayMonthly[$lastmonth][$i])) {
		$nLastMonthTxt = format_float($arrayMonthly[$lastmonth][$i],0);
	} else {
		$nLastMonthTxt = "0";
	}
	$nTotalTxt = format_float($arrayTotal[$i],0);
	$string .= "<tr class=\"$even\"><td>".$labelTestArr[$i]."</td>";
	$string .= "<td>".bartext($pcThisMonth, 100.*$max[$thismonth]/$total[$thismonth], "$nThisMonthTxt - $pcThisMonthTxt%")."</td>";
	$string .= "<td>".bartext($pcLastMonth, 100.*$max[$lastmonth]/$total[$lastmonth], "$nLastMonthTxt - $pcLastMonthTxt%")."</td>";
	$string .= "<td>".bartext($pcTotal, 100.*$max['total']/$bigTotal, "$nTotalTxt - $pcTotalTxt%")."</td></tr>\n";
}
$string .= "</table>\n";
return $string;
}


/*********************************************************************************/
/* Function: echoMonthly
/* Role: stats results for monthly quantity, such as OS, browser, or country
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $site: id of the website
/*   - $what: can be "os", "browser", "country"
/*   - $toplot: can be "history", "fulllist"
/*   - $sort: sort order for the table
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 06/2004
/* Split in several subroutines and fully rewritten, 09/2005
/* 11/2006: removed database connection function (moved earlier in the code)
/*********************************************************************************/
function echoMonthly($c,$table, $site, $what, $toplot, $sort) {
global $strings;
global $lang;

if ($toplot == 'history') {
	$title = $strings['History'];
	$plot = plotHistory($c,$table, $what);
	$link = "<a href=\"./index.php?mode=stats&amp;sid=$site&amp;show=$what&amp;toplot=thismonth&amp;lang=$lang\">".$strings['Plotforthismonth']."</a>";
} elseif ($toplot != "fulllist") {
	$title = $strings['Thismonth'];
	$plot = plotThisMonth($c, $site, $table, $what);
	$link = "<a href=\"./index.php?mode=stats&amp;sid=$site&amp;show=$what&amp;toplot=history&amp;lang=$lang\">".$strings['Plothistory']."</a>";
}
if ($what == "profile") {
	echo "<table class=\"stat\">
<tr class=\"title\"><td>$title</td></tr>
<tr><td align=\"center\">$plot</td></tr>
<tr><td align=\"center\">$link</td></tr>
</table>\n";
	echo tableProfileHistory($c, $table, $site);
} else {
	if ($toplot != "fulllist") {
		echo "<table class=\"stat\">
<tr class=\"title\"><td>$title</td></tr>
<tr><td align=\"center\">$plot</td></tr>
<tr><td align=\"center\">$link</td></tr>
</table>\n";
		$link = "./index.php?mode=stats&amp;sid=$site&amp;show=$what&amp;toplot=$toplot&amp;lang=$lang";
		echo tableHistory($c, $table, $site, $what, $link, $sort, false);
	} else {
		$link = "./index.php?mode=stats&amp;sid=$site&amp;show=$what&amp;toplot=$toplot&amp;lang=$lang";
		echo tableHistory($c, $table, $site, $what, $link, $sort, true);
	}
}
}

?>
