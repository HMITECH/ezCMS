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

/*********************************************************************************/
/* Function: echoRetention
/* Role: stats results for visitor retention
/* Parameters:
/*   - $c: connection to the database
/*   - $table: retention table
/*   - $what: 0 for duration, 1 for number of clicks
/* Output:
/*   - nothing, but echos whatever it finds
/* Created: 02/2005
/* 11/2006: removed database connection function (moved earlier in the code)
/*********************************************************************************/
function echoRetention($c, $table, $mode=0) {
	global $DEBUG;
	global $strings;
	global $tmpdirectory;

	// Time at which the routine was called (used in giving names for temporary files)
	$timecall = time();
	
	$table = $table."_retention";
	$thismonth=date("Y-m-01");

	// If mode=0, the most popular length will be 0 (less than 1 minute)
	// If mode=1, the most popular number of clicks will be 1 (1 click)
	// For now, I'll just fetch data for 1 click, 2, 3, 4 clicks, ... 5 or more
	// For now, I'll just fetch data for <1mn <5mn <10m <30mn more

	// Pull number of counts for each month
	//$req = "SELECT date,SUM(count) as count FROM ${table} WHERE mode=$mode GROUP BY date;";
	//$res = mysql_query($req,$c);
	//$nMonth = 0;
	//$date = array();
	//$total = array();
	//while($row = mysql_fetch_object($res)) {
	//	$date[$nMonth] = $row->date;
	//	$total[$nMonth] = $row->count;
	//	// echo "<br>$nMonth- $date[$nMonth]: $total[$nMonth]";
	//	$nMonth += 1;
	//}

	// Pull retention statistics for this month
	$date=date("Y-m-01");
	$nSep = 6;
	if ($mode == 0) {
		$ylabel = $strings['plot-Nvisitors'];
		$cond[0] = "length = 0";
		$leg[0] = $strings['plot-l1mn'];
		$cond[1] = "length >=1 AND length<2";
		$leg[1] = $strings['plot-upto2mn'] ;
		$cond[2] = "length >=2 AND length<5";
		$leg[2] = $strings['plot-upto5mn'] ;
		$cond[3] = "length >=5 AND length<10";
		$leg[3] = $strings['plot-upto10mn'] ;
		$cond[4] = "length >=10 AND length<30";
		$leg[4] = $strings['plot-upto30mn'] ;
		$cond[5] = "length >= 30";
		$leg[5] = $strings['plot-morethan30mn'];
	} else {
		$ylabel=$strings['plot-Nvisitors'];
		$cond[0] ="length = 1";
		$leg[0] = "1 ".$strings['plot-clicks'];
		$cond[1] ="length = 2";
		$leg[1] = "2 ".$strings['plot-clicks'];
		$cond[2] ="length = 3";
		$leg[2] = "3 ".$strings['plot-clicks'];
		$cond[3] ="length = 4";
		$leg[3] = "4 ".$strings['plot-clicks'];
		$cond[4] ="length >= 5 and length<=10";
		$leg[4] = "<=10 ".$strings['plot-clicks'];
		$cond[5] ="length > 10";
		$leg[5] = "> 10 ".$strings['plot-clicks'];
	}
	$total=0;
	for ($j=0;$j<$nSep;$j++) {
		$req = "SELECT count FROM ${table} WHERE mode=$mode AND $cond[$j] AND date='$date';";
		$res = mysql_query($req,$c);
		if ($res != '') {
			$row = mysql_fetch_array($res);
			$datecount[$j] = $row['count'];
		} else {
			$datecount[$j] = "0";
		}
		if($datecount[$j] == "") {$datecount[$j] = "0";}
		$total += $datecount[$j];
	}

	// This month plot
	if ($total>0) {
		$temp = fopen ("$tmpdirectory/tmp.$timecall.txt.php", 'w');
		fwrite($temp, "<?php  \n\$date_data = array(");
		$string="";
		$max=0;
		for ($j=0;$j<$nSep;$j++) {
			if ($datecount[$j]>$max) $max=$datecount[$j];
			$string .= "array(\"".$leg[$j]."\",".$datecount[$j].")";
			fwrite($temp, "$string");
			$string=",";
		}
		fwrite($temp, "\n);\n\$maxY = $max;\n\$plottype=\"bars\";");
		fwrite($temp, "\n\$ylabel = \"".$ylabel."\";");
		fwrite($temp, "\n\$yscale=\"qdrt\";\n?>");
		fclose($temp);
		echo "<table class='stat'>
<tr class='title'><td>".$strings['Statisticsforthismonth']."</td></tr>
<tr><td><center><img src='./plotStatBar.php?file=tmp.$timecall.txt.php' alt='".$strings['Thismonth']."'></center></tr>
</table>";
	} else {
		echo "<table class='stat'>
<tr class='title'><td>".$strings['Statisticsforthismonth']."</td></tr>
<tr><td>&nbsp;<center>".$strings['Nothingyet']."</center>&nbsp;</tr>
</table>";
	}

	// Cumulated plot
	// min duration / min number of clicks
	$req = "SELECT length FROM ${table} WHERE mode=$mode ORDER BY length ASC LIMIT 0,1;";
	$res = mysql_query($req,$c);
	if ($res != '') {
		$row = mysql_fetch_object($res);
		$start = $row->length;
		$req = "SELECT length FROM ${table} WHERE mode=$mode ORDER BY length DESC LIMIT 0,1;";
		$res = mysql_query($req,$c);
		$row = mysql_fetch_object($res);
		$end = $row->length;
		for ($i=$start;$i<=$end;$i++) {
			$counttable[$i] = 0;
		}
		$maxcount = 0;
		$req = "SELECT length, SUM(count) as count FROM ${table} WHERE mode=$mode GROUP BY length;";
		$res = mysql_query($req,$c);
		while($row = mysql_fetch_object($res)) {
			$count = $row->count;
			$length = $row->length;
			$counttable[$length] = $count;
			if ($count>$maxcount) $maxcount=$count;
		}
		if ($mode == 0) {
			$ylabel=$strings['plot-Nvisitors'];
			$xlabel=$strings['plot-Visitdurationmn'];
		} else {
			$ylabel=$strings['plot-Nvisitors'];
			$xlabel=$strings['plot-Hitspervisit'] ;
		}
		
		$temp = fopen ("$tmpdirectory/tmp2.$timecall.txt.php", 'w');
		fwrite($temp, "<?php  \n\$date_data = array(");
		$string = "";
		// Do not put it if $i<0, there was a bug when logging
		// We have the plot start at 0 in any case;
		// Add 2 in the end range
		// $start = max(0,$start);	
		$start = 0;
		$end = max($end+1,3);
		for ($i=$start;$i<=$end;$i++) {
			if (isset($counttable[$i])) { $count = $counttable[$i]; } else { $count = 0; }
			$string .= "array(\"\",$i,$count)";
 			fwrite($temp, "$string");
			$string = "\n,";
		}
		fwrite($temp, "\n);\n\$start=$start;\n\$end=$end;\n\$maxY=$maxcount;\n\$plottype=\"lines\";");
		fwrite($temp, "\n\$yscale=\"qdrt\";\n\$notime = TRUE;");
		fwrite($temp, "\n\$width = 600;");
		fwrite($temp, "\n\$height = 200;");
		fwrite($temp, "\n\$ylabel = \"$ylabel\";");
		fwrite($temp, "\n\$xlabel = \"$xlabel\";");
		fwrite($temp, "\n?>");
		fclose($temp);
	} else {
		$maxcount=0;
	}
	if ($mode==0) {
		$title = $strings['Visitdurationsinminutes'];
	} else {
		$title = $strings['Numberofhitspervisitor'];
	}
	if ($maxcount>0) {
		echo "<table class='stat'>
<tr class='title'><td>$title</td></tr>
<tr><td><center><img src='./plotStat.php?file=tmp2.$timecall.txt.php' alt='$title'></center></tr>
</table>";
	} else {
		echo "<table class='stat'>
<tr class='title'><td>$title</td></tr>
<tr><td><center>&nbsp;<br>".$strings['Nothingyet']."<BR>&nbsp;</center></tr>
</table>";
	}
}

?>
