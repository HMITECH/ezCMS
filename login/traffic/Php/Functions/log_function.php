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

/*********************************************************************************
/* Function: phpTrafficA_bannedIP
/* Role: make sure an IP address is not banned
/* Parameters:
/*   - $c: connection to the database
/*   - $config_table: root name for config table
/*   - $ip: IP address
/* Output:
/*   - true if banned, false otherwise
/* Created 09/2007 to replace the file 'sites.php'
**********************************************************************************/
function phpTrafficA_bannedIP($c, $config_table, $ip) {
	$long = ip2long($ip);
	if ($long == -1 || $long === FALSE) return true; // If fake invalid IP address: banned
	//if (($long >= 1094165760) && ($long<=1094166015)) return true; // Fake searches on live.com from within microsoft 65.55.165.*
	//if (($long >= 1094182912) && ($long<=1094183167)) return true; // Fake searches on live.com from within microsoft 65.55.232.*
	$datestring = date("Y-m-d");
	$sql = "UPDATE `${config_table}_ipban` SET count=count+1, last='$datestring' WHERE (($long>=ip) AND ($long<=(ip+`range`)))";
	$res = mysql_query($sql,$c);
	if (mysql_affected_rows() < 1) {
		// We failed incrementing the count, this IP is not banned
		return false;
	}
	return true;
}

/*********************************************************************************
/* Function: phpTrafficA_get_sites
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
function phpTrafficA_get_sites($c, $config_table) {
$sql = "SELECT * FROM ${config_table}_sites";
$res = mysql_query ($sql,$c);
$sites = array();
while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$sites = $sites + array($row["id"] => $row );
}
mysql_free_result($res);
return $sites;
}

/*********************************************************************************/
/* Function: phpTrafficA_getmicrotime
/* Role: time with milliseconds
/* Parameters:
/* Output:
/*   - returns the time
/* Source: php website
/*********************************************************************************/

function phpTrafficA_getmicrotime()  {
list($usec, $sec) = explode(" ", microtime());
return ((float)$usec + (float)$sec);
}

/*********************************************************************************/
/* Function: phpTrafficA_cleanTextBasic
/* Role: clean up text from ' and " to it does not fucks up php strings
/* Parameters:
/*   - $text: the text to be cleaned up
/* Output:
/*   - returns the clean text
/* Created 02/2008 from phpTrafficA_cleanText. Does not replace & with &amp; for backwards compatibility.
/*********************************************************************************/
function phpTrafficA_cleanTextBasic($text) {
$text = str_replace("\'", "&#39;", $text);
$text = str_replace("'", "&#39;", $text);
$text = str_replace("\"", "&#34;", $text);
$text = str_replace("\\\"", "&#34;", $text);
$text = str_replace("\\", "&#92;", $text);
$text = str_replace("\\\\", "&#92;", $text);
$text = str_replace("\n", "", $text);
$text = str_replace("\r", "", $text);
$text = str_replace("<", "&#060;", $text);
$text = str_replace(">", "&#062;", $text);
return $text;
}
/*********************************************************************************/
/* Function: phpTrafficA_cleanTextNoAmp
/* Role: clean up text from ' and " to it does not fucks up php strings
/* Parameters:
/*   - $text: the text to be cleaned up
/* Output:
/*   - returns the clean text
/* Created 05/2008 from phpTrafficA_cleanText
/* Same function but does not replace & with &amp; for backward compatibility in page names
/*********************************************************************************/
function phpTrafficA_cleanTextNoAmp($text) {
$text = str_replace("\'", "&#39;", $text);
$text = str_replace("'", "&#39;", $text);
$text = str_replace("\"", "&#34;", $text);
$text = str_replace("\\\"", "&#34;", $text);
$text = str_replace("\\", "&#92;", $text);
$text = str_replace("\\\\", "&#92;", $text);
$text = str_replace("\n", "", $text);
$text = str_replace("\r", "", $text);
$text = str_replace("<", "&#060;", $text);
$text = str_replace(">", "&#062;", $text);
return $text;
}


/*********************************************************************************/
/* Function: phpTrafficA_cleanText
/* Role: clean up text from ' and " to it does not fucks up php strings
/* Parameters:
/*   - $text: the text to be cleaned up
/* Output:
/*   - returns the clean text
/* Created 05/2004
/* Changed 01/2008 to forbid new lines and HTML codes in referrer to avoid XSS scripting
/*********************************************************************************/
function phpTrafficA_cleanText($text) {
$text = str_replace("&", "&amp;", $text);
$text = str_replace("\'", "&#39;", $text);
$text = str_replace("'", "&#39;", $text);
$text = str_replace("\"", "&#34;", $text);
$text = str_replace("\\\"", "&#34;", $text);
$text = str_replace("\\", "&#92;", $text);
$text = str_replace("\\\\", "&#92;", $text);
$text = str_replace("\n", "", $text);
$text = str_replace("\r", "", $text);
$text = str_replace("<", "&#060;", $text);
$text = str_replace(">", "&#062;", $text);
return $text;
}

