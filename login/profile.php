<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *View: Displays the users in the site
 * 
 */
require_once("include/init.php");
$msg = "";
if (isset($_REQUEST['Submit'])) {
	
	// check all the variables are posted
	if (isset($_POST['txtcpass'])) $curpass = trim($_POST['txtcpass']); else die('xx');
	if (isset($_POST['txtnpass'])) $newpass = trim($_POST['txtnpass']); else die('xx');
	if (isset($_POST['txtrpass'])) $reppass = trim($_POST['txtrpass']); else die('xx');

	// check password match
	if ($newpass != $reppass) {
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> The new password and repeat password do not match.</div>';
	} elseif (strlen($newpass)<6) {
		// check password len
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> The new password must be more than 6 characters in lenght.</div>';
	} else {
		// check current password
		$id = $_SESSION['USERID'];
		$sql = "SELECT `username` FROM `users` WHERE `id` = $id AND `passwd` = '$curpass' LIMIT 1";
		$rs = mysql_query($sql);
		if (!mysql_num_rows($rs)) {
			$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> Your current password is incorrect.</div>';	
		} else {
			// update
			$sql = "UPDATE `users`SET `passwd` = '$newpass' WHERE `id` = $id ";					
			if (mysql_query($sql)) 
				// updated
				$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Saved!</strong> You have successfully changed your password.</div>';
			else 
				// failed
				$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
							<strong>Failed!</strong> An error occurred and the layout was NOT saved.</div>';
							
		}
	}
}
?><!DOCTYPE html><html lang="en"><head>

	<title>Profile &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	
</head><body>
  
	<div id="wrap">
		<?php include('include/nav.php'); ?>  
		<div class="container">
			<div class="container-fluid" style="margin:60px auto 30px;">
			  <div class="row-fluid">
			  
			    <div class="span3"></div>
				
				<div class="span6 white-boxed">
				
					<blockquote>
					  <p>Change your password</p>
					  <small>Remember to change your password often.</small>
					</blockquote>
					
					<?php echo $msg; ?>
					
					<form id="frmPass" action="" method="post" enctype="multipart/form-data">
					
						<label class="control-label" for="inputTitle">Current Password</label>
						<input type="text" id="txtcpass" name="txtcpass"
							placeholder="Existing password"
							title="Enter your existing password here"
							data-toggle="tooltip" data-placement="top"
							class="input-block-level tooltipme2">
							
						<label class="control-label" for="inputTitle">New Password</label>
						<input type="text" id="txtnpass" name="txtnpass"
							placeholder="New password"
							title="Enter the new password here"
							data-toggle="tooltip" data-placement="top"
							class="input-block-level tooltipme2">
							
						<label class="control-label" for="inputTitle">Repeat New Password</label>
						<input type="text" id="txtrpass" name="txtrpass"
							placeholder="Repeat new password"
							title="Repeat the new password here"
							data-toggle="tooltip" data-placement="top"
							class="input-block-level tooltipme2">							

						<input type="submit" name="Submit" class="btn btn-primary" value="Change password">
					  
					</form>
				</div>
				<div class="span3"></div>
			  </div>
			</div>
		</div> 
	</div>
	
<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
</script>
</body></html>