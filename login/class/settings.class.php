<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * Version 4.160210
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Settings Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezSettings extends ezCMS {
	
	public $site;
	
	public $revs;	
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
				
		// Update the Controller of Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->update();
		}
		
		// fetch the data
		$this->site = $this->query('SELECT * FROM `site` ORDER BY `id` DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
		
		// Create the Revision Log here
		$this->revs['log'] = '';
		$this->revs['opt'] = '';
		$this->revs['cnt'] = 1;
		$revsql = "SELECT site.id,users.username,site.createdon
					FROM site LEFT JOIN users ON site.createdby = users.id
					WHERE site.id > 1 ORDER BY site.id DESC";		
		foreach ($this->query($revsql) as $entry) {
			$this->revs['opt'] .= '<option value="'.$entry['id'].'">#'.$this->revs['cnt'].' '.$entry['createdon'].' ('.$entry['username'].')</option>';	
			$this->revs['log'] .= '<tr><td>'.$this->revs['cnt'].'</td><td>'.$entry['username'].'</td><td>'.$entry['createdon'].'</td>
			  <td data-rev-id="'.$entry['id'].'"><a href="#">Revert</a> | <a href="#">Purge</a></td></tr>';
			$this->revs['cnt']++;
		}

		$this->revs['cnt']--;
		if ($this->revs['log'] == '') $this->revs['log'] = '<tr><td colspan="3">There are no revisions of the controller.</td></tr>';
		
		// Get the Message to display if any
		$this->getMessage();

	}
	
	// Function to Update the Defaults Settings
	private function update() {

		// Check permissions
		if (!$this->usr['editsettings']) {
			header("Location: setting.php?flg=noperms");
			exit;
		}
		
		// array to hold the data
		$data = array();
		
		// get the required post varables 
		$this->fetchPOSTData(array(
			'headercontent',
			'sidecontent', 
			'sidercontent', 
			'footercontent'), $data);

		// create a copy 
		$this->query($revsql);

		die('save it');

	}
	
	// Function to Set the Display Message
	private function getMessage() {
	
		// Set the HTML to display for this flag
		switch ($this->flg) {
			case "failed":
				$this->setMsgHTML('error','Save Failed !','An error occurred and the settings were NOT saved.');
				break;
			case "saved":
				$this->setMsgHTML('success','Controller Saved !','You have successfully saved the settings.');
				break;
			case "noperms":
				$this->setMsgHTML('info','Permission Denied !','You do not have permissions for this action.');
				break;
		}
		
	}
	
}
?>