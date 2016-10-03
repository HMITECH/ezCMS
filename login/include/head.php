<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Include: Displays the common head
 * 
 */
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">	
<meta name="author" content="mo.ahmed@hmi-tech.net">
<meta name="robots" content="noindex, nofollow">
<link type="image/x-icon" href="favicon.ico" rel="icon"/>
<link type="image/x-icon" href="favicon.ico" rel="shortcut icon"/>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="js/jquery.treeview/jquery.treeview.css" rel="stylesheet">
<?php if ((isset($_SESSION['EDITORTYPE'])) &&  ($_SESSION['EDITORTYPE'] == 3)) { ?>
	<link href="codemirror/lib/codemirror.css" rel="stylesheet">
	<link rel="stylesheet" href="codemirror/addon/fold/foldgutter.css" />
	<?php if ($_SESSION["CMTHEME"]!='default') { ?>
		<link rel="stylesheet" href="codemirror/theme/<?php echo $_SESSION["CMTHEME"]; ?>.css">
	<?php } ?>
	<link rel="stylesheet" href="codemirror/addon/hint/show-hint.css">
<?php } ?>
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
<![endif]-->
<style type="text/css"> 
	html,body {height: 100%;}
	body {
		/*background: linear-gradient(to bottom, #000000 0%, #F5F5F5 100%);*/
		background: url('img/bg.png');
		background-attachment: fixed;
	}
	.nav-tabs > .active > a, .nav-tabs > .active > a:hover,
	a,button { outline:none; }
	#wrap {
		min-height: 100%;
		height: auto !important;
		height: 100%;
		margin: 0 auto -30px;}
	#txtContents {
		height: 420px; 
		width:100%;
	}		
	#push,#footer {height: 30px;}
	#footer {background-color: rgba(245, 245, 245, 0.75);}
	.tooltip-inner {font-size:18px;}
	.label, .badge {white-space:normal;}
	.CodeMirror {height: auto;}
	.CodeMirror-scroll { min-height:420px; }
	div.white-boxed {
		background: rgba(255, 255, 255, 0.95);
		border: 1px solid #000;
		padding: 10px;
		margin-bottom:10px;
		box-shadow: 0 7px 4px -4px #333;
	}
	@media (max-width: 767px) {
		#footer {
			margin-left: -20px;
			margin-right: -20px;
			padding-left: 20px;
			padding-right: 20px;
			height: auto;}
		.navbar-text.pull-right {
			float: none;
			padding-left: 5px;
			padding-right: 5px;}        
	}
</style>