/*********************************************************************************/
/* Function: phpTrafficA_addBP_LDC
/* Role: add entry in a label-month-count database (monthly count)
/*         if label-month exists: count=count+1
/*         if not, new line, count=1
/* Parameters:
/*   - $table: table with page id's
/*   - $connection: link with database
/*   - $monthstring: month of entry in yyyy-mm-01 format
/*   - $label: label to be looking for
/* Output:
/*   - return 1 if successful
/*   - return -1 if failed
/* Created 05/2004
/* Completely Changed 06/2005 to reduce number of sql queries
/* 11/2008: Changed insert syntax for mysql5
/*********************************************************************************/
function phpTrafficA_addDB_LDC($table,$connection,$monthstring,$label) {
// We try to add 1 to the count value. If it fails we have some more work to do.
$sql3 = "UPDATE `$table` SET count=count+1 WHERE STRCMP(label,'$label')=0 AND date='$monthstring'";
$res3 = mysql_query($sql3,$connection);
if (mysql_affected_rows() < 1) {
	// We failed in incrementing the counter: we need to add a new line
	$req4 ="INSERT INTO `$table` SET label='$label', date='$monthstring', count='1'";
	$res4 = mysql_query($req4,$connection);
}
return 1;
}

/*********************************************************************************/
/* Function: phpTrafficA_add_access
/* Role: add access entry in a the access database
/*         if label-date exists: count=count+1
/*         if not, new line, count=1
/*       Also adds how we got there
/* Parameters:
/*   - $table: table with page id's
/*   - $c: link with database
/*   - $datestring: month of entry in yyyy-mm-01 format
/*   - $pageid: label to be looking for
/*   - $how: how did we get to the page
/*          0: unknow, 1: internal link, 2: search engine, 3: referer
/* Output:
/*   - return total number of access to this page
/* Created 11/2005 from an older version
/* 11/2008: Changed insert syntax for mysql5
/*********************************************************************************/
function phpTrafficA_add_access($table, $c, $datestring, $pageid, $how=0) {
// Saving how we got to the page
switch($how) {
	case 2:
		$sql = "UPDATE `${table}_pages` SET se=se+1 WHERE id=$pageid;";
		break;
	case 3:
		$sql = "UPDATE `${table}_pages` SET ref=ref+1 WHERE id=$pageid;";
		break;
	case 1:
		$sql = "UPDATE `${table}_pages` SET internal=internal+1 WHERE id=$pageid;";
		break;
	default:
		$sql = "UPDATE `${table}_pages` SET other=other+1 WHERE id=$pageid;";
		break;
}
$res = mysql_query($sql,$c);
// Updating count
$sql = "UPDATE `${table}_acces` SET count=count+1 WHERE label=$pageid AND date='$datestring'";
$res = mysql_query($sql,$c);
if (mysql_affected_rows() < 1) {
// We failed in incrementing the counter: we need to add a new line
	$sql ="INSERT INTO `${table}_acces` SET label=$pageid, date='$datestring', count='1'";
	$res = mysql_query($sql,$c);
}
// Updating count for the whole site
$sql = "UPDATE `${table}_acces` SET count=count+1 WHERE label=0 AND date='$datestring'";
$res = mysql_query($sql,$c);
if (mysql_affected_rows() < 1) {
// We failed in incrementing the counter: we need to add a new line
	$sql ="INSERT INTO `${table}_acces` SET label=0, date='$datestring', count='1'";
	$res = mysql_query($sql,$c);
}
// Get the total number of access
$sql = "SELECT (se+ref+other+internal+old) as count FROM ${table}_pages WHERE id=$pageid";
$res = mysql_query($sql,$c);
$count=mysql_fetch_array($res);
return $count['count'];
}


/*********************************************************************************/
/* Function: phpTrafficA_pageid
/* Role: lookup page id in the database, and create one if does not exist
/* Parameters: 
/*   - $table: table with page id's 
/*   - $connection: link with database
/*   - $page
/*   - $date: access date (used only with creating new ID)
/* Output: 
/*   - return id if successful
/*   - return -1 if failed
/* Created 05/2004
/* 10/2008: Improved error detection in mysql insert
/* 11/2008: Changed insert syntax for mysql5
/*********************************************************************************/
function phpTrafficA_pageid($table, $connection, $page, $date) {
$sql3 = "SELECT id FROM `$table` WHERE STRCMP(name,'$page')=0";
$res3 = mysql_query($sql3,$connection);
if ( (mysql_num_rows($res3) == 0) or (mysql_num_rows($res3) == FALSE) ) {
	$req4 ="INSERT INTO `$table` SET name='$page', added='$date', ref='0', se='0', internal='0', other='0', old='0'";
	$res4 = mysql_query($req4,$connection);
	if ($res4) return mysql_insert_id();
	return -1;
} else {
	$row2 = mysql_fetch_object($res3);
	$pageid = $row2->id;
}
return $pageid;
}

