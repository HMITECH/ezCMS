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

$dark = hex2rgb("#122C4D");
$dark = hex2rgb("#122C4D");
if (!isset($yscale)) {
	$yscale=FALSE;
}
if (!isset($yscale2)) {
	$yscale2=FALSE;
}
if (!isset($twoplots)) {
	$twoplots=0;
}
if (!isset($width)) {
	$width=500;
}
if (!isset($height)) {
	$height=200;
}
if (!isset($forcesize)) {
	$forcesize=false;
}

if ($twoplots) {
	// We need more space for the legend...
	$maxY = 1.5*$maxY;
	$maxY2  = 1.5*$maxY2;
}
$graph = new PHPlot($width,$height);
$graph->SetPrintImage(false); //Don't draw the image until specified explicitly 
if ($twoplots == 1) {
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
// Forcing plot size, for some cases, spaces for labels are way too large. You can force them to be smaller with this function...
if ($forcesize) {
	$h = $height - 50;
	$w = $width - 60;
	$graph->SetPlotAreaPixels(60,10,$w,$h);
}
$graph->SetGridColor($dark);
$graph->SetLightGridColor($dark);
//$graph->SetFont("x_label",2);
//$graph->SetFont("y_label",2);
$graph->SetDataType("text-data");  //Must be called before SetDataValues
if (isset($angleX)) {
	$graph->SetXDataLabelAngle($angleX);
} else {
	$graph->SetXDataLabelAngle(0);
}
if ($yscale=="sqrt") {
	$graph->SetYScaleType("sqrt");
	$tickStep = max(intval(sqrt($maxY)/6),1);
} elseif ($yscale=="cbrt") {
	$graph->SetYScaleType("cbrt");
	$tickStep = max(intval(pow($maxY,1./3)/6),1);
} elseif ($yscale=="qdrt") {
	$graph->SetYScaleType("qdrt");
	$tickStep = max(intval(pow($maxY,1./4)/6),1);
} else {
	$pow = intval(log($maxY,10));
	if ($maxY<2*pow(10,$pow)) {
		$tickStep = 2*pow(10,$pow-1);
	} else if ($maxY<5*pow(10,$pow)) {
		$tickStep = 5*pow(10,$pow-1);
	} else {
		$tickStep = pow(10,$pow);
	}
}
$graph->SetVertTickIncrement($tickStep);
if (isset($xlabel)) { $graph->SetXLabel($xlabel); } else { $graph->SetXLabel("");}
if (isset($ylabel)) { $graph->SetYLabel($ylabel); } else { $graph->SetYLabel("");}
$graph->SetDataValues($date_data);
$graph->SetPlotAreaWorld(0,0,count($date_data),$maxY);
$graph->SetColor($dark);
$graph->SetPlotType("$plottype");
$graph->SetDataColors( array( "red","blue","green","orange","violet", "black","peru", "aquamarine1","DarkGreen"), array( "black","black", "black", "black", "black", "black","black","black","black") );
// Setting the image format
if (function_exists("imagepng")) {
	$graph->SetFileFormat('png');
} elseif (function_exists("imagejpeg")) {
	$graph->SetFileFormat('jpg');
}  elseif (function_exists("imagewbmp")) {
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
	// $graph->SetDataType("data-data");
	$graph->SetLineWidth(2);
	$graph->SetPlotType($plottype2);
	$graph->SetDataValues($date_data2);
	$graph->SetDataColors(array("blue"));
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
	$graph->SetTextColor('blue');
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
		$pow = intval(log($maxY2,10));
		if ($maxY2<2*pow(10,$pow)) {
			$tickStep = 2*pow(10,$pow-1);
		} else if ($maxY2<5*pow(10,$pow)) {
			$tickStep = 5*pow(10,$pow-1);
		} else {
			$tickStep = pow(10,$pow);
		}
	}
	$graph->SetPlotAreaWorld(0,0,count($date_data),$maxY2);
	$graph->SetVertTickIncrement($tickStep);
	$graph->SetLegend(array());
}
$graph->DrawGraph();
$graph->PrintImage();
$graph->_PHPlot();
?>