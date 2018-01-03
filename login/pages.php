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
					<div class="navbar-inner">
						NAV BAR COMES HERE...
					</div>
				</div>

				<?php echo $cms->msg; ?>
				
			    <div class="tabbable tabs-top">
				<ul class="nav nav-tabs" id="myTab">
				  <li class="active"><a href="#d-main">Main</a></li>
				  <li><a href="#d-content">Content</a></li>
				  <li><a href="#d-header">Header</a></li>
				  <li><a href="#d-sidebar">Aside A</a></li>
				  <li><a href="#d-siderbar">Aside B</a></li>
				  <li><a href="#d-footer">Footer</a></li>
				  <li><a href="#d-head">Head</a></li>
				</ul>
				 
				<div class="tab-content">

				  <div class="tab-pane active" id="d-main">
				  	d-main
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
				  
				  <div class="tab-pane" id="d-footer">
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
