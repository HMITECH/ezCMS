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
/* Function: whoislink
/* Role: returns a whois link according to a 2 letters country code
/* Parameters:
/*   - $countrycode
/* Output:
/*   -whois link (just add the IP address)
/* Created 12/2006
/* Source: homemage
/*      Inspiration: BBClone source code
/*      Country vs. whois link extracted from database available at
/*             http://software77.net/cgi-bin/ip-country/
/*      The IP to country DB is not as good as the one available on maxmind.com
/*      (lots of 'eu' entries, seems to be a few errors) but this table seems to be
/*      OK.
**********************************************************************************/
function whoislink($countrycode) {
$link = array( "us" => "ar", "zz" => "ia", "ca" => "ar", "nl" => "ri", "pr" => "ar", "cl" => "la", "bs" => "ar", "ar" => "la", "gb" => "ri", "za" => "af", "dz" => "af", "sz" => "af", "gh" => "af", "cm" => "af", "mg" => "af", "tz" => "af", "ke" => "af", "ng" => "af", "ao" => "af", "eg" => "af", "na" => "af", "ma" => "af", "mu" => "af", "tg" => "af", "ci" => "af", "ly" => "af", "sn" => "af", "sd" => "af", "ug" => "af", "zw" => "af", "mz" => "af", "sl" => "af", "sc" => "af", "zm" => "af", "bw" => "af", "lr" => "af", "bj" => "af", "jp" => "ap", "de" => "ri", "fr" => "ri", "in" => "ap", "au" => "ap", "th" => "ap", "cn" => "ap", "my" => "ap", "pk" => "ap", "nz" => "ap", "kr" => "ap", "hk" => "ap", "sg" => "ap", "bd" => "ap", "id" => "ap", "ph" => "ap", "tw" => "ap", "af" => "ap", "vn" => "ap", "bn" => "ap", "ap" => "ap", "il" => "ri", "gr" => "ri", "ch" => "ri", "sa" => "ri", "se" => "ri", "pl" => "ri", "it" => "ri", "cz" => "ri", "be" => "ri", "ru" => "ri", "ie" => "ri", "dk" => "ri", "cy" => "ri", "at" => "ri", "es" => "ri", "ua" => "ri", "no" => "ri", "pt" => "ri", "tr" => "ri", "eu" => "ri", "bg" => "ri", "fi" => "ri", "ir" => "ri", "om" => "ri", "lv" => "ri", "ee" => "ri", "sk" => "ri", "jo" => "ri", "hu" => "ri", "kw" => "ri", "lt" => "ri", "lb" => "ri", "am" => "ri", "cs" => "ri", "kz" => "ri", "is" => "ri", "mk" => "ri", "ge" => "ri", "mt" => "ri", "az" => "ri", "ro" => "ri", "mc" => "ri", "tt" => "la", "do" => "la", "ls" => "af", "bm" => "ar", "co" => "la", "vi" => "ar", "ag" => "ar", "bb" => "ar", "jm" => "ar", "hr" => "ri", "rs" => "ri", "lu" => "ri", "ba" => "ri", "li" => "ri", "fo" => "ri", "iq" => "ri", "al" => "ri", "uz" => "ri", "bh" => "ri", "by" => "ri", "md" => "ri", "si" => "ri", "ae" => "ri", "kg" => "ri", "qa" => "ri", "ps" => "ri", "ye" => "ri", "sy" => "ri", "mr" => "af", "tj" => "ri", "ad" => "ri", "gi" => "ri", "gl" => "ri", "sm" => "ri", "gu" => "ap", "mn" => "ap", "kh" => "ap", "lk" => "ap", "pf" => "ap", "fj" => "ap", "fm" => "ap", "mo" => "ap", "ve" => "la", "mx" => "la", "br" => "la", "ec" => "la", "pe" => "la", "cr" => "la", "uy" => "la", "ni" => "la", "bo" => "la", "pa" => "la", "gt" => "la", "sv" => "la", "cu" => "la", "py" => "la", "an" => "la", "hn" => "la", "gy" => "la", "tn" => "af", "bf" => "af", "ne" => "af", "pg" => "ap", "ga" => "af", "bi" => "af", "gd" => "ar", "rw" => "af", "cd" => "af", "gw" => "af", "cf" => "af", "mw" => "af", "gm" => "af", "ml" => "af", "er" => "af", "dj" => "af", "bz" => "la", "sr" => "la", "ht" => "la", "aw" => "la", "gf" => "la", "sb" => "ap", "mv" => "ap", "tv" => "ap", "ws" => "ap", "ki" => "ap", "nc" => "ap", "to" => "ap", "io" => "ap", "np" => "ap", "la" => "ap", "nu" => "ap", "ck" => "ap", "as" => "ap", "vu" => "ap", "mp" => "ap", "bt" => "ap", "pw" => "ap", "nf" => "ap", "mm" => "ap", "nr" => "ap", "ai" => "ar", "kn" => "ar", "lc" => "ar", "vg" => "ar", "gp" => "ar", "va" => "ri", "et" => "af", "ky" => "ar", "ax" => "ri", "tm" => "ri");
switch($link[$countrycode]) {
	case "af":
		$url = "http://www.afrinic.net/cgi-bin/whois?searchtext=";
		break;
	case "ap":
		$url = "http://www.apnic.net/apnic-bin/whois.pl?searchtext=";
		break;
	case "la":
		$url = "http://lacnic.net/cgi-bin/lacnic/whois?query=";
		break;
	case "ri":
		$url = "http://www.ripe.net/fcgi-bin/whois?searchtext=";
		break;
	case "ar":
		$url = "http://ws.arin.net/whois/?queryinput=";
		break;
	// Don't return whois link for private or reserved ranges
	default:
		return false;
}
return $url;
}

/*********************************************************************************
/* Function: ip2Country
/* Role: returns the 2 letters country code corresponding to an IP address
/* Parameters:
/*   - $ip
/*   - $ip2c: path to the db
/* Output:
/*   - country code
/* Source: improved from older version within phpTrafficA, after looking at the BBClone source code.
/* Created 12/2006
/* Changed 09/2007: $ip2c parameter to avoid globals
**********************************************************************************/
function ip2Country($ip,$ip2c) {
$file = $ip2c."/".(substr($ip, 0, strpos($ip, ".")).".dat");
// echo "Looking for $file<br>";
$c = "nd";
if (!is_readable($file)) return $c;
// echo "I found it..";
$long = sprintf("%u",ip2long($ip));
$fp = fopen($file, "rb");
while (($range = fgetcsv($fp, 40, "|")) !== false) {
	if (($long >= $range[1]) && ($long <= ($range[1] + $range[2]))) {
		$c = $range[0];
		break;
	}
}
fclose($fp);
// echo "I found $c";
return $c;
}
?>