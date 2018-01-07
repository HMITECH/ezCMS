<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * INSTALLER : ezCMS Users Class 
 * 
 */

// if (file_exists('config.php')) die('FATAL : config.php exists. Delete before running this installer.');

 
?><!DOCTYPE html><html lang="en"><head>

	<title>ezCMS Installer</title>
	<meta charset="utf-8">
	<meta name="author" content="mo.ahmed@hmi-tech.net">
	<meta name="robots" content="noindex, nofollow">
	<link type="image/x-icon" href="login/favicon.ico" rel="icon"/>
	<link type="image/x-icon" href="login/favicon.ico" rel="shortcut icon"/>
	<link href="login/css/bootstrap.min.css" rel="stylesheet">
	<link href="login/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="login/css/custom.css" rel="stylesheet">

</head><body>
  
<div id="wrap">
	
	<div class="navbar navbar-inverse navbar-fixed-top text-center">
	  <div class="navbar-inner"><a class="brand" href="#" style="width:100%">ezCMS : INSTALLER</a></div>
	</div>
	
	<div class="container row-fluid">
	
		<div id="infoBox" class="white-boxed">
			<h1>PREINSTALL NOTICE</h1>
			Running this installer will wipe out your database and site.
		</div>
		
		<div id="dbCredsBox" class="white-boxed">
			<h1>DATABASE CREDS</h1>
			Provide the database creds here 
		</div>
		
		<div id="adminUsrBox" class="white-boxed">
			<h1>ADMIN USER DETAILS</h1>
			Provide the admin user creds here 
		</div>
		
		<div id="doneBox" class="white-boxed">
			<h1>INSTALL RESULTS</h1>
			ezCMS has been installed .. or error show messages.
		</div>
	
	</div> 
	
	
	
</div>
	
<div id="footer">
  <div class="row">
      <div class="span6"><a target="_blank" href="http://www.hmi-tech.net/">&copy; HMI Technologies</a> </div>
      <div class="span6 text-right"> ezCMS Installer Version:<strong>1.0</strong> </div>
  </div>
</div>

</body></html>