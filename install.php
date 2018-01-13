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

</head><body>
  
<div id="wrap">
	
	<div class="navbar navbar-inverse navbar-fixed-top text-center">
	  <div class="navbar-inner"><a class="brand" href="#" style="width:100%; cursor:default;">ezCMS : INSTALLER</a></div>
	</div>
	
	<div class="container row-fluid">
	
		<div id="infoBox" class="white-boxed">
			<h3>ezCMS PREINSTALL NOTICE</h3>
			<p>Welcome to ezCMS installer.</p>
			<p>Please note that this installer is only for new sites.<br>
				It will install a fresh database and connect it to ezCMS.</p>
			<p>Close this window if you do not want to proceed.</p>
			<div class="control-group">
				<div class="controls">
				  <button type="submit" class="btn btn-primary">Proceed to Installer</button>
				</div>
			</div>
			
		</div>
		
		<div id="dbCredsBox" class="white-boxed">
			<h3>DATABASE CREDS</h3>
			<form id="db_config" class="form-horizontal" method="post">
			  <p>Please enter your database info:</p>
			  <div class="control-group">
				<label class="control-label" for="host">Database host</label>
				<div class="controls">
				  <input type="text" name="db_host" />
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="name">Database name</label>
				<div class="controls">
				  <input type="text" name="db_name" placeholder="db name" />
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="user">Database user</label>
				<div class="controls">
				  <input type="text" name="db_user" placeholder="db user" />
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="pass">Database password</label>
				<div class="controls">
				  <input type="password" name="db_pass" placeholder="db password" />
				</div>
			  </div>
			  <div class="control-group">
				<div class="controls">
				  <button type="submit" class="btn btn-primary">Save configuration</button>
				</div>
			  </div>
			</form>
		</div>
		
		<div id="adminUsrBox" class="white-boxed">
			<h3>ADMIN USER DETAILS</h3>
			<form id="usr_config" class="form-horizontal" method="post">
			  <p>Please enter the admin user info:</p>
			  <div class="control-group">
				<label class="control-label" for="name">User Name</label>
				<div class="controls">
				  <input type="text" name="db_name" placeholder="db name" />
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="user">User Email</label>
				<div class="controls">
				  <input type="text" name="db_user" placeholder="db user" />
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="pass">Password</label>
				<div class="controls">
				  <input type="password" name="db_pass" placeholder="db password" />
				</div>
			  </div>
			  <div class="control-group">
				<div class="controls">
				  <button type="submit" class="btn btn-primary">Save configuration</button>
				</div>
			  </div>
			</form>
		</div>
		
		<div id="doneBox" class="white-boxed">
			<h3>INSTALL RESULTS</h3>
			ezCMS has been installed .. or error show messages.
		</div>
	
	</div> 
	
	<br><br>
	
</div>
	
<div id="footer">
  <div class="row">
      <div class="span6"><a target="_blank" href="http://www.hmi-tech.net/">&copy; HMI Technologies</a> </div>
      <div class="span6 text-right"> ezCMS Installer Version:<strong>1.0</strong> </div>
  </div>
</div>

</body></html>