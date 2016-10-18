<?php 
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *View: Login page to ezCMS (index.php)
 * 
 */
session_start();
include('../config.php');
if (!isset($_SESSION['LOGGEDIN'])) $_SESSION['LOGGEDIN'] = false;
if ($_SESSION['LOGGEDIN'] == true) { header("Location: pages.php"); exit; }
$userid = "";
if (isset($_GET["userid"])) $userid = $_GET["userid"]; 
if ($userid == '') { if (isset($_SESSION['userid'])) $userid = $_SESSION['userid']; }
$flg = "";
if (isset($_GET["flg"])) $flg = $_GET["flg"];
switch ($flg) {
	case "failed":
		$msg = '<span class="label label-important" style="display:block; margin-bottom:10px;">
					Incorrect email or password</span>';
		break;		
	case "expired":
		$msg = '<span class="label label-warning" style="display:block; margin-bottom:10px;">
					Your Session has Expired</span>';
		break;
	case "logout":
		$msg = '<span class="label label-success" style="display:block; margin-bottom:10px;">
					You have successfully  Logged out</span>';
		break;
		case "inactive":
		$msg = '<span class="label label-important" style="display:block; margin-bottom:10px;">
					Your Status is In Active</span>';
		break;		
	default:
		$msg = '';
} 
?><!DOCTYPE html><html lang="en"><head>

	<title>Login &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	<style type="text/css">    
		.form-signin {
			max-width: 300px;
			padding: 19px 29px 29px;
			margin: 60px auto 10px;
			background-color: rgba(255, 255, 255, 0.85);
			border: 1px solid #000;
			box-shadow: 0 1px 2px rgba(0,0,0,.05);}
		.form-signin .form-signin-heading,
		.form-signin .checkbox {margin-bottom: 10px;}
		.form-signin input[type="text"],
		.form-signin input[type="password"],
		.form-signin select {
			font-size: 16px;
			margin-bottom: 15px;
			padding: 7px 9px;}
		@media (max-width: 767px) {
			.form-signin {
				padding: 10px 20px 20px;
				margin: 10px auto 10px;}      
		}
			
	</style>
	
</head><body>
  
	<div id="wrap">
		
		<div class="navbar navbar-inverse navbar-fixed-top">
		  <div class="navbar-inner">
			  <a class="brand" href="/">ezCMS &middot; <?php echo $_SERVER['HTTP_HOST']; ?></a>
			  <div class="pull-right" style="color: #FFFFFF;margin: 10px 10px 2px 2px;">Your ip <strong><?php echo $_SERVER['REMOTE_ADDR']; ?></strong> is logged for security</div>
			  <div class="clearfix"></div>
		  </div>
		</div>		
		  
		<div class="container">
			
			<form id="frm-login" class="form-signin" method="post" action="scripts/login.php">
				<img src="../site-assets/HMI-logo.png" >
				<h2 class="form-signin-heading">Please sign in</h2>
				<?php echo $msg; ?>
				<input type="text" id="txtemail" name="userid"
					class="input-block-level tooltipme2" 
 					data-toggle="tooltip" 
					value="<?php echo $userid; ?>"
					data-placement="top" 
					title="Enter your full email address here."
					placeholder="Email address">
				<input type="password" id="txtpass" name="passwd"
					class="input-block-level tooltipme2" 
 					data-toggle="tooltip" 
					data-placement="top" 
					title="Enter your password here."					
					placeholder="Password">				
				<button class="btn btn-large btn-primary" type="submit">Sign in</button>
				<p class="pull-right">
					<a id="lnk-restpass" href="#" class="tooltipme2"
						data-toggle="tooltip" 
						data-placement="top" 
						style="display:none;"
						title="Password Lost, recover your password here.">Lost your password?</a><br>
					<a href="/" class="tooltipme2"
						data-toggle="tooltip" 
						data-placement="top" 
						title="Are you lost? Go back to the main site."><< Back to Site</a>
				</p>
				<p class="clearfix"></p>
			</form>	
			
		</div> 
	</div>
	
<?php include('include/footer.php'); ?>
</body></html>