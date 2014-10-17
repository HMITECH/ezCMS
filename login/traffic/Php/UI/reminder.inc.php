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

function reminder() {
global $sites, $phpTrafficA;
global $DEMO;
global $strings;
global $lang;

$writelog = $_SERVER["DOCUMENT_ROOT"].$_SERVER["PHP_SELF"]; 
$writelog = str_replace("index.php", "write_logs.php", $writelog);
$summary = str_replace("write_logs.php", "summary.php", $writelog);
$count = $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]; 
$count = str_replace("index.php", "count.php", $count);
$summaryimg = str_replace("count.php", "imagestats.php", $count);

echo "<div id=\"text\">\n";
while ($bar=each($sites)) {
	$id = $bar[0];
	$table = $bar[1]['table'];
	$site =  $_SERVER['HTTP_HOST'];
	if ($DEMO) {
		echo "<h1>$site</h1>
".$strings['noimagephp']."
<blockquote>".$strings['notindemo']."</blockquote>
".$strings['imagephp']."
<blockquote>".$strings['notindemo']."</blockquote>
".$strings['imagenophp']."
<blockquote>
&lt;script language=\"javascript\" type=\"text/javascript\"&gt;<br>
&lt;!-- <br>
stats(!*#*);<br>
//--&gt;&lt;/script&gt;
</blockquote>
".$strings['filetodownload'].": ".$strings['notindemo']."
<br>&nbsp;<br><b>".$strings['summaryphp']."</b>
<blockquote>".$strings['notindemo']."</blockquote>
<b>".$strings['summaryimg']."</b>
<blockquote>".$strings['notindemo']."</blockquote>\n";
	} else {
		echo "<h1>$site</h1>
".$strings['noimagephp']."
<blockquote><tt>
\$sid=\"$id\";<br>include(\"$writelog\");
</tt></blockquote>
".$strings['needJScookie']."
<blockquote><tt>&lt;script language=\"JavaScript\" type=\"text/JavaScript\" src=\"phpTACookie.js\">&lt;/script></tt></blockquote>
".$strings['filetodownload'].": <a class=\"basic\" href=\"./showJS2.php\" target=\"_new\">phpTACookie.js</a><br>&nbsp;<br>\n
".$strings['imagephp']."
<blockquote><tt>
\$referer = base64_encode(\$_SERVER[\"HTTP_REFERER\"]);
<br>\$thispage = base64_encode(\$_SERVER[\"REQUEST_URI\"]);
<br>\$id = \"$id\";
<br>\$time = time();
<br>\$resolutionTxt = \"\";
<br> if (isset(\$_COOKIE['phpTA_resolution'])) {
<br>\t	\$resolution = \$_COOKIE['phpTA_resolution'];
<br>\t	\$resolutionTxt = \"&#038;amp;res=\$resolution\";
<br>}
<br>echo \"&lt;img src=\\\"http://$count?sid=\$id&#038;amp;p=\$thispage&#038;amp;r=\$referer&#038;amp;t=\$time\$resolutionTxt\\\" alt=\\\"\\\"&gt;\";
</tt></blockquote>
".$strings['needJScookie']."
<blockquote><tt>&lt;script language=\"JavaScript\" type=\"text/JavaScript\" src=\"phpTACookie.js\">&lt;/script></tt></blockquote> 
".$strings['filetodownload'].": <a class=\"basic\" href=\"./showJS2.php\" target=\"_new\">phpTACookie.js</a><br>&nbsp;<br>\n
".$strings['imagenophp']."
<blockquote><tt>
&lt;script language=\"javascript\" type=\"text/javascript\"&gt;<br>
&lt;!-- <br>
stats($id);<br>
//--&gt;&lt;/script&gt;
</tt></blockquote>
".$strings['filetodownload'].": <a class=\"basic\" href=\"./showJS.php\" target=\"_new\">stats.js</a>\n
<br>&nbsp;<br><b>".$strings['summaryphp']."</b>:<br>
<blockquote><tt>
\$sid=\"$id\";<br>include(\"$summary\");
</tt></blockquote>
<b>".$strings['summaryimg']."</b>:<br>
<blockquote><tt>&lt;img src=\"http://$summaryimg?sid=$id\" alt=\"statistics\"&gt;</tt></blockquote>
";
	}
}
echo "</div>\n";
}
?>