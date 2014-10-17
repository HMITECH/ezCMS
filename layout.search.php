<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.0.0 Dated 23-Dec-2012
 * HMI Technologies Mumbai (2012-13)
 *
 * View: Default Front end layout - layout.search.php
 * 
 * This is a custom layout for the search page.
 * This layout will read the database and 
 * find all the pages which match.
 * It will also return json if ajax search is detected.
 *    TODO: Edit the code below to customise the
 *          look and feel of this page.
 */

// this function will return the html for each entry
function getEntryHTML($row, $term) {
	$key = '';
	$desc = '';
	// only add the description if available
	if (strlen($row['description']) > 1)
		$desc = '<br><span class="entry-description">'.
					preg_replace( '/('.$term .')/i',  '<span class="highlight">$1</span>', $row['description'] )
				.'</span>';
	// only add the keywords if available
	if (strlen($row['keywords']) > 1) {
		$key = str_replace(',' , ' | ' , $row['keywords']);
		//$key = str_replace($term , "<span class='highlight'>$term</span>" , $key);
		$key = preg_replace( '/('.$term .')/i',  '<span class="highlight">$1</span>', $key );
	}
	// return the html for this page
	return '<a href="' . $row['url'] . '" title="'.$row['description'].'">'.
				'<span class="entry-title">'.
					preg_replace( '/('.$term .')/i',  '<span class="highlight">$1</span>', $row['title'] ) .
				'</span>'.
				$desc. '</a><br><span class="entry-keywords">'.$key.'</span>';
}
 
// Check if ajax search
if (isset($_GET['ajax'])) {
	// If ajax search then output results json and exit.
	$term = ltrim($_GET['term']);
	if (strlen($term)>0) {	
		$sth = mysql_query("SELECT `id` , `title` , `url`, `description` FROM  `pages` WHERE ( 
			(`title` LIKE '%$term%') OR (`description` LIKE '%$term%') OR (`keywords` LIKE '%$term%') ) AND 
			(`published`=1 AND `id`<>2 AND `id`<>$id)");
		$rows = array();
		while($r = mysql_fetch_assoc($sth)) {
			$rows[] = $r;
		}
		die(json_encode($rows));
	} die('');
}
    
$term = '';
$searchresults = '';
// Search all the pages in the database
if (isset($_GET['term'])) {
	$term = trim($_GET['term']);
	if (strlen($term)>0) {
		$searchresults = "<h4>Results for <span class='highlight'>$term</span></h4>";
		$sql = "SELECT `id` , `title` , `url`, `description`, `keywords` FROM  `pages` WHERE ( 
			(`title` LIKE '%$term%') OR (`description` LIKE '%$term%') OR (`keywords` LIKE '%$term%') ) 
			AND (`published`=1)"; 
		$rs = mysql_query($sql) or die("Unable to Execute  Select query");
		$recordcount = mysql_num_rows($rs);
		if ($recordcount) {
			$searchresults .= '<ul>';
			while ($row = mysql_fetch_assoc($rs)) {
				// check if found page is not the search or 404 page
				if (($row['id']!=$id) && ($row['id']!=2)) {
					$searchresults .= '<li>' . getEntryHTML($row, $term) . '</li>';
				}
			}
			$searchresults .= '</ul>';	
		} else {
			$searchresults .= '<h5>Sorry, nothing was found for this term.</h5>';	
		}
	}
}
 
?>
<!DOCTYPE html><html><head>

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
    <?php echo $head; ?>
	<script type="text/javascript">
	$(function() {
		$( "#term" ).autocomplete({
		  minLength: 1,
		  source: "/search.html?ajax=",
		  focus: function( event, ui ) {
			$( "#term" ).val( ui.item.title );
			return false;
		  },
		  select: function( event, ui ) {
			window.location.href = ui.item.url;
			return false;
		  }
		})
		.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		  var thisTerm = $("#term").val();
		  var regex = new RegExp( '(' + thisTerm + ')', 'gi' );
		  var thisTitle = item.title.replace( regex , '<span class="highlight">$1</span>');
		  var thisDesc = item.description.replace( regex , '<span class="highlight">$1</span>');
		  return $( "<li>" )
			.append( "<a>" + thisTitle + "<br><small>" + thisDesc + "</small></a>" )
			.appendTo( ul );
		};
	});
	</script>

</head><body><div id="main">

    <div id="header"><?php echo $header; ?></div>
    
    <div id="content">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr>
			  <td width="20%" style="vertical-align: top; background-color: #EEE; padding: 5px;">
			  <div class="sidebar"><?php echo $sidebar;?></div>
			  </td>
			  <td width="60%" style="vertical-align: top; background-color: #FFF; padding: 5px;">
					<div class="maincontent">
						<div id="searchBlock" style="text-align:center; margin:20px ">
						
							<h4>Search the site</h4>
							<form action="" id="search" method="GET">
								<input name="term" id="term" value="<?php echo $term; ?>" placeholder="Search here..." type="text"> 
								<input type="submit" value="Go">
							</form>
							<br><br><hr>
						</div>
						<?php echo $searchresults; ?>
					</div>
			  </td>
			  <td width="20%" style="vertical-align: top; background-color: #EEE; padding: 5px;">
			  <div class="sidebar"><?php echo $siderbar;?></div>
			  </td>
			</tr>
		</table>
    </div>
    
    <div id="footer"><?php echo $footer; ?></div>
    
</div></body></html>
