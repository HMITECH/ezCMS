<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * Include: Displays the navigation bar
 * 
 */
	if ($_SESSION['EDITORTYPE']==0) $cke = 'class="badge-warning"'; else $cke = '';
	if ($_SESSION['EDITORTYPE']==1) $eda = 'class="badge-warning"'; else $eda = '';
	if ($_SESSION['EDITORTYPE']==2) $txt = 'class="badge-warning"'; else $txt = ''; 
?>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
	<div class="container-fluid">
	  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> 
		<span class="icon-bar"></span> 
		<span class="icon-bar"></span> 
		<span class="icon-bar"></span> 
	  </button>
	  <a class="brand" href="/"><small>ezCMS &middot; <?php echo $_SERVER['HTTP_HOST']; ?></small></a>
	  <div class="nav-collapse collapse">

		<ul class="nav" id="top-bar">
		
		  <li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-th-large"></i> Template <b class="caret"></b></a>
			  <ul class="dropdown-menu">
				<li><a href="setting.php"><i class="icon-th-list"></i> Settings</a></li>
				<li><a href="controllers.php"><i class="icon-play"></i> Controller</a></li>
				<li class="divider"></li>
				<li><a href="layouts.php"><i class="icon-list-alt"></i> Layouts</a></li>
				<li><a href="styles.php"><i class="icon-pencil"></i> Stylesheets</a></li>
				<li><a href="scripts.php"><i class="icon-align-left"></i> Javascripts</a></li>
				
				<li class="divider"></li>
				<li><a href="files.php"><i class="icon-folder-open"></i> File Manager</a></li>
			  </ul>
		  </li>	
		  
		  <li class="active"><a href="pages.php"><i class="icon-file"></i> Pages</a></li>
		  <li><a href="users.php"><i class="icon-user"></i> Users</a></li>
		  
		  <li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Tracking <b class="caret"></b></a>
			  <ul class="dropdown-menu">
				<li><a href="traffic.php"><i class="icon-signal"></i> Traffic Analysis<br>
					<img align="middle" height="90" alt="phpTrafficA statistics" src="traffic/imagestats.php?sid=39547">
				</a></li>
				<li class="divider"></li>
				<li><a href="options.php"><i class="icon-book"></i> Tracking Options</a></li>
				
			  </ul>
		  </li>
		</ul>
		
		<ul class="nav pull-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-share"></i>
					Welcome <?php echo $_SESSION['LOGINNAME']?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="profile.php"><i class="icon-comment"></i> Change Password</a></li>
					<li class="divider"></li>
					<li class="nav-header">Select Editor</li>
					<li <?php echo $cke; ?>>
						<a href="scripts/chg-editor.php?etype=0">
							<i class="icon-calendar"></i> CKEditor</a></li>
					<li <?php echo $eda; ?>>
						<a href="scripts/chg-editor.php?etype=1">
							<i class="icon-folder-close"></i> EditArea</a></li>
					<li <?php echo $txt; ?>>
						<a href="scripts/chg-editor.php?etype=2">
							<i class="icon-hdd"></i> TextArea</a></li>
					<li class="divider"></li>
					<li><a href="scripts/logout.php"><i class="icon-off"></i> Logout</a></li>
				</ul>
			</li>
		</ul>		
	  </div>
	</div>
  </div>
</div>
