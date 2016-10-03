<?php 
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Include: Contains the functions used by users.php
 * 
 */

// this function will echo the user tree
function getUserTreeHTML($id) {
	$sql = 'select `id` , `username` , `active` from `users` where id<>1 order by id;';		
    $rs = mysql_query($sql) or die("Unable to Execute  Select query");
    echo '<ul id="left-tree">';
	if ($id==1) $myclass = 'class="label label-info"'; else $myclass='';
	echo '<li class="open"><i class="icon-globe"></i> <a '.$myclass.' href="users.php?id=1">Webmaster</a>';
		echo '<ul>';
		while ($row = mysql_fetch_assoc($rs)) {
			echo '<li><i class="icon-user"></i> '  ;
			if ( $row["id"] == $id)  
				echo '<a class="label label-info" href="users.php?id=' . $row["id"] . '"> ' . $row["username"];
			else
				echo '<a href="users.php?id=' . $row["id"] . '"> ' . $row["username"];
				if ($row['active']!=1) echo ' <i class="icon-ban-circle" title="User is not active, cannot login"></i> ';
			echo '</a></li>';
		}
		echo '</ul>';
	echo '</li></ul>';
}

// this function will return the error html if any
function getErrorMsg($flg) {
	$msg = "";

	if ($flg=="red") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Save Failed!</strong> An error occurred and the user was NOT saved.</div>';
	if ($flg=="green")
		$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Saved!</strong> You have successfully saved the page.</div>';	
					
	if ($flg=="pink") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Add Page Failed!</strong> An error occurred and the user was NOT added.</div>';
	if ($flg=="added")
		$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Added!</strong> You have successfully added the user.</div>';			

	if ($flg=="delfailed") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Delete Failed!</strong> An error occurred and the user was NOT deleted.</div>';
	if ($flg=="deleted")
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Deleted!</strong> You have successfully deleted the user.</div>';

	if ($flg=="noname") 
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Invalid User Name!</strong> Please check the user name, lenght must be more that FOUR.</div>';
	if ($flg=="noemail") 
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Invalid Email!</strong> Please check the email, lenght must be more that FOUR.</div>';					
	if ($flg=="nopass") 
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Invalid Password!</strong> Please check the password, lenght must be more that FOUR.</div>';
	if ($flg=="noperms") 
		$msg = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Permission Denied!</strong> You do not have permissions for this action.</div>';

	if ($flg=="yell") 
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Not Found!</strong> You have requested a user which does not exist.</div>';	
					
	return $msg;
} 
?>
