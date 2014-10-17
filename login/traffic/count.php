<?php
function  MakeImage($cookie, $count = 0)  {
	global $cookieTxt;
	// Use custom image
	// $im = imagecreatefromjpeg("kurbster.jpg");
	// header("Content-Type: image/jpeg");
	// imagejpeg($im);
	// imagedestroy($im);
	// Creation of image with GD
	if ($cookie == 1) {
		$text = "$cookieTxt";
		$length = strlen($text);
		$size = 14+($length-1)*9;
		$im = imagecreate($size,20);
	} elseif ($cookie == 2) {
		if ($count == 0) { 
			$length = 1;
		} else {
			$length =  1+intval(log($count)/log(10));
		}
		$size = 14+($length-1)*9;
		$im = imagecreate($size,20);
		$text = "$count";
  } elseif ($cookie == 3) {
    $text = "Count";
    $im = imagecreate(50,20);
  } else  {
    $text = "";
    $im = imagecreate(1,1);
  }
  $black  =  ImageColorAllocate($im,  0,  0,  0); 
  //$transparent = imagecolortransparent($im, $black);
  $white  =  ImageColorAllocate($im,  255,  255,  255); 
  imagefill($im,0,0,$black);
  ImageString($im,5,3,2,$text,$white);
	if (function_exists("imagegif")) {
		header("Content-type: image/gif");
		imagegif($im);
	} elseif (function_exists("imagepng")) {
		header("Content-type: image/png");
		imagepng($im);
	} elseif (function_exists("imagejpeg")) {
		header("Content-type: image/jpeg");
		imagejpeg($im, "", 0.8);
	}  elseif (function_exists("imagewbmp")) {
		header("Content-type: image/vnd.wap.wbmp");
		imagewbmp($im);
	} else {
		die("No image support in this PHP server");
	}
	ImageDestroy($im);
}

function backstr($haystack, $needle) {
        return substr($haystack, 0, strlen($haystack) - strlen(strstr($haystack,$needle)));
 }

/***** Include configuration files *********/

$path = "./";
include ("$path/Php/config_sql.php");
include ("$path/Php/config.php");
include ("$path/Php/Functions/funct.country.inc.php"); 
include ("$path/Php/Functions/log_function.php");

// Fixing the path to the ip2c database
$ip2c = $path."/".$ip2c;

// path for temporary files
$phpTA_tmpdir = "$path/$tmpdirectory/";

if (!isset($cookieTxt)) $cookieTxt = "Admin";

/***** Catching Data **********************/
// Page
if (!isset($p)) {
	if (isset($_GET['p'])) {
		$p = $_GET['p'];
	} else if (isset($_GET['amp;p'])) {
		$p = $_GET['amp;p'];
	}
}
// Sid
if (!isset($sid)) $sid = $_GET['sid'];
// Referrer
if (!isset($r)) {
	if (isset($_GET['r'])) {
		$r = $_GET['r'];
	} else if (isset($_GET['amp;r'])) {
		$r = $_GET['amp;r'];
	}
}
// Screen Resolution
$resolution = '';
if (isset($_GET['res'])) {
	$resolution = $_GET['res'];
} else if (isset($_GET['amp;res'])) {
	$resolution = $_GET['amp;res'];
} else if (isset($_COOKIE['phpTA_resolution'])) {
	$resolution = $_COOKIE['phpTA_resolution'];
}

//
$phpTrafficA = "";
if (isset($_COOKIE['phpTrafficA'])) $phpTrafficA = $_COOKIE['phpTrafficA'];

/**** Connect to db and get sites array ***/

$c = mysql_connect("$server","$user","$password") or die("<br>Can not connect to database in count.php: ".mysql_error());
$db = mysql_select_db("$base",$c) or die("<br>Can not select base in count.php: ".mysql_error());
$sites = phpTrafficA_get_sites($c, $config_table);

/**** Processing Data *********************/
$to = base64_decode(rawurldecode($p));
if ($sites[$sid]['trim']) {
	$to = backstr($to,'?');
}
$table = $sites[$sid]['table'];
$site = $sites[$sid]['site'];
$referer = base64_decode(rawurldecode($r));
$ip = $_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$servertime = time()+$sites[$sid]['timediff']*3600;

/**** Checking the browser string to get rid of pure spammers that cause problem otherwise ***/
$ban = false;
$banagents = array("Mozilla/5.0", "Mozilla/4.0");
if (in_array(trim($_SERVER["HTTP_USER_AGENT"]),$banagents)) $ban = true;

/**** Make the image **********************/

if ($ban) {
	MakeImage(3);
} else if (phpTrafficA_bannedIP($c, $config_table, $ip)) {
	MakeImage(3);
} else if ($phpTrafficA != "Admin") {
	$pageview = phpTrafficA_logit($c,$config_table,$table, $site, $to, $ip, $agent, $servertime, $referer, $sites[$sid]['crawler'],$phpTA_tmpdir,$ip2c,$resolution);
	if ($sites[$sid]['counter'] == 1) {
		MakeImage(2, $pageview);
	} else {
		MakeImage(0, $pageview);
	}
} else {
	if ($cookieTxt != "") {
		MakeImage(1);
	} else {
		MakeImage(0);
	}
}
// Close database
@mysql_close ($c);
?>