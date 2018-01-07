<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 * $Header: /cygdrive/c/cvs/repo/xampp/htdocs/hmi/ezsite/login/pages.php,v 1.2 2017-12-02 09:33:28 a Exp $ 
 * View: Displays the web pages in the site
 */ 

// **************** ezCMS PAGES CLASS ****************
require_once ("class/pages.class.php"); 

// **************** ezCMS PAGES HANDLE ****************
$cms = new ezPages();  
 
?><!DOCTYPE html><html lang="en"><head>

	<title>Pages : ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	
</head><body>
  
<div id="wrap">
	<?php include('include/nav.php'); ?>  
	<div class="container">
		<div id="editBlock" class="row-fluid">
			<div class="span3 white-boxed">
				<p><input type="text" id="txtsearch" class="input-block-level" placeholder="Search here ..."></p>
				<?php echo $cms->treehtml; ?>
			</div>
			<div class="span9 white-boxed">
			
				<form id="frmPage" action="" method="post" enctype="multipart/form-data">
				<div class="navbar">
					<div class="navbar-inner"><?php echo $cms->btns ?></div><!-- /navbar-inner  -->
				</div>

				<?php echo $cms->msg; ?>
				
				<div id="revBlock">
				  <table class="table table-striped"><thead>
					<tr><th>#</th><th>User Name</th><th>Date &amp; Time</th><th>Action</th></tr>
				  </thead><tbody><?php echo $cms->revs['log']; ?></tbody></table>
				</div>
				
			    <div class="tabbable tabs-top">
				<ul class="nav nav-tabs" id="myTab">
				  <li class="active"><a href="#d-main">Main</a></li>
				  <li><a href="#d-content">Content</a></li>
				  <li><a href="#d-header">Header</a></li>
				  <li><a href="#d-sidebar">Aside 1</a></li>
				  <li><a href="#d-siderbar">Aside 2</a></li>
				  <li><a href="#d-footers">Footer</a></li>
				  <li><a href="#d-head">Head</a></li>
				</ul>
				 
				<div class="tab-content">

				  <div class="tab-pane active" id="d-main">

						<div class="row">
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputTitle">Title Tag</label>
								<div class="controls">
									<input type="text" id="txtTitle" name="title"
										placeholder="Enter the title of the page"
										title="Enter the full title of the page here."
										data-toggle="tooltip" 
										value="<?php echo $cms->page['title']; ?>"
										data-placement="top" minlength="2"
										class="input-block-level tooltipme2 countme2" required><br>
										<label class="checkbox" <?php if ($cms->id < 3) echo 'style="display:none"';?>>
										  <input name="published" type="checkbox" value="checkbox" <?php echo $cms->page['publishedCheck']; ?>>
										  Published on site
										</label>
								</div>
							  </div>
							</div>
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputName">Name</label>
								<div class="controls">
									<input type="text" id="txtName" name="pagename"
										placeholder="Enter the name of the page"
										title="Enter the full name of the page here."
										data-toggle="tooltip" 
										value="<?php echo $cms->page['pagename']; ?>"
										data-placement="top" minlength="2"
										class="input-block-level tooltipme2 countme2" required><br>
									<?php echo $cms->page['publishedMsg']; ?>
									<label class="checkbox checkRight" <?php if ($cms->id < 3) echo 'style="display:none"';?>>
									  <input name="nositemap" type="checkbox" value="checkbox" <?php echo $cms->page['nositemapCheck']; ?>>
									  Skip from <a href="/sitemap.xml" target="_blank">sitemap.xml</a>										
									</label>
								</div>
							  </div>								
							</div>
						</div>

						<div class="row">
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputName">Parent Page</label>
								<div class="controls"><?php echo $cms->ddOptions; ?></div>
							  </div>
							</div>
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputName">Layout</label>
								<div class="controls"><select name="layout" class="input-block-level">
									<?php echo $cms->slOptions; ?></select></div>
							  </div>
							</div>
						</div>
						
						<div class="row">
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputDescription">Meta Description</label>
								<div class="controls">
									<textarea name="description" rows="5" id="txtDesc" 
										placeholder="Enter the description of the page"
										title="Enter the description of the page here, this is VERY IMPORTANT for SEO. Do not duplicate on all pages"
										data-toggle="tooltip"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><?php echo $cms->page['description']; ?></textarea>
								</div>
							  </div>								
							</div>
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputKeywords">Meta Keywords</label>
								<div class="controls">
									<textarea name="keywords" rows="5" id="txtKeywords" 
										placeholder="Enter the Keywords of the page"
										title="Enter list keywords of the page here, not so important now but use it anyways. Do not stuff keywords"
										data-toggle="tooltip"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><?php echo $cms->page['keywords']; ?></textarea>
								</div>
							  </div>							
							</div>
						</div>

				  </div><!-- /d-main  -->

				  <div class="tab-pane" id="d-content">
					<div class="row">
						<div class="span1" style="margin-top:6px;">Page URL :</div>
						<div class="span11">
							<input type="text" name="url" minlength="2" value="<?php echo $cms->page['url']; ?>" class="input-block-level">
						</div>
					</div>
					<textarea id="txtMain" name="maincontent"><?php echo $cms->page['maincontent']; ?></textarea>
				  </div><!-- /d-content  -->
				    
				  <div class="tab-pane" id="d-header">
					<div class="row">
						<div class="span4"><label class="checkbox">
							<input name="useheader" type="checkbox" value="checkbox" <?php echo $cms->page['useheaderCheck']; ?>>
							Enable custom HEADER</label></div>
						<div class="span8 text-right"><?php echo $cms->page['useheaderMsg']; ?></div>
					</div>
					<textarea id="txtHeader" name="headercontent"><?php echo $cms->page['headercontent']; ?></textarea>
				  </div><!-- /d-header  -->
				    
				  <div class="tab-pane" id="d-sidebar">
					<div class="row">
						<div class="span4"><label class="checkbox">
							<input name="useside" type="checkbox" value="checkbox" <?php echo $cms->page['usesideCheck']; ?>>
							Enable custom ASIDE 1</label></div>
						<div class="span8 text-right"><?php echo $cms->page['usesideMsg']; ?></div>
					</div>
					<textarea id="txtSide" name="sidecontent"><?php echo $cms->page['sidecontent']; ?></textarea>
				  </div><!-- /d-sidebar  -->
				  
				  <div class="tab-pane" id="d-siderbar">
					<div class="row">
						<div class="span4"><label class="checkbox">
							<input name="usesider" type="checkbox" value="checkbox" <?php echo $cms->page['usesiderCheck']; ?>>
							Enable custom ASIDE 2</label></div>
						<div class="span8 text-right"><?php echo $cms->page['usesiderMsg']; ?></div>
					</div>
				  	<textarea id="txtrSide" name="sidercontent"><?php echo $cms->page['sidercontent']; ?></textarea>
				  </div><!-- /d-siderbar  -->
				  
				  <div class="tab-pane" id="d-footers">
					<div class="row">
						<div class="span4"><label class="checkbox">
							<input name="usefooter" type="checkbox" value="checkbox" <?php echo $cms->page['usefooterCheck']; ?>>
							Enable custom FOOTER</label></div>
						<div class="span8 text-right"><?php echo $cms->page['usefooterMsg']; ?></div>
					</div>
					<textarea id="txtFooter" name="footercontent"><?php echo $cms->page['footercontent']; ?></textarea>
				  </div><!-- /d-footer  -->
				  
				  <div class="tab-pane" id="d-head">
					<blockquote>
					  <p>Append to PAGE</p>
					  <small>Enter additional <strong>content</strong> for this page, you can include js, css, or anything else here</small>
					</blockquote>
				  	<textarea id="txtHead" name="head"><?php echo $cms->page['head']; ?></textarea>
				  </div><!-- /d-head  -->

				</div><!-- /tab-content  -->
				</div><!-- /tabbable tabs-top  -->
			  	</form>
			</div><!-- /span9 white-boxed  -->
			<div class="clearfix"></div>
		</div><!-- /editBlock row-fluid  -->

		<div id="diffBlock" class="white-boxed">
			<div class="navbar"><div class="navbar-inner">
				<a id="backEditBTN" href="#" class="btn btn-inverted btn-info">Back to Main Editor</a>
				<a id="waysDiffBTN" href="#" class="btn btn-inverted btn-warning">Three Way (3)</a>
				<a id="collaspeBTN" href="#" class="btn btn-inverted btn-warning">Collaspe Unchanged</a>
			</div></div>
			<table id="diffviewerControld" width="100%" border="0">
			  <tr><td><select><option value="0">Current (Last Saved)</option><?php echo $cms->revs['opt']; ?></select>
				</td><td><select disabled><option selected>Your Current Edit</option></select>
				</td><td><select><option value="0">Current (Last Saved)</option><?php echo $cms->revs['opt']; ?></select>
			  </td></tr>
			</table>
			<div class="tabbable tabs-top">
				<ul class="nav nav-tabs" id="revTab" data-open="content">
				  <li class="active"><a href="#" data-block="content">Content</a></li>
				  <li><a href="#" data-block="header">Header</a></li>
				  <li><a href="#" data-block="sidebar">Aside 1</a></li>
				  <li><a href="#" data-block="siderbar">Aside 2</a></li>
				  <li><a href="#" data-block="footer">Footer</a></li>
				  <li><a href="#" data-block="head">Head</a></li>
				</ul>
				<div id="diffviewer"></div>
			</div><!-- /tabbable  -->
		</div><!-- /diffBlock  -->
		<textarea name="txtTemps" id="txtTemps" class="input-block-level"></textarea>

	</div><!-- /container  -->
	<br><br>
