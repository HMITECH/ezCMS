<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * Version 4.160210
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Layouts Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezLayouts extends ezCMS {

	public $filename = "layout.php";
	
	public $homeclass = '';
	
	public $deletebtn = '';
	
	public $content = '';
	
	public $treehtml = '';
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Check if file to display is set
		if (isset($_GET['show'])) $this->filename = 'layout.'.$_GET['show'];
		
		// Check if file is to be deleted
		if (isset($_GET['delfile'])) $this->deleteFile();
		
		// Purge Revision
		if (isset($_GET['purgeRev'])) $this->delRevision();
		
		// Check if layout file is present
		if (!file_exists('../'.$this->filename)) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Layout not Found !<br><a href="layouts.php"> click here for layouts</a>');
		}
		
		// get the contents of the layout file
		$this->content = htmlspecialchars(file_get_contents('../'.$this->filename));
		
		// Update the Controller of Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();
		
		// Set selected class on home node if defaults file
		if ($this->filename=="layout.php") {
			$this->homeclass = 'label label-info';
		} else {
			$this->deletebtn = '<a href="layouts.php?delfile='.
				$this->filename.'" onclick="return confirm(\'Confirm Delete ?\');" class="btn btn-danger">Delete</a>';
		}
		
		//Build the HTML Treeview
		$this->buildTree();
		
		// Get the Revisions
		$this->getRevisions();

		// Get the Message to display if any
		$this->getMessage();

	}
	
	// Function to Update the Defaults Settings
	private function delRevision() {

		$show = substr($this->filename, 7 , strlen($this->filename)-7);
		if ($show == 'php') $show = ''; 

		// Check permissions
		if (!$this->usr['editlayout']) {
			header("Location: layouts.php?flg=noperms&show=$show");
			exit;
		}
		
		// Get the revision ID to delete
		$revID = intval($_GET['purgeRev']);
		
		// Delete the revision
		if ( $this->delete('git_files',$revID) ) {
			header("Location: layouts.php?flg=saved&show=$show");
			exit;
		}
		
		header("Location: layouts.php?flg=failed&show=$show");
		exit;		
	
	}
	
	// Function to Build Treeview HTML
	private function buildTree() {
		$this->treehtml = '<ul>';
		foreach (glob("../layout.*.php") as $entry) {
			$entry = substr($entry, 10, strlen($entry)-10);
			$myclass = ($this->filename == 'layout.'.$entry) ? 'label label-info' : '';
			$this->treehtml .= '<li><i class="icon-list-alt"></i> <a href="layouts.php?show='.
				$entry.'" class="'.$myclass.'">'.$entry.'</a></li>';
		}
		$this->treehtml .= '</ul>';
	}
	
	// Function to fetch the revisions
	private function getRevisions() {
		
		foreach ($this->query("SELECT git_files.*, users.username
				FROM users LEFT JOIN git_files ON users.id = git_files.createdby
				WHERE git_files.fullpath = '".$this->filename."'
				ORDER BY git_files.id DESC") as $entry) {

			$this->revs['opt'] .= '<option value="'.$entry['id'].'">#'.
				$this->revs['cnt'].' '.$entry['createdon'].' ('.$entry['username'].')</option>';

			$this->revs['log'] .= '<tr>
				<td>'.$this->revs['cnt'].'</td>
				<td>'.$entry['username'].'</td>
				<td>'.$entry['createdon'].'</td>
			  	<td data-rev-id="'.$entry['id'].'">
				<a href="#">Fetch</a> &nbsp;|&nbsp; 
				<a href="#">Diff</a> &nbsp;|&nbsp;
				<a href="layouts.php?show='.$show = substr($this->filename, 7 , strlen($this->filename)-7).'&purgeRev='.$entry['id'].'">Purge</a>	
				</td></tr>';

			$this->revs['jsn'][$entry['id']] = $entry['content'];

			$this->revs['cnt']++;
		}
		$this->revs['cnt']--;
		
		if ($this->revs['log'] == '') 
			$this->revs['log'] = '<tr><td colspan="3">There are no revisions.</td></tr>';	
	}

	
	// Function to Delete the Layout
	private function deleteFile() {
	
		$filename = $_REQUEST['delfile'];
		$show = substr($filename, 7 , strlen($filename)-7);
		
		// Check permissions
		if (!$this->usr['editlayout']) {
			die('MA');
			header("Location: layouts.php?flg=noperms&show=$show");
			exit;
		}
		
		// Default layout cannot be deleted and file must begin with 'layout.' and end with '.php'
		if (($filename=='layout.php') || (substr($filename,0,7)!='layout.') || (substr($filename,-4)!='.php') ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// Check if layout is writeable
		if (!is_writable("../$filename")) {
			header("Location: layouts.php?flg=unwriteable&show=$show");
			exit;	
		}		
		
		// Delete the file
		if (unlink("../$filename")) {
			header("Location: layouts.php?flg=deleted");
			exit;
		}
		// Failed to delete the file	
		header("Location: layouts.php?flg=delfailed&show=$show");
		exit;	
	}
	
	// Function to Update the Layout
	private function update() {
	
		// Check all the variables are posted
		if ( (!isset($_POST['Submit'])) || (!isset($_POST['txtContents'])) || (!isset($_POST["txtName"])) ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}
		
		$filename = $_POST["txtName"];
		$contents = $_POST["txtContents"];
		$show = substr($filename, 7 , strlen($filename)-7);
		
		
		// Check permissions
		if (!$this->usr['editlayout']) {
			header("Location: layouts.php?flg=noperms&show=$show");
			exit;
		}

		// Layout file must begin with 'layout.' and end with '.php'
		if ((substr($filename,0,7)!='layout.') || (substr($filename,-4)!='.php') ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// If file is missing then it is a copy 
		if (file_exists("../$filename")) {
		
			// Check if writeable
			if (!is_writable("../$filename")) {
				$this->flg = 'unwriteable';
				$this->filename = $filename;
				$this->content = htmlspecialchars($contents);
				return;
			}
			
			// Check if nothing has changed		
			$original = file_get_contents("../$filename");
			if ($original == $contents) {
				header("Location: layouts.php?flg=nochange&show=$show");
				exit;
			}
	
			// Create a revision
			$data = array (	'content' => $original, 
							'fullpath' => $filename, 
							'createdby' => $this->usr['id']);
			if ( !$this->add('git_files', $data) ) {
				header("Location: layouts.php?flg=revfailed&show=$show");
				exit;
			}			
			
		}
		
		// Save the layout file
		if (file_put_contents("../$filename", $contents ) !== false) {
			header("Location: layouts.php?flg=saved&show=$show");
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
			case "failed": // red
				$this->setMsgHTML('error','Save Failed !','An error occurred and the layout was NOT saved.');
				break;
			case "saved":
				$this->setMsgHTML('success','Layout Saved !','You have successfully saved the Layout.');
				break;
			case "unwriteable":
				$this->setMsgHTML('error','Not Writeable !','The Layout file is NOT writeable.');
				break;
			case "delfailed":
				$this->setMsgHTML('error','Deleted Failed !','An error occurred and the layout was NOT deleted.');
				break;
			case "nochange":
				$this->setMsgHTML('warn','No Change !','Nothing has changed to save.');
				break;
			case "deleted":
				$this->setMsgHTML('default','Layout Deleted !','You have successfully deleted the layout.');
				break;
			case "noperms":
				$this->setMsgHTML('info','Permission Denied !','You do not have permissions for this action.');
				break;
		}
		
	}
	
}
?>