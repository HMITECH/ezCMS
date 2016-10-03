<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * View: Displays the default setting of the site
 * 
 */
require_once("include/init.php");
$qry = "SELECT * FROM `site` WHERE `id` = 1;";
$rs = mysql_query($qry) or die("Unable to Execute  MYSQL query");
$arr = mysql_fetch_array($rs);
$title       = $arr["title"         ];
$keywords    = $arr["keywords"      ];
$description = $arr["description"   ];
$header      = htmlspecialchars($arr["headercontent" ]);
$sidebar     = htmlspecialchars($arr["sidecontent"   ]);
$siderbar    = htmlspecialchars($arr["sidercontent"  ]);
$footer      = htmlspecialchars($arr["footercontent" ]);
if ($arr["appendtitle"] == 1) $aptitle = "checked";	else $aptitle = '';
if ($arr["appendkey"  ] == 1) $apkey   = "checked";	else $apkey   = '';	
if ($arr["appenddesc" ] == 1) $apdesc  = "checked";	else $apdesc  = '';			
mysql_free_result($rs);
if (isset($_GET["flg"])) $flg = $_GET["flg"]; else $flg = "";
$msg = "";
if ($flg=="red") 
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> An error occurred and the settings were NOT saved.</div>';
if ($flg=="green")
	$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Saved!</strong> You have successfully saved the settings.</div>';
if ($flg=="noperms") 
	$msg = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Permission Denied!</strong> You do not have permissions for this action.</div>';
				
?><!DOCTYPE html><html lang="en"><head>

	<title>Settings &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	
</head><body>
  
	<div id="wrap">
		<?php include('include/nav.php'); ?>  
		<div class="container" style="margin-bottom:40px ">
			<div class="white-boxed" style="margin:60px auto 10px; width:95%;">
			  <form id="frmHome" action="scripts/set-defaults.php" method="post" enctype="multipart/form-data" class="form-horizontal">
				<div class="navbar">
					<div class="navbar-inner">
						<input type="submit" name="Submit" value="Save Changes" class="btn btn-primary">
					</div>
				</div>
				<?php echo $msg; ?>
				
				<div class="tabbable tabs-left">
				<ul class="nav nav-tabs" id="myTab">
				  <li class="active"><a href="#d-main">Main</a></li>
				  <li><a href="#d-header">Header</a></li>
				  <li><a href="#d-sidebar">Sidebar A</a></li>
				  <li><a href="#d-siderbar">Sidebar B</a></li>
				  <li><a href="#d-footer">Footer</a></li>
				</ul>
				 
				<div class="tab-content">
					<div class="tab-pane active" id="d-main">

						  <div class="control-group">
							<label class="control-label" for="inputEmail">Site Title</label>
							<div class="controls">
								<input type="text" id="txtTitle" name="txtTitle"
									placeholder="Enter the title of the site"
									title="Enter the full title of the site here."
									data-toggle="tooltip" 
									value="<?php echo $title; ?>"
									data-placement="top"
									class="input-block-level tooltipme2"><br>							
								<input name="ckapptitle" type="checkbox" id="ckapptitle" value="checkbox" <?php echo $aptitle; ?>>
								Append to Page Title 
								<?php if ($aptitle == "checked") echo 
										'<span class="label label-important">The page title will be appended. 
										Not Recommended, its better to have a unique title for each page.</span>';
									else echo 
										'<span class="label label-info">The page title will be not appended.
										Recommended, its better to have a unique title for each page.</span>';?>
							</div>
						  </div>
						  
						  <div class="control-group">
							<label class="control-label" for="inputEmail">Description</label>
							<div class="controls">
								<textarea name="txtDesc" rows="5" id="txtDesc" 
									placeholder="Enter the description of the site"
									title="Enter the description of the site here, this is VERY IMPORTANT for SEO. Do not duplicate on all pages"
									data-toggle="tooltip"
									data-placement="top"
									class="input-block-level tooltipme2"><?php echo $description; ?></textarea><br>
								<input name="ckappdesc" type="checkbox" id="ckappdesc" value="checkbox" <?php echo $apdesc; ?>>
								Append to Page Description 
								<?php if ($apdesc == "checked") echo 
										'<span class="label label-important">The page description will be appended. 
										Not Recommended, its better to have a unique description for each page.</span>';
									else echo 
										'<span class="label label-info">The page description will be not appended.
										Recommended, its better to have a unique description for each page.</span>';?>
							</div>
						  </div>							  
						  
						  <div class="control-group">
							<label class="control-label" for="inputEmail">Keywords</label>
							<div class="controls">
							 	<textarea name="txtKeywords" rows="5" id="txtKeywords" 
									placeholder="Enter the Keywords of the site"
									title="Enter list keywords of the site here, not so important now but use it anyways. Do not stuff keywords"
									data-toggle="tooltip"
									data-placement="top"
									class="input-block-level tooltipme2"><?php echo $keywords; ?></textarea><br>
								<input name="ckappkey" type="checkbox" id="ckappkey" value="checkbox" <?php echo $apkey; ?>>
								Append to Page Keywords 
									<?php if ($apkey == "checked") echo 
											'<span class="label label-important">The page keywords will be appended. 
											Not Recommended, its better to have a unique title for each page.</span>';
										else echo 
											'<span class="label label-info">The page keywords will be not appended.
											Recommended, its better to have unique keywords for each page.</span>';?>			
							</div>
						  </div>
					
					</div>
					<div class="tab-pane" id="d-header">
						<textarea name="txtHeader" rows="30" id="txtHeader" style="width:98%;"><?php echo $header; ?></textarea>
					</div>
					<div class="tab-pane" id="d-sidebar">
						<textarea name="txtSide" rows="30" id="txtSide" style="width:98%;"><?php echo $sidebar; ?></textarea>
					</div>
					<div class="tab-pane" id="d-siderbar">
						<textarea name="txtrSide" rows="30" id="txtrSide" style="width:98%;"><?php echo $siderbar; ?></textarea>
					</div>
					<div class="tab-pane" id="d-footer">
						<textarea name="txtFooter" id="txtFooter" rows="30" style="width:98%;"><?php echo $footer; ?></textarea>
					</div>				  
				</div>
				</div>
			  </form>
			</div>
		</div>
	</div>
