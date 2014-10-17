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

// everything moved into a function to avoid overwrite of local
// variable with those of phpTrafficA
if (!function_exists('log_phpTA')) {
	function log_phpTA($sid) {
		// Some agents are pure spammers, get rid of them now
		$banagents = array("Mozilla/5.0", "Mozilla/4.0");
		if (in_array(trim($_SERVER["HTTP_USER_AGENT"]),$banagents)) return;
		// Ok, moving on
		$phpTA_path =  __FILE__;
		$phpTA_path = preg_replace( "'\\\write_logs\.php'", "", $phpTA_path);
		$phpTA_path = preg_replace( "'/write_logs\.php'", "", $phpTA_path);
		include ("$phpTA_path/Php/config_sql.php");
		include ("$phpTA_path/Php/config.php");
		if (!function_exists('phpTrafficA_logit')) {
			include_once ("$phpTA_path/Php/Functions/funct.country.inc.php");
			include_once ("$phpTA_path/Php/Functions/log_function.php");
		}
		// Fixing the path to the ip2c database
		$ip2c = $phpTA_path."/".$ip2c;
		// path for temporary files
		$phpTA_tmpdir = "$phpTA_path/$tmpdirectory/";
		// Connect to db
		if (PHP_VERSION > 4.2) {
			$phpTA_c = mysql_connect("$server","$user","$password",true) or die("<br>Can not connect to database in count.php: ".mysql_error());
		} else {
			$phpTA_c = mysql_connect("$server","$user","$password") or die("<br>Can not connect to database in count.php: ".mysql_error());
		}
		$phpTA_db = mysql_select_db("$base",$phpTA_c) or die("<br>Can not select base in count.php[47] ".mysql_error());
		// Getting the sites array
		$phpTA_sites = phpTrafficA_get_sites($phpTA_c, $config_table);
		// Getting the cookie
		$phpTrafficA = "";
		if (isset($_COOKIE['phpTrafficA'])) $phpTrafficA = $_COOKIE['phpTrafficA'];
		// Making sure that the $sid matches something in the sites list
		if (!array_key_exists ($sid, $phpTA_sites)) die("Wrong sid");
		// Setting some variables that should have been set in config.php, but if
		// the file is old, they might be missing
		if (!isset($cookieTxt)) $cookieTxt = "Admin";
		// Making sure that this IP is not banned
		$phpTA_ip = $_SERVER["REMOTE_ADDR"];
		// If banned, do not record
		if (phpTrafficA_bannedIP($phpTA_c, $config_table, $phpTA_ip)) {
			mysql_close ($phpTA_c);
			return;
		}
		// If admin, do not record and echo admin text
		if ($phpTrafficA == "Admin") {
			echo $cookieTxt;
			mysql_close ($phpTA_c);
			return;
		}
		// Record entry
		if ($phpTA_sites[$sid]['trim']) {
			$phpTA_To = $_SERVER["PHP_SELF"];
		} else {
			$phpTA_To = $_SERVER["REQUEST_URI"];
		}
		$phpTA_servertime = time()+$phpTA_sites[$sid]['timediff']*3600;
		$phpTA_table = $phpTA_sites[$sid]['table'];
		$phpTA_domain = $phpTA_sites[$sid]['site'];
		$phpTA_resolution = '';
		if (isset($_COOKIE['phpTA_resolution'])) {
			$phpTA_resolution = $_COOKIE['phpTA_resolution'];
		}
		$phpTA_count = phpTrafficA_logit($phpTA_c,$config_table,$phpTA_table, $phpTA_domain, $phpTA_To, $phpTA_ip, $_SERVER["HTTP_USER_AGENT"], $phpTA_servertime, $_SERVER["HTTP_REFERER"], $sites[$sid]['crawler'], $phpTA_tmpdir, $ip2c,$phpTA_resolution);
		if ($phpTA_sites[$sid]['counter']) echo $phpTA_count;
		// Close database
		mysql_close ($phpTA_c);
	}
}

log_phpTA($sid);
?>
