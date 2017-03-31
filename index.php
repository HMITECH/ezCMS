<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.0.0 Dated 23-Dec-2012
 * HMI Technologies Mumbai (2012-13)
 *
 * Module: Front-end Controller - index.php (controller.php)
 * Renders all the pages in the site builder.
 */

// **************** REDIRECT TO WWW ****************
// Redirect visitors to www url.
	// TODO: Uncomment the lines below if you want to always redirect to www
	//if (!preg_match('/www\..*?/', $_SERVER['HTTP_HOST'])) {
	//    @header("location: http://www." . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	//}

// **************** DATABASE ****************
// Connect to the database
require_once ("config.php");

// **************** REQUESTED URL ****************
// Get the URL of the requested page
if(isset($_REQUEST["show"])) {
	// URL is requested, serve requested page
    $show = '/'.$_REQUEST["show"].'.html';
} else {
	// NO URL requested, serve home page
    $show = '/';
}

// **************** SITE DETAILS ****************
// Get the site details from the database
$rs = mysql_query('SELECT * FROM site WHERE id = 1 LIMIT 1')
    or die("Failed to read the site details from the database !");
$arr = mysql_fetch_array($rs);
$title       = $arr["title"        ];
$keywords    = $arr["keywords"     ];
$description = $arr["description"  ];
$sidebar     = $arr["sidecontent"  ];
$siderbar    = $arr["sidercontent" ];  
$header      = $arr["headercontent"];  
$footer      = $arr["footercontent"];  
$apptitle = false;
$appkey   = false;
$appdesc  = false;
if ($arr["appendtitle"]) $apptitle = true;
if ($arr["appendkey"  ]) $appkey   = true;
if ($arr["appenddesc" ]) $appdesc  = true;

// **************** PAGE DETAILS ****************
// Get page details from the database
$sql = "SELECT * FROM `pages` WHERE `url`='$show' LIMIT 1";
$rs = mysql_query($sql) or die("Unable to read Details for Page");

// If page is not found in the database, serve 404 page with id 2
if (mysql_num_rows($rs)<1) $rs = mysql_query("SELECT * FROM pages WHERE id = 2 LIMIT 1");
$arr = mysql_fetch_array($rs);

// Check if page is published or not.
if (!$arr["published"]) {
	session_start();	
	if (!isset($_SESSION['LOGGEDIN'])) $_SESSION['LOGGEDIN'] = false;
	
	// Check if Admin is logged in
	if (!$_SESSION['LOGGEDIN']) { 
	
		// If Admin is not logged in then serve 404 page.
		$rs = mysql_query("SELECT * FROM pages WHERE id = 2 LIMIT 1");
		$arr = mysql_fetch_array($rs);
		
	}
}

// Set the id of the page
$id = $arr["id"];

// Server 404 headers if page is not found
if ($id==2) Header("HTTP/1.0 404 Not Found");
if ($apptitle) $title = $arr["title"].' : '. $title; else $title = $arr["title"];
if ($appkey) $keywords .= "," . $arr["keywords"]; else $keywords = $arr["keywords"];
if ($appdesc) $description .= ". " . $arr["description"]; else $description = $arr["description"];
$maincontent = $arr["maincontent"];
$head = $arr["head"];
if ($arr["useheader"] == 1) $header = $arr["headercontent"];
if ($arr["useside"] == 1) $sidebar = $arr["sidecontent"]; 
if ($arr["usesider"] == 1) $siderbar = $arr["sidercontent"]; 
if ($arr["usefooter"] == 1) $footer = $arr["footercontent"];
mysql_free_result($rs);

// **************** PAGE LAYOUT ****************
// Determine the layout to be used for this page
$layoutFilename = 'layout.php';
if (strlen($arr['layout'])>4) 

	// Check if layout file exisits.
	if (file_exists($arr['layout'])) 
		$layoutFilename = $arr['layout'];

// **************** CANOMICAL LINK ****************
// Get the canomical link for this page
	// TODO: Remember to replace $_SERVER['HTTP_HOST']
	//       with actual site url like 'www.hmi-tech.net'
	//	     $canonical = 'www.example.com' . $arr["url"];
$canonical = $_SERVER['HTTP_HOST'] . $arr["url"];

// Serve the selected layout file
include($layoutFilename);

// **************** VISITOR TRACKING ****************
// Include the visitor Tracking Code
	// TODO: Uncomment the tracking code below to enable tracking
	//       Replace login with ezCMS folder name 
	//       $sid="39547";
	//       @include($_SERVER["DOCUMENT_ROOT"] . "/login/traffic/write_logs.php"); 
?>
