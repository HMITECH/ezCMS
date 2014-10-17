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

function echoStatsMain($c) {
global $table, $domain, $sid, $year, $month, $day, $interval, $pageid, $sort, $show, $toplot,$start,$nhits,$order;
global $strings;
global $lang;
global $display;
global $server, $user, $password, $base;
global $isLoggedIn;

// Getting supplementary variables...
if (isset($_GET["sort"])) $sort = $_GET["sort"];
if (isset($_GET["order"])) $order = $_GET["order"];
if (isset($_GET["start"])) $start = $_GET["start"];
if (isset($_GET["toplot"])) $toplot = $_GET["toplot"];
$varlist = array("pageid", "interval", "day", "month", "year", "show", "nhits");
foreach ($varlist as $var) {
	if (isset($_GET[$var])) {
		$$var = $_GET[$var];
	} else if (isset($_POST[$var])) {
		$$var = $_POST[$var];
	}
}
// Make sure that $pageid is an integer
$pageid = valid_pageid($c, $table,$pageid);

echo "<ul id=\"dropdownmenu\">
<li><a href=\"#\">".$strings['Main']."</a>
	<ul>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;lang=$lang\">".$strings['Summary']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=top&amp;lang=$lang\">".$strings['Top']." ".$display['ntop']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=new&amp;lang=$lang\">".$strings['Newtrends']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=period&amp;interval=day&amp;lang=$lang\">".$strings['Daily']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=period&amp;interval=week&amp;lang=$lang\">".$strings['Weekly']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=period&amp;interval=month&amp;lang=$lang\">".$strings['Monthly']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=page&amp;lang=$lang\">".$strings['Individualpagestatistics']."</a></li>
	</ul></li>
<li><a href=\"#\">".$strings['Who']."</a>
	<ul>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=os&amp;lang=$lang\">".$strings['Operatingsystem']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=browser&amp;lang=$lang\">".$strings['Browser']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=resolution&amp;lang=$lang\">".$strings['Screenresolution']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=country&amp;lang=$lang\">".$strings['Country']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=profile&amp;lang=$lang\">".$strings['Visitorsprofile']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=windows&amp;lang=$lang\">".$strings['Windowsversions']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=labelip&amp;lang=$lang\">".$strings['VisitorsWithLabel']."</a></li>
	</ul></li>
<li><a href=\"#\">".$strings['Sources']."</a>
	<ul>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=entryexit&amp;lang=$lang\">".$strings['Entryexitpages']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=ref&amp;sort=hits&amp;start=1&amp;pageid=all&amp;lang=$lang\">".$strings['Referrers']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=key&amp;lang=$lang\">".$strings['Keywords']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=seplot&amp;lang=$lang\">".$strings['Searchengines']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=kwdplot&amp;lang=$lang\">".$strings['Newkeywordsperday']."</a></li>
	</ul></li>
<li><a href=\"#\">".$strings['Navigation']."</a>
	<ul>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=pathfull&amp;lang=$lang\">".$strings['Pathanalysis']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=duration&amp;lang=$lang\">".$strings['Visitduration']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=retention&amp;lang=$lang\">".$strings['Visitorretention']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=visitstimeday&amp;lang=$lang\">".$strings['VisitsPerHour']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=visitsday&amp;lang=$lang\">".$strings['VisitsPerDay']."</a></li>
	</ul></li>
<li class=\"latest\"><a href=\"#\">".$strings['Latestvisitors']."</a>
	<ul>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=latest&amp;lang=$lang\">".$strings['Details']."</a></li>
		<li><a href=\"./index.php?mode=stats&amp;sid=$sid&amp;show=path&amp;sort=2&amp;lang=$lang\">".$strings['Path']."</a></li>
	</ul></li>
</ul>
<div class=\"clearer\">&nbsp;</div>
";

echo "<div id=\"stats\">\n";
if ($domain == "") {
	echo "<br>".$strings['SiteIDNotKnow'];
} else { 
	switch ($show) {
	case "page":
		include ("./Php/Stats/statsPage.inc.php"); 
		if ((!isset($pageid))||($pageid=="")) {
			// Getting list of page names
			echoPageList($c, $table,$domain,$sid,$sort,$order);
		} else {
			echoPage($c, $table,$domain,$pageid,$sid);
		}
		break;
	case "key":
		include ("./Php/Stats/statsSE.inc.php");  
		if (!isset($start)) $start = 1;
		if (isset($pageid)) {
			echoEngine($c, $table,$domain,$sid,$pageid,$sort,$order,$start);
		} else {
			echoEngine($c, $table,$domain,$sid,"all",$sort,$order,$start);
		}
		break;
	case "seplot":
		include ("./Php/Stats/statsSE.inc.php");
		if (isset($pageid)) {
			echoEngineSEPlot($c, $table, $domain, $sid, $pageid);
		} else {
			echoEngineSEPlot($c, $table, $domain, $sid, "all");
		}
		break;
	case "kwdplot":
		include ("./Php/Stats/statsSE.inc.php");
		if (isset($pageid)) {
			echoKwdPlot($c, $table, $domain, $sid, $pageid);
		} else {
			echoKwdPlot($c, $table, $domain, $sid, "all");
		}
		break;
	case "ref":
		include ("./Php/Stats/statsRef.inc.php");  
		echoReferer($c, $table,$domain,$sid,$sort,$order,$start,$pageid);
		break;
	case "new":
		include ("./Php/Stats/statsNewTrend.inc.php");
		if ($interval == "") $interval = 30;
		echoNewTrend($c, $table,$domain,$sid,$interval);
		break;
	case "os":
		include ("./Php/Stats/statsMonthly.inc.php");  
		echoOS($c, $table,$sid,$toplot,$sort);
		break;
	case "browser":
		include ("./Php/Stats/statsMonthly.inc.php");  
		echoBrowser($c, $table,$sid,$toplot,$sort);
		break;
	case "resolution":
		include ("./Php/Stats/statsMonthly.inc.php");
		echoResolution($c, $table,$sid,$toplot,$sort);
		break;
	case "country":
		include ("./Php/Stats/statsMonthly.inc.php");  
		echoCountry($c, $table,$sid,$toplot,$sort);
		break;
	case "profile":
		include ("./Php/Stats/statsMonthly.inc.php");  
		echoProfile($c, $table,$sid,$toplot,$sort);
		break;
	case "windows":
		include ("./Php/Stats/statsMonthly.inc.php");  
		echoWindows($c, $table,$sid,$toplot,$sort);
		break;
	case "duration";
		include ("./Php/Stats/statsRetention.inc.php");
		echoRetention($c, $table,0);
		break;
	case "retention":
		include ("./Php/Stats/statsRetention.inc.php");
		echoRetention($c, $table,1);
		break;
	case "latest":
		include ("./Php/Stats/statsRecent.inc.php");
		echoRecent($c, $table,$domain,$sid,$sort,$order);
		break; 
	case "clickstream":
		include ("./Php/Stats/statsRecent.inc.php");
		$ip = $_GET['ip'];
		clickstream($c, $table,$domain,$sid, $ip);
		break; 
	case "path":
		include ("./Php/Stats/statsMonthly.inc.php");  
		include('Php/Stats/pathAnalysis.php');
		pathAnalysis($c, $table,$domain,$sid,$sort);
		break; 
	case "period":
		include ("./Php/Stats/statsTimeInterval.inc.php");
		if (isset($day)) {
			echoTimeInterval($c, $table, $domain, $interval, $sid, "$year-$month-$day", $sort, $order);
		} else if (isset($_GET['enddate'])) {
			$enddate = $_GET['enddate'];
			echoTimeInterval($c, $table, $domain, $interval, $sid, $enddate, $sort, $order);
		} else {
			echoTimeInterval($c, $table, $domain, $interval, $sid, "today", $sort, $order);
		}
		break;
	case "entryexit":
		include ("./Php/Stats/traffic.inc.php");
		entryExit($c, $table, $domain, $sid, $sort);
		break;
	case "pathdesign":
		include ("./Php/Stats/traffic.inc.php");
		if (isset($_GET['pathid'])) {
			$pathid = $_GET['pathid'];
			$pathid = valid_pathid($c, $table,$pathid);
		} else $pathid = $pageid;
		pathDesigner($c, $table, $domain, $sid, $pathid);
		break;
	case "studypath":
		include ("./Php/Stats/traffic.inc.php");
		if (isset($_GET['pathid'])) {
			$pathid = $_GET['pathid'];
			$pathid = valid_pathid($c, $table,$pathid);
		} else $pathid = $pageid;
		studyPath($c, $table, $domain, $sid, $pathid);
		break;
	case "pathfull":
		include ("./Php/Stats/traffic.inc.php");
		mainPath($c, $table, $domain, $sid);
		break;
	case "commonpath":
		include ("./Php/Stats/traffic.inc.php");
		commonPath($c, $table, $domain, $sid,$nhits);
		break;
	case "top":
		include ("./Php/Stats/topXX.inc.php");
		echoTopXX($c, $table, $domain, $sid);
		break;
	case "visitstimeday":
		include ("./Php/Stats/statsDayHour.inc.php");
		echoVisitHour($c, $table);
		break;
	case "visitsday":
		include ("./Php/Stats/statsDayHour.inc.php");
		echoVisitDay($c, $table);
		break;
	case "labelip":
		include ("./Php/Stats/labelIP.inc.php");
		$todo = "";
		if (isset($_GET["todo"])) $todo = $_GET["todo"];
		if (isset($_POST["todo"])) $todo = $_POST["todo"];
		if (($todo == "dosetlabel") && ($isLoggedIn))  {
			dosetLabel($c, $table, $domain, $sid, $_POST["ip"], $_POST["label"]);
		}
		if ($todo == "setlabel") {
			setLabel($c, $table, $domain, $sid, $_GET["ip"]);
		} else {
			$start = 1;
			if (isset($_GET["start"])) $start = $_GET["start"];
			listLabels($c, $table, $domain, $sid, $start);
		}
		break;
	default:
		include ("./Php/Stats/stats.inc.php");
		echoMainStats($c, $table,$domain,$sid,$toplot);
		break;
	}
}
echo "</div>\n";
}
?>