<?

function plotBarVert($root, $data, $xsize, $ysize) {
$ndata = count($data);
$wplot = $xsize-100;
$wbar = intval($wplot/$ndata);
$wbarplot = intval($wplot/$ndata-10);
$wplot = $wbar*$ndata;
$htxt = $ysize-2;

$css = "#q-graph {position: relative; width: ${wplot}px; height: ${ysize}px; margin: 1.1em 0 3.5em; padding: 0; background: #EEE; border: 1px solid gray; list-style: none; font: 9px Helvetica, Geneva, sans-serif; margin-left:auto; margin-right:auto;}
#q-graph ul {margin: 0; padding: 0; list-style: none;}
#q-graph li {position: absolute; bottom: 0; width: {$wbar}px; z-index: 2; margin: 0; padding: 0; text-align: center; list-style: none;}
#q-graph li.qtr {height: {$htxt}px; padding-top: 2px; border-right: 1px dotted #C4C4C4; color: #AAA;}
#q-graph li.bar {width: {$wbarplot}; border: 1px solid; border-bottom: none; color: #000; left: 5px; background: #DCA; border-color: #EDC #BA9 #000 #EDC;}
#q-graph li.bar p {margin: 5px 0 0; padding: 0; display:none;}
#q-graph #ticks {width: ${wplot}px; height: ${ysize}px; z-index: 1;}
#q-graph #ticks .tick {position: relative; border-bottom: 1px dotted #BBB; width: ${wplot}px;}
#q-graph #ticks .tick p {position: absolute; left: 100%; top: -0.67em; margin: 0 0 0 0.5em;}";

$xdata = array();
$ydata = array();
foreach ($data as $thisdata) {
	$xdata[] = $thisdata[0];
	$ydata[] = $thisdata[1];
}
$max = max($ydata);
$left = 0;
$plot = "<ul id=\"q-graph\">\n";
for ($i=0; $i<$ndata; $i++) {
	$thisx = $xdata[$i];
	$thisy = $ydata[$i];
	$h = intval(1.0*($htxt-30)*$thisy/$max);
	$plot .= "<li class=\"qtr\" style=\"left:$left\">$thisx\n<ul>\n<li class=\"bar\" style=\"height: ${h}px;\"><p>$thisy</p></li>\n</ul>\n</li>\n";
	$left += $wbar;
}
$tick = 0;
$ntick = intval($max/10000);
$ticksep = 10000;
$tickheight = number_format(1.0*($htxt-30)*$ticksep/$max,2);
$plot .= "<li id=\"ticks\">\n";
while($tick < $max) {
	$plot .= "<div class=\"tick\" style=\"height: ${tickheight}px;\"><p>$tick</p></div>\n";
	$tick += $ticksep;
}
$plot .= "</li>\n</ul>\n";

$txt = "<style type=\"text/css\">\n$css\n</style>\n$plot";

return $txt;
}

?>