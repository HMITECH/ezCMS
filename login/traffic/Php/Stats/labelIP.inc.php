<?php

/*****************************************************************************/
/* Function: setLabel
/* Role: form to set a label for an IP address
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $domain: domain of the website
/*   - $sid: domain sid
/*   - $ip: IP address to set a label for
/* Output:
/*   -
/* Created: 09/2009
/*****************************************************************************/

function setLabel($c, $table, $domain, $sid, $ip) {
include ("Php/Stats/statsMonthly.inc.php");
include ("Php/Functions/funct.country.inc.php");
global $strings;
global $ip2c;
global $lang;
global $isLoggedIn;
$long = ip2long($ip);
$sql = "SELECT label,first,last,count  FROM ${table}_iplist WHERE ip=$long";
$res = mysql_query($sql,$c);
$row = mysql_fetch_object($res);
$visits = $row->count;
$first = $row->first;
$last = $row->last;
$label = $row->label;
$host = gethostbyaddr($ip);
$country = ip2Country($ip,$ip2c);
$isBanned = isIPbanned($c, $long);
if (whoislink($country) == FALSE) {
	$whoislink = "no whois link";
} else {
	$whoislink = "<a href=\"".whoislink($country)."$ip\"  target=\"blank\" class=\"basic\">whois</a>";
}
if (($isLoggedIn) && (!$isBanned)) {
	$baniplink = ", <a href=\"index.php?mode=setup&amp;todo=banip&amp;ip=$ip&amp;lang=$lang\" class=\"basic\">".$strings['banIP']."</a>";
} else {
	$baniplink = "";
}
if ($isBanned) {
	$bannedTxt = "<br>".$strings['IPisBanned'];
} else {
	$bannedTxt = "";
}

echo "<table class='stat'>
<tr class='title'><td colspan='3'>".$strings['Labelfor']." $ip</td></tr>
<tr><td>$ip ($whoislink$baniplink) <br> $host $bannedTxt</td><td>".countryNameFlag($country)."</td></tr>
<tr><td colspan='3'>".$strings['Firstvisit'].": ".dateandtime(strtotime($first))."<br>
".$strings['Lastvisit'].": ".dateandtime(strtotime($last))."<br>
".$strings['NumVisits'].": ".format_float($visits)."</td></tr>
<tr><td colspan='3'>";
if ($isLoggedIn) {
	echo "<form action=\"./index.php\" method=\"post\">
<table class=\"basic\" align=\"left\">
<tr><td>".$strings['Label']."</td>
<td><input type=\"text\" name=\"label\" maxlength=\"50\" size=\"40\" value=\"$label\"></td>
<td><input type=\"hidden\" name=\"mode\" value=\"stats\"><input type=\"hidden\" name=\"show\" value=\"labelip\"><input type=\"hidden\" name=\"ip\" value=\"$ip\"><input type=\"hidden\" name=\"todo\" value=\"dosetlabel\"><input type=\"hidden\" name=\"lang\" value=\"$lang\"><input type=\"hidden\" name=\"sid\" value=\"$sid\"><input type=\"submit\" name=\"change\" value=\"".$strings['Submit']."\"></td><td>
<input type=\"submit\" name=\"removelabel\" value=\"".$strings['RemoveLabel']."\">
</table></form>";
} else {
	echo $strings['Label'].": $label";
}
echo "</td></tr>";
echo "</table>\n";
}

/*****************************************************************************/
/* Function: doSetLabel
/* Role: Function to set a label for an IP address
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $domain: domain of the website
/*   - $sid: domain sid
/*   - $ip: IP address to set a label for
/*   - $label: the label
/* Output:
/*   -
/* Created: 09/2009
/*****************************************************************************/

