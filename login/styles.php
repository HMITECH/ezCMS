<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * View: Displays the css style sheets in the site
 * 
 */
require_once("include/init.php");
$filelist = '';
if (isset($_GET['show'])) $filename = $_GET['show']; else $filename = "../style.css";
if ($handle = opendir('../site-assets/css')) {
	while (false !== ($entry = readdir($handle))) {
		if (preg_match('/\.css$/i',$entry)) {
			if ($filename==$entry) $myclass = 'label label-info'; else $myclass = '';
			$filelist .= '<li><i class="icon-tint"></i> <a href="styles.php?show='.
				$entry.'" class="'.$myclass.'">'.$entry.'</a></li>';
		}
	}
	closedir($handle);
}

if ($filename != "../style.css") $filename = "../site-assets/css/$filename";
$content = @fread(fopen($filename, "r"), filesize($filename));
$content =  htmlspecialchars($content);
if (isset($_GET["flg"])) $flg = $_GET["flg"]; else $flg = "";
$msg = "";
if ($flg=="red") 
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> An error occurred and the stylesheet was NOT saved.</div>';
if ($flg=="green")
	$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Saved!</strong> You have successfully saved the stylesheet.</div>';
if ($flg=="pink") 
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> The stylesheet file is NOT writeable.
				You must contact HMI Tech Support to resolve this issue.</div>';
if ($flg=="delfailed") 
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Delete Failed!</strong> An error occurred and the stylesheet was NOT deleted.</div>';
if ($flg=="deleted")
	$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Deleted!</strong> You have successfully deleted the stylesheet.</div>';
if ($flg=="noperms") 
	$msg = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Permission Denied!</strong> You do not have permissions for this action.</div>';				
?><!DOCTYPE html><html lang="en"><head>

	<title>Styles &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	
</head><body>
  
	<div id="wrap">
		<?php include('include/nav.php'); ?>  
		<div class="container">
			<div class="container-fluid" style="margin:60px auto 30px;">
			  <div class="row-fluid">
				<div class="span3 white-boxed">
				
					<ul id="left-tree">
					  <li class="open" ><i class="icon-pencil"></i> 
						<a class="<?php if ($filename=="../style.css") echo 'label label-info'; ?>" href="styles.php">style.css</a>
					  	<ul><?php echo $filelist; ?></ul>
					  </li>
					</ul>
					
				</div>
				<div class="span9 white-boxed">
				  <form id="frm" action="scripts/set-styles.php" method="post" enctype="multipart/form-data">
					<div class="navbar">
						<div class="navbar-inner">
							<input type="submit" name="Submit" id="Submit" value="Save Changes" class="btn btn-primary" style="padding:5px 12px;"> 
							<div class="btn-group">
							  <a class="btn dropdown-toggle btn-info" data-toggle="dropdown" href="#">
								Save As <span class="caret"></span></a>
								
							  <div id="SaveAsDDM" class="dropdown-menu" style="padding:10px;">
								<blockquote>
								  <div>Save stylesheet as</div>
								  <small>Only Alphabets and Numbers, no spaces</small>
								</blockquote>
								<div class="input-prepend input-append">
								  <span class="add-on">/site-assets/css/</span>
								  <input id="txtSaveAs" name="txtSaveAs" type="text" class="input-medium appendedPrependedInput">
								  <span class="add-on">.css</span>
								</div><br>
								<p><a id="btnsaveas" href="#" class="btn btn-large btn-info">Save Now</a></p>
							  </div>
							  
							</div>
							<?php if ($filename!='../style.css') 
								echo '<a href="scripts/del-styles.php?delfile='.
									$filename.'" onclick="return confirm(\'Confirm Delete ?\');" class="btn btn-danger">Delete</a>'; ?>
						</div>
					</div>
					<?php echo $msg; ?>
					<input border="0" class="input-block-level" name="txtlnk" onFocus="this.select();" 
						style="cursor: pointer;" onClick="this.select();"  type="text" title="include this link in layouts or page head"
						value="&lt;link href=&quot;<?php echo substr($filename, 2); ?>&quot; rel=&quot;stylesheet&quot;&gt;" readonly/>					
					<input type="hidden" name="txtName" id="txtName" value="<?php echo $filename; ?>">
					<textarea name="txtContents" id="txtContents" class="input-block-level"
				  		style="height: 460px; width:100%"><?php echo $content; ?></textarea>
				  </form>
				</div>
			  </div>
			</div>
		</div> 
	</div>

<?php include('include/footer.php'); ?>
<script language="javascript" type="text/javascript" src="js/edit_area/edit_area_full.js"></script>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(4)").addClass('active');
	
	$('#SaveAsDDM').click(function (e) {
		e.stopPropagation();
	});	
	
	$('#btnsaveas').click( function () {
		var saveasfile = $('#txtSaveAs').val().trim();
		if (saveasfile.length < 1) {
			alert('Enter a valid filename to save as.');
			$('#txtSaveAs').focus();
			return false;
		}
		if (!saveasfile.match(/^[a-z0-9]+$/ig)) {
			alert('Enter a valid filename with lower case alphabets and numbers only.');
			$('#txtSaveAs').focus();
			return false;		
		}
		$('#txtName').val('../site-assets/css/'+saveasfile+'.css');		
		$('#Submit').click();
		return false;
	});
	
	editAreaLoader.init({
		id:"txtContents", 
		syntax: "css",
		allow_toggle: true,
		start_highlight: true,
		toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
	});
	
</script>
</body></html>