<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *View: Displays the web pages in the site
 * 
 */ 
require_once("include/init.php");
require_once("include/pages.functions.php");

if (isset( $_REQUEST["id"])) $id = $_REQUEST["id"]; else $id = 1;
$usefooter      = '';
$useheader      = '';
$useside        = '';
$usesider       = '';
$published      = '';
$nositemap      = '';
$parentid       = '';
$slLayout		= '';
$name           = '';
$title          = '';
$keywords       = '';
$redirect       = '';
$description 	= '';
$maincontent 	= '';
$sidebar    	= '';
$siderbar 		= '';
$header			= '';
$footer 		= '';
$head 			= '';
$url        	= '';

// check if form is posted 
if (isset($_REQUEST['Submit'])) {

	if (!$_SESSION['editpage']) {header("Location: pages.php?id=$id&flg=noperms");exit;}	// permission denied

	$title       	= mysql_real_escape_string($_REQUEST['txtTitle']);
	$name      	    = validateName($_REQUEST['txtName']);
	$keywords    	= mysql_real_escape_string($_REQUEST['txtKeywords']);
	$description 	= mysql_real_escape_string($_REQUEST['txtDesc']);
	$maincontent 	= mysql_real_escape_string($_REQUEST['txtMain']);
	$sidebar 		= mysql_real_escape_string($_REQUEST['txtSide']);
	$siderbar 		= mysql_real_escape_string($_REQUEST['txtrSide']);
	$header 		= mysql_real_escape_string($_REQUEST['txtHeader']);
	$footer 		= mysql_real_escape_string($_REQUEST['txtFooter']);
	$head 			= mysql_real_escape_string($_REQUEST['txtHead']);
	
	if (isset($_REQUEST['slGroup'])) $parentid = ($_REQUEST['slGroup']); else $parentid = '0';
	$slLayout		= ($_REQUEST['slLayout']);

	if(isset($_REQUEST['ckPublished'])) $published     =1; else $published    = '0';
	if(isset($_REQUEST['cknositemap'])) $nositemap     =1; else $nositemap    = '0';
	if(isset($_REQUEST['ckside'     ])) $useside       =1; else $useside      = '0';
	if(isset($_REQUEST['cksider'    ])) $usesider      =1; else $usesider     = '0';
	if(isset($_REQUEST['ckHeader'   ])) $useheader     =1; else $useheader    = '0';
	if(isset($_REQUEST['ckFooter'   ])) $usefooter     =1; else $usefooter    = '0';

	if (strlen(trim($_REQUEST['txtName'])) < 1 ) {
		$_GET["flg"] = 'noname';
		include("include/set-page-vars.php");
	} elseif (strlen(trim($_REQUEST['txtTitle'])) < 1 ) {
		$_GET["flg"] = 'notitle';
		include("include/set-page-vars.php");
	} elseif ($parentid == $id && $id > 1 ) {
		$_GET["flg"] = 'nestedparent';
		include("include/set-page-vars.php");
	} else {
		if ($id == 'new') { 
			// add new page here !
			$qry = "INSERT INTO `pages` ( 
				`pagename` , `title`, `keywords` , `description` , `maincontent` , 
				`useheader` , `headercontent` , `head` , `layout` , 
				`usefooter` , `footercontent` ,`useside` , `sidecontent` , `usesider` , `sidercontent` ,
				`published` , `nositemap` ,  `parentid`) VALUES ( ";
			$qry .= "'" . $name          . "', ";
			$qry .= "'" . $title         . "', ";
			$qry .= "'" . $keywords      . "', ";
			$qry .= "'" . $description   . "', ";
			$qry .= "'" . $maincontent   . "', ";
			$qry .= "'" . $useheader     . "', ";
			$qry .= "'" . $header 		 . "', ";
			$qry .= "'" . $head			 . "', ";
			$qry .= "'" . $slLayout		 . "', ";
			$qry .= "'" . $usefooter     . "', ";
			$qry .= "'" . $footer 		 . "', ";
			$qry .= "'" . $useside       . "', ";
			$qry .= "'" . $sidebar   	 . "', ";
			$qry .= "'" . $usesider      . "', ";
			$qry .= "'" . $siderbar  	 . "', ";
			$qry .= "'" . $published     . "', ";
			$qry .= "'" . $nositemap     . "', ";
			$qry .= "'" . $parentid      . "');";
			if (mysql_query($qry)) {
				$id = mysql_insert_id();
				resolveplace();
				reIndexPages();
				mysql_query('OPTIMIZE TABLE `pages`;');
				header("Location: pages.php?id=".$id."&flg=added");	// added
				exit;
			} else {
				$_GET["flg"] = 'pink';
				include("include/set-page-vars.php");
			}

		} else {
			// update page here !
			
			// create a copy here ....
			mysql_query("INSERT INTO `git_pages` ( 
						  `page_id`, 
						  `pagename`,
						  `title`,
						  `keywords`,
						  `description`,
						  `maincontent`,
						  `useheader` ,
						  `headercontent` ,
						  `usefooter` ,
						  `footercontent` ,
						  `useside` ,
						  `sidecontent` ,
						  `published` ,
						  `parentid` ,
						  `place` ,
						  `url` ,
						  `sidercontent` ,
						  `usesider` ,
						  `head` ,
						  `layout` ,
						  `nositemap` , 
						  `createdby` ) SELECT 
						  `id` AS page_id, 
						  `pagename`,
						  `title`,
						  `keywords`,
						  `description`,
						  `maincontent`,
						  `useheader` ,
						  `headercontent` ,
						  `usefooter` ,
						  `footercontent` ,
						  `useside` ,
						  `sidecontent` ,
						  `published` ,
						  `parentid` ,
						  `place` ,
						  `url` ,
						  `sidercontent` ,
						  `usesider` ,
						  `head` ,
						  `layout` ,
						  `nositemap` , 
						  '".$_SESSION['USERID']."' as `createdby` 
						  FROM `pages` WHERE `id` = $id");
			
			$qry = "UPDATE `pages` SET ";
			$qry .= "`pagename`      = '" . $name          . "', ";
			$qry .= "`title`         = '" . $title         . "', ";
			$qry .= "`keywords`      = '" . $keywords      . "', ";
			$qry .= "`description`   = '" . $description   . "', ";
			$qry .= "`maincontent`   = '" . $maincontent   . "', ";
			$qry .= "`useheader`     = '" . $useheader     . "', ";
			$qry .= "`headercontent` = '" . $header 	   . "', ";
			$qry .= "`head`          = '" . $head          . "', ";
			$qry .= "`usefooter`     = '" . $usefooter     . "', ";
			$qry .= "`footercontent` = '" . $footer 	   . "', ";
			$qry .= "`useside`       = '" . $useside       . "', ";
			$qry .= "`sidecontent`   = '" . $sidebar   	   . "', ";
			$qry .= "`usesider`      = '" . $usesider      . "', ";
			$qry .= "`sidercontent`  = '" . $siderbar  	   . "', ";
			$qry .= "`published`     = '" . $published     . "', ";
			$qry .= "`nositemap`     = '" . $nositemap     . "', ";
			$qry .= "`parentid`      = '" . $parentid      . "', ";
			$qry .= "`layout`      = '" . $slLayout      . "'  ";
			$qry .= "WHERE `id` =" . $id . " LIMIT 1";
			if (mysql_query($qry)) {
				reIndexPages();
				mysql_query('OPTIMIZE TABLE `pages`;');
				header("Location: pages.php?id=".$id."&flg=green");	// updated
			} else
				header("Location: pages.php?id=".$id."&flg=red");	// failed
			exit;
		}
	}
} else if ($id <> 'new')  {

	$qry = "SELECT * FROM `pages` WHERE `id` = " . $id;
	$rs = mysql_query($qry);
	
	if (!mysql_num_rows($rs))
		header("Location: pages.php?show=&flg=yell");
	
	$arr = mysql_fetch_array($rs);
	mysql_free_result($rs);
	$title       	= $arr["title"     ];
	$name      	 	= $arr["pagename"  ];
	$url      	 	= $arr["url"       ];
	if ((!isset($url)) or ($url == "" ) or (empty($url))) $url="/";
	$keywords    	= htmlspecialchars($arr["keywords"     ]);
	$description 	= htmlspecialchars($arr["description"  ]);
	$maincontent 	= htmlspecialchars($arr["maincontent"  ]);
	$header      	= htmlspecialchars($arr["headercontent" ]);
	$sidebar     	= htmlspecialchars($arr["sidecontent"   ]);
	$siderbar    	= htmlspecialchars($arr["sidercontent"  ]);
	$footer			= htmlspecialchars($arr["footercontent" ]);
	$head			= htmlspecialchars($arr["head" ]);
	$parentid 		= $arr["parentid"];
	$slLayout		= $arr["layout"];
	$usefooter      = '';
	$useheader      = '';
	$useside        = '';
	$usesider       = '';
	$published      =  '';
	$nositemap      =  '';
	if ($arr["published"   ] == 1) $published    = "checked";
	if ($arr["nositemap"   ] == 1) $nositemap    = "checked";
	if ($arr["useheader"   ] == 1) $useheader    = "checked";
	if ($arr["usefooter"   ] == 1) $usefooter    = "checked";
	if ($arr["useside"     ] == 1) $useside      = "checked";
	if ($arr["usesider"    ] == 1) $usesider     = "checked";
	
	// Create the Revision Log here
	$revsql = "SELECT git_pages.id,git_pages.page_id,users.username,git_pages.createdon
				FROM git_pages LEFT JOIN users ON git_pages.createdby = users.id
				WHERE git_pages.page_id = $id 
				ORDER BY git_pages.id DESC";		
    $rs = mysql_query($revsql) or die("Unable to Execute  Select query");
	$revLog = '';
	$revCount = 1;
	while ($row = mysql_fetch_assoc($rs)) {	
		$revLog .= 	'<tr><td>'.$revCount.'</td><td>'.$row['username'].'</td><td>'.$row['createdon'].'</td>
		  <td data-rev-id="'.$row['id'].'"><a href="#">Revert</a> | <a href="#">Purge</a></td></tr>';
		$revCount++;
	}
	$revCount--;
	if ($revLog == '') $revLog = '<tr><td colspan="3">There are no revisions of this page.</td></tr>';
	
	// find url in traffic_pages
	if ($url=='/') $turl = '/index.php'; else $turl = $url;
	$rs = mysql_query("SELECT `id` FROM `traffic__pages` WHERE `name` = '$turl' LIMIT 1");
	if (mysql_num_rows($rs)) {
		$arr = mysql_fetch_array($rs);
		$tid = $arr['id'];
		$PageTrackingLinks = 
			'<li class="nav-header">Page Visitor Tracking</li><li class="dropdown-submenu">
				<a tabindex="-1" href="#">Visitor Tracking</a><ul class="dropdown-menu">
				  <li><a class="lframe" target="_blank" title="Page Visitor Tracking Summary"
					href="traffic/index.php?mode=stats&sid=39547&show=page&pageid='.$tid.'&lang=en">
					<i class="icon-chevron-right"></i> Summary</a></li>
				  <li><a class="lframe" target="_blank" title="Page Keyword Tracking" 
					href="traffic/index.php?mode=stats&sid=39547&show=key&pageid='.$tid.'&lang=en">
					<i class="icon-chevron-right"></i> Keywords</a>
				  <li><a class="lframe" target="_blank" title="Page Referrer Tracking" 
					href="traffic/index.php?mode=stats&sid=39547&show=ref&start=1&sort=hits&pageid='.$tid.'&lang=en">
					<i class="icon-chevron-right"></i> Referrers</a></li>
				  <li><a class="lframe" target="_blank" title="Page Visitor Path Analysis" 
					href="traffic/index.php?mode=stats&sid=39547&show=pathdesign&pathid='.$tid.'&lang=en">
					<i class="icon-chevron-right"></i> Visitor Path</a>
				  <li><a class="lframe" target="_blank" title="Page Visitor Flow Analysis" 
					href="traffic/index.php?mode=stats&sid=39547&show=studypath&pathid='.$tid.'&lang=en">
					<i class="icon-chevron-right"></i> Visitor Flow</a></li></ul></li>';
	} else {
		$PageTrackingLinks = 
			'<li class="nav-header">no visitor tracking data yet,<br>'.
			'check after visiting the page.<br>also make sure tracking is enabled.</li>';
	}
	mysql_free_result($rs);
} else {
	if (!$_SESSION['editpage']) {header("Location: pages.php?flg=noperms");exit;}	// permission denied
}
if (isset($_GET["flg"])) $msg = getErrorMsg($_GET["flg"]); else $msg = "";

