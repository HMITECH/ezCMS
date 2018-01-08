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
<style>
/*	
#left-tree li .over {
  border: 2px dashed #000;
}
*/
</style>
<script type="text/javascript">(function($) {

"use strict";

// Change CMS backgrund color	
var updateBgColor = function (jscolor) {
	 $('body').css('background-color','#' + jscolor );
}


// Open the treeview to selected item
var tSelc = $('#left-tree a.label-info').closest('li');
while ( tSelc.length ) {
	tSelc.addClass('open');
	tSelc = tSelc.parent().closest('li');
}


$('.tooltipme2').tooltip();

// Confirm Delete Action
$('.conf-del').click( function () {
	return confirm('Confirm Delete Action ?');
});

// Create treeview out of Left side UL 
$("#left-tree").treeview({
	collapsed: true,
	animated: "medium",
	unique: true
});

// Change code mirror theme
$('#divCmTheme, #divbgcolor').click(function (e) {
	e.stopPropagation();
});

// Code Mirror Theme Change
$('#slCmTheme')
	.val('<?php if (isset($_SESSION["CMTHEME"])) echo $_SESSION["CMTHEME"]; ?>')
	.change(function (e) {
		location.href = "?theme="+$(this).val();
});

// Show or  the revisions block
$('#showrevs').click(function () {
	$('#revBlock').slideToggle();
	return false;
});

// Stop propagation of drop down events
$('#SaveAsDDM').click(function (e) {
	e.stopPropagation();
});	

// CMS Background color
$('#txtbgcolor').val(localStorage.getItem("cmsBgColor")).change(function () {
	$('body').css('background-color','#'+$(this).val());
	localStorage.setItem("cmsBgColor", $(this).val());
});
$('body').css('background-color','#'+localStorage.getItem("cmsBgColor"));

/*	
	// Drag and drop ... 
	$('#left-tree li ul li').prop('draggable', true);
	$('#left-tree li ul li a').prop('draggable', false);
	
	function handleDragStart(e) {
	  this.style.opacity = '0.7';  // this / e.target is the source node.
	}
	
	[].forEach.call(document.querySelectorAll('#left-tree li ul li'), function(n) {
	  n.addEventListener('dragstart', handleDragStart, false);
	});
	
	function handleDragOver(e) {
	  if (e.preventDefault) {
		e.preventDefault(); // Necessary. Allows us to drop.
	  }
	
	  e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
	
	  return false;
	}
	
	function handleDragEnter(e) {
	  // this / e.target is the current hover target.
	  this.classList.add('over');
	}
	
	function handleDragLeave(e) {
	  this.classList.remove('over');  // this / e.target is previous target element.
	}
	
	function handleDrop(e) {
	  // this / e.target is current target element.
	
	  if (e.stopPropagation) {
		e.stopPropagation(); // stops the browser from redirecting.
	  }
	
	  // See the section on the DataTransfer object.
	
	  return false;
	}
	
	function handleDragEnd(e) {
	  // this/e.target is the source node.
	
	  [].forEach.call(cols, function (col) {
		col.classList.remove('over');
	  });
	}
	
	[].forEach.call(document.querySelectorAll('#left-tree li ul li'), function(n) {
	  n.addEventListener('dragstart', handleDragStart, false);
	  n.addEventListener('dragenter', handleDragEnter, false)
	  n.addEventListener('dragover', handleDragOver, false);
	  n.addEventListener('dragleave', handleDragLeave, false);
	  n.addEventListener('drop', handleDrop, false);
	  n.addEventListener('dragend', handleDragEnd, false);
	});
*/
})(jQuery);</script>