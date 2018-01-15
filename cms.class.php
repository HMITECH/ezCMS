<?php 
/*
 * Code written by mo.ahmed@hmi-tech.net 
 *
 * HMI Technologies Mumbai - DEC 2017
 *
 */
class db extends PDO {	

	public function __construct() { 
	
		// read config file 
		if (!file_exists('config.php')) {
			if (!file_exists('install.php')) die('FATAL : Config and installer missing.');
			header("Location: install.php"); 
			exit; 
		}
		
		$config = include("config.php");
		
		try {

			parent::__construct( 'mysql:host='.
				$config['dbHost'].';dbname='.
				$config['dbName'], 
				$config['dbUser'], 
				$config['dbPass'] );

			$this->exec("SET names utf8");

			/* SET TIME ZONE IF Defined*/
			if (isset($config['dbTime'])) {
				date_default_timezone_set(); // 'Europe/Stockholm'
				$dt = new DateTime($config['dbTime']);
				$offset = $dt->format("P");	
				$this->exec("SET time_zone='$offset';");
			}

		} catch (PDOException $e) {

			/** MySQL Connection error message */ 
			header('HTTP/1.0 500 Internal Server Error');
			die("<h1>Connection to Database failed.</h1><p>Check config.php file</p>");


		}
	}
	
}?>