?><!DOCTYPE html><html lang="en"><head>

	<title>Pages &middot; ezCMS Admin</title>
	<style>
		.countDisplay {float: right;}
		.checkRight {
			position: absolute;
			bottom: -5px;
			right: 0;
		}
	</style>
	<?php include('include/head.php'); ?>
	
</head><body>
  
	<div id="wrap">
		<?php include('include/nav.php'); ?>  
		<div class="container">
			<div class="container-fluid" style="margin:60px auto 30px;">
			  <div class="row-fluid">
				<div class="span3 white-boxed">
					<p><input type="text" id="txtsearch" class="input-block-level" placeholder="Search here ..."></p>
					<?php $dropdownOptionsHTML = getTreeHTML(0, $id, $parentid , str_replace('.html', '' ,$url)); ?>
				</div>
				<div class="span9 white-boxed">
				
					<form id="frmPage" action="" method="post" enctype="multipart/form-data">
					<div class="navbar">
						<div class="navbar-inner">
							<input type="submit" name="Submit" class="btn btn-primary"
								value="<?php if ($id == 'new') echo 'Add Page'; else echo 'Save Changes';?>">
							  <?php if ($id != 'new') { ?>
								<a href="<?php echo $url; ?>" target="_blank" 
									<?php if ($published!='checked') echo 'onclick="return confirm(\'The page is Not published, its only visible to you.\');"'; ?>
									class="btn btn-success">View</a>
								<a href="pages.php?id=new" class="btn btn-info">New</a>
								<a href="scripts/copy-page.php?copyid=<?php echo $id; ?>" class="btn btn-warning">Copy</a>
								<?php if ($id != 1 && $id != 2) echo '<a href="scripts/del-page.php?delid='.$id.
										'" onclick="return confirm(\'Confirm Delete ?\');" class="btn btn-danger">Delete</a>'; ?>
								<a id="showrevs" href="#" class="btn btn-secondary">Revisions <sup><?php echo $revCount; ?></sup></a>
								<div class="btn-group">
									<button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown">More <span class="caret"></span></button>
									<ul class="dropdown-menu">
									  <li class="nav-header">Validate</li>
									  <li><a class="lframe" target="_blank" title="Validate the Page HTML" href=
									  	"http://validator.w3.org/check?uri=http%3A%2F%2F<?php 
										echo $_SERVER['HTTP_HOST'] . $url; 
										?>&charset=%28detect+automatically%29&fbc=1&doctype=Inline&fbd=1&group=0&verbose=1">
										<i class="icon-chevron-right"></i> HTML W3C</a></li>
									  <li><a class="lframe" target="_blank" title="Validate the Page CSS" href=
									  	"http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2F<?php 
										echo $_SERVER['HTTP_HOST'] . $url; 
										?>&profile=css21&usermedium=all&warning=1&vextwarning=&lang=en">
										<i class="icon-chevron-right"></i> CSS W3C</a></li>
									  <li class="divider"></li>
									  <li class="nav-header">Check</li>
									  <li><a class="lframe" target="_blank" title="Check the Page for broken links" href=
									  	"http://validator.w3.org/checklink?uri=http%3A%2F%2F<?php 
										echo $_SERVER['HTTP_HOST'] . $url; ?>&hide_type=all&depth=1&check=Check">
										<i class="icon-chevron-right"></i> Broken Links</a></li>
 									  <li><a class="lframe" target="_blank" title="Check the Page keyword density" href=
									  	"http://www.webconfs.com/keyword-density-checker.php?url=http%3A%2F%2F<?php 
										echo $_SERVER['HTTP_HOST'] . $url; ?>">
										<i class="icon-chevron-right"></i> Keyword Density</a></li>
									  <li class="divider"></li>
									  <?php echo $PageTrackingLinks; ?>
									</ul>
								</div>							
							  <?php } ?>
						</div>
					</div>					
					
					<?php echo $msg; ?>
					
					<div id="revBlock">
					  <table class="table table-striped"><thead>
						<tr><th>#</th><th>User Name</th><th>Date &amp; Time</th><th>Action</th></tr>
					  </thead><tbody><?php echo $revLog; ?></tbody></table>
					</div>
					
				    <div class="tabbable tabs-left">
					<ul class="nav nav-tabs" id="myTab">
					  <li class="active"><a href="#d-main">Main</a></li>
					  <li><a href="#d-content">Content</a></li>
					  <li><a href="#d-header">Header</a></li>
					  <li><a href="#d-sidebar">Sidebar A</a></li>
					  <li><a href="#d-siderbar">Sidebar B</a></li>
					  <li><a href="#d-footer">Footer</a></li>
					  <li><a href="#d-head">Head</a></li>
					</ul>
					 
					<div class="tab-content">
					  <div class="tab-pane active" id="d-main">
					  
						<div class="row" style="margin-left:0">
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputTitle">Title Tag</label>
								<div class="controls" style="position:relative">
									<input type="text" id="txtTitle" name="txtTitle"
										placeholder="Enter the title of the page"
										title="Enter the full title of the page here."
										data-toggle="tooltip" 
										value="<?php echo $title; ?>"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><br>
										<label class="checkbox" <?php if ($id == 1 || $id == 2) echo 'style="display:none"';?>>
										  <input name="ckPublished" type="checkbox" id="ckPublished" value="checkbox" <?php echo $published; ?>>
										  Published on site
										</label>
										<label class="checkbox checkRight" <?php if ($id == 1 || $id == 2) echo 'style="display:none"';?>>
										  <input name="cknositemap" type="checkbox" id="cknositemap" value="checkbox" <?php echo $nositemap; ?>>
										  Skip from <a href="/sitemap.xml" target="_blank">sitemap.xml</a>										
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
										value="<?php echo $name; ?>"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><br>
									<?php if ($published!='checked') 
												echo '<span class="label label-important">Unpublished page only visible when logged in.</span>';
											else 
												echo '<span class="label label-info">Page is published and visible to all.</span>'; ?>
									
								</div>
							  </div>								
							</div>
						</div>

						<div class="row" style="margin-left:0">
							<div class="span6">

							  <div class="control-group">
								<label class="control-label" for="inputName">Parent Page</label>
								<div class="controls">
								  <?php if ($id == 1 || $id == 2) echo 
								  			'<div class="alert alert-info" style="margin: 0 0 3px;padding: 5px 10px;"><strong>'.
												'Site Root</strong></div>';
										else echo 
											'<select name="slGroup" id="slGroup" class="input-block-level">' . 
													$dropdownOptionsHTML . '</select>'; ?>
								</div>
							  </div>
								
							</div>
							<div class="span6">
							
							  <div class="control-group">
								<label class="control-label" for="inputName">Layout</label>
								<div class="controls">
									<select name="slLayout" id="slLayout" class="input-block-level">
										<?php 
											if (($slLayout=='') || ($slLayout=='layout.php'))
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

						<div class="row" style="margin-left:0">
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputDescription">Meta Description</label>
								<div class="controls">
									<textarea name="txtDesc" rows="5" id="txtDesc" 
										placeholder="Enter the description of the page"
										title="Enter the description of the page here, this is VERY IMPORTANT for SEO. Do not duplicate on all pages"
										data-toggle="tooltip"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><?php echo $description; ?></textarea>
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
										class="input-block-level tooltipme2 countme2"><?php echo $keywords; ?></textarea>
								</div>
							  </div>							
							</div>
						</div>							
					  </div>
					  
					  <div class="tab-pane" id="d-content">
					    <input border="0" class="input-block-level" name="txtURL" onFocus="this.select();" 
							style="cursor: pointer;" onClick="this.select();"  type="text" value="<?php echo $url; ?>" readonly/> 
						<textarea name="txtMain" rows="30" id="txtMain" style="height: 420px; width:100%"
							class="input-block-level"><?php echo $maincontent; ?></textarea>
					  </div>
					    
					  <div class="tab-pane" id="d-header">
						<div class="row" style="margin-left:0">
							<div class="span4">
								<label class="checkbox">
								  <input name="ckHeader" type="checkbox" id="ckHeader" value="checkbox" <?php echo $useheader; ?>>
								  Enable custom header
								</label>
							</div>
							<div class="span4" style="text-align:center">
								<?php if ($useheader=='checked') 
											echo '<span class="label label-important">Page will display custom header below.</span>';
										else 
											echo '<span class="label label-info">Page will display the default header.</span>'; ?>
							</div>									
							<div class="span4" style="text-align:right ">
								<a href="scripts/copy-block.php?headcopyid=<?php echo $id; ?>" class="btn btn-mini btn-primary">Copy Default Header</a>
							</div>
						</div>
						<textarea name="txtHeader" rows="30" id="txtHeader" style="height: 420px; width:100%"
							class="input-block-level"><?php echo $header; ?></textarea>
					  </div>
					  
					  <div class="tab-pane" id="d-sidebar">
						<div class="row" style="margin-left:0">
							<div class="span4">
								<label class="checkbox">
								  <input name="ckside" type="checkbox" id="ckside" value="checkbox" <?php echo $useside; ?>>
								  Enable custom sidebar A
								</label>
							</div>
							<div class="span4" style="text-align:center">
								<?php if ($useside=='checked') 
											echo '<span class="label label-important">Page will display custom sidebar A below.</span>';
										else 
											echo '<span class="label label-info">Page will display the default sidebar A.</span>'; ?>
							</div>									
							<div class="span4" style="text-align:right ">
								<a href="scripts/copy-block.php?sidecopyid=<?php echo $id; ?>" class="btn btn-mini btn-primary">Copy Default Sidebar A</a>
							</div>							
						</div>
						<textarea name="txtSide" rows="30" id="txtSide" style="height: 420px; width:100%"
							class="input-block-level"><?php echo $sidebar; ?></textarea>
					  </div>
					  
					  <div class="tab-pane" id="d-siderbar">
						<div class="row" style="margin-left:0">
							<div class="span4">
								<label class="checkbox">
								  <input name="cksider" type="checkbox" id="cksider" value="checkbox" <?php echo $usesider; ?>>
								  Enable custom sidebar B
								</label>
							</div>
							<div class="span4" style="text-align:center">
								<?php if ($usesider=='checked') 
											echo '<span class="label label-important">Page will display custom sidebar B below.</span>';
										else 
											echo '<span class="label label-info">Page will display the default sidebar B.</span>'; ?>
							</div>									
							<div class="span4" style="text-align:right ">
								<a href="scripts/copy-block.php?sidercopyid=<?php echo $id; ?>" class="btn btn-mini btn-primary">Copy Default Sidebar B</a>
							</div>							
						</div> 					  
						<textarea name="txtrSide" rows="30" id="txtrSide" style="height: 420px; width:100%"
							class="input-block-level"><?php echo $siderbar; ?></textarea>
					  </div>
					  
					  <div class="tab-pane" id="d-footer">
						<div class="row" style="margin-left:0">
							<div class="span4">
								<label class="checkbox">
								  <input name="ckFooter" type="checkbox" id="ckFooter" value="checkbox" <?php echo $usefooter; ?>>
								  Enable custom footer
								</label>
							</div>
							<div class="span4" style="text-align:center">
								<?php if ($usefooter=='checked') 
											echo '<span class="label label-important">Page will display custom footer below.</span>';
										else 
											echo '<span class="label label-info">Page will display the default footer.</span>'; ?>							
							</div>								
							<div class="span4" style="text-align:right ">
								<a href="scripts/copy-block.php?footcopyid=<?php echo $id; ?>" class="btn btn-mini btn-primary">Copy Default Footer</a>
							</div>							
						</div> 
						<textarea name="txtFooter" id="txtFooter" rows="30" style="height: 420px; width:100%"
							class="input-block-level"><?php echo $footer; ?></textarea>
					  </div>
					  
					  <div class="tab-pane" id="d-head">

						<blockquote>
						  <p>Append to page head (&lt;head&gt;...include here&lt;\head&gt;) </p>
						  <small>Enter additional <strong>page head content</strong> for this page, you can include js, css, or anything else here</small>
						</blockquote>

						<textarea name="txtHead" rows="30" id="txtHead" style="height: 320px; width:100%"
							class="input-block-level"><?php echo $head; ?></textarea>
					  </div>
					
					  </div>
					</div>				  
				  	</form>
				</div>
				<div class="clearfix"></div>
			  </div>
			</div>
		</div> 
	</div>
	