function doSetLabel($c, $table, $domain, $sid, $ip, $label) {
global $isLoggedIn;
global $DEMO;
global $strings;
if ((!$DEMO) && ($isLoggedIn)) {
	if (isset($_POST["change"])) {
		$long = ip2long($ip);
		$sql = "UPDATE ${table}_iplist SET label=\"$label\" WHERE ip=$long";
		$res = mysql_query($sql,$c);
		if (!$res) {
			echo "<div class=\"error\">".$strings['errorwithdb']." ".__LINE__.": ".mysql_error()."</div>\n";
		}
	} else if (isset($_POST["removelabel"])) {
		$long = ip2long($ip);
		$sql = "UPDATE ${table}_iplist SET label=\"\" WHERE ip=$long";
		$res = mysql_query($sql,$c);
		if (!$res) {
			echo "<div class=\"error\">".$strings['errorwithdb']." ".__LINE__.": ".mysql_error()."</div>\n";
		}
	}
}
}

/*****************************************************************************/
/* Function: listLabels
/* Role: list IP addresses with labels and offers link to edit them
/* Parameters:
/*   - $c: connection to the database
/*   - $table: base name for sql tables
/*   - $domain: domain of the website
/*   - $sid: domain sid
/*   - $start: page number
/* Output:
/*   -
/* Created: 09/2009
/*****************************************************************************/

function listLabels($c, $table, $domain, $sid, $start) {
include ("Php/Functions/funct.country.inc.php");
include ("Php/Stats/statsMonthly.inc.php");
global $strings;
global $ip2c;
global $lang;
global $display;
global $sites;

$timeserver = time() + 3600*$sites[$sid]['timediff'];
$today = strtotime(date("M j, Y", $timeserver));

echo "<table class='stat'>
<tr class='title'><td colspan='7'>".$strings['VisitorsWithLabel']."</td></tr>
<tr class='caption'><td colspan='3'>&nbsp;</td><td>".$strings['Label']."</td><td>".$strings['Firstvisit']."</td><td>".$strings['Lastvisit']."</td><td>".$strings['Visits']."</td></tr>\n";

$nperpage = $display['ntoplong'];
$f = $nperpage * ($start - 1);
$limits =  "LIMIT $f,$nperpage";
$order = "ORDER BY label ASC";
$sql = "SELECT ip,label,first,last,count  FROM ${table}_iplist WHERE label!=\"\" $order $limits";
$res = mysql_query($sql,$c);
$n = 0;
while($row = mysql_fetch_object($res)) {
	$n += 1;
	$visits = $row->count;
	$datefirst = strtotime($row->first);
	$datelast = strtotime($row->last);
	$label = $row->label;
	$long = $row->ip;
	$ip = long2ip($long);
	$country = ip2Country($ip,$ip2c);
	$isBanned = isIPbanned($c, $long);
	if ($datefirst>$today) {
		$first = timetoday($datefirst,0);
	} else {
		$first = dayMmYear($datefirst,0);
	}
	if ($datelast>$today) {
		$last = timetoday($datelast,0);
	} else {
		$last = dayMmYear($datelast,0);
	}
	$bannedTxt = "";
	if ($isBanned) {
		$bannedTxt = " (".$strings['IPBanned'].")";
	}
	$editlink = "index.php?mode=stats&amp;show=labelip&amp;sid=$sid&amp;todo=setlabel&amp;ip=$ip&amp;lang=$lang";
	echo "<tr><td><a href=\"$editlink\" class=\"basic\">$ip</a>$bannedTxt</td><td>".countryNameFlag($country)."</td>
<td>$label</td><td>$first</td><td>$last</td><td>".format_float($visits)."</td></tr>\n";
}
if ($n == 0) {
	echo "\n<tr><td colspan=\"7\"><div align=\"center\">".$strings['Nothingyet']."</div></td></tr>\n";
} else {
	$req = "SELECT COUNT(*) as count FROM ${table}_iplist WHERE label!=\"\"";
	$res = mysql_query($req,$c);
	$row = mysql_fetch_object($res);
	$nip = $row->count;
	$npages = intval(($nip-1)/$nperpage)+1;
	echo "<tr><td colspan=\"7\"><div align=\"center\"><strong>".$strings['Page'].":</strong> ";
	echo navPage("./index.php?mode=stats&amp;sid=$sid&amp;show=labelip&amp;lang=$lang&amp;start=", $npages, $start);
	echo "</div></td></tr>\n";
}
echo "</table>";

}

?>