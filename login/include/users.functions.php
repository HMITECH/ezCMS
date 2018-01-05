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


					
	return $msg;
} 
?>