<?php include('include/footer.php'); ?>
<div id="myModal" class="modal hide fade" style="width:90%; margin:2% auto;left: 5%;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h5 style="margin:0; ">Page Stats</h5>
  </div>
  <div class="modal-body">
    <iframe id="shrFrm" src="loading.php"
		width='100%' height='480px' frameborder='0' marginheight='0' marginwidth='0' scrolling="auto"></iframe>
  </div>
</div>
<script type="text/javascript">
	
	$('.lframe').click( function() {
		// set the src of the iframe here		
		$('#myModal .modal-header h5').text($(this).attr('title'));
		$('#shrFrm').attr('src',$(this).attr('href'));
		$('#myModal').modal('show');
		return false;
	});
	
	$('#left-tree.treeview li a').click( function() {
		$(this).attr('href', $(this).attr('href')+window.location.hash);
		return true;
	});
	
	$('#myModal').on('hidden', function () {
  		$('#shrFrm').attr('src','loading.php');
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
	
</script>
<script language="javascript" type="text/javascript" src="js/edit_area/edit_area_full.js"></script>

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
		if ((!txtMain_loaded)&&($(this).attr('href')=='#d-content')) {
			CodeMirror.fromTextArea(document.getElementById("txtMain"), codeMirrorJSON);
			txtMain_loaded = true;
		}
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
		if ((!txtHead_loaded)&&($(this).attr('href')=='#d-head')) {
			CodeMirror.fromTextArea(document.getElementById("txtHead"), codeMirrorJSON);
			txtHead_loaded = true;
		}
	});
	</script>


<?php } ?>
<script language="javascript" type="text/javascript">
	if(window.location.hash) $('a[href="'+window.location.hash.replace('#','#d-')+'"]').click(); 
</script>
</body></html>