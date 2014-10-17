<?php
function phpTASumm($sid) {
	global $path;
	// Getting the necessary variables
	$path =  __FILE__;
	$path = preg_replace( "'\\\summary\.php'", "", $path);
	$path = preg_replace( "'/summary\.php'", "", $path);
	include ("$path/Php/config_sql.php");
	include ("$path/Php/config.php");
	include ("$path/Php/Functions/funct.inc.php");
	// Connect to the db
	$c = mysql_connect("$server","$user","$password");
	$db = mysql_select_db("$base",$c);
	// Getting the info
	$sites = get_sites($c);
	$table = $sites[$sid]['table'];
	// Today count
	$nToday = nToday($c,$table, $sid, time());
	$online = vOnline($table);
	$vToday = vToday($c,$table, $sid, time()) + $online;
	// Month count
	$nThisMonth = nThisMonth($c,$table, $sid, time());
	$vThisMonth = vThisMonth($c,$table, $sid, time()) + $online;
	// Total count
	$nTotal = nTotal($c,$table, $sid, time());
	$vTotal = vTotal($c,$table, $sid, time()) + $online;
	// Closing connection to db
	@mysql_close ($c);
	// Send it back!
	$content = "<style type=\"text/css\">
div.phpTrafficA {border: #000 solid 1px; width: 150px; margin: 5px; padding: 5px; text-align: left;}
div.phpTrafficA div.title { font-weight: bold; }
div.phpTrafficA ul { margin: 0; padding: 0; }
div.phpTrafficA ul li { margin: 0 0 0 15px; padding: 0 0 0 0; }
</style>";
	$content .= "<div class='phpTrafficA'><div class='title'>Online:</div><ul><li>$online visitors</li></ul><div class='title'>Today:</div><ul><li>$vToday visitors</li><li>$nToday hits</li></ul><div class='title'>This month:</div><ul><li>$vThisMonth visitors</li><li>$nThisMonth hits</li></ul><div class='title'>Total:</div><ul><li>$vTotal visitors</li><li>$nTotal hits</li></ul></div>";
	echo $content;
}
if (isset($sid)) {
	phpTASumm($sid);
}
?>