<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(0)").addClass('active');
	$('#myTab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
</script>
<?php if ($_SESSION['EDITORTYPE'] == 0) { ?>

	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
	  CKEDITOR.replace( 'txtHeader', { uiColor : '#59ACFF' }); 
	  CKEDITOR.replace( 'txtrSide' , { uiColor : '#FFD5AA' });    
	  CKEDITOR.replace( 'txtSide'  , { uiColor : '#FFAAAA' }); 
	  CKEDITOR.replace( 'txtFooter', { uiColor : '#CCCCCC' });	
	</script>

<?php } else if ($_SESSION['EDITORTYPE'] == 1) { ?>

	<script language="javascript" type="text/javascript" src="js/edit_area/edit_area_full.js"></script>
	<script language="javascript" type="text/javascript">
	var txtHeader_loaded = false;
	var txtFooter_loaded = false;
	var txtSide_loaded = false;
	var txtSider_loaded = false;
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
	<script src="codemirror/mode/css/css.js"></script>
	<script src="codemirror/mode/clike/clike.js"></script>
	<script language="javascript" type="text/javascript">
	var txtHeader_loaded = false;
	var txtFooter_loaded = false;
	var txtSide_loaded = false;
	var txtSider_loaded = false;
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
		if ((!txtHeader_loaded)&&($(this).attr('href')=='#d-header')) {
			CodeMirror.fromTextArea(document.getElementById("txtHeader"), codeMirrorJSON);
			txtHeader_loaded = true;
		}
		if ((!txtFooter_loaded)&&($(this).attr('href')=='#d-footer')) {
			CodeMirror.fromTextArea(document.getElementById("txtFooter"), codeMirrorJSON);
			txtFooter_loaded = true;
		}
		if ((!txtSider_loaded)&&($(this).attr('href')=='#d-siderbar')) {
			CodeMirror.fromTextArea(document.getElementById("txtrSide"), codeMirrorJSON);
			txtSider_loaded = true;
		}
		if ((!txtSide_loaded)&&($(this).attr('href')=='#d-sidebar')) {
			CodeMirror.fromTextArea(document.getElementById("txtSide"), codeMirrorJSON);
			txtSide_loaded = true;
		}
	});	
	</script>

<?php } ?>
</body></html>