/*********************************************************************************/
/* Function: phpTrafficA_updateCount
/* Role: update counter in a simple database (value, count) with no line instertion
/* Parameters: 
/*   - $table: table with logs
/*   - $connection: link with database
/*   - $value: the value to be updated (will do count=count+1 for value = $value)
/* Output: 
/*   - return 1 if successful
/*   - return -1 if failed
/* Created 05/2004
/*********************************************************************************/
function phpTrafficA_updateCount($table, $connection, $value) {
$sql3 = "UPDATE `$table` SET count=count+1 WHERE value=$value";
$res3 = mysql_query($sql3,$connection);
return 1;
}

/*********************************************************************************/
/* Function: phpTrafficA_addUniqueIP
/* Role: add a log of a unique IP into the database
/* Parameters: 
/*   - $table: root for table name
/*   - $connection: link with database
/*   - $ip: IP address
/*   - $datefirst: date for first click
/*   - $datalast: date for last click
/* Output: 
/*   - return 1 if successful
/*   - return -1 if failed
/* Created 07/2009, adapted from phpTrafficA_addReferrer
/*********************************************************************************/
function phpTrafficA_addUniqueIP($table, $connection, $ip, $datefirst, $datelast) {
$long = ip2long($ip);
$sql3 = "UPDATE ${table}_iplist SET count=count+1,last='$datelast' WHERE ip=$long";
$res3 = mysql_query($sql3,$connection);
if (mysql_affected_rows() < 1) {
	$req4 ="INSERT INTO ${table}_iplist SET ip=$long, first='$datefirst', last='$datelast', count='1'";
	$res4 = mysql_query($req4,$connection);
}
return 1;
}

/*********************************************************************************/
/* Function: phpTrafficA_addReferrer
/* Role: add a log of a referrer into the database
/* Parameters: 
/*   - $table: table with referrer logs
/*   - $connection: link with database
/*   - $referer: referrer
/*   - $date: date of the entry in yyyy-mm-dd format
/* Output: 
/*   - return 1 if successful
/*   - return -1 if failed
/* Created 05/2004
/* Completely changed 06/2005 to reduce number of SQL access
/* Changed 02/2008 to avoid XSS scripting with referrer string.
/* 11/2008: Changed insert syntax for mysql5
/*********************************************************************************/
function phpTrafficA_addReferrer($table, $connection, $referer, $date,$pageid) {
$cleanref = phpTrafficA_cleanTextBasic($referer);
$sql3 = "UPDATE `$table` SET count=count+1,last='$date' WHERE STRCMP(address,'$referer')=0 AND page=$pageid";
$res3 = mysql_query($sql3,$connection);
if (mysql_affected_rows() < 1) {
	$req4 ="INSERT INTO `$table` SET address='$referer', page='$pageid', first='$date', last='$date', count='1', visited='0'";
	$res4 = mysql_query($req4,$connection);
}
return 1;
}

/*********************************************************************************/
/* Function: phpTrafficA_addSEngine
/* Role: add a log of search engine and keywords into the database
/* Parameters: 
/*   - $table: table with search engine logs
/*   - $connection: link with database
/*   - $engine: search engine name
/*   - $keywords
/*   - $date: date of the entry in yyyy-mm-dd format
/* Output: 
/*   - return 1 if successful
/*   - return -1 if failed
/* Created 05/2004
/* Completely changed 06/2005 to reduce number of SQL access
/* 11/2008: Changed insert syntax for mysql5
/*********************************************************************************/
function phpTrafficA_addSEngine($table, $connection, $engine, $keywords, $date, $pageid) {
$sql3 = "UPDATE `$table` SET count=count+1,last='$date' WHERE STRCMP(engine,'$engine')=0 AND STRCMP(keyword,'$keywords')=0 AND page=$pageid";
$res3 = mysql_query($sql3,$connection);
if (mysql_affected_rows() < 1) {
	$req4 ="INSERT INTO `$table` SET engine='$engine', keyword='$keywords', page='$pageid', first='$date', last='$date', count='1'";
	$res4 = mysql_query($req4,$connection);
}
return 1;
}

/*********************************************************************************/
/* Function: phpTrafficA_ExtractAgent
/* Role: extract browser and OS from OS string recorded in the log
/* Parameters:
/*   - $agt: agent and browser string
/*   - $browser_id: array with browser ID's
/*   - $browser_label: array with browser names
/*   - $os_id: array with OS ID's
/*   - $os_label: array with OS names
/* Output: a string like WebBrowser;OS
/*    You can extract OS and WebBrowser using:
/*    list($wb,$os)=explode(";",phpTrafficA_ExtractAgent($HTTP_USER_AGENT));
/*     echo $wb."<br>";
/*     echo $os."<br>";
/* Created 04/2004
/* Inspiration from existing function in ezboo webstats, http://www.ezboo.com
/*********************************************************************************/
function phpTrafficA_ExtractAgent($agt,$browser_id,$browser_label,$os_id,$os_label) {
// Init default values
$new_agt_browser="???";   // Do not change these ??? . It is used in other scripts
$new_agt_os="???";        // Do not change these ??? . It is used in other scripts
if (trim($agt) == "") {
	$new_agt_browser="Crawler";
} else if ( trim($agt) == "Mozilla/4.0 (compatible;)") {
	$new_agt_browser="Crawler";
} else {
	// Check for browser
	for ($cpt = 0; $cpt < count($browser_id) ; $cpt++) {
		if (strpos($agt,$browser_id[$cpt]) !== FALSE) {
			$new_agt_browser=$browser_label[$cpt];
			break;
		}
	}
}
if (($new_agt_browser=="Googlebot") || ($new_agt_browser=="Crawler") || ($new_agt_browser=="Google Adwords")) {
	$new_agt_os = $new_agt_browser;
} else {
	// Check for OS
	for ($cpt = 0; $cpt < count($os_id) ; $cpt++) {
		if (strpos($agt,$os_id[$cpt]) !== FALSE) {
			$new_agt_os=$os_label[$cpt];
			break;
		}
	}
}
return($new_agt_browser.";".$new_agt_os);   # Systax is=  WebBrowser;OS
}

