<?php 
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Include: Contains the functions used by pages.php
 * 
 */
 
// This function will return a valid items name which can be used as the url of the item.
function validateName($input) {
	return str_replace(array(" ", "/", "`", "'", ",", "?", "&", "@", "!", ".", ">", "<", ":",";"), 
		"-", trim($input));
} 
 
// this function will echo the ul li for the pages in the site 
function getTreeHTML($id, $pageid , $parentid, $url) {
	static $ddOptions = '';
	static $nestCount;
	 
	$sql = 'select `id` , `title` , `url`, `published`, `description` from  `pages` where `parentid` = ' . $id . ' order by place;';		
    $rs = mysql_query($sql) or die("Unable to Execute  Select query");
    $recordcount = mysql_num_rows($rs);
    
    if ($recordcount) {	 
		$nestCount += 1;
		if ($nestCount == 1) echo '<ul id="left-tree">'; else echo '<ul>';
	    $cnt = 0;
	    while ($row = mysql_fetch_assoc($rs)) {
		    $cnt++;
			$liclass = '';
		    if ($row['id']==1)
				{ $action = '<i class="icon-home"></i> ';$liclass = ' class="open" '; }
			elseif 	($row['id']==2)
				$action = '<i class="icon-question-sign"></i>  ';
			elseif ($recordcount < 2) 
		    	$action = '<i class="icon-file"></i> ';
		    else
		    	if ($cnt == 1)
		    		$action = '<a style="font-size:small;" href="scripts/move-page.php?downid=' . $row['id'] . '"><i class="icon-arrow-down"></i> </a>';		    	
		    	elseif ($cnt == $recordcount)
		    		$action = '<a style="font-size:small;" href="scripts/move-page.php?upid='   . $row['id'] . '"><i class="icon-arrow-up"></i></a>';			    	
		    	else
		    		$action = '<a style="font-size:small;" href="scripts/move-page.php?upid='   . $row['id'] . '"><i class="icon-arrow-up"></i></a>
			    			   <a style="font-size:small;" href="scripts/move-page.php?downid=' . $row['id'] . '"><i class="icon-arrow-down"></i></a>';
							   
			if ($pageid == $row['id']) $lStyle = 'class="label label-info"'; else  $lStyle = '';
			$thisURL = str_replace(".html","", $row['url']);
			//echo $url. '<br>' . $thisURL;
			if ( $thisURL == substr( $url,0,strlen($thisURL) ) ) $liclass = ' class="open" '; else $liclass = '';   
		    echo '<li' . $liclass . '>' . $action . '<a ' . $lStyle .' title="'.$row['description'].'" href="?id=' . $row['id'] . '">';
			if ($nestCount <= 2) echo '<strong>';
			echo $row['title'];
			if ($nestCount <= 2) echo '</strong>';
			if ($row['published']!=1) echo ' <i class="icon-ban-circle" title="Page is not published"></i> ';
			echo '</a>';
			if ($parentid == $row['id'])
				$ddOptions .= '<option value="' . $row['id'] . '" SELECTED>';
			else
				$ddOptions .= '<option value="' . $row['id'] . '">';
			$ddOptions .= str_repeat(' > ',$nestCount - 1) . $row['title'] . '</option>';
		    getTreeHTML($row['id'], $pageid , $parentid, $url);
		    echo '</li>';
		}
     	echo '</ul>';
		$nestCount -= 1;
     	return $ddOptions;
     }
}

