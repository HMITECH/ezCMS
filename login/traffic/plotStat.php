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

if (!isset($file) or $file=="") {$file = $_GET['file'];}
include("./Php/Functions/phplot.php");
include("./Php/config.php");
// Secure the file parameter
$file = "$tmpdirectory/".str_replace("/", "", $file);
if (is_file("$file")) {
	include("$file");
} else {
	die("Something is wrong.");
}
function hex2rgb($hex) {
   $color = str_replace('#','',$hex);
   $rgb = array(hexdec(substr($color,0,2)),
                hexdec(substr($color,2,2)),
                hexdec(substr($color,4,2)));
   return $rgb;
 } 

if (!isset($notime)) {
	$notime=FALSE;
}
if (!isset($yscale)) {
	$yscale=FALSE;
}
if (!isset($yscale2)) {
	$yscale2=FALSE;
}
if (!isset($twoplots)) {
	$twoplots=0;
}
$dark = hex2rgb("#122C4D");

$graph = new PHPlot($width,$height);

$graph->SetPrintImage(false); //Don't draw the image until specified explicitly 
if ($twoplots == 1) {
	// We need more space for the legend...
	$maxY = 1.5*$maxY;
	$maxY2  = 1.5*$maxY2;
	$maxY2 = max(2,$maxY2);
if ($maxY < 10000) {
		$offset1 = 65;
	} else if ($maxY < 100000) {
		$offset1 = 70;
	} else {
		$offset1 = 80;
	} if ($maxY2 < 10000) {
		$offset2 = 65;
	} else if ($maxY2 < 100000) {
		$offset2 = 70;
	} else {
		$offset2 = 80;
	}
	$h = $height - 50;
	$w = $width - $offset2;
	$graph->SetPlotAreaPixels($offset1,10,$w,$h); // where do we want the graph to go
}
$maxY = max(2,$maxY);
$graph->SetIsInline(true);
$graph->SetGridColor($dark);
$graph->SetLightGridColor($dark);
//$graph->SetFont("x_label",2);
//$graph->SetFont("y_label",2);
if ($plottype=="bars") {
	$graph->SetDataType("text-data");
} else {
	$graph->SetDataType("data-data");
}
if (!$notime) {
	$graph->SetXGridLabelType("time");
	$graph->SetXTimeFormat("%b %y");
}
if ($yscale=='sqrt') {
	// $graph->setYGridLabelType("sqrt");
	$graph->SetYScaleType("sqrt");
	$tickStep = max(intval(sqrt($maxY)/6),1);
} elseif ($yscale=="cbrt") {
	//$graph->setYGridLabelType("sqrt");
	$graph->SetYScaleType("cbrt");
	$tickStep = max(intval(pow($maxY,1./3)/6),1); 
} elseif ($yscale=="qdrt") {
	//$graph->setYGridLabelType("sqrt");
	$graph->SetYScaleType("qdrt");
	$tickStep = max(intval(pow($maxY,1./4)/6),1); 
} else {
	$pow = intval(log($maxY,10));
	if ($maxY<2*pow(10,$pow)) {
		$tickStep = intval(2*pow(10,$pow-1));
	} else if ($maxY<5*pow(10,$pow)) {
		$tickStep = intval(5*pow(10,$pow-1));
	} else {
		$tickStep = intval(pow(10,$pow));
	}
	if ($tickStep == 0) $tickStep = 1;
}
//echo "MaxY is $maxY, pow is $pow and tickStep is $tickStep";
$graph->SetVertTickIncrement($tickStep);
$graph->SetXDataLabelAngle(90);
if (isset($xlabel)) { $graph->SetXLabel($xlabel); } else { $graph->SetXLabel("");}
if (isset($ylabel)) { $graph->SetYLabel($ylabel); } else { $graph->SetYLabel("");}
$graph->SetDataValues($date_data);
$graph->SetColor($dark);
if (!$notime) {
	// If data more than 3 years, ticks every 4 months
	if (($end-$start) > (36*2679000)) {
	  $graph->SetHorizTickIncrement(4*2679000);
	  $graph->SetXTimeFormat("%m/%y") ;
	// If data more than 1.5 years, ticks every 2 months
	} else if (($end-$start) > (18*2679000)) {
	  $graph->SetHorizTickIncrement(2*2679000);
	  $graph->SetXTimeFormat("%m/%y") ;
	// data for more than three month, monthly ticks in X
	} else if (($end-$start) > (3*2679000)) {
	  $graph->SetHorizTickIncrement(2679000);
	  $graph->SetXTimeFormat("%m/%y") ;
	// if data for more than two week, weekly ticks in X
	} elseif  (($end-$start) > (2*604800)) {
	  $graph->SetHorizTickIncrement(604800);
	  $graph->SetXTimeFormat("%e/%m") ;
	} else {
	  $graph->SetHorizTickIncrement(86400);
	  $graph->SetXTimeFormat("%e/%m") ;
	}
} else {
	$rangeX = $end-$start;
	if ($xscale=='sqrt') {
		$graph->SetXScaleType("sqrt");
		$incr = max(intval(sqrt($rangeX)/3),1);
	} elseif ($xscale=="cbrt") {
		//$graph->setYGridLabelType("sqrt");
		$graph->SetYXcaleType("cbrt");
		$incr = max(intval(pow($rangeX,1./3)/6),1); 
	} elseif ($xscale=="qdrt") {
		//$graph->setYGridLabelType("sqrt");
		$graph->SetXScaleType("qdrt");
		$incr = max(intval(pow($rangeX,1./4)/6),1); 
	} else {
		$incr = intval(($end-$start)/15);
	}
	if ($incr<1) $incr=1;
	$graph->SetHorizTickIncrement($incr);
}
// Making space for the legend (I can't find the option)
if (isset($legende)) {
	if (($end-$start) > (48*2679000)) { // 48 months
		$end += 15*2679000;
	} if (($end-$start) > (24*2679000)) { // 24 months
		$end += 10*2679000;
	} else if (($end-$start) > (8*2679000)) { // 8 months
		$end += 5*2679000;
	} elseif (($end-$start) > (3*2679000)) { // 3 months
		$end +=2679000;
	} elseif  (($end-$start) > (2*604800)) { // 2 weeks
		$end +=2*604800;
	} else {
		$end +=86400*2;
	}
}
$graph->SetPlotType("$plottype");
$graph->SetDrawXDataLabels(false);
$graph->SetPlotAreaWorld($start,0,$end,$maxY);

