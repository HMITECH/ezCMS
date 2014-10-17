<?php

function testadminpwd($c, $pwd) {
global $config_table;
$md5 = md5($pwd);
$doit = "SELECT value FROM $config_table WHERE variable='adminpassword'";
$res = mysql_query($doit,$c);
while ($row = mysql_fetch_object($res)) {
	if ($md5 == $row->value) return 1;
}
return 0;
}

function isloggedin ($c) {
	return 1;
/*
	global $config_table;
	global $HTTP_COOKIE_VARS, $_COOKIE;
	if(isset($HTTP_COOKIE_VARS["phpTrafficA_pwd"])) {
		$cmd5_password = $HTTP_COOKIE_VARS["phpTrafficA_pwd"]; 
	} elseif(isset($_COOKIE["phpTrafficA_pwd"])) {
		$cmd5_password = $_COOKIE["phpTrafficA_pwd"];
	}
	if (isset($cmd5_password)) {
		$doit = "SELECT value FROM $config_table WHERE variable='adminpassword'";
		$res = mysql_query($doit,$c);
		while ($row = mysql_fetch_object($res)) {
			if ($cmd5_password == $row->value) return 1;
		}
	}
	return 0;
	*/
}

function loginform($align="left", $DEMO=0) {
	global $adminpwd;
	global $strings;
	global $lang;
	$txt = "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
<table border=\"0\" align=\"$align\">
<tr><td valign=\"middle\"><input type=\"password\" name=\"passwd\" maxlength=\"50\">
<td valign=\"middle\"><input type=\"submit\" name=\"submit\" value=\"".$strings['Login']."\"><input type=\"hidden\" name=\"mode\" value=\"login\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"></td></tr>";
	if ($DEMO) {
		$txt .= "<tr><td colspan=2>".$strings['Password'].": $adminpwd</td></tr>";
	}
	$txt .= "</table>
</form>\n";
	return $txt;
}

function logIn($c) {
global $strings;
global $lang;
global $config_table;
if(!$_POST['passwd']) {
	die("<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"></head><body>".$strings['FieldMissing']."</body></html>");
}
// authenticate
$md5 = md5($_POST['passwd']);
$doit = "SELECT value FROM $config_table WHERE variable='adminpassword'";
$res = mysql_query($doit,$c);
while ($row = mysql_fetch_object($res)) {
	$value = $row->value;
	if ($value == $md5) {
		setcookie("phpTrafficA_pwd", $value);
		@mysql_close ($c);
		header("Location: index.php");
	}
}
@mysql_close ($c);
die("<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"></head><body>".$strings['IncorrectPassword']."</body></html>");
}

function logOut($c) {
global $lang;
@mysql_close ($c);
setcookie("phpTrafficA_pwd","");
header("Location: index.php?lang=$lang");
}

?>