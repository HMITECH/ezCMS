<?php
session_start();
if (!isset($_SESSION['SESSION' ])) 
	$_SESSION['SESSION' ] = true;
if (!isset($_SESSION['LOGGEDIN'])) 
	$_SESSION['LOGGEDIN'] = false;
if (!$_SESSION['LOGGEDIN']) { 
	echo '<div align="center"><h1 style="color: #3A87AD;font-family: Tahoma;text-align: center;text-shadow: 1px 1px #999999;">
    Permission denied.<br>You must be logged in to<br>ezCMS to access this page</h1>
	<p><img src="../img/noaccess.png" width="128" height="128"></p>
	<h3 style="font-family: Tahoma;"><a href="../">Login Now</a></h3>
	<h3 style="font-family: Tahoma;">Please emails <a href="mailto:support@hmi-tech.net">support@hmi-tech.net</a> for support.</h3></div>';
	exit; }
if (!$_SESSION['viewstats']) { 
	echo '<div align="center"><h1 style="color: #3A87AD;font-family: Tahoma;text-align: center;text-shadow: 1px 1px #999999;">
    Permission denied.<br>You do not have<br> permission to access this page</h1>
	<p><img src="../img/noaccess.png" width="128" height="128"></p>
	<h3 style="font-family: Tahoma;">Please emails <a href="mailto:support@hmi-tech.net">support@hmi-tech.net</a> for support.</h3></div>';
	exit; }	

/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2008 ZoneO-soft, Butchu (email: "butchu" with the domain "zoneo.net")

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 More Info About The Licence At http://www.gnu.org/copyleft/gpl.html
****************************************************************************/

if (function_exists('error_reporting')) {
	error_reporting(E_ALL ^ E_NOTICE);
	# error_reporting(E_ALL);
}

$path =  __FILE__;
$path = preg_replace( "'\\\index\.php'", "", $path);
$path = preg_replace( "'/index\.php'", "", $path);

include ("./Php/Functions/funct.inc.php");
include ("./Php/Functions/funct.lang.inc.php");
$timestart = getmicrotime();
include ("./Php/config_sql.php");
include ("./Php/config.php");
include ("./Php/Functions/login.inc.php");
// Dealing with language in the interface. If the user set the form to choose a language, we use this information to set a cookie for the next visit.
// Then guess from parameters
// - cookie
// - lang parameter in the URL
// - browser interface
// - default language
if (isset($_POST['mode'])) {
	if ($_POST['mode'] == "setlang") {
		$lang = $_POST['lang'];
		SetCookie("phpTrafficA_lang","$lang",time()+100000000,"/","",0);
	}
}
$extracopyright = setlanguage($defaultlang, $path);
// Connecting to the database...
$c = mysql_connect("$server","$user","$password");
if (!$c) {
	if (!$mysql_error_forward) {
		die("<br>Can not connect to database in index.php[".__LINE__."]: ".mysql_error());
	} else {
		header("Location: $mysql_error_location", true, 307);
	}
}
$db = mysql_select_db("$base",$c) or die("<br>Can not select base in index.php[".__LINE__."]: ".mysql_error());
// Setting display variables and make sure the upgrade was run
$version = read_config($c);
$versionArray = explode('b',$version);
$lengthVersion = count($versionArray);
if ($lengthVersion > 1) {
	$versionConfig = $versionArray[0] - 0.01 + 0.001*$versionArray[1];
} else {
	$versionConfig = 0.;
	$versionArray2 = explode('.',$versionArray[0]);
	foreach ($versionArray2 as $i => $n) { $versionConfig += 1.*$n*pow(10,-$i); }
}
if ($versionConfig < 2.3) die("<br>Welcome to phpTrafficA.<br>&nbsp;<br>Error with database.<br>You have to run the installation or upgrade script. You can find them here <ul><li>installation: <a href=\"Install/install.php\">Install/install.php</a></li><li>upgrade: <a href=\"Install/upgrade.php\">Install/upgrade.php</a></li></ul>");
// Getting the sites that are being followed
$sites = get_sites($c);
// DEMO Stuff
$DEBUG = 0;
$DEMO = 0; // Set to 1 for demo mode
if ($DEMO) {
        $demo = " ".$strings['demo'];
} else {
        $demo = "";
}
// If not in demo, check if auto-clean of tables is needed
if (!$DEMO) performAutoClean($c);
// Processing todo list
$mode = "";
if (isset($_GET["mode"])) $mode = $_GET["mode"];
if (isset($_POST["mode"])) $mode = $_POST["mode"];
$sid = "";
if (isset($_GET["sid"])) $sid = $_GET["sid"];
if (isset($_POST["sid"])) $sid = $_POST["sid"];
// Trying to login or logout and make sure we are
if ($mode == "login") {
	logIn($c);
} elseif ($mode == "logout") {
	logOut($c);
}
$isLoggedIn = isloggedin($c);
// Following links, if needed (has to be done fairly high because it should be in the header)
if ($mode == "followref") {
	$id = intval($_GET["id"]);
	$sid = intval($_GET["sid"]);
	$table = $sites[$sid]['table'];
	include ("./Php/Stats/statsRef.inc.php");
	followref($c, $table, $sid, $id);
}