/*********************************************************************************/
/* Function: phpTrafficA_ExtractKeywords
/* Role: extract keywords and search engines (SE) from a referrer string
/* Parameters:
/*   - $refurl: referrer string
/*   - $engine_id: array with SE ID's
/*   - $engine_url: array with strings to detect the SE
/*   - $engine_kwd: array with an array of strings to detect the SE keywords
/*   - $engine_charset: array with an array of strings with the Charset used by this search engine
/* Output: a list (keyword1 keyword2... SE)
/* Created 05/2004
/* Inspiration from code at scriptygoddess
/*          http://www.scriptygoddess.com/archives/003277.php
/*          but heavily modified
/* Modified: 11/2006, for better handling of charsets
/* Modified: 07/2007, fixing charsets for Russian search engines
/*********************************************************************************/
function  phpTrafficA_ExtractKeywords($refurl,$engine_id,$engine_url,$engine_kwd,$engine_charset,$debugit=false) {
$found = 0;
// Separate hosts and the rest
$test = parse_url($refurl);
$refhost = $test['host'];
if (isset($test['query'])) {
	$refquery = $test['query'];
} else {
	$refquery = "";
}
// if (ereg('^(.*)\?(.*)',$refurl,$regs)) {
//	$refhost=$regs[1];
//	$refquery=$regs[2];
//}
$nSengine = count($engine_url);
for ($i=0; $i<$nSengine;$i++) {
	// Is url matching the search engine?
	if (strpos($refhost,$engine_url[$i]) !== FALSE) {
		// Look for keywords
		$nKeySearch = count($engine_kwd[$i]);
		foreach($engine_kwd[$i] as $key) {
			if (preg_match('/\b'.$key.'=(.*)(?:&|$)/Ui',$refquery,$regs)) {
				// ok, we have a match, store the SE name
				$engid = $engine_id[$i];
				// look for keywords
				$searched=urldecode($regs[1]);
				// Deal with google cache (double barrel keywords);
				if (preg_match('/google cache/i', $engid)) {
					$strings = explode(" ", $searched);
					$s = array_shift($strings);
					$searched = implode(" ",$strings);
				}
				// Deal with google images (double barrel keywords);
				if (preg_match('/google images/i', $engid)) {
					$key = "q";
					preg_match('/\b'.$key.'=(.*)(?:&|$)/Ui',$searched,$regs);
					$searched=urldecode($regs[1]);
				}
				// Try to convert keywords to UTF-8
				// Russian Search Engines
				if (!preg_match('%^(?:[\x09\x0A\x0D\x20-\x7E]|[\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})*$%xs', $searched)) {
					$searched = iconv ($engine_charset[$i], "UTF-8", $searched);
				} else if (isset($engine_charset[$i])) {
					$searched = iconv ($engine_charset[$i], "UTF-8", $searched);
				} else if (preg_match('/google/i', $engid)) {
					$key = "ie";
					if (preg_match('/\b'.$key.'=(.*)(?:&|$)/Ui',$refquery,$regs)) {
						$encoding=urldecode($regs[1]);
						$searched = iconv ($encoding, "UTF-8", $searched);
					}
				} else if (preg_match('/yahoo/i', $engid)) {
					$key = "ei";
					if (preg_match('/\b'.$key.'=(.*)(?:&|$)/Ui',$refquery,$regs)) {
						$encoding=urldecode($regs[1]);
						$searched = iconv ($encoding, "UTF-8", $searched);
					}
				}
				$found = 1;
				$engkwd[0] = trim(preg_replace('/\s\s+/', ' ', $searched))." "; // Space for backwards compatibility
				$engkwd[1] = $engid;
				# print_r($engkwd);
				return $engkwd;
				// Exit the whole loop on SE, we found it...
			}
		}
	}
}
return;
}

