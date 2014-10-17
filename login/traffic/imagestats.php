<?php
function drawImage($online,$nToday,$vToday,$nTotal,$vTotal) {
	global $path;
	$font = "$path/Img/Font/DejaVuSans.ttf";
	$fontsize = 10;
	$text[] = "Online:";
	$text[] = "- $online visitors";
	$text[] = "Today:";
	$text[] = "- $vToday visitors";
	$text[] = "- $nToday hits";
	$text[] = "Total:";
	$text[] = "- $vTotal visitors";
	$text[] = "- $nTotal hits";
	$flat_width = array();
	$flat_height = array();
	foreach($text as $i=>$tt) {
		$arr = imagettfbbox ($fontsize, 0, $font, $tt);
		$flat_width[$i]  = $arr[2] - $arr[0];
		$flat_height[$i] = abs($arr[3] - $arr[5]);
	}
	$maxL = max($flat_width);
	$totalH = array_sum($flat_height)+2*(count($text)-1);
	$sizeW = 14+$maxL;
	$sizeH = 14+$totalH;
	// Use ImageCreateTrueColor if possible (problem on some linux platform otherwise)
	if (function_exists('ImageCreateTrueColor')) {
		$im = ImageCreateTrueColor($sizeW,$sizeH);
	} else {
		$im = ImageCreate($sizeW,$sizeH);
	}
	if (!$im) die('Could not create image resource.');
	$foreground = ImageColorAllocate($im,  0,  0,  0);
	$background = ImageColorAllocate($im,  255,  255,  255);
	imagefill($im,0,0,$background);
	$x = 5;
	$y = 3+floor($flat_height[0]);
	foreach($text as $i=>$tt) {
		ImageTTFText($im, $fontsize, 0, $x, $y, $foreground, $font, $tt);
		$y += $flat_height[$i]+2;
	}
	imageline($im, 0, 0, 0, $sizeH-1, $foreground);
	imageline($im, 0, $sizeH-1, $sizeW-1, $sizeH-1, $foreground);
	imageline($im, $sizeW-1, $sizeH-1, $sizeW-1, 0, $foreground);
	imageline($im, $sizeW-1, 0, 0, 0, $foreground);
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

function phpTASumm($sid) {
	global $path;
	// Getting the necessary variables
	$path =  __FILE__;
	$path = preg_replace( "'\\\imagestats\.php'", "", $path);
	$path = preg_replace( "'/imagestats\.php'", "", $path);
	include ("$path/Php/config_sql.php");
	include ("$path/Php/config.php");
	include ("$path/Php/Functions/funct.inc.php");
	// Connect to the db
	$c = mysql_connect("$server","$user","$password");
	$db = mysql_select_db("$base",$c);
	// Getting the info
	$sites = get_sites($c);
	if (! isset($sites[$sid]) ) {
		@mysql_close ($c);
		drawImage(0,0,0,0,0);
		return;
	}
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
	// Prepare the image
	drawImage($online,$nToday,$vToday,$nTotal,$vTotal);
}
$sid = $_GET['sid'];
phpTASumm($sid);
?>