// Dealing with cookies
if (($mode=="addcookie") && ($isLoggedIn) && (!$DEMO)) {
	SetCookie("phpTrafficA","Admin",time()+100000000,"/","",0);
	@mysql_close ($c);
	header("Location: index.php?mode=cookie&amp;lang=$lang");
}
if (($mode=="removecookie") && ($isLoggedIn) && (!$DEMO)) {
	SetCookie("phpTrafficA","",time()+100000000,"/","",0);
	@mysql_close ($c);
	header("Location: index.php?mode=cookie&amp;lang=$lang");
}
// Getting the stylesheet
if ($mode == "setstylesheet") {
	$style = $_POST['style'];
	SetCookie("phpTrafficA_style","$style",time()+100000000,"/","",0);
	$stylesheet = $style.".css";
} else {
	$stylesheet = "red.css";
	if (isset($HTTP_COOKIE_VARS["phpTrafficA_style"])) {
		$stylesheet = $HTTP_COOKIE_VARS["phpTrafficA_style"].".css";
	}
}
if (!is_file($stylesheet)) {
	$stylesheet = "red.css";
}
// Clean up temp files directory
tmpclean();
// Check that the request is allowed otherwise deny it
if (($mode=="setup") && (!$isLoggedIn)) $mode = "main";
if (($mode=="update") && (!$isLoggedIn)) $mode = "main";
if (($mode=="reminder") && (!$isLoggedIn)) $mode = "main";
if (($mode=="licence") && (!$isLoggedIn)) $mode = "main";
if (($mode=="about") && (!$isLoggedIn)) $mode = "main";
if (($mode=="cookie") && (!$isLoggedIn)) $mode = "main";
if (($mode=="stats") && (!$isLoggedIn) && (!$sites[$sid]['public'])) $mode = "main";
// Setting page titles
if ($mode=="setup") {
	$title = "phpTrafficA -- ".$strings['webstats'];
	$subtitle = "phpTrafficA$demo: ".$strings['setup'];
} else if ($mode=="update") {
	$title = "phpTrafficA -- ".$strings['webstats'];
	$subtitle = "phpTrafficA$demo: ".$strings['updateservice'];
}else if ($mode=="reminder") {
	$title = "phpTrafficA -- web statistics";
	$subtitle = "phpTrafficA$demo: ".$strings['reminder'];
} else if ($mode=="about") {
	$title = "phpTrafficA -- web statistics";
	$subtitle = $strings['About']." phpTrafficA";
} else if ($mode=="licence") {
	$title = "phpTrafficA -- web statistics";
	$subtitle = "phpTrafficA$demo: ".$strings['licence'];
} else if ($mode=="cookie") {
	$title = "phpTrafficA -- web statistics";
	$subtitle = "phpTrafficA$demo: ".$strings['admincookie'];
} else if ($mode=="stats") {
	$table = $sites[$sid]['table'];
	$domain = $sites[$sid]['site'];
	$timediff = $sites[$sid]['timediff'];
	$title = $strings['Statisticsfor']." " .$domain;
	$subtitle = "phpTrafficA$demo: ".$strings['statsfor']." $domain";
} else {
	$title = "phpTrafficA -- ".$strings['webstats'];
	$subtitle = "phpTrafficA$demo";
}
// Setting a few variables
$cachepagenameset = false; // We did not set up a cache with a list of page names yet
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php  echo $title;?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="author" content="phpTrafficA">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
<link rel="stylesheet" href="<?php echo $stylesheet;?>" type="text/css">
</head>
<body>
<script type="text/javascript" language="javascript">
function help(item) {
  var file = "./<?php  echo $helpFile?>?lang=<?php  echo $lang?>#"+item;
  popup=window.open(file, "HelpWindow", "width=350,height=250,directories=no,status=no,location=no,toolbar=no,scrollbars=yes,resizable=yes,menubar=no,copyhistory=no");
}
function banRef(sid,item) {
  var file = "./banref.php?lang=<?php  echo $lang?>&sid="+sid+"&id="+item;
  popup=window.open(file, "BanReferrerWindow", "width=500,height=300,directories=no,status=no,location=no,toolbar=no,scrollbars=yes,resizable=yes,menubar=no,copyhistory=no");
}
// Get elements by class name...
// http://www.robertnyman.com/2005/11/07/the-ultimate-getelementsbyclassname/
function getElementsByClassName(oElm, strTagName, strClassName){
var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
var arrReturnElements = new Array();
strClassName = strClassName.replace(/\-/g, "\\-");
var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
var oElement;
for(var i=0; i<arrElements.length; i++){
	oElement = arrElements[i];
	if(oRegExp.test(oElement.className)){
		arrReturnElements.push(oElement);
	}
}
return (arrReturnElements)
}
// Menu functio, to fix hover problem in IE. Comes from
// http://www.htmldog.com/articles/suckerfish/dropdowns/
sfHover = function() {
	if(document.getElementById("dropdownmenu")) {
		var sfEls = document.getElementById("dropdownmenu").getElementsByTagName("LI");
		for (var i=0; i<sfEls.length; i++) {
			sfEls[i].onmouseover=function() {
				this.className+=" sfhover";
			}
			sfEls[i].onmouseout=function() {
				this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
			}
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);
// Fixing hover in IE for menu in setup
setupmenuHover = function() {
  if(document.getElementById("setupmenu")) {
		var sfEls = document.getElementById("setupmenu").getElementsByTagName("LI");
		for (var i=0; i<sfEls.length; i++) {
			sfEls[i].onmouseover=function() {
				this.className+=" sfhover";
			}
			sfEls[i].onmouseout=function() {
				this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
			}
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", setupmenuHover);
</script>
<div id="main">
<?php
$lookforupdate = false;
if ($mode=="setup") {
	include ("./Php/UI/setup.inc.php");
	setup($c);
} else if ($mode=="update") {
	include ("./Php/UI/update.inc.php");
	update($c);
} else if ($mode=="reminder") {
	include ("./Php/UI/reminder.inc.php");
	reminder();
} else if ($mode=="cookie") {
  include ("./Php/UI/setcookie.inc.php");
  showSetCookie();
} else if ($mode=="about") {
  include ("./Lang/about_$lang.php");
} else if ($mode=="licence") {
  include ("./Php/UI/licence.inc.php");
} else if ($mode=="stats") {
  include ("./Php/UI/showStats.inc.php"); 
  echoStatsMain($c);
} else {
	include ("./Php/UI/main.inc.php");
	echomain($c);
	$lookforupdate = true;
}
// if ($isLoggedIn) { $registered = registrationCheck($c,$lookforupdate); }
@mysql_close ($c);
?>
</div>
</body>
</html>