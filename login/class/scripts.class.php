<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * Version 4.160210
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Javascripts Class 
 * 


if ($flg=="red")
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> An error occurred and the javascript file was NOT saved.</div>';
if ($flg=="green")
	$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Saved!</strong> You have successfully saved the javascript file.</div>';
if ($flg=="pink")
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> The javascript file is NOT writeable.
				You must contact HMI Tech Support to resolve this issue.</div>';
if ($flg=="delfailed")
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Delete Failed!</strong> An error occurred and the javascript file was NOT deleted.</div>';
if ($flg=="deleted")
	$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Deleted!</strong> You have successfully deleted the javascript file.</div>';
if ($flg=="noperms")
	$msg = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Permission Denied!</strong> You do not have permissions for this action.</div>'; 
 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezScripts extends ezCMS {

	public $filename = "../main.js";
	
	public $homeclass = '';
	
	public $deletebtn = '';
	
	public $content = '';
	
	public $treehtml = '';
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Check if file to display is set
		if (isset($_GET['show'])) {
			$this->filename = $_GET['show'];
		} 
		
		// Check if file is to be deleted
		if (isset($_GET['delfile'])) {
			$this->delete();
		}


		// Get the path to the target file
		if ($this->filename != "../main.js") {
			$this->filename = "../site-assets/js/".$this->filename;
		} else {
			$this->homeclass = 'label label-info';
			$this->deletebtn = '<a href="scripts.php?delfile='.
				$this->filename.'" onclick="return confirm(\'Confirm Delete ?\');" class="btn btn-danger">Delete</a>';
		}
		
		// Check if layout file is present
		if (!file_exists($this->filename)) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Javascript not Found !<br><a href="scripts.php"> click here for scripts</a>');
		}

		// get the contents of the controller file (index.php)
		$this->content = htmlspecialchars(file_get_contents($this->filename));
		
		// Update the Controller of Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->update();
		}
				
		//Build the HTML Treeview
		$this->buildTree();

		// Get the Message to display if any
		$this->getMessage();
		
	}
	
	// Function to Build Treeview HTML
	private function buildTree() {
		$this->treehtml = '<ul>';
		foreach (glob("../site-assets/js/*.js") as $entry) {
			$myclass = ($this->filename == $entry) ? 'label label-info' : '';
			$entry = substr($entry, 18, strlen($entry)-18);
			
			$this->treehtml .= '<li><i class="icon-indent-left icon-white"></i> <a href="scripts.php?show='.
				$entry.'" class="'.$myclass.'">'.$entry.'</a></li>';

		}
		$this->treehtml .= '</ul>';		
	}
	
	// Function to Delete the Javascript file
	private function delete() {
	
		$filename = $_REQUEST['delfile'];
		$show = substr($filename, 18 , strlen($filename)-18);
		
		// Check permissions
		if (!$this->usr['editjs']) {
			header("Location: scripts.php?flg=noperms&show=$show");
			exit;
		}
		
		// Default Javascript cannot be deleted and file must end with '.js'
		if (($filename=='../main.js') || (substr($filename,-3)!='.js') ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// Check if Javascript is writeable
		if (!is_writable($filename)) {
			header("Location: scripts.php?flg=unwriteable&show=$show");
			exit;	
		}		
		
		// Delete the file
		if (unlink($filename)) {
			header("Location: scripts.php?flg=deleted");
			exit;
		}
		// Failed to delete the file	
		header("Location: scripts.php?flg=delfailed&show=$show");
		exit;	
	}

	// Function to Update the Javascript files
	private function update() {
	
		// Check all the variables are posted
		if ( (!isset($_POST['Submit'])) || (!isset($_POST['txtContents'])) || (!isset($_POST["txtName"])) ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}		
	
		$filename = $_POST["txtName"];
		$contents = ($_POST["txtContents"]);
		$show = substr($filename, 18 , strlen($filename)-18);

		// Check permissions
		if (!$this->usr['editjs']) {
			header("Location: scripts.php?flg=noperms&show=$show");
			exit;
		}
	
		// Layout file must end with '.js'
		if (substr($filename,-3)!='.js') {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// Check if controller is writeable
		if (!is_writable($filename)) {
			$this->flg = 'unwriteable';
			$this->filename = $filename;
			$this->content = htmlspecialchars($contents);
			return;
		}
		
		// Save the layout file
		if (file_put_contents($filename, $contents ) !== false) {
			header("Location: scripts.php?flg=saved&show=$show");
			exit;
		}
		
		// Failed to update layout
		$this->flg = 'failed';
		$this->filename = $filename;
		$this->content = htmlspecialchars($contents);
		
	}
	
	// Function to Set the Display Message
	private function getMessage() {
	
		// Set the HTML to display for this flag
		switch ($this->flg) {
			case "failed":
				$this->setMsgHTML('error','Save Failed !','An error occurred and the controller was NOT saved.');
				break;
			case "saved":
				$this->setMsgHTML('success','Controller Saved !','You have successfully saved the controller.');
				break;
			case "unwriteable":
				$this->setMsgHTML('error','Not Writeable !','The controller file is NOT writeable.');
				break;
			case "noperms":
				$this->setMsgHTML('info','Permission Denied !','You do not have permissions for this action.');
				break;
		}
		
	}
	
}
?>