<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 * View: Displays the php controller of the site
 * /index.php
 */
require_once("include/init.php");
$filename = "../index.php";
$content = @fread(fopen($filename, "r"), filesize($filename));
$content =  htmlspecialchars($content);

// Create the Revision Log here
$revsql = "SELECT git_files.id, users.username, git_files.content, git_files.createdon
	FROM users LEFT JOIN git_files ON users.id = git_files.createdby
	WHERE git_files.fullpath = 'index.php'
	ORDER BY git_files.id DESC";		
$rs = mysql_query($revsql) or die("Unable to Execute  Select query");
$revLog = '';
$revOption = '';
$revJson = array();
$revCount = 1;
while ($row = mysql_fetch_assoc($rs)) {	
	$revLog .= 	'<tr><td>'.$revCount.'</td><td>'.$row['username'].'</td><td>'.$row['createdon'].'</td>
	  <td data-rev-id="'.$row['id'].'">
	  	<a href="#">Fetch</a> &nbsp;|&nbsp; <a href="#">Diff</a> &nbsp;|&nbsp;
		<a href="scripts/purge-version.php?controller='.$row['id'].'">Purge</a>
		</td></tr>';
	$revOption .= 	'<option value="'.$row['id'].'">#'.$revCount.' '.$row['createdon'].' ('.$row['username'].')</option>';		
	$revJson[$row['id']] =  ($row['content']);
	$revCount++;
}
$revCount--;
if ($revLog == '') $revLog = '<tr><td colspan="3">There are no revisions of the controller.</td></tr>';

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
if ($flg=="redrev") 
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> An error occurred and the controller revision was not purged.</div>';
if ($flg=="greenrev")
	$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Purged!</strong> You have successfully purged the controller revision.</div>';
if ($flg=="nochange") 
	$msg = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>No Change!</strong> There are no changes to save.</div>';

?><!DOCTYPE html><html lang="en"><head>

	<title>Controller &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
  
	<div id="wrap">
		<?php include('include/nav.php'); ?>  
		<div class="container">
				
				<div id="editBlock" class="white-boxed" style="margin:60px auto 50px; width:95%;">
				  <form id="frmHome" action="scripts/set-controller.php" method="post" enctype="multipart/form-data">
					<div class="navbar">
						<div class="navbar-inner">
							<?php if ($_SESSION['EDITORTYPE'] == 3) {?>
							<a id="showdiff" href="#" class="btn btn-inverted btn-danger">Review DIFF</a>
							<?php } ?>
							<input type="submit" name="Submit" value="Save Changes" class="btn btn-primary ">
							<?php if ($_SESSION['EDITORTYPE'] == 3) {?>
							<a id="showrevs" href="#" class="btn btn-secondary">Revision Log <sup><?php echo $revCount; ?></sup></a>
							<?php } ?>
						</div>
					</div>
					<?php echo $msg; ?>
					<div id="revBlock">
					  <table class="table table-striped"><thead>
						<tr><th>#</th><th>User Name</th><th>Date &amp; Time</th><th>Action</th></tr>
					  </thead><tbody><?php echo $revLog; ?></tbody></table>
					</div>
					<textarea name="txtContents" id="txtContents" class="input-block-level"><?php echo $content; ?></textarea>
				  </form>
				</div>
			  
				<div id="diffBlock" class="white-boxed" style="margin:60px auto 50px; width:95%;">
					<div class="navbar"><div class="navbar-inner">
						<a id="backEditBTN" href="#" class="btn btn-inverted btn-info">Back to Main Editor</a>
						<a id="waysDiffBTN" href="#" class="btn btn-inverted btn-warning">Three Way (3)</a>
						<a id="collaspeBTN" href="#" class="btn btn-inverted btn-warning">Collaspe Unchanged</a>
					</div></div>
					<table id="diffviewerControld" width="100%" border="0">
					  <tr><td><select><option value="0">Current Page (Last Saved)</option><?php echo $revOption; ?></select>
						</td><td><select disabled><option selected>Your Current Edit</option></select>
						</td><td><select><option value="0">Current Page (Last Saved)</option><?php echo $revOption; ?></select>
					  </td></tr>
					</table>
					<div id="diffviewer"></div>
				</div>
				<textarea name="txtTemps" id="txtTemps" class="input-block-level"></textarea>

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
	<script src="codemirror/addon/merge/diff_match_patch.js"></script>
	<script src="codemirror/addon/merge/merge.js"></script>
	<script src="codemirror/mode/css/css.js"></script>
	<script src="codemirror/mode/clike/clike.js"></script>
	<script src="codemirror/mode/php/php.js"></script>
	<script language="javascript" type="text/javascript">
		var revJson = <?php echo json_encode($revJson); ?>,
			cmTheme = '<?php echo $_SESSION["CMTHEME"]; ?>',
			cmMode = 'application/x-httpd-php';
	</script>
	<script src="js/gitFileCode.js"></script>

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