/*********************************************************************************/
/* Function: phpTrafficA_read_config
/* Role: Read configuration table
/* Parameters: 
/*   - $config_table: table name
/*   - $connection: connection to the database
/* Output:
/*   - a table with 
/*        $conf[save_host] = number of hosts to save in hosts history table
/*        $conf[os_id] = list with operating systems ID's
/*        $conf[os_label] = list with operating systems labels
/*        $conf[browser_id] = list with browser ID's
/*        $conf[browser_label] = list with browser labels
/*        $conf[engine_id] = list with search engines ID's
/*        $conf[engine_url] = list with search engines url's
/*        $conf[engine_kwd] = list with search engines keyword parameters
/*        $conf[blacklist] = blacklist for referers
/*        $conf[visitcutoff] = cut-off time for individual visits
/* Created 05/2004
/*********************************************************************************/
function phpTrafficA_read_config($config_table, $connection) {
// get configuration (browser, OS list, search engines definitions)
$sql = "SELECT * FROM $config_table";
$result = mysql_query ($sql,$connection);
$config = array("x" =>"x");
while($row = mysql_fetch_array($result)) {
	$config = $config + array($row["variable"] => $row["value"] );
}
mysql_free_result ($result);
// Read the os_list and assign 2 arrays (label and id)
$buffer = explode ("\n",$config['os_list']);
if (trim(end($buffer)) == "") {array_pop($buffer);}
$i=0;
foreach($buffer as $buffer1) {
	list ($os_id[$i], $os_label[$i]) = explode ('|', trim($buffer1));
	$i+=1;
}
// Read the browser_list and assign 2 arrays (label and id)
$buffer = explode ("\n",$config['browser_list']);
if (trim(end($buffer)) == "") {array_pop($buffer);}
$i=0;
foreach($buffer as $buffer1) {
	list ($browser_id[$i], $browser_label[$i]) = explode ('|', trim($buffer1));
	$i+=1;
}
// Read search engines and keywords table
$buffer = explode ("\n",$config['search_engines']);
if (trim(end($buffer)) == "") {array_pop($buffer);}
$i=0;
foreach($buffer as $buffer1) {
	$array = explode ('|', trim($buffer1));
	$engine_id[$i] = $array[0];
	$engine_url[$i] = $array[1];
	$keywords = $array[2];
	if (isset($array[3])) $engine_charset[$i] = $array[3];
	$engine_kwd[$i] = explode (':', $keywords);
	$i+=1;
}
// Read number of hosts to be saved
$save_host = $config['save_host'];
// Read the referrer black list
$blacklist = explode ("\n",$config['blacklist']);
if (trim(end($blacklist)) == "") {array_pop($blacklist);}
// Search engines in referrer table
$seAreRef =  $config['seref'];
// Visit cut-off
$visitCutoff =  $config['visitcutoff'];
// Prepare data to return
$conf['save_host'] = $save_host;
$conf['os_id'] = $os_id;
$conf['os_label'] = $os_label;
$conf['browser_id'] = $browser_id;
$conf['browser_label'] = $browser_label;
$conf['engine_id'] = $engine_id;
$conf['engine_url'] = $engine_url;
$conf['engine_kwd'] = $engine_kwd;
$conf['engine_charset'] = $engine_charset;
$conf['blacklist'] = $blacklist;
$conf['seAreRef'] = $seAreRef;
$conf['visitcutoff'] = $visitCutoff;
return $conf;
}

