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



// We need those functions as well
include('Php/Functions/log_function.php');
include ("Php/config.php");
include ("Php/Functions/funct.country.inc.php");
include ("Php/Stats/statsMonthly.inc.php");

function formatRefString($ref,$engine_id,$engine_url,$engine_kwd,$engine_charset,$class="class=\"basic\"") {
global $strings;
global $domain;

if (strlen(trim($ref)) == 0) return $strings['Directaccess'];
$parse = parse_url($ref);
$refhost = "" . $parse["scheme"] . "://" . $parse["host"] . $parse["path"];
$refquery = $parse["query"];
$pos_domain = strpos(strtolower($refhost), strtolower($domain));
if ($pos_domain === false) {
	$keywordsArray = phpTrafficA_ExtractKeywords($ref,$engine_id,$engine_url,$engine_kwd,$engine_charset,0);
	if ($keywordsArray != "") {
		// search engine 
		$how = 2;
		$engine = $keywordsArray[1];
		$keywords = phpTrafficA_cleanText($keywordsArray[0]);
		$keywords2 = shortenPage($keywords, 2);
		if($keywords2 != $keywords) $keywords2 .= "...";
		return "$engine: <a href=\"$ref\" $class target=\"_new\" rel=\"nofollow\">$keywords2</a>";
	} else {
		return urlLink($ref,2,$class);
	}
} else {
// $refString = "";
$pos_domain = strpos($ref, $domain."/");
return "$domain: <a href=\"$ref\" title=\"$ref\" class=\"basic\" target=\"_new\" rel=\"nofollow\">".substr($ref, $pos_domain+strlen($domain), 30)."...</a>";
}
}

/*********************************************************************************/
/* Function: echoRecent
/* Role: latest access to the website
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $site: root of the website,
/*   - $sort: parameter to use for sorting 
/*   - $order: in what order (ascending of descending)
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 06/2004
/* 11/2006: removed database connection function (moved earlier in the code)
/* 09/2007: shorten the output (group by IP)
/*          new parameters: $order and $sort
/* 09/2008: Change ordering and SQL calls to separate multiple visits from same IP
/* 09/2008: IP addresses stored as long in the db
/*********************************************************************************/

