<?php
// Update the information below 

	// Database Connection Setting
	$databaseServer = 'localhost';
	$databaseUser   = 'root';
	$databasePasswd = '';	
	$databaseName   = 'ezsite_db';
	
	
// Do not edit the code below as it is common to the CMS
	// Expire the headers
	header ("Expires: Thu, 17 May 2011 10:17:17 GMT");    			// Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate");  			// HTTP/1.1
	header ("Pragma: no-cache");  	                                // HTTP/1.0	
	// connect to the database	
	@mysql_connect  ($databaseServer,$databaseUser,$databasePasswd) 
		or die("<h1>Site down for maintenance.</h1><p>Please visit us later !</p>");
	@mysql_select_db($databaseName)
		or die("<h1>Site down for maintenance.</h1><p>Please visit us later !</p>");
  
?>