// this function will reIndex the pages, create sitemap.xml and set the url for all the pages
function reIndexPages() {

	// Create the XML Site Map
	$sitemapXML  = '<?xml version="1.0" encoding="UTF-8"?>';
	$sitemapXML  .= '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>
						<!-- generator="ezCMS" -->
						<!-- sitemap-generator-url="http://www.hmi-tech.net" sitemap-generator-version="2.0" -->';
	$sitemapXML  .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	$sitemapXML  .= '<url><loc>http://' . $_SERVER['SERVER_NAME'] .  '/index.html</loc></url>';

	$sql = 'SELECT `id` , `pagename` , `parentid`, `published` FROM `pages` WHERE `id` > 2 AND `nositemap` < 1';
	$rs = mysql_query($sql) or die("Unable to Execute  Select query");

	while ($row = mysql_fetch_assoc($rs)) {
		$url = $row['pagename'] . '.html';
		if ($row['parentid'] > 1) $url = getPagePath($row['parentid']) . $url;
		//  XML Site Map
		if ($row['published']==1) $sitemapXML  .= '<url><loc>http://' . $_SERVER['SERVER_NAME'] . '/' . $url . '</loc></url>';
		// update table
		mysql_query("UPDATE `pages` SET `url` = '/" . $url ."' WHERE `id` = " . $row['id']);
	}
	$sitemapXML  .= '</urlset>';

	// save XML Site Map
	if (preg_match('/pages\.php$/', $_SERVER['SCRIPT_NAME'])) $filename = '../sitemap.xml';
	else $filename = '../../sitemap.xml';
	$handle = fopen($filename,"w");
	fwrite($handle, $sitemapXML);
	fclose($handle);
}

// the function will return the URI of the page
function getPagePath($id) {
	$path='';
	$sql = 'SELECT `id` , `pagename` , `parentid` FROM `pages` WHERE `id` = ' . $id . '; ';
	$rs = mysql_query($sql) or die("Unable to Execute  Select query");
	$row = mysql_fetch_assoc($rs);
	$path .= $row['pagename'] . '/';
	if ($row['parentid'] > 2) $path = getPagePath($row['parentid']) . $path;
	return $path;
}

// the function will resolve any page place error
function resolveplace() {
	$qry = 'UPDATE `pages` set `place` = `id` WHERE `place` = 0;';
	mysql_query($qry);
}

// this function will return the error html if any
function getErrorMsg($flg) {
	$msg = "";

	if ($flg=="red") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Save Failed!</strong> An error occurred and the page was NOT saved.</div>';
	if ($flg=="green")
		$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Saved!</strong> You have successfully saved the page.</div>';	
					
	if ($flg=="pink") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Add Page Failed!</strong> An error occurred and the page was NOT added.</div>';
	if ($flg=="added")
		$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Added!</strong> You have successfully added the page.</div>';			
	
	if ($flg=="headcopyfailed") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Header Copy Failed!</strong> An error occurred and the header was NOT copied.</div>';
	if ($flg=="headcopied")
		$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Copied!</strong> You have successfully copied the header.</div>';
	
	if ($flg=="sidecopyfailed") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Sidebar A Copy Failed!</strong> An error occurred and the sidebar A was NOT copied.</div>';
	if ($flg=="sidecopied")
		$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Copied!</strong> You have successfully copied the sidebar A.</div>';
	
	if ($flg=="sidercopyfailed") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Sidebar B Copy Failed!</strong> An error occurred and the sidebar B was NOT copied.</div>';
	if ($flg=="sidercopied")
		$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Copied!</strong> You have successfully copied the sidebar B.</div>';
					
	if ($flg=="footcopyfailed") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Footer Copy Failed!</strong> An error occurred and the footer was NOT copied.</div>';
	if ($flg=="footcopied")
		$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Copied!</strong> You have successfully copied the footer.</div>';

	if ($flg=="copyfailed") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Copy Failed!</strong> An error occurred and the page was NOT copied.</div>';
	if ($flg=="copied")
		$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Copied!</strong> You have successfully copied the page.</div>';

	if ($flg=="delfailed") 
		$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Delete Failed!</strong> An error occurred and the page was NOT deleted.</div>';
	if ($flg=="deleted")
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Deleted!</strong> You have successfully deleted the page.</div>';

	if ($flg=="noname") 
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Invalid Page Name!</strong> Please check the page name.</div>';
	if ($flg=="notitle") 
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Invalid Page Title!</strong> Please check the page title.</div>';
	if ($flg=="nestedparent") 
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Nested Parent Error!</strong> The Page cannot be its own parent.</div>';					
	if ($flg=="noperms") 
		$msg = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Permission Denied!</strong> You do not have permissions for this action.</div>';
	if ($flg=="yell") 
		$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
					<strong>Not Found!</strong> You have requested a page which does not exist.</div>';	
					
	return $msg;
}
?>
