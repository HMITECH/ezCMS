<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * INSTALLER : ezCMS Installer
 * 
 */

// Do not run the installer if the config file is present.
// if (file_exists('config.php')) die('FATAL : config.php exists. Delete before running this installer.');

// Create the tables
function createTables($host, $user, $pass, $base) {
	
	try {
		$db = new PDO("mysql:host=$host;dbname=$base", $user, $pass);
	} catch(PDOException $e) {
		return 'dbfailed';
	}
	
	if ($db->exec(file_get_contents('login/_sql/ezcms.5.sql'))) 
		return 'done';
	return 'sqlFailed';
	
}

 
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
	<script src="login/js/jquery-1.9.1.min.js"></script>
	<style>
	.form-horizontal .control-label { width: 130px; }
	input[type="text"], input[type="password"], input[type="email"] {
		width: 100%; max-width:200px;}
	.navbar-inner .brand {width:100%; cursor:default;}
	</style>

</head><body>
  
<div id="wrap">
	
	<div class="navbar navbar-inverse navbar-fixed-top text-center">
	  <div class="navbar-inner"><a class="brand" href="#">ezCMS : INSTALLER</a></div>
	</div>
	
	<div class="container row">
		<div class="text-center">
			<h2>Welcome to ezCMS Installer.</h2>
			<p>Please note this installer is only for new sites. It will install a fresh database.</p>
			<p class="label label-important">CLOSE THIS WINDOW IF YOU DONNOT WANT TO PROCEED</p>
		</div>
		
		<div class="white-boxed"><form class="form-horizontal" method="post">
			<div class="row-fluid">
				<div class="span6 well">
					<h4>DATABASE DETAILS</h4>
					<p>Please enter your database information:</p>
					<div class="control-group">
						<label class="control-label">Database host</label>
						<div class="controls"><input type="text" name="db_host" placeholder="db host" minlength="4" required/></div>
					</div>
					<div class="control-group">
						<label class="control-label">Database name</label>
						<div class="controls"><input type="text" name="db_name" placeholder="db name" minlength="1" required/></div>
					</div>
					<div class="control-group">
						<label class="control-label">Database user</label>
						<div class="controls"><input type="text" name="db_user" placeholder="db user" minlength="1" required/></div>
					</div>
					<div class="control-group">
						<label class="control-label">Database password</label>
						<div class="controls">
							<input type="password" name="db_pass" placeholder="db password"/>
							&nbsp;<a href="#" id="verifynow" class="btn">VERIFY</a>
						</div>
					</div>
				</div>
				<div class="span6 well">
					<h4>ADMINISTRATOR DETAILS</h4>
					<p>Please enter the administrator information:</p>
					<div class="control-group">
						<label class="control-label">User name</label>
						<div class="controls"><input type="text" name="user_name" placeholder="user name" minlength="2" required/></div>
					</div>
					<div class="control-group">
						<label class="control-label">User email</label>
						<div class="controls"><input type="email" name="user_name" placeholder="user email" required/></div>
					</div>
					<div class="control-group">
						<label class="control-label">User password</label>
						<div class="controls"><input type="password" name="user_pass" placeholder="user password" minlength="8" required/></div>
					</div>			
					<div class="control-group">
						<label class="control-label">Confirm password</label>
						<div class="controls">
							<input type="password" name="user_pass1" placeholder="user password" minlength="8" required/>
							&nbsp;<a href="#" id="checknow" class="btn">CHECK</a>
						</div>
					</div>					
					
				</div>
			</div>
			<p class="text-center"><button type="submit" class="btn btn-primary">INSTALL ezCMS NOW</button></p>
		</form></div>
	
	</div><br><br>
	
</div>
	
<div id="footer"><div class="row">
  <div class="span6"><a target="_blank" href="http://www.hmi-tech.net/">&copy; HMI Technologies</a> </div>
  <div class="span6 text-right"> ezCMS Installer Version:<strong>1.0</strong> </div>
</div></div>
<script>(function($) {

	"use strict";

	$('#verifynow').click(function () {
		alert('Verify db creds!');
		return false;
	});
	
	$('form').submit(function () {
		alert('Install Now!');
		return false;
	});	
	
	

})(jQuery);</script>
</body></html>