</div><!-- /wrap  -->

<?php include('include/footer.php'); ?>

<script type="text/javascript">
		
	$('#left-tree.treeview li a').click( function() {
		$(this).attr('href', $(this).attr('href')+window.location.hash);
		return true;
	});
		
	$('#txtsearch').typeahead({
		source: function (typeahead, query) {
			var pgs=new Array(); 
			$('#left-tree li a').each( function() {
				pgs.push($(this).text()+']]-->>'+$(this).attr('href'));
			});
			return pgs;
		},
		highlighter: function (item) {
			var regex = new RegExp( '(' + this.query + ')', 'gi' );
			var parts = item.split(']]-->>');
			return (parts[0].replace( regex, "<strong>$1</strong>" )+
					'<span style="display:none;">]]-->>'+parts[1]+'</span>');
		},
		updater: function (item) {
			window.location.href = item.split(']]-->>')[1];
		}
	});
	
	$('.countme2').each( function() {
		var navKeys = [33,34,35,36,37,38,39,40];
		var that = $(this)
		var thisLabel = $(this).closest('.control-group').find('.control-label');
		
		$(thisLabel).html( $(thisLabel).text()+
		  	' <span class="countDisplay"><span class="label label-info">'+$(that).val().length+' chars(s)</span></span>');
		
		// attach event on change
		$(this).on('keyup blur paste', function(e) {
			switch(e.type) {
			  case 'keyup':
				// Skip navigational key presses
				if ($.inArray(e.which, navKeys) < 0) { 
					$(thisLabel).find('span.label').text( $(that).val().length+' chars(s)' );
				}
				break;
			  case 'paste':
				// Wait a few miliseconds if a paste event
				setTimeout(function () {
					$(thisLabel).find('span.label').text( $(that).val().length+' chars(s)' );
				}, (e.type === 'paste' ? 5 : 0));
				break;
			  default:
				$(thisLabel).find('span.label').text( $(that).val().length+' chars(s)' );
				break;
			}
		});

	});
	
	$('#myTab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
		window.location.hash = $(this).attr('href').replace('#d-','');
	});
	/*
	 function dragStart(event) {
	 	console.log(event);
		event.dataTransfer.effectAllowed='move';
		event.dataTransfer.setData("Text", ev.target.getAttribute('id'));
		event.dataTransfer.setDragImage(ev.target,0,0);
		return true;
	 }	*/
	
	$('.nopubmsg').click( function () {
		return confirm('The page is Not published, its only visible to you.');
	});

