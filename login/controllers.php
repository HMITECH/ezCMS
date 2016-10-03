<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * View: Displays the php controller of the site
 * /index.php
 */
require_once("include/init.php");
$filename = "../index.php";
$content = @fread(fopen($filename, "r"), filesize($filename));
$content =  htmlspecialchars($content);
if (isset($_GET["flg"])) $flg = $_GET["flg"]; else $flg = "";
$msg = "";
if ($flg=="red") 
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> An error occurred and the controller were NOT saved.</div>';
if ($flg=="green")
	$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Saved!</strong> You have successfully saved the controller.</div>';
if ($flg=="pink") 
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> The controller file is NOT writeable.
				You must contact HMI Tech Support to resolve this issue.</div>';
if ($flg=="noperms") 
	$msg = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Permission Denied!</strong> You do not have permissions for this action.</div>';

?><!DOCTYPE html><html lang="en"><head>

	<title>Controller &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>
  
	<div id="wrap">
		<?php include('include/nav.php'); ?>  
		<div class="container" style="margin-bottom:40px ">
				
				<div class="white-boxed" style="margin:60px auto 10px; width:95%;">
					<form id="frmHome" action="scripts/set-controller.php" method="post" enctype="multipart/form-data">
					<div class="navbar">
						<div class="navbar-inner">
							<input type="submit" name="Submit" value="Save Changes" class="btn btn-primary">
						</div>
					</div>
					<?php echo $msg; ?>
					<textarea name="txtContents" id="txtContents" 
						class="input-block-level"><?php echo $content; ?></textarea>
					</form>
				</div>
			  </div>

		</div> 
	</div>

<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(1)").addClass('active');
</script>

<?php if ($_SESSION['EDITORTYPE'] == 3) { ?>

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
	<script src="codemirror/mode/php/php.js"></script>
	<script language="javascript" type="text/javascript">
		var myCode = CodeMirror.fromTextArea(document.getElementById("txtContents"), {
	        lineNumbers: true,
	        matchBrackets: true,
	        mode: "application/x-httpd-php",
	        indentUnit: 4,
	        indentWithTabs: true,
			theme: 'liquibyte',
			lineWrapping: true,
			extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
			foldGutter: true,
			gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
	        viewportMargin: Infinity
		});
	</script>


<?php } else { ?>

	<script language="javascript" type="text/javascript" src="js/edit_area/edit_area_full.js"></script>
	<script type="text/javascript">
		editAreaLoader.init({
			id:"txtContents", 
			syntax: "php",
			allow_toggle: true,
			start_highlight: true,
			toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight"
		});
	</script>

<?php } ?>

</body></html>