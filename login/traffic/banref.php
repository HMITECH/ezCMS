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

$DEMO = 0; // Set to 1 for demo mode
// Get language
$lang = 'en';
if (isset($_GET["lang"])) $lang = $_GET["lang"];
if (isset($_POST["lang"])) $lang = $_POST["lang"];
// Secure the lang parameter
$lang = substr(str_replace("/", "", $lang),0,2);
if (file_exists("./Lang/$lang.php")) {
	include ("./Lang/$lang.php");
} else if  (file_exists("./Lang/en.php")) {
	include ("./Lang/en.php");
} else {
	die("Problem with language files.");
}
// Include other files
include ("./Php/Functions/funct.inc.php");
include ("./Php/config_sql.php");
include ("./Php/config.php");
include ("./Php/Functions/login.inc.php");
$c = mysql_connect("$server","$user","$password") or die("<br>Can not connect to database in banref.php[49]: ".mysql_error());
$db = mysql_select_db("$base",$c) or die("<br>Can not select base in banref.php[50]: ".mysql_error());
$isLoggedIn = isloggedin ($c);
if (!$isLoggedIn) {
	@mysql_close ($c);
	header('Location: index.php');
}
// Stylesheet
$stylesheet = "red.css";
if (isset($HTTP_COOKIE_VARS["phpTrafficA_style"])) {
	$stylesheet = $HTTP_COOKIE_VARS["phpTrafficA_style"].".css";
}
if (!is_file($stylesheet)) {
	$stylesheet = "red.css";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<title>phpTrafficA</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<META NAME="AUTHOR" CONTENT="phpTrafficA">
<link rel="stylesheet" href="<?php echo $stylesheet;?>" type="text/css">
</head>
<div class="ban">
<div class="top"><div align="right"><a href="javascript:window.close();"><?php   echo $strings['Closewindow'];?></a></div>
</div>
<div id='text'>
<?php  
$sites = get_sites($c);
if (!isset($id) or $id=="") {
	$id = $_GET["id"];
	if ($id == "") $id = $_POST["id"];
}
if (!isset($sid) or $sid=="") {
	$sid = $_GET["sid"];
	if ($sid == "") $sid = $_POST["sid"];
}
if (!isset($mode) or $mode=="") {
	$mode = $_POST["mode"];
}
$table = $sites[$sid]['table'];
if ($mode=="delete") {
	if ($DEMO) {
		echo "<P>".$strings['notindemo']."
<div align=\"center\"><a href=\"javascript:window.close();\">".$strings['Closewindow']."</a></div>";
	} else {
		$sql = "SELECT * FROM $config_table";
		$result = mysql_query ($sql,$c);
		$config = array("x" =>"x");
		while($row = mysql_fetch_array($result)) {
			$config = $config + array($row["variable"] => $row["value"] );
		}
		mysql_free_result ($result);
		$bl = $config[blacklist];
		$req = "SELECT address FROM ${table}_referrer WHERE id=$id;";
		$res = mysql_query($req,$c);
		$row = mysql_fetch_object($res);
		$address = $row->address;
		$address = str_replace("http://", "", $address);
		$bl .= "\n$address";
		$bl = str_replace("\r\n", "\n", $bl);
		$bl = str_replace("\n\r", "\n", $bl);
		$bl = preg_replace('/\n\n+/', "\n", $bl);
		$bl = preg_replace('~^(\n*)(.*?)(\n*)$~m', "\\2", $bl);
		$sql = "UPDATE $config_table SET value='$bl' WHERE variable LIKE 'blacklist'";
		echo "<P>".$strings['Savingnewblacklist'].": ";
		$result = mysql_query ($sql,$c);
		// echo "req is $sql";
		echo $strings['done'].".";
		echo "<P>".$strings['DelEntRefTable'].": ";
		$req = "DELETE FROM ${table}_referrer WHERE address LIKE '%$address%';";
		$res = mysql_query($req,$c);
		// echo "req is $req";
		echo $strings['done'].".";
	}
} else {
	$req = "SELECT address FROM ${table}_referrer WHERE id=$id;";
	$res = mysql_query($req,$c);
	$row = mysql_fetch_object($res);
	$address = $row->address;
	if ($address == "") {
		echo "<P>".$strings['Notfound']."...";
	} else {
		echo "<form action=\"./banref.php?lang=$lang\" method=\"post\">
<P>".$strings['Youdecidedbanthisreferrer']."
<blockquote><code>$address</code></blockquote>
<P>".$strings['Seeninpages']."
<ul>\n";
		$address = str_replace("http://", "", $address);
		$req = "SELECT address,page,count FROM ${table}_referrer WHERE address LIKE '%$address%';";
		$res = mysql_query($req,$c);
		while ($row = mysql_fetch_object($res)) {
			$thispageid = $row->page;
			$count = $row->count;
			$address = $row->address;
			$sql2 = "SELECT name FROM ${table}_pages WHERE id=$thispageid";
			$res2 = mysql_query($sql2,$c);
			$row2 = mysql_fetch_object($res2);
			$page = $row2->name;
			if ($count>1) {$hits = $strings['hits'];} else {$hits = $strings['hit'];}
			echo "<li><code>$address</code> -> <code>$page</code>: $count $hits</li>\n";
		}
	echo "</ul>
<P>".$strings['Approvebanref']."
<div align=\"center\">
<input type=\"hidden\" name=\"sid\" value=\"$sid\">
<input type=\"hidden\" name=\"id\" value=\"$id\">
<input type=\"hidden\" name=\"mode\" value=\"delete\">
<input type=\"submit\" value=\" ".$strings['Ok']."  \">
<input type=\"button\" value=\"".$strings['Cancel']."\" onClick=\"window.close()\">
</div>
</form>\n";
	}
}
@mysql_close ($c);
?>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2008, ZoneO-soft</div>
</body>
</html>
