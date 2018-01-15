<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net and mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 * 
 * The layout is an example to show pages with only left sidebar
 * without the aside content. 
 * Change the layout of any page to this for full width content.
 */

?><!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	
    <title><?php echo $page["title"]; ?></title>
    <link rel="canonical" href="http://<?php echo $page["canonical"]; ?>">
	
    <meta name="keywords" content="<?php echo $page["keywords"]; ?>">
    <meta name="description" content="<?php echo $page["description"]; ?>">
	
    <link rel="stylesheet" href="<?php echo $siteFolder; ?>/style.css">
    <script src="<?php echo $siteFolder; ?>/main.js"></script>
	
    <?php echo $page["head"]; ?>

</head>
<body>

	<header><?php echo $header; ?></header>
	
	<table>
		<tr>
			<td class="asidebar"><aside><?php echo $sidebar;?></aside></td>
			<td><main><?php echo $maincontent;?></main></td>
		</tr>
	</table>
	
	<footer><?php echo $footer; ?></footer>
    
</body>
</html>
