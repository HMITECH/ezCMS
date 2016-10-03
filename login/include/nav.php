<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Include: Displays the navigation bar
 * 
 */ 
	if ($_SESSION['EDITORTYPE']==0) $cke = 'class="badge-warning"'; else $cke = '';
	if ($_SESSION['EDITORTYPE']==1) $eda = 'class="badge-warning"'; else $eda = '';
	if ($_SESSION['EDITORTYPE']==2) $txt = 'class="badge-warning"'; else $txt = ''; 
	if ($_SESSION['EDITORTYPE']==3) $cme = 'class="badge-warning"'; else $cme = ''; 
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
		  <?php if ($_SESSION['EDITORTYPE'] == 3) { ?><li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-edit"></i> Editor Style <b class="caret"></b></a>
			  <div id="divCmTheme" class="dropdown-menu" style="padding:10px;">
				<blockquote>
				  <p><i class="icon-edit"></i> Code Mirror Theme</p>
				  <small>Change Code Mirror Theme</small>
				</blockquote>
				<div>
				  <select id="slCmTheme">
					<option selected>default</option>
					<option>3024-day</option>
					<option>3024-night</option>
					<option>abcdef</option>
					<option>ambiance</option>
					<option>base16-dark</option>
					<option>base16-light</option>
					<option>bespin</option>
					<option>blackboard</option>
					<option>cobalt</option>
					<option>colorforth</option>
					<option>dracula</option>
					<option>eclipse</option>
					<option>elegant</option>
					<option>erlang-dark</option>
					<option>hopscotch</option>
					<option>icecoder</option>
					<option>isotope</option>
					<option>lesser-dark</option>
					<option>liquibyte</option>
					<option>material</option>
					<option>mbo</option>
					<option>mdn-like</option>
					<option>midnight</option>
					<option>monokai</option>
					<option>neat</option>
					<option>neo</option>
					<option>night</option>
					<option>paraiso-dark</option>
					<option>paraiso-light</option>
					<option>pastel-on-dark</option>
					<option>railscasts</option>
					<option>rubyblue</option>
					<option>seti</option>
					<option>solarized dark</option>
					<option>solarized light</option>
					<option>the-matrix</option>
					<option>tomorrow-night-bright</option>
					<option>tomorrow-night-eighties</option>
					<option>ttcn</option>
					<option>twilight</option>
					<option>vibrant-ink</option>
					<option>xq-dark</option>
					<option>xq-light</option>
					<option>yeti</option>
					<option>zenburn</option>
				  </select>
				</div>
			  </div>
		  </li><?php } ?>		
		</ul>
		
		<ul class="nav pull-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-share"></i>
					Welcome <?php echo $_SESSION['LOGINNAME']?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="profile.php"><i class="icon-comment"></i> Change Password</a></li>
					<li class="divider"></li>
					<li class="nav-header">Select Editor</li>
 					<li <?php echo $cme; ?>>
						<a href="scripts/chg-editor.php?etype=3">
							<i class="icon-edit"></i> Code Mirror</a></li> 
					<li <?php echo $cke; ?>>
						<a href="scripts/chg-editor.php?etype=0">
							<i class="icon-calendar"></i> CK Editor</a></li>
					<li <?php echo $eda; ?>>
						<a href="scripts/chg-editor.php?etype=1">
							<i class="icon-folder-close"></i> Edit Area</a></li>
					<li <?php echo $txt; ?>>
						<a href="scripts/chg-editor.php?etype=2">
							<i class="icon-hdd"></i> Text Area</a></li>
					<li class="divider"></li>
					<li><a href="scripts/logout.php"><i class="icon-off"></i> Logout</a></li>
				</ul>
			</li>
		</ul>
		
	  </div>
	</div>
  </div>
</div>
