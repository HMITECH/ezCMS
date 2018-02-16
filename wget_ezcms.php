<h1>WGET ezCMS Master</h1>
<?php

$repo = file_get_contents('https://github.com/HMITECH/ezCMS/archive/master.zip');

if ($repo) {

	unlink('ezCMS-master.zip');
	file_put_contents('ezCMS-master.zip', $repo);

	if (file_exists('ezCMS-master.zip')) 
		die('<h1>Repo Copied, unzip and open URL to proceed.</h1>');
}

?>
<h2>WGET failed. Copy repo master manually from <a href="https://github.com/HMITECH/ezCMS">github</a></h2>