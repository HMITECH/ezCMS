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

	<title>Find Replace : ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	<style>
	textarea { height: auto; }
	#frmreplace { display:none; }
	</style>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
	  <div class="row-fluid">
		<div class="span3">
		
		  <div id="frmfind" class="white-boxed"><form method="post" action="#">
			<div class="navbar"><div class="navbar-inner">
				<input type="submit" name="find" class="btn btn-primary pull-left" value="FIND">
				<ul class="nav pull-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-flag"></i>
							WHERE <b class="caret"></b></a>
						<ul id="findinDD" class="dropdown-menu">
							<li><a data-loc="body" href="#"><i class="icon-file"></i> Pages &gt;&gt; Body</a></li>
							<li><a data-loc="head" href="#"><i class="icon-arrow-up"></i> Pages &gt;&gt; Head</a></li>
							<li class="divider"></li>
							<li class="nav-header">Template</li>
							<li><a data-loc="php" href="#"><i class="icon-list-alt"></i> PHP Layouts</a></li>
							<li><a data-loc="css" href="#"><i class="icon-pencil"></i> CSS Stylesheets</a></li>
							<li><a data-loc="js" href="#"><i class="icon-align-left"></i> JS Javascripts</a></li>
						</ul>
					</li>
				</ul>
			</div></div>

			<div class="control-group">
				<label class="control-label">FIND TEXT</label>
				<div class="controls">
					<textarea name="find" id="txtfind" rows="5"
						placeholder="Enter the text or code to find"
						class="input-block-level" required minlength="3"></textarea>
				</div>
			</div>
			<input type="hidden" name="findinTxt" id="findinTxt" />
		  </form></div><br>

		  <div id="frmreplace" class="white-boxed"><form method="post" action="#">
			<div class="navbar"><div class="navbar-inner">
				<a id="repall" href="#" class="btn btn-danger">Replace</a>
			</div></div>
			<div class="control-group">
				<label class="control-label">REPLACE WITH</label>
				<div class="controls">
					<textarea name="replace" id="txtreplace" rows="5"
						placeholder="Enter the text or code to replace"
						class="input-block-level"></textarea>
				</div>
			</div>			
		  </form></div>
			
		</div>
		<div class="span9 white-boxed">
			<div class="alert alert-info"><h4 id="findinlbl"></h4></div>
			<table id="resultsTable" class="table table-striped"><thead>
			<tr><th>Search Results</th></tr>
			</thead><tbody>
			<tr><td>No Results</td></tr>
			</tbody></table>
		</div>
	  </div>
	</div>
	<br><br>
</div><!-- /wrap  -->

<?php include('include/footer.php'); ?>
<script>
$('#findinDD a').click(function (e) {
	// prevent defaults
	e.preventDefaults;
	$('#findinlbl').html('WHERE : ' + $(this).html());
	$('#findinTxt').val($(this).data('loc'));
	//return false;
	
}).eq(0).click();
$('#frmfind form').submit(function (e) {
	
	// prevent defaults
	e.preventDefaults;
	
	// ajax to the server
	$.post( 'find.php?action=fetch', $( this ).serialize(), 
		function(data) {
			if (data.success) {
				//alert('Done.');
				var row;
				$('#resultsTable tbody').empty();
				$('#resultsTable thead tr').empty().html('<th>ID</th><th>NAME</th><th>URL</th><th>ACTION</th>');
				for(var k in data.pages) {
					row = '<td></td>';
					row += '<td>'+data.pages[k].pagename+'</td>';
					row += '<td>'+data.pages[k].url+'</td>';
					row += '<td><a href="#">View</a> | <a href="#">Edit</a> | <a href="#">Replace</a></td>';
					$('<tr></tr>').html(row).appendTo('#resultsTable tbody');
				}
				if ($('#resultsTable tbody').html() == '')
					$('#resultsTable tbody').html('<td colspan="4">Nothing found for `'+$('#txtfind').val()+'`</td>');
				
			} else alert('Error: '+ data,msg);
	}, 'json').fail( function() { 
		alert('Failed: The request failed.'); 
	});
	
	return false;
	
});
</script>
<script>
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(10)").addClass('active');
</script>
</body>
</html>
