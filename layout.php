<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.0.0 Dated 23-Dec-2012
 * HMI Technologies Mumbai (2012-13)
 *
 * View: Default Front end layout - layout.php
 * 
 * The layout is a basic structure of the page to be rendered
 * you can copy his layout and create your own custom layouts 
 * and then use then in the pages of the site.
 */
?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html lang="en">
<head>

    <title><?php echo $title; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="canonical" href="http://<?php echo $canonical; ?>">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="generator" content="http://www.hmi-tech.net">
    <meta name="Robots" content="index, follow">
    <meta name="Revisit-After" content="30 days">
    <meta name="Rating" content="General">
    <link rel="stylesheet" href="/site-assets/css/normalize.css">
    <link rel="stylesheet" href="/style.css">
    <script src="/site-assets/js/modernizr-2.6.2.min.js"></script>
    <script src="/site-assets/js/jqery-1.9.1.min.js"></script>
    <script src="/main.js"></script>
    <?php echo $head; ?>
	
</head>
<body>
<div id="main">
    

    <header id="header">
    	<?php echo $header; ?>
    </header>
    
    <div id="content">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td width="20%" style="vertical-align: top; background-color: #EEE; padding: 5px;">
		  <div class="sidebar"><?php echo $sidebar;?></div>
	      </td>
	      <td width="60%" style="vertical-align: top; background-color: #FFF; padding: 5px;">
		  <div class="maincontent"><?php echo $maincontent;?></div>
	      </td>
	      <td width="20%" style="vertical-align: top; background-color: #EEE; padding: 5px;">
		  <div class="sidebar"><?php echo $siderbar;?></div>
	      </td>
	    </tr>
	</table>
    </div>
    
    <footer id="footer">
    	<?php echo $footer; ?>
    </footer>
    
</div>
<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src='//www.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
</body>
</html>
