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
require_once("include/users.functions.php");

if (isset($_REQUEST["id"])) $id =  $_REQUEST["id"] ; else $id = 1; 
$username = '';
$email = '';
$active = '';
$viewstats = '';
$edituser = '';
$deluser = '';
$editpage = '';
$delpage = '';
$editsettings = '';
$editcontroller = '';
$editlayout = '';
$editcss = '';
$editjs = '';

if (isset($_REQUEST['Submit'])) {
	
	if (!$_SESSION['edituser']) {header("Location: users.php?id=$id&flg=noperms");exit;}	// permission denied

	$username = $_REQUEST['txtusername'];
	$email = $_REQUEST['txtemail'];		
	$pass = $_REQUEST['txtpsswd'];
	
	if(isset($_REQUEST['ckactive'])) $active='checked'; else $active= '';
	if(isset($_REQUEST['ckviewstats'])) $viewstats='checked'; else $viewstats= '';
	if(isset($_REQUEST['ckedituser'])) $edituser='checked'; else $edituser= '';
	if(isset($_REQUEST['ckdeluser'])) $deluser='checked'; else $deluser= '';
	if(isset($_REQUEST['ckeditpage'])) $editpage='checked'; else $editpage= '';
	if(isset($_REQUEST['ckdelpage'])) $delpage='checked'; else $delpage= '';
	if(isset($_REQUEST['ckeditsettings' ])) $editsettings  ='checked'; else $editsettings = '';
	if(isset($_REQUEST['ckeditcontroller' ])) $editcontroller  ='checked'; else $editcontroller = '';
	if(isset($_REQUEST['ckeditlayout' ])) $editlayout  ='checked'; else $editlayout = '';
	if(isset($_REQUEST['ckeditcss' ])) $editcss  ='checked'; else $editcss = '';
	if(isset($_REQUEST['ckeditjs' ])) $editjs  ='checked'; else $editjs = '';
	
	if (strlen(trim($username)) < 4 ) $_GET["flg"] = 'noname';
	elseif (strlen(trim($email)) < 4 ) $_GET["flg"] = 'noemail';	
	elseif ((strlen(trim($pass)) < 4 ) && ($id == 'new')) $_GET["flg"] = 'nopass';						
			
	else {	
		if ($active=='checked') $active=1; else $active= 0;
		if ($viewstats=='checked') $viewstats=1; else $viewstats= 0;
		if ($edituser=='checked') $edituser=1; else $edituser= 0;
		if ($deluser=='checked') $deluser=1; else $deluser= 0;
		if ($editpage=='checked') $editpage=1; else $editpage= 0;
		if ($delpage=='checked') $delpage=1; else $delpage= 0;
		if ($editsettings=='checked') $editsettings=1; else $editsettings= 0;
		if ($editcontroller=='checked') $editcontroller=1; else $editcontroller= 0;
		if ($editlayout=='checked') $editlayout=1; else $editlayout= 0;
		if ($editcss=='checked') $editcss=1; else $editcss= 0;
		if ($editjs=='checked') $editjs=1; else $editjs= 0;
		if ($id == 'new') {
			// add new user here !
			$qry  = '';
			$qry .= "INSERT INTO `users` ( ";
			$qry .= "`username`, `email`, `passwd`,`active`, `viewstats`, ";
			$qry .= "`edituser`, `deluser`, `editpage`, `delpage`, ";			
			$qry .= "`editsettings`, `editcont`, `editlayout`, `editcss` , `editjs`) VALUES ( ";
			$qry .= "'" . $username. "', ";
			$qry .= "'" . $email. "', ";
			$qry .= "'" . $pass. "', ";
			$qry .= "'" . $active. "', ";
			$qry .= "'" . $viewstats. "', ";
			$qry .= "'" . $edituser. "', "; 
			$qry .= "'" . $deluser. "', "; 
			$qry .= "'" . $editpage. "', "; 
			$qry .= "'" . $delpage. "', "; 
			$qry .= "'" . $editsettings. "', "; 
			$qry .= "'" . $editcontroller. "', "; 
			$qry .= "'" . $editlayout. "', "; 
			$qry .= "'" . $editcss. "', ";			
			$qry .= "'" . $editjs. "');";
			//die($qry);
			if (mysql_query($qry)) {
				$id = mysql_insert_id();
				header("Location: ?id=".$id."&flg=added");	// added
				exit; 
			} else $_GET["flg"] = 'pink';

		} else {
			// update user here !
			$qry  = '';
			$qry .= "UPDATE `users` SET ";
			$qry .= "`username`= '" . $username. "', ";
			$qry .= "`email`= '" . $email. "', ";
			if ( $_REQUEST['txtpsswd'] != '' )
				$qry .= "`passwd` = '" .$pass. "', ";
			$qry .= "`active`= '" . $active. "', ";
			$qry .= "`viewstats`= '" . $viewstats. "', ";
			$qry .= "`edituser`= '" . $edituser. "', ";
			$qry .= "`deluser`= '" . $deluser. "', ";
			$qry .= "`editpage`= '" . $editpage. "', ";
			$qry .= "`delpage`= '" . $delpage. "', ";
			$qry .= "`editsettings`= '" . $editsettings. "', ";
			$qry .= "`editcont`= '" . $editcontroller. "', ";
			$qry .= "`editlayout`= '" . $editlayout. "', ";
			$qry .= "`editcss`= '" . $editcss. "', ";
			$qry .= "`editjs`= '" . $editjs. "' ";

			$qry .= "WHERE `id` =" . $id . " LIMIT 1 ;";	
			//echo $qry; exit;						
			if (mysql_query($qry)) 
				header("Location: users.php?id=".$id."&flg=green");	// updated
			else 
				header("Location: users.php?id=".$id."&flg=red");	// failed
			exit;			
		}	
	}
}

