<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * Version 4.160210
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Class 
 * 
 */

// **************** DATABASE ****************
require_once ("../cms.class.php"); // PDO Class for database access

// Class to handle post data
class ezCMS extends db {
 
	public $flg = ''; 	// Set the error message flag to none
	public $msg = ''; 	// Message to disaply if any
	public $usr; 		// Logged in user record
	// Stores Revision Details
	public $revs = array('log' => '', 'opt' => '', 'cnt' => 1, 'jsn' => array());
	
	// Consturct the class
	public function __construct ( $loginRequired = true ) {
	
		// call parent constuctor
		parent::__construct();
		
		// Start SESSION if not started 
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start(); 
		}
		
		// Set SESSION ADMIN Login Flag to false if not set
		if (!isset($_SESSION['LOGGEDIN'])) {
			$_SESSION['LOGGEDIN'] = false;
		}
		
		// Redirect the user if NOT logged in
		if ((!$_SESSION['LOGGEDIN']) && ($loginRequired) ) { 
			header("Location: index.php?flg=expired"); 
			exit; 
		}
		
		// Fetch the Logged in users details if login is required
		if ($loginRequired) { 
			$this->usr = $this->query('SELECT * FROM `users` WHERE `id` = '.
				$_SESSION['EZUSERID'].' LIMIT 1')->fetch(PDO::FETCH_ASSOC); // get the user details
		}
		
		// init revision vars
		$this->revs = array('log' => '', 'opt' => '', 'cnt' => 1, 'jsn' => array());
		
		// Check if Message Flag is set
		if (isset($_GET["flg"])) { 
			$this->flg = $_GET["flg"];
		}
		
	}
	
	// Add to Database table and returns new ID, false if failed
	protected function add($table, $data) {
	
		/* Uncomment to debug 
		die("INSERT INTO $table (`".
			implode("`,`", array_keys($data))."`) VALUES (".
			implode(',', array_fill(0, count($data), '?')).")");*/
		$stmt = $this->prepare("INSERT INTO $table (`".
			implode("`,`", array_keys($data))."`) VALUES (".
			implode(',', array_fill(0, count($data), '?')).")");
		if ($stmt->execute(array_values($data))) {
			$newid = $this->lastInsertId();			
			$this->query("OPTIMIZE TABLE $table");
			return $newid;
		} 
		return false;
		
	}
	
	// Edit Database table row
	protected function edit($table, $id, $data) {
	
		/* Uncomment to debug 
		die("INSERT INTO $table (`".
			implode("`,`", array_keys($data))."`) VALUES (".
			implode(',', array_fill(0, count($data), '?')).")");*/	
		$stmt = $this->prepare("UPDATE $table SET ".$this->arrayToPDOstr($data)." WHERE id = ? ");
		$data[] = $id;
		if ($stmt->execute(array_values($data))) {
			$this->query("OPTIMIZE TABLE $table");
			return true;
		} 
		return false;
		
	}
	
	// Delete from Database table
	protected function delete($t, $id) {
	
		$stmt = $this->prepare("DELETE FROM $t where id = ?");
		if ($stmt->execute(array($id))) {
			$this->query("OPTIMIZE TABLE $t");
			return true;
		}
		return false;
		
	}	

	// this function will set the formatted html to display
	protected function setMsgHTML ($class, $caption, $subcaption ) {
		$this->msg = '<div class="alert alert-'.$class.'">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>'.$caption.'</strong><br>'.$subcaption.'</div>';
	}
	
	protected  function fetchPOSTData($f, &$d) { 
		foreach($f as $k) {
			if (isset($_POST[$k])) {
				$d[$k] = trim($_POST[$k]); 
			} else {
				header('HTTP/1.1 400 BAD REQUEST');
				die('BAD REQUEST');
			}
		}
	}

	protected  function fetchPOSTCheck($f, &$d) { 
		foreach($f as $k) {
			$d[$k] = (isset($_POST[$k])) ? 1 : 0;
		}
	}
	
	private function arrayToPDOstr($a) { 
		$t = array(); 
		foreach (array_keys($a) as $n) {
			$t[] = "`$n` = ?"; 
		}
		return implode(', ', $t); 
	}	

}
?>