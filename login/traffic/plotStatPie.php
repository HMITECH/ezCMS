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

if (!isset($width)) {$width = 500;}
if (!isset($height)) {$height = 200;}
$plot = min($width,$height);

$graph = new PHPlot($width,$height);
$graph->SetGridColor($dark);
$graph->SetLightGridColor($dark);
//$graph->SetFont("x_label",2);
//$graph->SetFont("y_label",2);
$graph->SetDataType("text-data");  //Must be called before SetDataValues
$graph->SetXDataLabelAngle(0);
$graph->SetDataValues($date_data);
$graph->SetColor($dark);
$graph->SetPlotType("$plottype");
$graph->SetLegend($legende);
// Special function (I added it to phplot to shif my pie plots to the left)
// 60 pixels margin on the left, 5 on top
$graph->SetPiePlotPixels(30, 5, $plot+30, $plot+5);
//position lower left corner
$graph->SetLegendPixels($plot+70,10);
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
$graph->DrawGraph();
?>