</script>


<?php if ($_SESSION['EDITORTYPE'] == 0) { ?>
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
	CKEDITOR.replace( 'txtMain'  , { uiColor : '#AAAAFF' });
	CKEDITOR.replace( 'txtHeader', { uiColor : '#59ACFF' }); 
	CKEDITOR.replace( 'txtrSide' , { uiColor : '#FFD5AA' }); 
	CKEDITOR.replace( 'txtSide'  , { uiColor : '#FFAAAA' }); 
	CKEDITOR.replace( 'txtFooter', { uiColor : '#CCCCCC' });	
	</script>  
<?php } else if ($_SESSION['EDITORTYPE'] == 1) { ?>
	<script language="javascript" type="text/javascript" src="js/edit_area/edit_area_full.js"></script>
	<script language="javascript" type="text/javascript">
	var txtMain_loaded = false;
	var txtHeader_loaded = false;
	var txtFooter_loaded = false;
	var txtSide_loaded = false;
	var txtSider_loaded = false;
	var txtHead_loaded = false;
	var getEditAreaJSON = function (strID) {
		return {
			id: strID, 
			syntax: "html",
			allow_toggle: false,
			start_highlight: true,
			toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
		}
	}
	$('#myTab a').click(function (e) {
		e.preventDefault();
		if ((!txtMain_loaded)&&($(this).attr('href')=='#d-content')) {
			editAreaLoader.init(getEditAreaJSON("txtMain"));
			txtMain_loaded = true;
		}
		if ((!txtHeader_loaded)&&($(this).attr('href')=='#d-header')) {
			editAreaLoader.init(getEditAreaJSON("txtHeader"));
			txtHeader_loaded = true;
		}
		if ((!txtFooter_loaded)&&($(this).attr('href')=='#d-footer')) {
			editAreaLoader.init(getEditAreaJSON("txtFooter"));
			txtFooter_loaded = true;
		}
		if ((!txtSider_loaded)&&($(this).attr('href')=='#d-siderbar')) {
			editAreaLoader.init(getEditAreaJSON("txtrSide"));
			txtSider_loaded = true;
		}
		if ((!txtSide_loaded)&&($(this).attr('href')=='#d-sidebar')) {
			editAreaLoader.init(getEditAreaJSON("txtSide"));
			txtSide_loaded = true;
		}
		if ((!txtHead_loaded)&&($(this).attr('href')=='#d-head')) {
			editAreaLoader.init(getEditAreaJSON("txtHead"));
			txtHead_loaded = true;
		}
	});
	</script>
	
<?php } else if ($_SESSION['EDITORTYPE'] == 3) { ?>

	<script src="codemirror/lib/codemirror.js"></script>
	<script src="codemirror/mode/javascript/javascript.js"></script>
	<script src="codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script src="codemirror/addon/edit/matchbrackets.js"></script>
	<script src="codemirror/mode/xml/xml.js"></script>
	<script src="codemirror/addon/fold/foldcode.js"></script>
	<script src="codemirror/addon/fold/foldgutter.js"></script>
	<script src="codemirror/addon/fold/brace-fold.js"></script>
	<script src="codemirror/addon/fold/xml-fold.js"></script>
	<script src="codemirror/addon/fold/markdown-fold.js"></script>
	<script src="codemirror/addon/fold/comment-fold.js"></script>
	<script src="codemirror/addon/merge/diff_match_patch.js"></script>
	<script src="codemirror/addon/merge/merge.js"></script>
	<script src="codemirror/mode/css/css.js"></script>
	<script src="codemirror/mode/clike/clike.js"></script>
	<script language="javascript" type="text/javascript">
	
	var revJson = <?php echo json_encode($cms->revs['jsn']); ?>;
	
	var myCodeMain, myCodeHeader, myCodeSide1, myCodeSide2, myCodeFooter, myCodeHead;

	var codeMirrorJSON = {
		lineNumbers: true,
		matchBrackets: true,
		mode: "htmlmixed",
		indentUnit: 4,
		indentWithTabs: true,
		theme: '<?php echo $_SESSION["CMTHEME"]; ?>',
		lineWrapping: true,
		extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
		foldGutter: true,
		gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
	}
	
	// DIFF Viewer Options
	var codeMain = '',
		codeRight = $("#txtMain").val(), 
		codeLeft = codeRight,
		panes = 2, collapse = false, dv;
	
	// function to build DIFF UI
	var buildDiffUI = function () {
		var target = document.getElementById("diffviewer");
		target.innerHTML = "";
		dv = CodeMirror.MergeView(target, {
			value: codeMain,
			origLeft: panes == 3 ? codeLeft : null,
			orig: codeRight,
			lineNumbers: true,
			mode: "htmlmixed",
			theme: '<?php echo $_SESSION["CMTHEME"]; ?>',
			extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
			foldGutter: true,
			gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
			highlightDifferences: true,
			connect: null,
			collapseIdentical: collapse
		});
	}
	
	$('#revTab a').click(function (e) {
		e.preventDefault();
		
		var block = $(this).data('block');
		var openB = $('#revTab').data('open');
		
		// do nothing is same block is clicked
		if (block==openB) return false;
		
		// now put the open back data back into the main editor
		if (openB == 'content') myCodeMain.setValue(dv.editor().getValue());
		if (openB == 'header') myCodeHeader.setValue(dv.editor().getValue());
		if (openB == 'sidebar') myCodeSide1.setValue(dv.editor().getValue());
		if (openB == 'siderbar') myCodeSide2.setValue(dv.editor().getValue());
		if (openB == 'footer') myCodeFooter.setValue(dv.editor().getValue());	
		if (openB == 'head') myCodeHead.setValue(dv.editor().getValue());

		if (block == 'content') {
			codeMain = myCodeMain.getValue();
			codeRight = $("#txtMain").val(); }
		if (block == 'header') {
			codeMain = myCodeHeader.getValue();
			codeRight = $("#txtHeader").val(); }
		if (block == 'sidebar') {
			codeMain = myCodeSide1.getValue();
			codeRight = $("#txtSide").val(); }
		if (block == 'siderbar') {
			codeMain = myCodeSide2.getValue();
			codeRight = $("#txtrSide").val(); }		
		if (block == 'footer') {
			codeMain = myCodeFooter.getValue();
			codeRight = $("#txtFooter").val(); }
		if (block == 'head') {
			codeMain = myCodeHead.getValue();
			codeRight = $("#txtHead").val(); }
		
		codeLeft = codeRight;
		buildDiffUI();		
		
		$('#revTab').data('open', block)
		$(this).tab('show');
		
	});
	
	// Change to DIff UI
	$('#showdiff').click( function () {
		$('#editBlock').slideUp('slow');
		$('#diffBlock').slideDown('slow', function () {
			codeMain = myCodeMain.getValue(),
			buildDiffUI();
		});
		return false;
	});
	
	// Toggle 2 or 3 wya Diff
	$("#waysDiffBTN").click( function () {
		if (panes == 2) {
			panes = 3;
			$(this).text('Two Way (2)');
			$('#diffviewerControld td').width('33.33%');
			$('#diffviewerControld td:first-child').show();
		} else {
			panes = 2;
			$(this).text('Three Way (3)');
			$('#diffviewerControld td').width('50%');
			$('#diffviewerControld td:first-child').hide();				
		}
		codeMain = dv.editor().getValue();
		buildDiffUI();
		return false;
	}); 
	
	// Click on Fetch or DIFF in revision log
	$('#revBlock a').click( function () {
		var loadID = $(this).parent().data('rev-id');
		if ($(this).text() == 'Fetch') {
			myCodeMain.setValue(revJson[loadID]['maincontent']);
			return false;
		} else if ($(this).text() == 'Diff') {
			$("#txtTemps").val(revJson[loadID]['maincontent']);
			codeRight= $("#txtTemps").val();
			$('#diffviewerControld td:last-child select').val(loadID);
			$('#showdiff').click();
			return false;
		}
	});

	
	// Back to Main editor from DIFF UI
	$('#backEditBTN').click( function () {
		var openB = $('#revTab').data('open');
		// now put the open back data back into the main editor
		if (openB == 'content') myCodeMain.setValue(dv.editor().getValue());
		if (openB == 'header') myCodeHeader.setValue(dv.editor().getValue());
		if (openB == 'sidebar') myCodeSide1.setValue(dv.editor().getValue());
		if (openB == 'siderbar') myCodeSide2.setValue(dv.editor().getValue());
		if (openB == 'footer') myCodeFooter.setValue(dv.editor().getValue());	
		if (openB == 'head') myCodeHead.setValue(dv.editor().getValue());
		myCodeMain.setValue(dv.editor().getValue());
		$('#editBlock').slideDown('slow', function () {
			myCodeMain.refresh();
			myCodeHeader.refresh();
			myCodeSide1.refresh();
			myCodeSide2.refresh();
			myCodeFooter.refresh();
			myCodeHead.refresh();		
		});
		$('#diffBlock').slideUp('slow');
	
		return false;
	});
	
	// Toggle Collapse Unchanged sections
	$("#collaspeBTN").click( function () {
		if (collapse) {
			collapse = false;
			$(this).text('Collapase Unchanged');
		} else {
			collapse = true;
			$(this).text('Expand Unchanged');
		}
		codeMain = dv.editor().getValue();
		buildDiffUI();
		return false;
	});
	
	// Change Rev in Diff Viewer select dropdown
	$('#diffviewerControld select').change( function () {
		var revID2Load = $(this).val();
		if (revID2Load == '0') {
			var revContentLoad = $("#txtMain").val(); // shoe last saved 
		} else {
			$("#txtTemps").val(revJson[revID2Load]['maincontent']);
			var revContentLoad = $("#txtTemps").val();
		}
		if ($(this).parent().index() == 0) codeLeft = revContentLoad;
		else codeRight = revContentLoad;
		codeMain = dv.editor().getValue();
		buildDiffUI();
	});	

	
	$('#myTab a').click(function (e) {
		e.preventDefault();
		myCodeMain.refresh();
		myCodeHeader.refresh();
		myCodeSide1.refresh();
		myCodeSide2.refresh();
		myCodeFooter.refresh();
		myCodeHead.refresh();
	});	
	myCodeMain = CodeMirror.fromTextArea(document.getElementById("txtMain"), codeMirrorJSON);
	myCodeHeader = CodeMirror.fromTextArea(document.getElementById("txtHeader"), codeMirrorJSON);
	myCodeFooter = CodeMirror.fromTextArea(document.getElementById("txtFooter"), codeMirrorJSON);
	myCodeSide1 = CodeMirror.fromTextArea(document.getElementById("txtSide"), codeMirrorJSON);
	myCodeSide2 = CodeMirror.fromTextArea(document.getElementById("txtrSide"), codeMirrorJSON);
	myCodeHead = CodeMirror.fromTextArea(document.getElementById("txtHead"), codeMirrorJSON);

</script>
<?php } ?>
<script language="javascript" type="text/javascript">
	if(window.location.hash) $('a[href="'+window.location.hash.replace('#','#d-')+'"]').click(); 
</script>
</body></html>