function echoRecent($c,$table,$site,$sid, $sort, $order) {
global $DEBUG;
global $strings;
global $config_table;
global $lang;
global $ip2c;
global $botsagents;

// We read the config, we will need some of it
$conf = phpTrafficA_read_config($config_table, $c);
$os_id =  $conf['os_id'];
$os_label = $conf['os_label'];
$browser_id = $conf['browser_id'];
$browser_label = $conf['browser_label'];
$engine_id = $conf['engine_id'];
$engine_url = $conf['engine_url'] ;
$engine_kwd = $conf['engine_kwd'];
$engine_kwd = $conf['engine_kwd'];
$save_host = $conf['save_host'];
$engine_charset = $conf['engine_charset'];
$visitcutoff = $conf['visitcutoff'];

$arrowsDate = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=latest&amp;lang=$lang&amp;sort=date", $strings['Sortby']." ".$strings['Dateandtime'], '');
$arrowsDuration = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=latest&amp;lang=$lang&amp;sort=dur", $strings['Sortby']." ".$strings['Duration'], '');
$arrowsIP = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=latest&amp;lang=$lang&amp;sort=IP", $strings['Sortby']." IP", '');
$arrowsHits = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=latest&amp;lang=$lang&amp;sort=hits", $strings['Sortby']." ".$strings['Hits'], '');
$arrowsPage = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=latest&amp;lang=$lang&amp;sort=page", $strings['Sortby']." ".$strings['Page'], '');
$arrowsReferrers = linksUpDown("./index.php?mode=stats&amp;sid=$sid&amp;show=latest&amp;lang=$lang&amp;sort=ref", $strings['Sortby']." ".$strings['Referrer'], '');

?>
<script language="JavaScript" type="text/javascript">
// Hide or show lines of the 'tablerecent' table according to style
// Send an array of styles that should be hidden
function hideShow(hideWhat) {
var listLines = document.getElementById("tablerecent").getElementsByTagName("tr");
var thisclass;
var show;
for ( var i = 0; i<listLines.length; i++ ) {
	if (listLines[i].className) {
		thisclass = listLines[i].className.split(" ");
		show = true;
		for (var j=0;j<thisclass.length;j++) {
			if (hideWhat.toString().indexOf(thisclass[j])!==-1) show = false;
		}
		if (!show) {
			listLines[i].style.display = 'none';
		} else {
			listLines[i].style.display = '';
		}
	}
}
}

// Hide or show elements
function hideShowRecent(what){
hideWhat = new Array();
if (what=="humans") {
	hideWhat.push("robots");
	if (document.getElementById("returninglink").className == "buttonselected") {
		hideWhat.push("new");
	} else if (document.getElementById("newlink").className == "buttonselected") {
		hideWhat.push("returning");
	}
	document.getElementById("botslink").className = "buttontoselect";
	document.getElementById("humanslink").className = "buttonselected";
	document.getElementById("bothlink").className = "buttontoselect";
} else if (what=="bots") {
	hideWhat.push("humans");
	if (document.getElementById("returninglink").className == "buttonselected") {
		hideWhat.push("new");
	} else if (document.getElementById("newlink").className == "buttonselected") {
		hideWhat.push("returning");
	}
	document.getElementById("botslink").className = "buttonselected";
	document.getElementById("humanslink").className = "buttontoselect";
	document.getElementById("bothlink").className = "buttontoselect";
} else if (what=="bothHumBots") {
	if (document.getElementById("returninglink").className == "buttonselected") {
		hideWhat.push("new");
	} else if (document.getElementById("newlink").className == "buttonselected") {
		hideWhat.push("returning");
	}
	document.getElementById("botslink").className = "buttontoselect";
	document.getElementById("humanslink").className = "buttontoselect";
	document.getElementById("bothlink").className = "buttonselected";
} else if (what=="new") {
	hideWhat.push("returning");
	if (document.getElementById("botslink").className == "buttonselected") {
		hideWhat.push("humans");
	} else if (document.getElementById("humanslink").className == "buttonselected") {
		hideWhat.push("robots");
	}
	document.getElementById("returninglink").className = "buttontoselect";
	document.getElementById("newlink").className = "buttonselected";
	document.getElementById("bothnewlink").className = "buttontoselect";
}  else if (what=="returning") {
	hideWhat.push("new");
	if (document.getElementById("botslink").className == "buttonselected") {
		hideWhat.push("humans");
	} else if (document.getElementById("humanslink").className == "buttonselected") {
		hideWhat.push("robots");
	}
	document.getElementById("returninglink").className = "buttonselected";
	document.getElementById("newlink").className = "buttontoselect";
	document.getElementById("bothnewlink").className = "buttontoselect";
} else if (what=="bothnew") {
	if (document.getElementById("botslink").className == "buttonselected") {
		hideWhat.push("humans");
	} else if (document.getElementById("humanslink").className == "buttonselected") {
		hideWhat.push("robots");
	}
	document.getElementById("returninglink").className = "buttontoselect";
	document.getElementById("newlink").className = "buttontoselect";
	document.getElementById("bothnewlink").className = "buttonselected";
}

// Do the hiding
hideShow(hideWhat);

// Update background colors
var listLines = document.getElementById("tablerecent").getElementsByTagName("tr");
var j = 0;
var titleRegExp = new RegExp("(^|\\s)" + "title" + "(\\s|$)");
var captionRegExp = new RegExp("(^|\\s)" + "caption" + "(\\s|$)");
var color;
var listCells;
for ( var i = 0; i<listLines.length; i++ ) {
	if ((! titleRegExp.test(listLines[i].className)) &&  (! captionRegExp.test(listLines[i].className)) && (listLines[i].style.display != 'none')) {
		j = j+1;
		if ((j % 2) == 0) {
			color = "#eaeaea";
		} else {
			color ="#FFFFFF";
		}
		listLines[i].style.backgroundColor = color;
	}
}
}
</script>
<table class="form" align="center"><TR>
<td rowspan="2"><b><?php echo $strings['Show'];?>:</b></td>
<td><a href="#" onclick="hideShowRecent('humans'); return false;" id="humanslink" class="buttontoselect"><?php echo $strings['Humans'];?></a></td><td><a href="#" onclick="hideShowRecent('bots'); return false;" id="botslink" class="buttontoselect"><?php echo $strings['Bots'];?></a></td><td><a href="#" onclick="hideShowRecent('bothHumBots'); return false;" id="bothlink" class="buttonselected"><?php echo $strings['Both'];?></a></TD>
</TR>
<TR>
<td><a href="#" onclick="hideShowRecent('returning'); return false;" id="returninglink" class="buttontoselect"><?php echo $strings['Returning'];?></a></td><td><a href="#" onclick="hideShowRecent('new'); return false;" id="newlink" class="buttontoselect"><?php echo $strings['New'];?></a></td><td><a href="#" onclick="hideShowRecent('bothnew'); return false;" id="bothnewlink" class="buttonselected"><?php echo $strings['Both'];?></a></TD>
</TR>

</table>
<?php
echo "<table id='tablerecent' class='stat'>
<tr class='title'><td colspan='10'>".$strings['Latestvisits']."</td></tr>
<tr class='caption'><td>&nbsp;</td><td>".$strings['Duration']."</td><td>".$strings['Pageviews']."</td><td>IP</td><td>".$strings['Visits']."</td><td colspan=\"3\">&nbsp;</td><td>".$strings['Entrypages']."</td><td>".$strings['Referrer']."</td></tr>
<tr class='caption'><td>$arrowsDate</td><td>$arrowsDuration</td><td>$arrowsHits</td><td>$arrowsIP</td><td>&nbsp;</td><td colspan=\"3\">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";

$sql = "SELECT longIP,date FROM ${table}_host ORDER BY date DESC";
$res = mysql_query($sql,$c);
$n=0;
$iplist = array();
$startdate = array();
$starttime = array();
$enddate = array();
$endtime = array();
$duration = array();
$nhits = array();
while ($row = mysql_fetch_object($res)) {
	$ip = $row->longIP;
	$date = $row->date;
	$time = strtotime($date);
	$found = 0;
	$keys = array_keys($iplist, $ip);
	foreach ($keys as $i) {
		if (($starttime[$i]-$time) <= ($visitcutoff*60.)) {
			$found = 1;
			$startdate[$i] = $date;
			$nhits[$i] += 1;
			$starttime[$i] = $time;
			$duration[$i] = $endtime[$i]-$time;
			break;
		}
	}
	if (!$found) {
		$iplist[] = $ip;
		$startdate[] = $date;
		$enddate[] = $date;
		$endtime[] = $time;
		$starttime[] = $time;
		$nhits[] = 1;
		$duration[] = 0;
	}
}

if ($sort == "dur") {
	$arrSort = $duration;
} else if ($sort == "IP") {
	$arrSort = $iplist;
} else if ($sort == "hits") {
	$arrSort = $nhits;
} else {
	$arrSort = $endtime;
}
if ($order == "desc") {
	$res = asort($arrSort);
} else {
	$res = arsort($arrSort);
}

$n=0;
foreach ($arrSort as $i=>$stuff) {
	$n+=1;
	$long = $iplist[$i];
	$ip=long2ip($long);
	$dur = getTimeEn($duration[$i]);
	$start = $startdate[$i];
	$end = date("M j G:i",$endtime[$i]);
	$hits = $nhits[$i];
	$sql = "SELECT page,ref,agent FROM ${table}_host  WHERE longIP=$long AND date='$start'";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$page = shortenPage($row->page);
	$ref = $row->ref;
	$agent = $row->agent;
	$country = ip2Country($ip,$ip2c);
	$sql = "SELECT count,label FROM ${table}_iplist WHERE ip=$long";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	$visits = $row->count;
	$label = $row->label;
	if ($visits > 1) { $clreturn = "returning"; } else { $visits = 1;  $clreturn = "new";}
	$ipText = $ip;
	if ($label != "") $ipText = $label;
	list($wb,$os)=explode(";",phpTrafficA_ExtractAgent($agent,$browser_id,$browser_label,$os_id,$os_label));
	$refString = formatRefString($ref,$engine_id,$engine_url,$engine_kwd,$engine_charset);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	if (in_array($os, $botsagents)) { $clrobots = "robots"; } else { $clrobots = "humans"; }
	echo "<tr class=\"data av $even $clrobots $clreturn\"><td nowrap>$end</td><td>&nbsp;$dur</td><td align=\"center\">&nbsp;".format_float($hits)."&nbsp;</td><td>&nbsp;<a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=clickstream&amp;lang=$lang&amp;ip=$ip\" title=\"".$strings['Moreinfovisitor']."\" class=\"basic\">$ipText</a>&nbsp;</td><td align=\"center\">&nbsp;".format_float($visits)."&nbsp;</td><td>".countryFlag($country)."</td><td>".osImg($os,'')."</td><td>".browserImg($wb,$agent)."</td><td>$page</td><td>$refString</td></tr>\n";
}

if ($n==0) echo "<tr><td colspan=\"9\" align=\"center\">".$strings['Nothingyet']."</td></tr>\n";
echo "</table>";
}