if ($id <> 'new') {
	$qry = "SELECT * FROM `users` WHERE `id` = " . $id;		
	$rs = mysql_query($qry);
	if (!mysql_num_rows($rs)) 
		header("Location: users.php?show=&flg=yell");
	$arr = mysql_fetch_array($rs);
	$username= $arr["username"];
	$email= $arr["email"];
	if ($arr["active"] == 1) $active= "checked";
	if ($arr["viewstats"] == 1) $viewstats= "checked";
	if ($arr["edituser"] == 1) $edituser= "checked";
	if ($arr["deluser"] == 1) $deluser= "checked";
	if ($arr["editpage"] == 1) $editpage= "checked";
	if ($arr["editpage"] == 1) $editpage= "checked";
	if ($arr["delpage"] == 1) $delpage= "checked";
	if ($arr["editsettings"] == 1) $editsettings= "checked";
	if ($arr["editcont"] == 1) $editcontroller= "checked";
	if ($arr["editlayout"] == 1) $editlayout= "checked";
	if ($arr["editcss"] == 1) $editcss= "checked";
	if ($arr["editjs"] == 1) $editjs= "checked";
	mysql_free_result($rs);
} else {
	if (!$_SESSION['edituser']) {header("Location: users.php?flg=noperms");exit;}	// permission denied
}
if (isset($_GET["flg"])) $msg = getErrorMsg($_GET["flg"]); else $msg = "";

?><!DOCTYPE html><html lang="en"><head>

	<title>Users &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	
