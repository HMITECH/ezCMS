<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *View: Displays the tracking options for the site
 * 
 */
require_once("include/init.php");

if (isset($_POST['submit'])) {
	if (isset($_POST['mode'])) $mode = $_POST['mode']; else die('xx');
	if ($mode == "addcookie") {
		SetCookie("phpTrafficA","Admin",time()+100000000,"/","",0);
	} elseif  ($mode == "remcookie") {
		SetCookie("phpTrafficA","",time()+100000000,"/","",0);
	} else {
		die('xxx');
	}
	header("Location: options.php");
	exit;
}

// the file paths for tracking
$writelog = $_SERVER["DOCUMENT_ROOT"].$_SERVER["PHP_SELF"]; 
$writelog = str_replace("options.php", "traffic/write_logs.php", $writelog);
$summary = str_replace("write_logs.php", "summary.php", $writelog);
$count = $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]; 
$count = str_replace("index.php", "count.php", $count);
$summaryimg = str_replace("count.php", "imagestats.php", $count);

// the tracking cookie
if (isset($_COOKIE["phpTrafficA"])) $phpTrafficA = $_COOKIE["phpTrafficA"];
else $phpTrafficA = "";


?><!DOCTYPE html><html lang="en"><head>

	<title>Tracking options &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	<style type="text/css">
		h4.includehead {
			margin:20px 0; 
			padding:10px; 
			background:#EFEFEF; 
			border-radius: 5px;
			border:thin solid #aaa;
			box-shadow: 0px 2px 4px rgba(71, 71, 71, 0.5);
		}
		blockquote.includecode {
			background:#fff; 
			border-color:#666666;
			box-shadow: 4px 4px 4px rgba(50, 50, 50, 0.5);
		}
	</style>	
	
