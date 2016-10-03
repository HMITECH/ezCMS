<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *View: Displays the visitor traffic of the site
 * 
 */
require_once("include/init.php");
?><!DOCTYPE html><html lang="en"><head>

	<title>Traffic &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	
</head><body>
  
	<div id="wrap">
		<?php include('include/nav.php'); ?>  
		<div class="container" style="margin-bottom:40px ">
			<div class="white-boxed" style="margin:60px auto 10px; width:95%;">
				<iframe id="shrFrm" src="traffic/index.php?mode=stats&sid=39547&lang=en"
            		width='100%' height='500px' frameborder='0' marginheight='0' marginwidth='0' scrolling="auto"></iframe>
			</div>
		</div>
	</div>
	
<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(11)").addClass('active');
	$("#top-bar li:eq(12)").addClass('active');
</script>
</body></html>