/*********************************************************************************/
/* Function: phpTrafficA_insert_ipbased_entry
/* Role: Inserts data in database for one visitor: visitor count for each page, path, and visitor retention
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for tables
/*   - $first: time when visit started (unix timestamp)
/*   - $last: time when visit ended (unix timestamp)
/*   - $clicks: number of hits
/*   - $path: path, page id's separated with "|"
/*   - $ip: IP adress
/*   - $tmpdir: directory with temporary files
/* Output: none
/* Created 11/2005
/* Changed 09/2008 to deal with screen resolution
/* 11/2008: Changed insert syntax for mysql5
/*********************************************************************************/
function phpTrafficA_insert_ipbased_entry($c, $table, $first, $last, $clicks, $path,$ip,$tmpdir) {
// Convert duration of stay to minutes
$duration = intval(($last-$first)/60);
// If the duration is negative, something went wrong and we ignore it
if ($duration>=0) {
	$date = date("Y-m-01",$first);
	$sql3 = "UPDATE `${table}_retention` SET count=count+1 WHERE date='$date' AND mode=0 AND length=$duration";
	$res3 = mysql_query($sql3,$c);
	if (mysql_affected_rows() < 1) {
		$req4 ="INSERT INTO `${table}_retention` SET date='$date', mode='0', length='$duration', count='1'";
		$res4 = mysql_query($req4,$c);
	} 
}
// Number of clicks
$sql3 = "UPDATE `${table}_retention` SET count=count+1 WHERE date='$date' AND mode=1 AND length=$clicks";
$res3 = mysql_query($sql3,$c);
if (mysql_affected_rows() < 1) {
	$req4 ="INSERT INTO `${table}_retention` SET date='$date', mode='1', length='$clicks', count='1'";
	$res4 = mysql_query($req4,$c);
}
// IP tracking
$datetimelast = date("Y-m-d H:i:s",$last);
$datetimefirst = date("Y-m-d H:i:s",$first);
phpTrafficA_addUniqueIP($table, $c, $ip, $datetimefirst, $datetimelast);
// Unique IP hits...
$datestring = date("Y-m-d",$first);
phpTrafficA_addDB_LDC("${table}_uniq",$c,$datestring,0); // whole site
$pages = explode("|", $path);
$pageuniq = array_unique($pages);
foreach ($pageuniq as $page) {
	if ($page != 0) phpTrafficA_addDB_LDC("${table}_uniq",$c,$datestring,$page);
}
// Path
$path = "|$path|";
$entry = $pages[0];
$exit = $pages[count($pages)-1];
$date = date("Y-m-d H:i:s",$first);
$sql3 = "UPDATE `${table}_path` SET count=count+1,last='$date' WHERE length=$clicks AND STRCMP(path,'$path')=0";
$res3 = mysql_query($sql3,$c);
if (mysql_affected_rows() < 1) {
	$req4 ="INSERT INTO `${table}_path` SET ${table}_path.first='$date', ${table}_path.last='$date', ${table}_path.entry=$entry, ${table}_path.exit=$exit, ${table}_path.path='$path', ${table}_path.length=$clicks, ${table}_path.count=1";
	// 03/09/2009 Exit column should include the table name because it is a special name.
	$res4 = mysql_query($req4,$c);
}
// Screen resolution
$tmpfile = "$tmpdir/resolution.$table.dat";
if (!file_exists($tmpfile)) {
	touch($tmpfile);
}
$stats = file("$tmpfile");
$found = FALSE;
$newString = "";
foreach($stats as $log) {
	$log_arr = explode("|>|", $log);
	$host = $log_arr[0];
	$res = $log_arr[1];
	if ($host==$ip) {
		$found = true;
		$resolution = $res;
	} else {
		if (trim($host) != "") $newString .= "$host|>|$res\n";
	}
}
$newstats =fopen("$tmpfile", "w");
flock ($newstats,2);
fwrite($newstats,$newString);
// flock ($newstats,3); Removed. No need to free the lock since it is done automatically when closing.
fclose($newstats);
if ($found) {
	$monthstring = date("Y-m",$first)."-01";
	$test = phpTrafficA_addDB_LDC("${table}_resolution",$c,$monthstring,$resolution);
}

return 1;
}

/*********************************************************************************/
/* Function: phpTrafficA_ipbased
/* Role: deals with stats based on unique IP's
/* Parameters:
/*   - $c: connection to the database
/*   - $pageid: page being viewed
/*   - $ip: IP address
/*   - $agent: agent string 
/*   - $time: unix timestamp 
/*   - $conf: configuration table
/*   - $table: root for table names
/*   - $site
/*   - $visitCutoff: time of inactivity after which  visit is considered to be finished, in minutes
/*   - $tmpdir: path to temporary directory
/*   - $ip2c: path to the IP to country database
/* Output: none
/* Created 11/2005
/* Changed 12/2006: added the $visitCutoff parameter
/* Changed 09/2007: $tmpdir and $ip2c parameters to avoid global variables
/* Changed 09/2008 to deal with screen resolution
/*********************************************************************************/
function phpTrafficA_ipbased($c,$pageid,$ip,$agent,$time,$conf,$table,$site,$visitCutoff,$tmpdir,$ip2c,$resolution) {

$max_keep_ipbased = $visitCutoff*60; // 1800 = 30 minutes, 3600 = 1 hour
$pageid = trim($pageid, " \n\r");
$tmpfile = "$tmpdir/ipbased.$table.dat";
if (!file_exists($tmpfile)) {
	touch($tmpfile);
}
$stats = file("$tmpfile");
$found = FALSE;
$newpath = TRUE;
$newString = "";
foreach($stats as $log) {
	$log_arr = explode("|>|", $log);
	$host = $log_arr[0];
	$first = $log_arr[1];
	$last = $log_arr[2];
	$clicks = $log_arr[3];
	$thispath = $log_arr[4];
	if ($first>100) {
		if ($host==$ip) {
			if ($last+$max_keep_ipbased > $time) {
				$newpath = FALSE;
				$clicks += 1;
				$thispath .= "|$pageid";
				$newString .= "$ip|>|$first|>|$time|>|$clicks|>|$thispath|>|\n";
			} else {
				phpTrafficA_insert_ipbased_entry($c, $table, $first, $last, $clicks, $thispath, $host,$tmpdir);
				$newString .= "$ip|>|$time|>|$time|>|1|>|$pageid|>|\n";
			}
			$found = TRUE;
		} else {
			if ($last+$max_keep_ipbased > $time) {
				$newString .= "$host|>|$first|>|$last|>|$clicks|>|$thispath|>|\n";
			} else {
				phpTrafficA_insert_ipbased_entry($c, $table, $first, $last, $clicks, $thispath, $host,$tmpdir);
			}
		}
	}
}
if (!$found) { $newString .= "$ip|>|$time|>|$time|>|1|>|$pageid|>|\n";}
$newstats =fopen("$tmpfile", "w");
flock ($newstats,2);
fwrite($newstats,$newString);
// @flock ($newstats,3); Removed. No need to free the lock since it is done automatically when closing.
fclose($newstats);

if ($newpath) {
	// Add country, OS, and browser
	$monthstring = date("Y-m",$time)."-01";
	list($wb,$os)=explode(";",phpTrafficA_ExtractAgent($agent,$conf[browser_id],$conf[browser_label],$conf[os_id],$conf[os_label]));
	$test = phpTrafficA_addDB_LDC("${table}_os",$c,$monthstring,$os);
	$test = phpTrafficA_addDB_LDC("${table}_browser",$c,$monthstring,$wb);
	if (!(preg_match("/Crawler/i", $wb))) {
		if (!(preg_match("/Google/i", $wb))) {
			$country = ip2Country($ip,$ip2c);
			$test = phpTrafficA_addDB_LDC("${table}_country",$c,$monthstring,$country);
		}
	}
}

// If screen resolution has been recorded, store it for this IP
if (trim($resolution)!="") {
	$tmpfile = "$tmpdir/resolution.$table.dat";
	if (!file_exists($tmpfile)) {
		touch($tmpfile);
	}
	$stats = file("$tmpfile");
	$found = FALSE;
	$newString = "";
	foreach($stats as $log) {
		$log_arr = explode("|>|", $log);
		$host = $log_arr[0];
		$res = $log_arr[1];
		if ($host==$ip) {
			$newString .= "$ip|>|$resolution\n";
			$found = true;
		} else if (trim($host) != '') $newString .= "$host|>|$res\n";
	}
	if (!$found) $newString .= "$ip|>|$resolution\n";
	$newstats =fopen("$tmpfile", "w");
	flock ($newstats,2);
	fwrite($newstats,$newString);
	// @flock ($newstats,3);Removed. No need to free the lock since it is done automatically when closing.
	fclose($newstats);
}

return 1;
}

