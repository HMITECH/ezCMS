<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Users Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezPages extends ezCMS {

	public $id = 1;
	
	public $treehtml = '';
	
	public $addNewBtn;
	
	public $page;	
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Update the Controller of Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();
		
		// Check if file to display is set
		if (isset($_GET['id'])) $this->id = $_GET['id'];
		
		if ($this->id <> 'new' ) {
			$this->page = $this->query('SELECT * FROM `pages` WHERE `id` = '.$this->id.' LIMIT 1')
				->fetch(PDO::FETCH_ASSOC); // get the selected user details
			$this->setOptions('published', 
				'Page is published and visible to all.', 
				'Unpublished page only visible when logged in.');
		}
		
		//Build the HTML Treeview
		$this->buildTree();
		
		// Get the Message to display if any
		$this->getMessage();

	}
	
	// Function to fetch the revisions
	private function getRevisions() {
	
		foreach ($this->query("SELECT git_files.*, users.username 
				FROM users LEFT JOIN git_files ON users.id = git_files.createdby
				WHERE git_files.fullpath = 'index.php'
				ORDER BY git_files.id DESC") as $entry) {
	
			$this->revs['opt'] .= '<option value="'.$entry['id'].'">#'.
				$this->revs['cnt'].' '.$entry['createdon'].' ('.$entry['username'].')</option>';
			
			$this->revs['log'] .= '<tr>
				<td>'.$entry['id'].'</td>
				<td>'.$entry['username'].'</td>
				<td>'.$entry['createdon'].'</td>
			  	<td data-rev-id="'.$entry['id'].'">
				<a href="#">Fetch</a> &nbsp;|&nbsp; 
				<a href="#">Diff</a> &nbsp;|&nbsp;
				<a href="controllers.php?purgeRev='.$entry['id'].'">Purge</a>	
				</td></tr>';

			$this->revs['jsn'][$entry['id']] = $entry['content'];

			$this->revs['cnt']++;
		}
		$this->revs['cnt']--;
		
		if ($this->revs['log'] == '') 
			$this->revs['log'] = '<tr><td colspan="3">There are no revisions.</td></tr>';	
	}
	
	protected function setOptions($itm, $msgOn, $mgsOff) {
		if ($this->page[$itm]) {
			$this->page[$itm.'Check'] = 'checked';
			$this->page[$itm.'Msg'] = '<span class="label label-info">'.$msgOn.'</span>';
		} else {
			$this->page[$itm.'Check'] = '';
			$this->page[$itm.'Msg'] = '<span class="label label-important">'.$mgsOff.'</span>';
		}
	}		
	
	// Function to Build Treeview HTML
	private function buildTree() {
		$this->treehtml = '<ul id="left-tree">';
		foreach ($this->query('select `id` , `title` , `url`, `published`, `description` from  `pages` where `parentid` = 1 order by place;') as $entry) {
			$myclass = ($entry["id"] == $this->id) ? 'label label-info' : '';
			$this->treehtml .= '<li><i class="icon-user"></i> <a href="pages.php?id='.
				$entry['id'].'" class="'.$myclass.'">'.$entry["title"].'</a></li>';
		}
		$this->treehtml .= '</ul>';		

	}
	
	// Function to Update the Controller
	private function update() {
	
		// Check all the variables are posted
		if ( (!isset($_POST['Submit'])) || (!isset($_POST['txtContents'])) ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// Check permissions
		if (!$this->usr['editpages']) {
			header("Location: controllers.php?flg=noperms");
			exit;
		}
		
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