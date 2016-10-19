<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Include: Displays the footer
 * 
 */
$sql = 'SELECT (SELECT Count(*) from pages where `published`=1) as pubCNT, (SELECT Count(*) from pages where `published`=0) as unCNT';
$rs = mysql_query($sql) or die("Unable to Details for Web Page");
$row      = mysql_fetch_array($rs);
$pubCNT   = $row['pubCNT'];
$unpubCNT = $row['unCNT'];
$totCNT   = $pubCNT+$unpubCNT;
mysql_free_result($rs);
?>
<div class="clearfix"></div>
<div id="footer">
  <div class="container">
    <div class="row-fluid" style="text-align:center; font-size:0.9em; ">
      <div class="span3"><a target="_blank" href="http://www.hmi-tech.net/">&copy; HMI Technologies</a> 
	  </div>
      <div class="span6"> 
	  	<span class="label label-info">Published: <?php echo $pubCNT; ?> page(s)</span> &middot; 
		<span class="label label-warning">Drafts: <?php echo $unpubCNT; ?> page(s)</span> &middot; 
		<span class="label label-inverse">Total: <?php echo $totCNT; ?> pages</span> 
	  </div>
      <div class="span3"> ezCMS Ver:<strong>4.161005</strong> </div>
    </div>
  </div>
</div>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.treeview/jquery.treeview.js"></script>
<script type="text/javascript">
	$('.tooltipme2').tooltip();
	$("#left-tree").treeview({
		collapsed: true,
		animated: "medium",
		unique: true
	});	
	$('#divCmTheme').click(function (e) {
		e.stopPropagation();
	});
	$('#slCmTheme').val('<?php echo $_SESSION["CMTHEME"]; ?>').change(function (e) {
		location.href = "scripts/chg-editor-theme.php?theme="+$(this).val();
	});
	$('#showrevs').click(function () {
		$('#revBlock').slideToggle();
		return false;
	});	
	
</script>