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
if (file_exists('config.php')) 
	die('FATAL : config.php exists. Delete before running this installer.');
	
// The SQL must exisit.
if (!file_exists('login/_sql/ezcms.5.sql'))
	 die('FATAL : login/_sql/ezcms.5.sql missing. Check repo for this file.');
	 
// INTSALL WHEN POSTED 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Get all the posted variables...
	if (isset($_POST['db_host'])) $db_host = $_POST['db_host']; else die('Invalid Request');
	if (isset($_POST['db_name'])) $db_name = $_POST['db_name']; else die('Invalid Request');
	if (isset($_POST['db_user'])) $db_user = $_POST['db_user']; else die('Invalid Request');
	if (isset($_POST['db_pass'])) $db_pass = $_POST['db_pass']; else die('Invalid Request');
	if (isset($_POST['user_name'])) $user_name = $_POST['user_name']; else die('Invalid Request');
	if (isset($_POST['user_email'])) $user_email = $_POST['user_email']; else die('Invalid Request');
	if (isset($_POST['user_pass'])) $user_pass = $_POST['user_pass']; else die('Invalid Request');
	if (isset($_POST['user_pass1'])) $user_pass1 = $_POST['user_pass1']; else die('Invalid Request');

	// TEST db conn
	try {
		$db = @new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
	} catch(PDOException $e) {
		die('dbfailed');
	}
	
	// User Name: must be 2 to 255 chars
	$s = strlen($user_name); if ( ($s < 2) || ($s > 255) ) die('User Name is too short');
	// email must be valid
	if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)) die('Invalid Request - Email Check Failed');
	// pass cannot be less than 8
	$s = strlen($user_pass); if ( ($s < 8) || ($s > 255) ) die('Invalid Request - Password Length Failed');
	if ($user_pass != $user_pass1) die('Confirm password does not match.');
	
	// Create the tables 
	if (!$db->exec(file_get_contents('login/_sql/ezcms.5.sql'))) die('Failed to create tables. Run SQL');
	
	// Update the admin info ...
	$stmt = $db->prepare("UPDATE `users` SET `username` = ?, `email` = ?, `passwd` = ? WHERE id = 1");
	if (!$stmt->execute(array($user_name, $user_email, $user_pass))) die('updatefailed');
	
	// Create a config file 
	$conf = "<?php return array('dbHost'=>'$db_host', 'dbUser'=>'$db_user', 'dbPass'=>'$db_pass', 'dbName'=>'$db_name');?>";
	if (file_put_contents("config.php", $conf ) === false) die('configfailed');
	
	
	// say all done !
	die('ezCMS Installed!');
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
	input {width: 100%; max-width:200px;}
	div.white-boxed {max-width: 1000px; margin: 0 auto; padding:20px;}
	.label {font-size: 1em; padding: 6px 20px;}
	.brand {width:100%; cursor:default;}
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
			<p class="label label-important">CLOSE THIS WINDOW IF YOU DO NOT WANT TO PROCEED</p>
		</div>
		
		<div class="white-boxed"><form class="form-horizontal" method="post">
			<div class="row-fluid">
				<div class="span6 well">
					<h4>DATABASE DETAILS</h4>
					<p>Please enter your database information:</p>
					<div class="control-group">
						<label class="control-label">Database host</label>
						<div class="controls"><input type="text" id="db_host" name="db_host" placeholder="db host" minlength="4" required/></div>
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
						<div class="controls"><input type="password" name="db_pass" placeholder="db password"/></div>
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
						<div class="controls"><input type="email" name="user_email" placeholder="user email" required/></div>
					</div>
					<div class="control-group">
						<label class="control-label">User password</label>
						<div class="controls"><input type="password" id="user_pass" name="user_pass" 
							placeholder="user password" minlength="8" required/></div>
					</div>			
					<div class="control-group">
						<label class="control-label">Confirm password</label>
						<div class="controls"><input type="password" id="user_pass1" name="user_pass1" 
							placeholder="user password" minlength="8" required/></div>
					</div>					
					
				</div>
			</div>
			<p class="text-center">
				<button type="submit" class="btn btn-primary">INSTALL ezCMS NOW</button>
				<img src="login/img/ajax-loader.gif" style="display:none;"></p>
		</form></div>
	
	</div><br><br>
	
</div>
	
<div id="footer"><div class="row">
  <div class="span6"><a target="_blank" href="http://www.hmi-tech.net/">&copy; HMI Technologies</a> </div>
  <div class="span6 text-right"> ezCMS Installer Version:<strong>1.0</strong> </div>
</div></div>
<script>(function($) {

	"use strict";
	
	$('form').submit(function (e) {
	
		e.preventDefault();
		
		if ($('#user_pass').val() != $('#user_pass1').val()) {
			alert('The administrator confirm password does not match.');
			$('#user_pass1').focus();
			return false;
		}
		
		// Submit the form via ajax.
		$('form').find('.btn-primary').hide().next().show();
		$.post( 'install.php', $(this).serialize() , function(data) {
		
			if (data == 'dbfailed') {
				alert('Errors:\nConnection to database failed.\nPlease check the database details');
				$('#db_host').focus();
				$('form').find('.btn-primary').show().next().hide();
				return false;
			}
		
			if (data != 'ezCMS Installed!') alert('Errors: '+data);
			else alert(data);
			$('form').find('.btn-primary').show().next().hide();
		
		}).fail( function() { 
			alert('Request Failed!');
			$('form').find('.btn-primary').show().next().hide();
		});	
	
		return false;
	});	
	
	

})(jQuery);</script>
</body></html>