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
		<div class="row-fluid">
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
									<input type="text" id="txtTitle" name="txtTitle"
										placeholder="Enter the title of the page"
										title="Enter the full title of the page here."
										data-toggle="tooltip" 
										value="<?php echo $cms->page['title']; ?>"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><br>
										<label class="checkbox" <?php if ($cms->id == 1 || $cms->id == 2) echo 'style="display:none"';?>>
										  <input name="ckPublished" type="checkbox" id="ckPublished" value="checkbox" <?php echo $cms->page['published']; ?>>
										  Published on site
										</label>
								</div>
							  </div>
							</div>
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputName">Name (URL)</label>
								<div class="controls">
									<input type="text" id="txtName" name="txtName"
										placeholder="Enter the name of the page"
										title="Enter the full name of the page here."
										data-toggle="tooltip" 
										value="<?php echo $cms->page['pagename']; ?>"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><br>
									<?php if (!$cms->page['published']) 
												echo '<span class="label label-important">Unpublished page only visible when logged in.</span>';
											else 
												echo '<span class="label label-info">Page is published and visible to all.</span>'; ?>
									<label class="checkbox checkRight" <?php if ($cms->id == 1 || $cms->id == 2) echo 'style="display:none"';?>>
									  <input name="cknositemap" type="checkbox" id="cknositemap" value="checkbox" <?php echo $cms->page['nositemap']; ?>>
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
								<div class="controls">
								  <?php if ($cms->id == 1 || $cms->id == 2) echo 
								  			'<div class="alert alert-info" style="margin: 0 0 3px;padding: 5px 10px;"><strong>'.
												'Site Root</strong></div>';
										else echo 
											'<select name="slGroup" id="slGroup" class="input-block-level">' . 
													$cms->ddOptions . '</select>'; ?>
								</div>
							  </div>
							</div>
							<div class="span6">
							
							  <div class="control-group">
								<label class="control-label" for="inputName">Layout</label>
								<div class="controls">
									<select name="slLayout" id="slLayout" class="input-block-level">
										<?php 
											if (($cms->page['slLayout'] =='') || ($cms->page['slLayout']=='layout.php'))
												echo '<option value="layout.php" selected>Default - layout.php</option>';
											else
												echo '<option value="layout.php">Default - layout.php</option>';
												
											if ($handle = opendir('..')) {
												while (false !== ($entry = readdir($handle))) {
													if (preg_match('/^layout\.[a-z0-9_-]+\.php$/i',$entry)) {
														if ($entry==$slLayout) $myclass = 'selected'; else $myclass = '';
														echo "<option $myclass>$entry</option>";
													}
												}
												closedir($handle);
											}
										?>	
									</select>
								</div>
							  </div>
							
							</div>
						</div>	
						
						<div class="row">
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputDescription">Meta Description</label>
								<div class="controls">
									<textarea name="txtDesc" rows="5" id="txtDesc" 
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
									<textarea name="txtKeywords" rows="5" id="txtKeywords" 
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
					<textarea id="txtMain" name="maincontent"><?php echo $cms->page['maincontent']; ?></textarea>
				  </div><!-- /d-content  -->
				    
				  <div class="tab-pane" id="d-header">
					<textarea id="txtHeader" name="headercontent"><?php echo $cms->page['headercontent']; ?></textarea>
				  </div><!-- /d-header  -->
				    
				  <div class="tab-pane" id="d-sidebar">
					<textarea id="txtSide" name="sidecontent"><?php echo $cms->page['sidecontent']; ?></textarea>
				  </div><!-- /d-sidebar  -->
				  
				  <div class="tab-pane" id="d-siderbar">
				  	<textarea id="txtrSide" name="sidercontent"><?php echo $cms->page['sidercontent']; ?></textarea>
				  </div><!-- /d-siderbar  -->
				  
				  <div class="tab-pane" id="d-footers">
					<textarea id="txtFooter" name="footercontent"><?php echo $cms->page['footercontent']; ?></textarea>
				  </div><!-- /d-footer  -->
				  
				  <div class="tab-pane" id="d-head">
				  	<textarea id="txtHead" name="head"><?php echo $cms->page['head']; ?></textarea>
				  </div><!-- /d-head  -->

				</div><!-- /tab-content  -->
				</div><!-- /tabbable tabs-top  -->
			  	</form>
			</div><!-- /span9 white-boxed  -->
			<div class="clearfix"></div>
		</div><!-- /row-fluid  -->
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
	$('.conf-del').click( function () {
		return confirm('Confirm Delete Action ?');
	});
	
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
	var txtMain_loaded = false;
	var txtHeader_loaded = false;
	var txtFooter_loaded = false;
	var txtSide_loaded = false;
	var txtSider_loaded = false;
	var txtHead_loaded = false;
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
		viewportMargin: Infinity
	}
	
	$('#myTab a').click(function (e) {
		e.preventDefault();
		myCodeMain.refresh();
		myCodeHeader.refresh();
		myCodeSide1.refresh();
		myCodeSide2.refresh();
		myCodeFooter.refresh();
		myCodeHead.refresh();
	});	
	$(window).load( function () {
		myCodeMain = CodeMirror.fromTextArea(document.getElementById("txtMain"), codeMirrorJSON);
		myCodeHeader = CodeMirror.fromTextArea(document.getElementById("txtHeader"), codeMirrorJSON);
		myCodeFooter = CodeMirror.fromTextArea(document.getElementById("txtFooter"), codeMirrorJSON);
		myCodeSide1 = CodeMirror.fromTextArea(document.getElementById("txtSide"), codeMirrorJSON);
		myCodeSide2 = CodeMirror.fromTextArea(document.getElementById("txtrSide"), codeMirrorJSON);
		myCodeHead = CodeMirror.fromTextArea(document.getElementById("txtHead"), codeMirrorJSON);
	});

</script>
<?php } ?>
<script language="javascript" type="text/javascript">
	if(window.location.hash) $('a[href="'+window.location.hash.replace('#','#d-')+'"]').click(); 
</script>
</body></html>
