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

// Handles Web Pages in ezCMS
class ezPages extends ezCMS {

	public $id = 1;
	public $treehtml = '';
	public $ddOptions = '';
	public $slOptions = '';	
	public $addNewBtn;
	public $page;
	public $btns;
	
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
			$this->setOptions('nositemap', '', '');
			$this->setOptions('useheader', 'Page will display this custom HEADER.', 'Page will display the default HEADER.');
			$this->setOptions('useside'  , 'Page will display this custom ASIDE1.', 'Page will display the default ASIDE1.');
			$this->setOptions('usesider' , 'Page will display this custom ASIDE2.', 'Page will display the default ASIDE2.');
			$this->setOptions('usefooter', 'Page will display this custom FOOTER.', 'Page will display the default FOOTER.');
			$this->setOptions('published','Page is published.','Unpublished page is only visible when logged in.');
		}
		
		//Build the Menu to show
		$this->buildMenu();

		// Get the layouts for select options
		$this->buildlayoutOpts();

		//Build the HTML Treeview
		$this->buildTree();

		//Disable parent page drop down
		if ( ($this->id > 2) || ($this->id == 'new') ) $this->ddOptions = 
			'<select name="slGroup" id="slGroup" class="input-block-level">'.$this->ddOptions .'</select>';
		else $this->ddOptions = '<div class="alert alert-info slRootMsg">'.'Root</div>';
		
		// Get the Message to display if any
		$this->getMessage();

	}

	// Function to build the menu to display
	private function buildlayoutOpts() {
		$isSel = '';
		if (($this->page['layout'] =='') || ($this->page['layout']=='layout.php')) $isSel = 'selected';
		$this->slOptions .= '<option value="layout.php" '.$isSel.'>Default - layout.php</option>';
		foreach (glob("../layout.*.php") as $entry) {
			$entry = substr($entry, 3 , strlen($entry)-3);
			$isSel = '';
			if ($this->page['layout'] == $entry) $isSel = 'selected';
			$this->slOptions .= "<option $isSel>$entry</option>";
		}
	}
	
	// Function to build the menu to display
	private function buildMenu() {
	
		$this->btns = '';
		if ($this->id == 'new') { 
			$this->btns .= '<input type="submit" name="Submit" class="btn btn-primary" value="Add New">';
			return;
		}
		if ($_SESSION['EDITORTYPE'] == 3)
			$this->btns .= '<a id="showdiff" href="#" class="btn btn-inverted btn-danger">Review DIFF</a>';
		$this->btns .= '<input type="submit" name="Submit" class="btn btn-primary" value="Save Changes">';
		$myclass = ''; // 
		if ( !$this->page['published'] ) $myclass = 'nopubmsg';
		$this->btns .= '<a href="../'.$this->page['url'].'" target="_blank"  class="btn btn-success '.$myclass.' ">View</a>';
		$this->btns .= '<a href="?id=new" class="btn btn-info">New</a>';
		$this->btns .= '<a href="?copyid='.$this->id.'" class="btn btn-warning">Copy</a>';
		if ($this->id > 2)
			$this->btns .= '<a href="?delid='.$this->id.'" class="btn btn-danger conf-del">Delete</a>';
		if ($_SESSION['EDITORTYPE'] == 3)
			$this->btns .= '<a id="showrevs" href="#" class="btn btn-secondary">Revisions <sup>1</sup></a>';
	
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
	private function buildTree($parentid = 0) {
		
		static $nestCount;
		
		$treeSQL = $this->prepare(
			"SELECT `id`, `title`, `url`, `published`, `description` 
			FROM  `pages` WHERE `parentid` = ? order by place");
		$treeSQL->execute( array($parentid) );

		if ($treeSQL->rowCount()) {

			$nestCount += 1;
			if ($nestCount == 1) $this->treehtml .= '<ul id="left-tree">'; else $this->treehtml .=  '<ul>';
			$cnt = 0;
			
			while ($entry = $treeSQL->fetch()) {
				$cnt++;
				
				$action = '<i class="icon-file"></i>';
				if ($entry['id']==1) $action = '<i class="icon-home"></i>';
				if ($entry['id']==2) $action = '<i class="icon-question-sign"></i> ';
				
				$myclass = ($entry["id"] == $this->id) ? 'label label-info' : '';
				$myPub   = ($entry["published"]) ? '' : ' <i class="icon-ban-circle" title="Page is not published"></i>';
				$this->treehtml .= '<li>'.$action.' <a href="pages.php?id='.$entry['id'].
							'" class="'.$myclass.'">'.$entry["title"].'</a>'.$myPub;
				$isSel = '';
				if ($this->page['parentid'] == $entry['id']) $isSel = 'selected';
				$this->ddOptions .= '<option value="' . $entry['id'] . '" '.$isSel.'>'.
					str_repeat(' - ',$nestCount - 1) . $entry['title'].'</option>';				
				
				$this->buildTree($entry['id']);
				$this->treehtml .= '</li>';
			}
			$this->treehtml .= '</ul>';
		}

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
		}

	}

}
?>