/*********************************************************************************/
/* Function: phpTrafficA_figureOutRefOrSE
/* Role: deals with extraction of referrers and search engine keywords
/* Parameters:
/*   - $c: connection to the database
/*   - $referer: referrer string
/*   - $site: URL of the site
/*   - $table
/*   - $engine_id: array with SE id's
/*   - $engine_url: array with SE URL's
/*   - $engine_kwd: array with the way to extract keywords
/*   - $engine_charset: array with the charsets used by a SE
/*   - $blacklist: referrer blaclist 
/*   - $date
/*   - $pageid
/*   - $seAreRef: 1 if SE are inserted in referrer table
/* Output: 0 if not know, 1 if link inside site, 2 if SE, 3 if referer
/* Created 11/2006
/*********************************************************************************/
function phpTrafficA_figureOutRefOrSE ($c, $referer, $site, $table, $engine_id,$engine_url,$engine_kwd,$engine_charset, $blacklist, $date, $pageid,$seAreRef) {
$how = 0;
if ($referer != "") {
	// Separate hosts and parameters
	$parse = parse_url($referer);
	$refhost = "" . $parse["scheme"] . "://" . $parse["host"] . $parse["path"];
	$refquery = $parse["query"];
	$pos2 = strpos(strtolower($refhost), strtolower($site));
	if ($pos2 !== false) {
		// if link from within the site do nothing, otherwise, check
		$how = 1;
	} else {
		$keywords = "";
		$keywordsArray = phpTrafficA_ExtractKeywords($referer,$engine_id,$engine_url,$engine_kwd,$engine_charset,0);
		if ($keywordsArray != "") {
			// search engine
			$how = 2;
			$test = phpTrafficA_addSEngine("${table}_keyword", $c, $keywordsArray[1], $keywordsArray[0], $date,$pageid);
			if ($test == -1) { break;}
			if ($seAreRef) {
				$test = phpTrafficA_addReferrer("${table}_referrer", $c, $referer, $date,$pageid);
				if ($test == -1) { break;}
			}
		} else {
			// not a search engine, it's a link, we check the black list
			$test = 1;
			foreach ($blacklist as $black) {
				// Is url matching the one in the black list
				$pos = strpos($referer,$black);
				if ($pos !== false) {
					$test = 0;
					break;
				}
			}
			if ($test == 1) {
				$how = 3;
				$test = phpTrafficA_addReferrer("${table}_referrer", $c, $referer, $date,$pageid);
				if ($test == -1) { break;}
			}
		}
	}
}
return $how;
}

