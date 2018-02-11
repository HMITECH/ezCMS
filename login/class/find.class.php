<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Find and Replace Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezFind extends ezCMS {

	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Handle post request
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$r = new stdClass();
			$r->success = true;
			$findin = $_POST['findinTxt'];
			if ($findin == 'body') $this->findBody();
			if ($findin == 'head') $this->findHead();
			if ($findin == 'php' ) $this->findLayouts();
			if ($findin == 'css' ) $this->findCSS();
			if ($findin == 'js'  ) $this->findJS();
			die(json_encode($r));
		}

	}
	
	private function findBody () {

	}
	
	private function findHead () {

	}
	
	private function findLayouts () {
		foreach (glob("../layout.*.php") as $entry) {
			// open and find in this file ... 
			$content = file_get_contents($entry);
			
		}
	}	
	
	private function findCSS () {
		foreach (glob("../site-assets/css/*.css") as $entry) {
			// open and find in this file ... 
		}
	}
	
	private function findJS () {
		foreach (glob("../site-assets/js/*.js") as $entry) {
			// open and find in this file ... 
		}
	}
/*	
	private function findinhead () {
		$stmt = $this->prepare("SELECT id, pagename, url FROM `pages` WHERE 
			`head` LIKE CONCAT ('%' , :findstr , '%') OR 
			`title` LIKE CONCAT ('%' , :findstr , '%') OR 
			`keywords` LIKE CONCAT ('%' , :findstr , '%') OR 
			`description` LIKE CONCAT ('%' , :findstr , '%') ");
		$stmt->bindParam(':findstr', $_POST['find'], PDO::PARAM_STR);
		$stmt->execute();
		$r = new stdClass();
		$r->success = true;
		$r->pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
		die(json_encode($r));		
	}	
	
	private function findinbody($findstr) {
		$stmt = $this->prepare("SELECT id, pagename, url FROM `pages` WHERE 
			`maincontent` LIKE CONCAT ('%' , :findstr , '%') OR
			`headercontent` LIKE CONCAT ('%' , :findstr , '%') OR
			`footercontent` LIKE CONCAT ('%' , :findstr , '%') ");
		$stmt->bindParam(':findstr', $_POST['find'], PDO::PARAM_STR);
		$stmt->execute();
		$r = new stdClass();
		$r->success = true;
		$r->pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
		die(json_encode($r));		
	}
	
	private function replace($findstr, $replacestr) {
		$r = new stdClass();
		$r->success = true;
		//$r->pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
		die(json_encode($r));		
	}
*/
}
?>