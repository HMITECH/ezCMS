<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.0.0 Dated 23-Dec-2012
 * HMI Technologies Mumbai (2012-13)
 *
 * View: Default Front end layout - layout.sitemap.php
 * 
 * This is a custom layout for the sitemap page.
 * This layout will read the database and 
 * dynamically create the sitemap of the site.
 *    TODO: Edit the code below to customise the
 *          look and feel of this page.
 */

// this function will return the html for each entry
function getEntryHTML($row) {
	$key = '';
	$desc = '';
	// only add the description if available
	if (strlen($row['description']) > 1)
		$desc = '<br><span class="entry-description">'.$row['description'].'</span>';
	// only add the keywords if available
	if (strlen($row['keywords']) > 1)
		$key = str_replace(',' , ' | ' , $row['keywords']);
	// return the html for this page
	return '<a href="' . $row['url'] . '" title="'.$row['description'].'">'.
				'<span class="entry-title">'.$row['title'].'</span>'.
				$desc. '</a><br><span class="entry-keywords">'.$key.'</span>';
}

 
// Recurrsively fetch all the pages from the database
function getSiteMap($id) {
	$sql = "SELECT `id` , `title` , `url`, `published`, `description`, `keywords` " .
			"FROM  `pages` WHERE `parentid` = $id ORDER BY `place`";		
    $rs = mysql_query($sql) or die("Unable to Execute  Select query");
    $recordcount = mysql_num_rows($rs);
    if ($recordcount) {	 
		echo '<ul>';
	    while ($row = mysql_fetch_assoc($rs)) {
			// check if page is published and is not 404 page
			if (($row['published']==1) && ($row['id']<>2)) {
				echo '<li>' . getEntryHTML($row);
				if ($row['id']!=1) getSiteMap($row['id']);
				echo '</li>';
			}
		}
     	echo '</ul>';
     }
}
?><!DOCTYPE html><html><head>

    <title><?php echo $title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="canonical" href="http://<?php echo $canonical; ?>">
    <meta content="<?php echo $keywords; ?>" name="KEYWORDS"/>
    <meta content="<?php echo $description; ?>" name="DESCRIPTION"/>
    <meta content="http://www.hmi-tech.net" name="GENERATOR"/>
    <meta name="Robots" content="index, follow">
    <meta name="Revisit-After" content="30 days">
    <meta name="Rating" content="General">	
    <link href="/style.css" rel="stylesheet" type="text/css" />
    <script src="/main.js" type="text/javascript"></script>
    <?php echo $head; ?>
	
</head><body><div id="main">

    <div id="header"><?php echo $header; ?></div>
    
    <div id="content">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr>
			  <td width="30%" style="vertical-align: top; background-color: #EEE; padding: 5px;">
			  <div class="sidebar"><?php echo $sidebar;?></div>
			  </td>
			  <td width="70%" style="vertical-align: top; background-color: #FFF; padding: 5px;">
			  <div class="maincontent"><?php 
			  		// fetch all top level pages from the database
					$sql = "SELECT `id` , `title` , `url`, `published`, `description`, `keywords` " .
							"FROM  `pages` WHERE `parentid` < 2 ORDER BY `place`";		
					$rs = mysql_query($sql) or die("Unable to Execute  Select query");
					$recordcount = mysql_num_rows($rs);
					echo '<ul>';
					while ($row = mysql_fetch_assoc($rs)) {
						// check if page is published and is not 404 page
						if (($row['published']==1) && ($row['id']<>2)) {
							echo '<li>' . getEntryHTML($row);
							if ($row['id']!=1) getSiteMap($row['id']);
							echo '</li>';
						}
					}
					echo '</ul>';
			  ?></div>
			  </td>
			</tr>
		</table>
    </div>
    
    <div id="footer"><?php echo $footer; ?></div>
    
</div></body></html>
