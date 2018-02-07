<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * Version 2.010413 Dated 20/March/2013
 * Rev: 04-Oct-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 * $Header: /cygdrive/c/cvs/repo/xampp/htdocs/hmi/ezsite/login/users.php,v 1.2 2017-12-02 09:33:28 a Exp $
 * View: Displays the UI for find and replace
 */

// **************** ezCMS USERS CLASS ****************
require_once ("class/find.class.php");

// **************** ezCMS USERS HANDLE ****************
$cms = new ezFind();

?><!DOCTYPE html><html lang="en"><head>

	<title>Global Find Replace : ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
	  <div class="row-fluid">
		<div class="span3 white-boxed">
			This is the form .. 
		</div>
		<div class="span9 white-boxed">
			this is the results of the search 
		</div>
	  </div>
	</div>
	<br><br>
</div><!-- /wrap  -->

<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(10)").addClass('active');
</script>
</body>
</html>