</head><body>
  
	<div id="wrap">
		<?php include('include/nav.php'); ?>  
		<div class="container">
			<div class="container-fluid" style="margin:60px auto 30px;">
			  <div class="row-fluid">
				<div class="span3">
				
				  <div class="white-boxed">
					<blockquote>
					  <p>Tracking Cookie</p>
					  <small>If you do not want to be counted in the statistics, SET a cookie to be ignored.</small>
					</blockquote>
					<?php if ($phpTrafficA == "Admin") { ?>
						<p>The <i>cookie</i> is <span class="label label-important">SET</span>, you will NOT be counted in the statistics.</p>
						<form action="" method="post">
							<input type="submit" name="submit" value="Include in Tracking" class="btn btn-danger">
							<input type="hidden" name="mode" value="remcookie">
							<input type="hidden" name="lang" value="en">
						</form>									
					<?php } else { ?>
						<p>The <i>cookie</i> is <span class="label label-info">NOT SET</span>, you will be counted in the statistics.</p>
						<form action="" method="post">
							<input type="submit" name="submit" value="Exclude from Tracking" class="btn btn-primary">
							<input type="hidden" name="mode" value="addcookie">
							<input type="hidden" name="lang" value="en">
						</form>
					<?php } ?>

				  </div>
				  
				  <div class="white-boxed">
					<blockquote>
					  <p><i class="icon-info-sign" title="include js to get screen resolution"></i> Screen Resolution</p>
					  <small>PHP can not record screen resolution. If you are using a php based method then
					  you must include this js file in all the layouts.</small>
					</blockquote>
					<blockquote class="includecode"><tt>
						&lt;script language="JavaScript" type="text/JavaScript" src="/site-assets/js/track.scr.res.js"&gt;&lt;/script&gt;</tt>
					</blockquote>

				  </div>  
				  
				</div>
				<div class="span9 white-boxed">

					<blockquote>
					  <p>Visitor Tracking</p>
					  <small>Given below is a list of tracking options available, read the instructions to use them.</small>
					</blockquote>
					
					<h4 class="includehead">Default No image: <small>(This code is already present at the end of the 
					<a href="controllers.php">CONTROLLER</a>)</small></h4>					
					Uncomment the following lines at the end of the <a href="controllers.php">CONTROLLER</a>: <i class="icon-info-sign" title="include js to get screen resolution"></i>
					<blockquote class="includecode"><tt>
					$sid="39547";<br>include("<?php echo $writelog; ?>");
					</tt></blockquote>
					<hr style="border-width:2px; border-color:#666">
					
					<h4 class="includehead">Dispay Image of Tracking Stats, in PHP</h4>					
					Somewhere in your <a href="layouts.php">LAYOUTS</a> or <a href="controllers.php">CONTROLLER</a>, include the following lines of PHP code:  <i class="icon-info-sign" title="include js to get screen resolution"></i>
					<blockquote class="includecode"><tt>
					$referer = base64_encode($_SERVER["HTTP_REFERER"]);
					<br>$thispage = base64_encode($_SERVER["REQUEST_URI"]);
					<br>$id = "39547";
					<br>$time = time();
					<br>$resolutionTxt = "";
					<br> if (isset($_COOKIE['phpTA_resolution'])) {
					<br>	&nbsp;&nbsp;&nbsp;$resolution = $_COOKIE['phpTA_resolution'];
					<br>	&nbsp;&nbsp;&nbsp;$resolutionTxt = "&amp;amp;res=$resolution";
					<br>}
					<br>echo "&lt;img src=\"<?php echo str_replace('/options.php','',$_SERVER["PHP_SELF"]); ?>/traffic/count.php?sid=$id&amp;amp;p=$thispage&amp;amp;r=$referer&amp;amp;t=$time$resolutionTxt\" alt=\"\"&gt;";
					</tt></blockquote>
					<hr style="border-width:2px; border-color:#666">
					
					<h4 class="includehead">Dispay Image, in javascript</h4>
					 Add the code below to the <a href="layouts.php">LAYOUTS</a> or <a href="pages.php">PAGES</a> to display an image with tracking summary
				  	 <blockquote class="includecode">
						&lt;script language="javascript" type="text/javascript"&gt;<br>
						function encode64(inp){<br>
						&nbsp;&nbsp;&nbsp;var key=&quot;ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=&quot;;<br>
						&nbsp;&nbsp;&nbsp;var chr1,chr2,chr3,enc3,enc4,i=0,out=&quot;&quot;;<br>
						&nbsp;&nbsp;&nbsp;while(i&lt;inp.length){<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;chr1=inp.charCodeAt(i++);if(chr1&gt;127) chr1=88;<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;chr2=inp.charCodeAt(i++);if(chr2&gt;127) chr2=88;<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;chr3=inp.charCodeAt(i++);if(chr3&gt;127) chr3=88;<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(isNaN(chr3)) {enc4=64;chr3=0;} else enc4=chr3&amp;63;<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(isNaN(chr2)) {enc3=64;chr2=0;} else enc3=((chr2&lt;&lt;2)|(chr3&gt;&gt;6))&amp;63;<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;out += key.charAt((chr1&gt;&gt;2)&amp;63) + key.charAt(((chr1&lt;&lt;4)|(chr2&gt;&gt;4))&amp;63) + key.charAt(enc3) + key.charAt(enc4);<br>
						&nbsp;&nbsp;&nbsp;}<br>
						 &nbsp;&nbsp;&nbsp; return encodeURIComponent(out);<br>
						}<br>
						var referer=encode64(document.referrer);<br>
						var thispage=encode64(window.location.pathname+location.search);<br>
						var date=new Date();<br>
						var time=date.getTime();<br>
						var resolution= screen.width + &quot;x&quot; + screen.height;<br>
						document.writeln(&quot;&lt;img src=&quot;<?php echo str_replace('/options.php','',$_SERVER["PHP_SELF"]); ?>/traffic/count.php?sid=39547&amp;p=&quot;+thispage+&quot;&amp;r=&quot;+referer+&quot;&amp;t=&quot;+time+&quot;&amp;res=&quot;+resolution+&quot;&quot; alt=&quot;&quot; border=&quot;0&quot; /&gt;n&quot;);<br>
					  &lt;/script&gt;
					</blockquote>

					<hr style="border-width:2px; border-color:#666">

					<h4 class="includehead">To show a text with summary statistics </h4>
					Add this php code to to your <a href="layouts.php">LAYOUTS</a> display text with tracking summary.
					<blockquote  class="includecode"><tt>
					$sid="39547";<br>include("<?php echo str_replace("write_logs.php", "summary.php", $writelog);?>");
					</tt></blockquote>
					<hr style="border-width:2px; border-color:#666">
					
					<h4 class="includehead">To show an image with summary statistics</h4>
					Add this image tag in the <a href="layouts.php">LAYOUTS</a> or <a href="pages.php">PAGES</a> to display an image with tracking summary.
					<blockquote class="includecode"><tt>&lt;img src="<?php echo str_replace('/options.php','',$_SERVER["PHP_SELF"]); ?>/traffic/imagestats.php?sid=39547" alt="statistics"&gt;</tt></blockquote>
					<hr style="border-width:2px; border-color:#666">

				</div>
			  </div>
			</div>
		</div> 
	</div>
	
<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(11)").addClass('active');
	$("#top-bar li:eq(14)").addClass('active');
</script>
</body></html>