</head><body>
  
	<div id="wrap">
		<?php include('include/nav.php'); ?>  
		<div class="container">
			<div class="container-fluid" style="margin:60px auto 30px;">
			  <div class="row-fluid">
				<div class="span3 white-boxed"><?php getUserTreeHTML($id); ?></div>
				<div class="span9 white-boxed">
					<form id="frmUser" action="" method="post" enctype="multipart/form-data" class="form-horizontal"> 
					  <div class="navbar">
							<div class="navbar-inner">
								<input type="submit" name="Submit" class="btn btn-primary" style="padding:5px 12px;"
									value="<?php if ($id == 'new') echo 'Add New'; else echo 'Save Changes';?>"> 
								
								<?php 
									if ($id != 'new') echo 
										'<a href="?id=new" class="btn btn-info">New User</a> 
										<a id="showlog" href="#" class="btn btn-secondary">Action Log</a>';
									if (($id != 'new') && ($id != 1)) echo '<a href="scripts/del-user.php?delid=' . $id . 
										'" onclick="return confirm(\'Confirm Delete ?\');" class="btn btn-danger">Delete</a>'; 
								?>
							</div>
						</div>
							
						<?php echo $msg; ?>
						
						<div class="row" style="margin-left:0">
							<div class="span4">
								<label for="inputName">User Name</label>
								<input type="text" id="txtusername" name="txtusername"
									placeholder="Enter the full name"
									title="Enter the full name of the user here."
									data-toggle="tooltip" 
									value="<?php echo $username; ?>"
									data-placement="top"
									class="input-block-level tooltipme2">
							</div>
							<div class="span4">
								<label for="inputEmail">Email Address</label>
								<input type="text" id="txtemail" name="txtemail"
									placeholder="Enter the full email address"
									title="Enter the full email address of the user here."
									data-toggle="tooltip" 
									value="<?php echo $email; ?>"
									data-placement="top"
									class="input-block-level tooltipme2">
							</div>
							<div class="span4">
								<label for="txtpsswd">Password</label>
								<input type="text" id="txtpsswd" name="txtpsswd"
									placeholder="<?php if ($id=='new') echo 'Enter the password'; else echo 'Leave blank to keep unchanged';?>"
									title="<?php if ($id=='new') echo 'Enter the password here'; else echo 'Enter a new password or leave blank to keep unchanged';?>"
									data-toggle="tooltip" 
									data-placement="top"
									class="input-block-level tooltipme2">							
							</div>																	
						</div>	
						<h4 style="margin:20px 0; padding:10px; background-color:#EFEFEF; border-radius: 5px;">
							User Rights <small>(User must Logout and login again for changes to take effect)</small></h4>

						<div class="row" style="margin-left:0">
							<div class="span4">
								<label class="checkbox">
									<input name="ckactive" type="checkbox" id="ckactive"
										value="checkbox" <?php echo $active; ?>>
									Active</label>
								<?php if ($active == "checked") echo 
										'<span class="label label-info">User is Active.</span>';
									else echo 
										'<span class="label label-important">Inactive user cannot login.</span>';?>									
								<br><br>
								<label class="checkbox">
									<input name="ckviewstats" type="checkbox" id="ckviewstats" value="checkbox" <?php echo $viewstats; ?>>
									Visitor Tracking</label>
								<?php if ($viewstats == "checked") echo 
										'<span class="label label-info">Visitor tracking available.</span>';
									else echo 
										'<span class="label label-important">Visitor tracking blocked.</span>';?>
								<hr>
								<label class="checkbox">
									<input name="ckeditpage" type="checkbox" id="ckeditpage" value="checkbox" <?php echo $editpage; ?>>
									Manage Pages</label>
								<?php if ($editpage == "checked") echo 
										'<span class="label label-info">Page management available.</span>';
									else echo 
										'<span class="label label-important">Page management blocked.</span>';?>			
								<br><br>
								<label class="checkbox">
									<input name="ckdelpage" type="checkbox" id="ckdelpage" value="checkbox" <?php echo $delpage; ?>>
									Delete Pages</label>
								<?php if ($delpage == "checked") echo 
										'<span class="label label-info">Page delete available.</span>';
									else echo 
										'<span class="label label-important">Page delete blocked.</span>';?>
								<hr>
							</div>
							<div class="span4">
								<label class="checkbox">
									<input name="ckedituser" type="checkbox" id="ckedituser" value="checkbox" <?php echo $edituser; ?>>
									Manage Users</label>
								<?php if ($edituser == "checked") echo 
										'<span class="label label-info">User can manage other users.</span>';
									else echo 
										'<span class="label label-important">User cannot manage other users.</span>';?>	
								<br><br>
								<label class="checkbox">
									<input name="ckdeluser" type="checkbox" id="ckdeluser" value="checkbox" <?php echo $deluser; ?>>
									Delete Users</label>
								<?php if ($deluser == "checked") echo 
										'<span class="label label-info">User can delete other users.</span>';
									else echo 
										'<span class="label label-important">User cannot delete other users.</span>';?>
								<hr>							
								<label class="checkbox">
									<input name="ckeditsettings" type="checkbox" id="ckusemailer" value="checkbox" <?php echo $editsettings; ?>>
									Manage Settings</label>
								<?php if ($editsettings == "checked") echo 
										'<span class="label label-info">Template Settings management available.</span>';
									else echo 
										'<span class="label label-important">Template Settings management blocked.</span>';?>
								<br><br>
								<label class="checkbox">
									<input name="ckeditcontroller" type="checkbox" id="ckeditcontroller" value="checkbox" <?php echo $editcontroller; ?>>
									Manage Controller</label>
								<?php if ($editcontroller == "checked") echo 
										'<span class="label label-info">Template Controller management available.</span>';
									else echo 
										'<span class="label label-important">Template Controller management blocked.</span>';?>
								<hr>

							</div>
							<div class="span4">
								<label class="checkbox">
									<input name="ckeditlayout" type="checkbox" id="ckeditlayout" value="checkbox" <?php echo $editlayout; ?>>
									Manage Layouts</label>
								<?php if ($editlayout == "checked") echo 
										'<span class="label label-info">Template Layout management available.</span>';
									else echo 
										'<span class="label label-important">Template Layout management blocked.</span>';?>
								<br><br>
								<label class="checkbox">
									<input name="ckeditcss" type="checkbox" id="ckeditcss" value="checkbox" <?php echo $editcss; ?>>
									Manage Styles</label>
								<?php if ($editcss == "checked") echo 
										'<span class="label label-info">Template Stylesheet management available.</span>';
									else echo 
										'<span class="label label-important">Template Stylesheet management blocked.</span>';?>
								<br><br>
								<label class="checkbox">
									<input name="ckeditjs" type="checkbox" id="ckeditjs" value="checkbox" <?php echo $editjs; ?>>
									Manage Javascripts</label>
								<?php if ($editjs == "checked") echo 
										'<span class="label label-info">Template Javascript management available.</span>';
									else echo 
										'<span class="label label-important">Template Javascript management blocked.</span>';?>							
								<hr>
							</div>
						</div>
				    </form>
				</div>
			  </div>
			</div>
		</div> 
	</div>
	
<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(10)").addClass('active');
</script>
</body></html>