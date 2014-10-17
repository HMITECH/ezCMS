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
/* Function: pathAnalysis
/* Role: performs path analysis on the latest visitors
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $site: root of the website
/*   - $sid: id of the website
/*   - $threshold: lower limit for hit numbers
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 06/2004
/* 11/2006: removed database connection function (moved earlier in the code)
/* 09/2008: Change ordering and SQL calls to separate multiple visits from same IP
/* 09/2008: IP addresses stored as long in the db
/*********************************************************************************/

// We need those functions as well
include('Php/Functions/log_function.php');
include ("Php/config.php");
include ("Php/Functions/funct.country.inc.php");

function pathAnalysis($c, $table, $site, $sid, $threshold=3) {
global $config_table;
global $strings;
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
$engine_charset = $conf['engine_charset'];
$save_host = $conf['save_host'];
$visitCutoff = $conf['visitcutoff'];

$req = "SELECT longIP, date, agent FROM ${table}_host ORDER BY date ASC";
$res = mysql_query($req,$c);
$iplist = array();
$detailsIP = array();
while($row = mysql_fetch_object($res)) {
	$ip = $row->longIP;
	$time = strtotime($row->date);
	$keys = array_keys($iplist,$ip);
	if (count($keys) == 0) {
		$iplist[] = $ip;
		$detailsIP[] = array('ip'=>$ip, 'start'=>$time, 'end'=>$time, 'agent'=>$row->agent);
	} else {
		$found = false;
		foreach ($keys as $key) {
			if (($detailsIP[$key]['end']+$visitCutoff*60) > $time) {
				$detailsIP[$key]['end']=$time;
				$found = true;
				break;
			}
		}
		if (!$found) {
			$iplist[] = $ip;
			$detailsIP[] = array('ip'=>$ip, 'start'=>$time, 'end'=>$time, 'agent'=>$row->agent);
		}
	}
}
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
// Show or hide humans or bots
function setHumansRobots(what){
	// What do we hide
	var hideWhat = new Array();
	if (what=="humans") {
		hideWhat.push("robots");
	} else if (what=="bots") {
		hideWhat.push("humans");
	}
	if (document.getElementById("threshold2").className == "buttonselected") {
		hideWhat.push("class1");
	} else if (document.getElementById("threshold3").className == "buttonselected") {
		hideWhat.push("class1","class2");
	} else if (document.getElementById("threshold5").className == "buttonselected") {
		hideWhat.push("class1","class2","class3");
	} else if (document.getElementById("threshold10").className == "buttonselected") {
		hideWhat.push("class1","class2","class3","class5");
	}
	// Do the hiding and showing
	hideShow(hideWhat);
// Update button text
	if (what=="humans") {
		document.getElementById("botslink").className = "buttontoselect";
		document.getElementById("humanslink").className = "buttonselected";
		document.getElementById("bothlink").className = "buttontoselect";
	} else if (what=="bots") {
		document.getElementById("botslink").className = "buttonselected";
		document.getElementById("humanslink").className = "buttontoselect";
		document.getElementById("bothlink").className = "buttontoselect";
	} else {
		document.getElementById("botslink").className = "buttontoselect";
		document.getElementById("humanslink").className = "buttontoselect";
		document.getElementById("bothlink").className = "buttonselected";
	}
}
// Hide according to threshold
function setThreshold(threshold){
	// What do we hide?
	var hideWhat = new Array();
	if (document.getElementById("botslink").className == "buttonselected") {
		hideWhat.push("humans");
	} else if (document.getElementById("humanslink").className == "buttonselected") {
		hideWhat.push("robots");
	}
	if (threshold == 2) {
		hideWhat.push("class1");
	} else if (threshold == 3) {
		hideWhat.push("class1", "class2");
	} else if (threshold == 5) {
		hideWhat.push("class1", "class2", "class3");
	} else if (threshold == 10) {
		hideWhat.push("class1", "class2", "class3", "class5");
	}
	// Do the hiding
	hideShow(hideWhat);
	// Update buttons
	document.getElementById("threshold1").className = "buttontoselect";
	document.getElementById("threshold2").className = "buttontoselect";
	document.getElementById("threshold3").className = "buttontoselect";
	document.getElementById("threshold5").className = "buttontoselect";
	document.getElementById("threshold10").className = "buttontoselect";
	if (threshold==1) {
		document.getElementById("threshold1").className = "buttonselected";
	} else if (threshold==2) {
		document.getElementById("threshold2").className = "buttonselected";
	}  else if (threshold==3) {
		document.getElementById("threshold3").className = "buttonselected";
	}  else if (threshold==5) {
		document.getElementById("threshold5").className = "buttonselected";
	}  else if (threshold==10) {
		document.getElementById("threshold10").className = "buttonselected";
	}
}
</script>
<?php
echo "<table class=\"form\">
<tr><td><table align=\"center\" class=\"basic\"><tr><td><b>".$strings['Threshold']."</b></td><td>";
echo "<td><a href=\"#\" onclick=\"setThreshold('1'); return false;\" id=\"threshold1\" class=\"buttontoselect\">".$strings['none']."</a></td>";
echo "<td><a href=\"#\" onclick=\"setThreshold('2'); return false;\" id=\"threshold2\" class=\"buttontoselect\">2&nbsp;".$strings['hits']."</a></td>";
echo "<td><a href=\"#\" onclick=\"setThreshold('3'); return false;\" id=\"threshold3\" class=\"buttonselected\">3&nbsp;".$strings['hits']."</a></td>";
echo "<td><a href=\"#\" onclick=\"setThreshold('5'); return false;\" id=\"threshold5\" class=\"buttontoselect\">5&nbsp;".$strings['hits']."</a></td>";
echo "<td><a href=\"#\" onclick=\"setThreshold('10'); return false;\" id=\"threshold10\" class=\"buttontoselect\">10&nbsp;".$strings['hits']."</a></td>";

echo "</tr></table></td></tr>
<TR><td><table align=\"center\" class=\"basic\"><tr></td>
<td><b>".$strings['Show']."</b></td>
<td><a href=\"#\" onclick=\"setHumansRobots('humans'); return false;\" id=\"humanslink\" class=\"buttontoselect\">".$strings['Humans']."</a></td><td><a href=\"#\" onclick=\"setHumansRobots('bots'); return false;\" id=\"botslink\" class=\"buttontoselect\">".$strings['Bots']."</a></td><td><a href=\"#\" onclick=\"setHumansRobots('both'); return false;\" id=\"bothlink\" class=\"buttonselected\">".$strings['Both']."</a></TD></tr></table></td>
</TR>
</table>";

echo "<table class=\"stat\" id=\"tablerecent\">\n<tr class=\"title\"><td>".$strings['Pathanalysisforthelatest']." $save_host ".$strings['hits']."</td></tr>\n";
$ntot = 0;   // Total number of path displayed
$nhits = 0;  // Number of hist, for one visitor
$detailsIP = array_reverse($detailsIP);
foreach ($detailsIP as $thisone) {
	$long = $thisone['ip'];
	$ip=long2ip($long);
	$agent = $thisone['agent'];
	$start = $thisone['start'];
	$end = $thisone['end'];
	$startSQL = date("Y-m-d H:i:s", $start);
	$endSQL = date("Y-m-d H:i:s", $end);
	$country = ip2Country($ip,$ip2c);
	if (whoislink($country) == FALSE) {
		$whoislink = "no whois link";
	} else {
		$whoislink = "<a href=\"".whoislink($country)."$ip\"  target=\"blank\" class=\"basic\">whois</a>";
	}
	$maplink = "<a href=\"http://soft.zoneo.net/phpTrafficA/mapIP.php?ip=$ip\" target=\"blank\" class=\"basic\">".$strings['map']."</a>";
	list($wb,$os)=explode(";",phpTrafficA_ExtractAgent($agent,$browser_id,$browser_label,$os_id,$os_label));
	$req = "SELECT date,page,ref,agent FROM ${table}_host WHERE (longIP=$long AND date BETWEEN '$startSQL' AND '$endSQL') ORDER BY date";
	$res = mysql_query($req,$c);
	$n = 0;
	while($row = mysql_fetch_object($res)) {
		$time = $row->date;
		$referer = $row->ref;
		$thisagent = $row->agent;
		$page = shortenPage($row->page,2);
		// If it is the first time we work with this IP, or if we have a change of
		// agent (probably another visitor, we lookup the main info (wb, os, origin)
		// Removed agent filtering (too heavy on server...)
		if ($n==0) {
			$nhits = 0;
			if (in_array($os, $botsagents)) { $clrobots = "robots"; } else { $clrobots = "humans"; }
			$echo = "<tr class=\"$clrobots classnhits\" style=\"display:thedisplay;\"><td><table class=\"postit\"><tr><td colspan=\"2\">$ip</td><td colspan=\"2\">$startSQL</td></tr><tr><td colspan=\"4\">$whoislink, $maplink</td></tr><tr><td>".countryNameFlag($country)."</td><td colspan=\"2\"></td></tr><tr><td>".osImgName($os)."</td><td>".browserImgName($wb)."</td></tr>";
			if ($referer == "") {
				$echo .= "<tr><td colspan=\"4\">".$strings['Directaccess']."</td></tr>";
			} else {
				if ((preg_match("/". preg_quote($site, '/') . "/i", "$referer")))  {
					$refString = urlLink($referer,1);
					$echo .= "<tr><td colspan=\"4\">$refString</td></tr>";
				} else {
					$keywords = "";
					$debugit = 0;
					$keywordsArray = phpTrafficA_ExtractKeywords($referer,$engine_id,$engine_url,$engine_kwd,$engine_charset,$debugit);
					if ($keywordsArray != "") {
						// search engine
						$how = 2;
						$engine = $keywordsArray[1];
						$keywords = phpTrafficA_cleanText($keywordsArray[0]);
						$echo .= "<tr><td colspan=\"4\">$engine: <a href=\"$referer\" class=\"basic\" target=\"_new\" rel=\"nofollow\">$keywords</a></td></tr>";
					} else {
						$refString = urlLink($referer,1);
						$echo .= "<tr><td colspan=\"4\">$refString</td></tr>";
					}
				}
			}
			$echo .= "</table>";
		}
		// simple path analysis
		$n += 1;
		$nhits += 1;
		$when = getTimeEn(strtotime($time)-$start);
		$echo .= "<div class=\"postit\">$when<br>$page</div>";
	}
	if ($nhits>0) {
		if ($nhits >= 10) {
			$class = "class10";
		} else if ($nhits >= 5) {
			$class = "class5";
		} else if ($nhits >= 3) {
			$class = "class3";
		} else if ($nhits == 2) {
			$class = "class2";
		} else {
			$class = "class1";
		}
		$echo = str_replace("classnhits", $class, $echo);
		if ( $nhits>= 3 ) {
			$echo = str_replace("style=\"display:thedisplay;\"", "", $echo);
		} else {
			$echo = str_replace("style=\"display:thedisplay;\"", "style=\"display: none;\"", $echo);
		}
		echo "$echo\n";
		$ntot += $nhits;
		echo "<div class=\"clearer\">&nbsp;</div></td></tr>\n";
	}
}
if ($ntot == 0) {
	echo "<tr><td><div align=\"center\">".$strings['Nothingyet']."</div></td></tr>\n";
}
echo "</table>\n";
}