if ($twoplots == 0) {
	//$graph->SetDataColors( array( '#00AEEF', '#00A99D', '#00A651', '#8DC63F', '#FFF200', '#F7941D', '#ED1C24', '#662D91', '#0072BC'), array( "black","black", "black", "black", "black", "black","black","black","black") );
	$graph->SetDataColors( array(  '#E500FF', '#4300FF', '#00C5FF', '#36D958', '#FFF400', '#FFAA00', '#FF0400', '#8D8D8D', '#000000'), array( "black","black", "black", "black", "black", "black","black","black","black") );
} else {
	$graph->SetDataColors( array( 'red', 'blue'), array( "black","black") );
}
// Color changes after suggestion from Martin Hoffmann
// $graph->SetDataColors( array( "red","blue","green","orange","violet", "black","peru", "aquamarine1","DarkGreen"), array( "black","black", "black", "black", "black", "black","black","black","black") );

// $graph->SetLegendPixel(500,0,"");
$graph->SetLineStyles('solid');
if (isset($legende)) {
  $graph->SetLegend($legende);
  //$graph->SetLegendWorld($end, $maxYY);
}
if ($twoplots == 1) {
	$graph->SetLineWidth(1);
} else {
	$graph->SetLineWidth(2);
}
// Setting the image format
if (function_exists("imagepng")) {
	$graph->SetFileFormat('png');
} elseif (function_exists("imagejpeg")) {
	$graph->SetFileFormat('jpg');
} elseif (function_exists("imagewbmp")) {
	$graph->SetFileFormat('wbmp');
} else {
	die("No image support in this PHP server");
}

// Plot the second one, if any!
if ($twoplots == 1) {
	// We need to add a legend for the first plot
	$graph->SetLegend(array($ylabel, $ylabel2)); //Lets have a legend
	$graph->SetLegendPixels($offset1+5,15);
	// We draw the first one
	$graph->DrawGraph();
	//
	$graph->SetPlotType($plottype2);
	$graph->SetDataValues($date_data2);
	$graph->SetDataColors(array('blue'));
	$graph->SetDrawXDataLabels(0); //We already got them in the first graph
	$graph->SetDrawXGrid(false);
	$graph->SetDrawYGrid(false);
	// $graph->SetLineStyles('solid');
	//Set Params of another Y Axis 
	$graph->SetYTitle($ylabel2, 'plotright');
	$graph->SetYTickPos('plotright');
	$graph->SetYTickLabelPos('plotright');
	$graph->SetYDataLabelPos('plotright');
	$graph->SetTickColor('blue');
	$graph->SetTextColor('black');
	$graph->SetLabelColor('black');
	// Tick seps
	if ($yscale2=='sqrt') {
		$graph->SetYScaleType("sqrt");
		$tickStep = max(intval(sqrt($maxY2)/6),1);
	} elseif ($yscale2=="cbrt") {
		$graph->SetYScaleType("cbrt");
		$tickStep = max(intval(pow($maxY2,1./3)/6),1);
	} elseif ($yscale2=="qdrt") {
		$graph->SetYScaleType("qdrt");
		$tickStep = max(intval(pow($maxY2,1./4)/6),1);
	} else {
		// We need the same number of ticks than the other plot...
		// We also want the ticks to be at nice values only...
// 		$nticks = $maxY/$tickStep;
// 		$pow = intval(log($maxY2,10));
// 		if ($maxY2<2*pow(10,$pow)) {
// 			$tickStep = 2*pow(10,$pow-1);
// 		} else if ($maxY2<5*pow(10,$pow)) {
// 			$tickStep = 5*pow(10,$pow-1);
// 		} else {
// 			$tickStep = pow(10,$pow);
// 		}
// 		$tickStep = max(intval($maxY2/$nticks),1);
// 		$maxY2 = 1.0*$tickStep*($nticks+1);
// 		$tickStep = max(intval($maxY2/$nticks),1);
// 		$maxY2 = 1.0*$tickStep*($nticks);
		$pow = intval(log($maxY2,10));
		if ($maxY2<2*pow(10,$pow)) {
			$tickStep = 2*pow(10,$pow-1);
		} else if ($maxY2<5*pow(10,$pow)) {
			$tickStep = 5*pow(10,$pow-1);
		} else {
			$tickStep = pow(10,$pow);
		}
	}
	$graph->SetPlotAreaWorld($start,0,$end,$maxY2);
	$graph->SetVertTickIncrement($tickStep);
	$graph->SetLegend(array());
}
$graph->DrawGraph();
$graph->PrintImage();
$graph->_PHPlot();
?>
