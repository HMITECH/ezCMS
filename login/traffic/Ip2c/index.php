<?php

$ip2c = "./";
$flags="../Flags/";  // set a correct relative or absolute path to the flag directory
$extn='png';      // set format of the flag images /case sensitive/
$dtme=1;          // set whether time to be displayed (1 or 0)


error_reporting(1);

function tmm(){
	$u=microtime();
	$u=explode(" ",$u);
	return $u[1]+$u[0];
}

function ip2Country($ip) {
global $ip2c;
$file = $ip2c."/".(substr($ip, 0, strpos($ip, ".")).".dat");
echo "Looking for $file<br>";
$c = "nd";
if (!is_readable($file)) return $c;
echo "I found it..";
$long = sprintf("%u",ip2long($ip));
$fp = fopen($file, "rb");
while (($range = fgetcsv($fp, 40, "|")) !== false) {
	if (($long >= $range[1]) && ($long <= ($range[1] + $range[2]))) {
		$c = $range[0];
		break;
	}
}
fclose($fp);
echo "I found $c";
return $c;
}

$s=tmm();
$pv=phpversion();
$pv=substr($pv,0,1).substr($pv,2,1);
if($pv>40) {
	import_request_variables("gpc");
	$REMOTE_ADDR=$HTTP_SERVER_VARS['REMOTE_ADDR'];
	$PHP_SELF=$HTTP_SERVER_VARS['PHP_SELF'];
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head><title>ip2c 1.0</title>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<style type="text/css">
body{color:#222222;background-color:#eeeeee} 
div{padding:2px;font-size:11px;font-family:Verdana,sans-serif}
input{font-family:Verdana,sans-serif;font-size:11px;border:1px #666666 solid}
</style></head><body onload="document.forms[0].ip.focus()">
<form action="<?php print $PHP_SELF;?>">
<input type="text" name="ip" value="<?php if(!isset($ip)||$ip==''){print $REMOTE_ADDR;}?>" />
<input type="submit" value="OK" />
</form><div><?php
if(isset($ip)&&$ip!=''){
	$ty = ip2Country($ip);
	if($ty=='nd') {
		print "Unknown IP address<br />\n";
	} else {
		$ty=strtolower($ty);
		$ty=str_replace("\n","",$ty);
		switch($ty) {
			case'ac':$rz='Ascension Island';break;
			case'ad':$rz='Andorra';break;
			case'ae':$rz='United Arab Emirates';break;
			case'af':$rz='Afghanistan';break;
			case'ag':$rz='Antigua and Barbuda';break;
			case'ai':$rz='Anguilla';break;
			case'al':$rz='Albania';break;
			case'am':$rz='Armenia';break;
			case'an':$rz='Netherlands Antilles';break;
			case'ao':$rz='Angola';break;
			case'aq':$rz='Antarctica';break;
			case'ar':$rz='Argentina';break;
			case'as':$rz='American Samoa';break;
			case'at':$rz='Austria';break;
			case'au':$rz='Australia';break;
			case'aw':$rz='Aruba';break;
			case'az':$rz='Azerbaijan';break;
			case'ba':$rz='Bosnia and Herzegowina';break;
			case'bb':$rz='Barbados';break;
			case'bd':$rz='Bangladesh';break;
			case'be':$rz='Belgium';break;
			case'bf':$rz='Burkina Faso';break;
			case'bg':$rz='Bulgaria';break;
			case'bh':$rz='Bahrain';break;
			case'bi':$rz='Burundi';break;
			case'bj':$rz='Benin';break;
			case'bm':$rz='Bermuda';break;
			case'bn':$rz='Brunei Darussalam';break;
			case'bo':$rz='Bolivia';break;
			case'br':$rz='Brazil';break;
			case'bs':$rz='Bahamas';break;
			case'bt':$rz='Bhutan';break;
			case'bv':$rz='Bouvet Island';break;
			case'bw':$rz='Botswana';break;
			case'by':$rz='Belarus';break;
			case'bz':$rz='Belize';break;
			case'ca':$rz='Canada';break;
			case'cc':$rz='Cocos (Keeling) Islands';break;
			case'cd':$rz='Zaire';break;
			case'cf':$rz='Central African Republic';break;
			case'cg':$rz='Congo';break;
			case'ch':$rz='Switzerland';break;
			case'ci':$rz='Cote d\'Ivoire';break;
			case'ck':$rz='Cook Islands';break;
			case'cl':$rz='Chile';break;
			case'cm':$rz='Cameroon';break;
			case'cn':$rz='China';break;
			case'co':$rz='Colombia';break;
			case'cr':$rz='Costa Rica';break;
			case'cs':$rz='Czechoslovakia (former)';break;
			case'cu':$rz='Cuba';break;
			case'cv':$rz='Cape Verde';break;
			case'cx':$rz='Christmas Island';break;
			case'cy':$rz='Cyprus';break;
			case'cz':$rz='Czech Republic';break;
			case'de':$rz='Germany';break;
			case'dj':$rz='Djibouti';break;
			case'dk':$rz='Denmark';break;
			case'dm':$rz='Dominica';break;
			case'do':$rz='Dominican Republic';break;
			case'dz':$rz='Algeria';break;
			case'ec':$rz='Ecuador';break;
			case'ee':$rz='Estonia';break;
			case'eg':$rz='Egypt';break;
			case'eh':$rz='Western Sahara';break;
			case'er':$rz='Eritrea';break;
			case'es':$rz='Spain';break;
			case'et':$rz='Ethiopia';break;
			case'fi':$rz='Finland';break;
			case'fj':$rz='Fiji';break;
			case'fk':$rz='Falkland Islands';break;
			case'fm':$rz='Micronesia';break;
			case'fo':$rz='Faroe Islands';break;
			case'fr':$rz='France';break;
			case'fx':$rz='France, Metropolitan';break;
			case'ga':$rz='Gabon';break;
			case'gb':$rz='United Kingdom';break;
			case'gd':$rz='Grenada';break;
			case'ge':$rz='Georgia';break;
			case'gf':$rz='French Guiana';break;
			case'gg':$rz='Guernsey';break;
			case'gh':$rz='Ghana';break;
			case'gi':$rz='Gibraltar';break;
			case'gl':$rz='Greenland';break;
			case'gm':$rz='Gambia';break;
			case'gn':$rz='Guinea';break;
			case'gp':$rz='Guadelope';break;
			case'gq':$rz='Equatorial Guinea';break;
			case'gr':$rz='Greece';break;
			case'gs':$rz='Sth Georgia &Sth Sandwich Is.';break;
			case'gt':$rz='Guatemala';break;
			case'gu':$rz='Guam';break;
			case'gw':$rz='Guinea-Bissau';break;
			case'gy':$rz='Guyana';break;
			case'hk':$rz='Hong Kong';break;
			case'hm':$rz='Heard and McDonald Islands';break;
			case'hn':$rz='Honduras';break;
			case'hr':$rz='Croatia';break;
			case'ht':$rz='Haiti';break;
			case'hu':$rz='Hungary';break;
			case'id':$rz='Indonesia';break;
			case'ie':$rz='Ireland';break;
			case'il':$rz='Israel';break;
			case'im':$rz='Isle of Man';break;
			case'in':$rz='India';break;
			case'io':$rz='British Indian Ocean Terr.';break;
			case'iq':$rz='Iraq';break;
			case'ir':$rz='Iran';break;
			case'is':$rz='Iceland';break;
			case'it':$rz='Italy';break;
			case'je':$rz='Jersey';break;
			case'jm':$rz='Jamaica';break;
			case'jo':$rz='Jordan';break;
			case'jp':$rz='Japan';break;
			case'ke':$rz='Kenya';break;
			case'kg':$rz='Kyrgystan';break;
			case'kh':$rz='Cambodia';break;
			case'ki':$rz='Kiribati';break;
			case'km':$rz='Comoros';break;
			case'kn':$rz='Saint Kitts and Nevis';break;
			case'kp':$rz='Korea, DPR';break;
			case'kr':$rz='Korea, Republic of';break;
			case'kw':$rz='Kuwait';break;
			case'ky':$rz='Cayman Islands';break;
			case'kz':$rz='Kazakhstan';break;
			case'la':$rz='Lao PDR';break;
			case'lb':$rz='Lebanon';break;
			case'lc':$rz='Saint Lucia';break;
			case'li':$rz='Liechtenstein';break;
			case'lk':$rz='Sri Lanka';break;
			case'lr':$rz='Liberia';break;
			case'ls':$rz='Lesotho';break;
			case'lt':$rz='Lithuania';break;
			case'lu':$rz='Luxembourg';break;
			case'lv':$rz='Latvia';break;
			case'ly':$rz='Libyan Arab Jamahiriya';break;
			case'ma':$rz='Morocco';break;
			case'mc':$rz='Monaco';break;
			case'md':$rz='Moldova';break;
			case'mg':$rz='Madagascar';break;
			case'mh':$rz='Marshall Islands';break;
			case'mk':$rz='Macedonia';break;
			case'ml':$rz='Mali';break;
			case'mm':$rz='Myanmar';break;
			case'mn':$rz='Mongolia';break;
			case'mo':$rz='Macau';break;
			case'mp':$rz='Northern Mariana Is.';break;
			case'mq':$rz='Martinique';break;
			case'mr':$rz='Mauritania';break;
			case'ms':$rz='Montserrat';break;
			case'mt':$rz='Malta';break;
			case'mu':$rz='Mauritius';break;
			case'mv':$rz='Maldives';break;
			case'mw':$rz='Malawi';break;
			case'mx':$rz='Mexico';break;
			case'my':$rz='Malaysia';break;
			case'mz':$rz='Mozambique';break;
			case'na':$rz='Namibia';break;
			case'nc':$rz='New Caledonia';break;
			case'ne':$rz='Niger';break;
			case'nf':$rz='Norfolk Island';break;
			case'ng':$rz='Nigeria';break;
			case'ni':$rz='Nicaragua';break;
			case'nl':$rz='The Netherlands';break;
			case'no':$rz='Norway';break;
			case'np':$rz='Nepal';break;
			case'nr':$rz='Nauru';break;
			case'nt':$rz='Neutral Zone';break;
			case'nu':$rz='Niue';break;
			case'nz':$rz='New Zealand';break;
			case'om':$rz='Oman';break;
			case'pa':$rz='Panama';break;
			case'pe':$rz='Peru';break;
			case'pf':$rz='French Polynesia';break;
			case'pg':$rz='Papua New Guinea';break;
			case'ph':$rz='Philippines';break;
			case'pk':$rz='Pakistan';break;
			case'pl':$rz='Poland';break;
			case'pm':$rz='St. Pierre and Miquelon';break;
			case'pn':$rz='Pitcairn';break;
			case'pr':$rz='Puerto Rico';break;
			case'pt':$rz='Portugal';break;
			case'pw':$rz='Palau';break;
			case'py':$rz='Paraguay';break;
			case'qa':$rz='Qatar';break;
			case're':$rz='Reunion';break;
			case'ro':$rz='Romania';break;
			case'ru':$rz='Russia';break;
			case'rw':$rz='Rwanda';break;
			case'sa':$rz='Saudi Arabia';break;
			case'sb':$rz='Solomon Islands';break;
			case'sc':$rz='Seychelles';break;
			case'sd':$rz='Sudan';break;
			case'se':$rz='Sweden';break;
			case'sg':$rz='Singapore';break;
			case'sh':$rz='St. Helena';break;
			case'si':$rz='Slovenia';break;
			case'sj':$rz='Svalbard and Jan Mayen Is.';break;
			case'sk':$rz='Slovakia';break;
			case'sl':$rz='Sierra Leone';break;
			case'sm':$rz='San Marino';break;
			case'sn':$rz='Senegal';break;
			case'so':$rz='Somalia';break;
			case'sr':$rz='Surinam';break;
			case'st':$rz='Sao Tome and Principe';break;
			case'su':$rz='USSR';break;
			case'sv':$rz='El Salvador';break;
			case'sy':$rz='Syrian Arab Republic';break;
			case'sz':$rz='Swaziland';break;
			case'tc':$rz='The Turks & Caicos Islands';break;
			case'td':$rz='Chad';break;
			case'tf':$rz='French Southern Territories';break;
			case'tg':$rz='Togo';break;
			case'th':$rz='Thailand';break;
			case'tj':$rz='Tajikistan';break;
			case'tk':$rz='Tokelau';break;
			case'tm':$rz='Turkmenistan';break;
			case'tn':$rz='Tunisia';break;
			case'to':$rz='Tonga';break;
			case'tp':$rz='East Timor';break;
			case'tr':$rz='Turkey';break;
			case'tt':$rz='Trinidad and Tobago';break;
			case'tv':$rz='Tuvalu';break;
			case'tw':$rz='Taiwan';break;
			case'tz':$rz='Tanzania';break;
			case'ua':$rz='Ukraine';break;
			case'ug':$rz='Uganda';break;
			case'uk':$rz='United Kingdom';break;
			case'um':$rz='United States M.O Is.';break;
			case'us':$rz='United States';break;
			case'uy':$rz='Uruguay';break;
			case'uz':$rz='Uzbekistan';break;
			case'va':$rz='Holy See (Vatican)';break;
			case'vc':$rz='St Vincent & Grenadines';break;
			case've':$rz='Venezuela';break;
			case'vg':$rz='Virgin Islands British';break;
			case'vi':$rz='Virgin Islands U.S';break;
			case'vn':$rz='Vietnam';break;
			case'vu':$rz='Vanuatu';break;
			case'wf':$rz='Wallis and Futuna Islands';break;
			case'ws':$rz='Samoa';break;
			case'ye':$rz='Yemen';break;
			case'yt':$rz='Mayotte';break;
			case'yu':$rz='Yugoslavia';break;
			case'za':$rz='South Africa';break;
			case'zm':$rz='Zambia';break;
			case'zr':$rz='Zaire';break;
			case'zw':$rz='Zimbabwe';break;
			default:$rz='';break;
		}
		$img=$flags.$ty.".$extn";
		if(is_file($img)) {
			list($width, $height, $type, $attr) = getimagesize("img/flag.jpg");
			print "<img src=\"$img\" $attr alt=\"$rz\" /> ";
		}
		print "$rz<br />\n";
	}
	if($dtme==1){
		$t=tmm();
		$st3=substr(($t-$s),0,5);
		print "<span style=\"font-size:10px;color:#888888\">$st3 sec</span>\n";
	}
}
?></div></body></html>
