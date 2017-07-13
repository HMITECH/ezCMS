<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.0.0 Dated 23-Dec-2012
 * HMI Technologies Mumbai (2012-13)
 *
 * View: Default Front end layout - layout.home.php
 * 
 * This is a custom layout for the home page
 * it will render the content without any sidebars 
 * This is an example of how to use custom layouts.
 */
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

    <div id="header">
    	<?php echo $header; ?>
    </div>
    
    <div id="content">
    	<?php echo $maincontent;?>
    </div>
    
    <div id="footer">
    	<?php echo $footer; ?>
    </div>
    
</div></body></html>