/*********************************************************************************/
/* Function: phpTrafficA_logit
/* Role: logs a access into proper databases
/* Parameters:
/*   - $c: link to the database
/*   - $config_table: name of the configuration table
/*   - $table: base name for tables
/*   - $site: site being audited
/*   - $page: page accessed
/*   - $ip: ip number of host
/*   - $agent: agent string
/*   - $time: unix timestamp
/*   - $referer: referer string
/*   - $visitCutoff: time of inactivity after which  visit is considered to be finished, in minutes
/*   - $recordCrawler: true or false, depending on if you want to record visits from Crawlers
/*   - $tmpdir: path to temporary directory
/*   - $ip2c: path to the IP to country database
/*   - $resolution: screen resolution
/* Output: none
/* Created 05/2004
/* Changed heavily 11/2005 to switch to unique IP based stats...
/* Changed heavily 11/2006 to accomodate a function to not record to record visits from crawlers
/* Changed 11/2006 to pull out the referrer and search engine extraction functions
/* Changed 12/2006: added the $visitCutoff parameter (it used to default at 1hour)
/* Changed 01/2007: removed the $visitCutoff parameter (moved into SQL config)
/* Changed 09/2007: moved connection to the database outside the function
/*    $tmpdir and $ip2c parameters to avoid all globals
/* Changed 09/2008 to deal with screen resolution
/* 11/2008: Changed insert syntax for mysql5
/*********************************************************************************/
function phpTrafficA_logit($c,$config_table,$table, $site, $page, $ip, $agent, $time, $referer, $recordCrawler, $tmpdir, $ip2c,$resolution) {
// basic stuff
$hour = date("H", $time);
$day = date("w", $time);
$datestring = date("Y-m-d",$time);
$date = date("Y-m-d H:i:s",$time);
// mysql_query("SET NAMES 'utf8'", $c);
// Read configurations
$conf = phpTrafficA_read_config($config_table, $c);
$save_host = $conf['save_host'];
$engine_id = $conf['engine_id'];
$engine_url = $conf['engine_url'] ;
$engine_kwd = $conf['engine_kwd'];
$engine_charset = $conf['engine_charset'];
$blacklist= $conf['blacklist'];
$seAreRef = $conf['seAreRef'];
$visitCutoff = $conf['visitcutoff'];
// Extracting the OS and webbrowser
list($wb,$os)=explode(";",phpTrafficA_ExtractAgent($agent,$conf['browser_id'],$conf['browser_label'],$conf['os_id'],$conf['os_label']));
$crawlerArray = array ("Crawler", "Googlebot", "Google Adwords");
if ( $recordCrawler || (!$recordCrawler && (array_search ($wb,$crawlerArray) === FALSE)) ) {
	// If we record crawlers, or if it is not a crawler, go ahead
	// Fixing pagename
	$page = preg_replace("/\/+/", "/", $page); // replace all // with / in the page url
	// replace all possible index by an empty page name
	$patterns = array("index.html", "index.shtml", "index.asp", "index.xml", "index.php", "index.php3", "index.php4", "index.php5");
	$page = str_replace($patterns, "", $page);
	// $page = ereg_replace ("index.(html|shtml|asp|xml|php|php3|php4|php5){1}$", "", $page);
	// SPECIAL ZONEO: / Becomes index.php This should be fixed... FIX ME!
	$isindex = substr($page, -1);
	if ($isindex == "/") {
		$page = $page."index.php";
	}
	$page = phpTrafficA_cleanTextNoAmp($page);
	// Find out if this page has been audited yet, and get the index
	$pageid = phpTrafficA_pageid("${table}_pages", $c, $page, $datestring);
	if ($pageid == -1) { break;}
	// Hour and day of the week
	$test = phpTrafficA_updateCount("${table}_hour", $c, $hour);
	$test = phpTrafficA_updateCount("${table}_day", $c, $day);
	// Referrer, search engines, keywords
	$how = phpTrafficA_figureOutRefOrSE ($c, $referer, $site, $table, $engine_id,$engine_url,$engine_kwd,$engine_charset, $blacklist, $date, $pageid,$seAreRef);
	// Add page view + way we got here
	$pageview = phpTrafficA_add_access($table,$c,$datestring,$pageid,$how);
	// IP Based stats are in a different function
	//   includes OS, browser, and country stats
	// Move after record of page views to avoid more unique visitors than page views issue...
	phpTrafficA_ipbased($c,$pageid,$ip,$agent,$time,$conf,$table,$site,$visitCutoff,$tmpdir, $ip2c,$resolution);
}
// Insert access in latest hosts table, even if it is a crawler
$cleanref = phpTrafficA_cleanText($referer);
$iplong = ip2long($ip);
$sql3 ="INSERT INTO `${table}_host` SET date='$date', host='', hostname='', page='$page', ref='$cleanref', agent='$agent', longIP='$iplong'";
$res3 = mysql_query($sql3,$c);
// Cleanup the host table, we do not want that many... Just keep the last ones.
// We will keep twice more hosts than keep_hosts so we do not have to clear up the table all the time. This is for the sake of speeeeed!
$req3 = "SELECT COUNT(*) as count FROM ${table}_host";
$res3 = mysql_query($req3,$c);
$tmp=mysql_fetch_array($res3);
if (($tmp['count']-2*$save_host)>0) {
	$hostdelete = $tmp['count']-$save_host;
	$req3 = "ALTER TABLE `${table}_host` ORDER BY `date`";
	$res3 = mysql_query($req3,$c);
	$req3 = "DELETE FROM `${table}_host` LIMIT $hostdelete";
	$res3 = mysql_query($req3,$c);
	$req3 = "OPTIMIZE TABLE `${table}_host` ";
	$res3 = mysql_query($req3,$c);
}
return $pageview;
}
?>