function clickstream($c, $table, $site, $sid, $ip) {
global $strings;
global $config_table;
global $ip2c;
global $isLoggedIn;
global $lang;

// We read the config, we will need some of it
$conf = phpTrafficA_read_config($config_table, $c);
$os_id =  $conf['os_id'];
$os_label = $conf['os_label'];
$browser_id = $conf['browser_id'];
$browser_label = $conf['browser_label'];
$engine_id = $conf['engine_id'];
$engine_url = $conf['engine_url'] ;
$engine_kwd = $conf['engine_kwd'];
$engine_charset = $conf['engine_charset'];
$save_host = $conf['save_host'];

echo "<table class='stat'>
<tr class='title'><td colspan='5'>".$strings['Clickstreamfor']."$ip</td></tr>";

$long=ip2long($ip);
$sql = "SELECT count,label,first FROM ${table}_iplist WHERE ip=$long";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$visits = $row->count;
$label = $row->label;
$firstever = $row->first;
$sql = "SELECT date,page,ref,agent FROM ${table}_host WHERE longIP=$long ORDER BY date ASC";
$res = mysql_query($sql,$c);
$n = 0;
$host = gethostbyaddr($ip);
$country = ip2Country($ip,$ip2c);
if (whoislink($country) == FALSE) {
	$whoislink = "no whois link";
} else {
	$whoislink = "<a href=\"".whoislink($country)."$ip\"  target=\"blank\" class=\"basic\">whois</a>";
}
$baniplink = "";
if ($isLoggedIn) {
	$baniplink = ", <a href=\"index.php?mode=setup&amp;todo=banip&amp;ip=$ip&amp;lang=$lang\" class=\"basic\">".$strings['banIP']."</a>";
}
$setlabellink = "index.php?mode=stats&amp;show=labelip&amp;sid=$sid&amp;todo=setlabel&amp;ip=$ip&amp;lang=$lang";
while($row = mysql_fetch_object($res)) {
	$time = $row->date;
	$referer = $row->ref;
	$thisagent = $row->agent;
	$page = $row->page;
	if ($n==0) {
		$nhits = 0;
		$start = $row->date;
		list($wb,$os)=explode(";",phpTrafficA_ExtractAgent($thisagent,$browser_id,$browser_label,$os_id,$os_label));
		$oldagent = $thisagent;
		$labelTxt = "";
		if ($label != "") $labelTxt = $strings['Label'].": $label<br>";
		// Removed map link since it does not work anymore
		// <a href=\"http://soft.zoneo.net/phpTrafficA/mapIP.php?ip=$ip\" target=\"blank\" class=\"basic\">".$strings['map']."</a>
		$echo = "<tr><td valign=\"top\" colspan=\"3\">$ip ($whoislink$baniplink)<br>$host<br>$labelTxt<table class=\"basic\"><tr><td>".countryNameFlag($country)."</td></tr></table></td><td valign=\"top\" colspan=\"2\">".$strings['Agent'].": $thisagent<br><table class=\"basic\"><tr><td>".osImgName($os)."</td><td>".browserImgName($wb)."</td></tr></table>".$strings['Referrer'].": ";
		$echo .= formatRefString($referer,$engine_id,$engine_url,$engine_kwd,$engine_charset,"class=\"basic\"");
		$echo .= "</td></tr>\n";
		if ($visits > 1) {
			if ($label == "") {
				if ($isLoggedIn) {
					$set = " (<a href=\"$setlabellink \"class=\"basic\">".$strings['setlabel']."</a>)";
				} else {
					$set = "";
				}
				$labelstring = $strings['Labelnotset']."$set.";
			} else {
				if ($isLoggedIn) {
					$set = " (<a href=\"$setlabellink \"class=\"basic\">".$strings['change']."</a>)"; 
				} else {
					$set = "";
				}
				$labelstring = $strings['Label'].": $label$set.";
			}
			$echo .= "<tr><td colspan=\"5\">".sprintf($strings["Seenbefore"],$visits)." ".$strings['Firstvisit'].": ".dateandtime(strtotime($firstever)).". $labelstring</td></tr>\n";
		} else {
			if ($isLoggedIn) { $set = " (<a href=\"$setlabellink \"class=\"basic\">".$strings['setlabel']."</a>)"; } else {$set = ""; }
			$labelstring = $strings['Labelnotset']."$set.";
			$echo .= "<tr><td colspan=\"5\">".$strings['Never-seen']." $labelstring</td></tr>\n";
		}
		$echo .= "<tr class=\"caption\"><td colspan=\"5\" class=\"btop\">".$strings['Path']."</td></tr>\n";
		$echo .= "<tr class=\"caption\"><td>&nbsp;</td><td colspan=\"2\">".$strings['Dateandtime']."</td><td>".$strings['Page']."</td><td>".$strings['Referrer']."</td></tr>\n";
	}
	// simple path analysis
	$n += 1;
	$nhits += 1;
	$thisdate = $time;
	$when = getTimeEn(strtotime($time)-strtotime($start));
	$refstring = urlLink($referer,2);
	if (($n % 2) == 0) { $even = "even";} else {$even="odd";}
	$echo .= "<tr class=\"$even data\"><td align=\"center\">".format_float($n,0)."</td><td>$thisdate</td><td>$when</td><td>$page</td><td>$refstring</td></tr>";
}
echo "$echo\n";
echo "</table>\n";
}

?>
