<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Include: Displays the footer
 * 
 */
// Fetch the site stats
$stats = $cms->query('SELECT COUNT(DISTINCT `url`) as `ispublished` from `pages` where `published`=1')->fetch(PDO::FETCH_ASSOC);
?>
<div class="clearfix"></div>
<div id="footer">
  <div class="container">
    <div class="row-fluid" style=" ">
      <div class="span3"><a target="_blank" href="http://www.hmi-tech.net/">&copy; HMI Technologies</a> 
	  </div>
      <div class="span6"> 
  	    <a href="../sitemap.xml"><strong><?php echo $stats['ispublished']; ?></strong> published page(s)</a>		  
	  </div>
      <div class="span3"> ezCMS Ver:<strong>5.171201</strong> </div>
    </div>
  </div>
</div>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.treeview/jquery.treeview.js"></script>
<script src="js/pass-strength.js"></script>
<script src="js/jscolor.min.js"></script>
<script type="text/javascript">

	var tSelc = $('#left-tree a.label-info').closest('li');
	while ( tSelc.length ) {
		tSelc.addClass('open');
		tSelc = tSelc.parent().closest('li');
	}
	
	$('.tooltipme2').tooltip();

	$("#left-tree").treeview({
		collapsed: true,
		animated: "medium",
		unique: true
	});	
	$('#divCmTheme, #divbgcolor').click(function (e) {
		e.stopPropagation();
	});
	$('#slCmTheme').val('<?php if (isset($_SESSION["CMTHEME"])) echo $_SESSION["CMTHEME"]; ?>').change(function (e) {
		location.href = "?theme="+$(this).val();
	});
	$('#showrevs').click(function () {
		$('#revBlock').slideToggle();
		return false;
	});
	$('.conf-del').click( function () {
		return confirm('Confirm Delete Action ?');
	});

	$('#txtbgcolor').val(localStorage.getItem("cmsBgColor")).change(function () {
		$('body').css('background-color','#'+$(this).val());
		localStorage.setItem("cmsBgColor", $(this).val());
	});
	$('body').css('background-color','#'+localStorage.getItem("cmsBgColor"));

	function updateBgColor(jscolor) {
	    $('body').css('background-color','#' + jscolor );
	}
</script>