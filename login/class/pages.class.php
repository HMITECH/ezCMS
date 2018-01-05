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
		
		// Check if file to display is set
		if (isset($_GET['id'])) $this->id = $_GET['id'];		
		
		if ($this->id <> 'new' ) {
			$this->page = $this->query('SELECT * FROM `pages` WHERE `id` = '.$this->id.' LIMIT 1')
				->fetch(PDO::FETCH_ASSOC); // get the selected user details
			$this->setOptions('nositemap', '', '');
			$this->setOptions('useheader', 'Page will display this custom HEADER', 'Page will display the default HEADER');
			$this->setOptions('useside'  , 'Page will display this custom ASIDE1', 'Page will display the default ASIDE1');
			$this->setOptions('usesider' , 'Page will display this custom ASIDE2', 'Page will display the default ASIDE2');
			$this->setOptions('usefooter', 'Page will display this custom FOOTER', 'Page will display the default FOOTER');
			$this->setOptions('published','Page is published','Unpublished page is only visible when logged in');
		}
		
		// Update the Controller of Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();
		
		// Get the Revisions
		if ($this->id != 'new') $this->getRevisions();
		
		//Build the Menu to show
		$this->buildMenu();

		// Get the layouts for select options
		$this->buildlayoutOpts();

		//Build the HTML Treeview
		$this->buildTree();

		//Disable parent page drop down
		if ( ($this->id > 2) || ($this->id == 'new') ) $this->ddOptions = 
			'<select name="parentid" class="input-block-level">'.$this->ddOptions.'</select>';
		else $this->ddOptions = '<div class="alert alert-info slRootMsg">Root</div>';
		
		// process variable for html display
		$this->page['keywords'] = htmlspecialchars($this->page["keywords"]);
		$this->page['description'] = htmlspecialchars($this->page["description"]);
		$this->page['maincontent'] = htmlspecialchars($this->page["maincontent"]);		
		$this->page['headercontent'] = htmlspecialchars($this->page["headercontent"]);
		$this->page['sidecontent'] = htmlspecialchars($this->page["sidecontent"]);
		$this->page['sidercontent'] = htmlspecialchars($this->page["sidercontent"]);		
		$this->page['footercontent'] = htmlspecialchars($this->page["footercontent"]);		
		$this->page['head'] = htmlspecialchars($this->page["head"]);
		
		// Get the Message to display if any
		//$this->getMessage();
		$this->msg = str_replace('File','Page',$this->msg);

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
			return ;
		}
		if ($_SESSION['EDITORTYPE'] == 3)
			$this->btns .= '<a id="showdiff" href="#" class="btn btn-inverted btn-danger">Review DIFF</a>';
		$this->btns .= '<input type="submit" name="Submit" class="btn btn-primary" value="Save Changes">';
		$myclass = ''; // 
		if ( !$this->page['published'] ) $myclass = 'nopubmsg';
		$this->btns .= '<a href="'.$this->page['url'].'" target="_blank"  class="btn btn-success '.$myclass.' ">View</a>';
		$this->btns .= '<a href="?id=new" class="btn btn-info">New</a>';
		$this->btns .= '<a href="?copyid='.$this->id.'" class="btn btn-warning">Copy</a>';
		if ($this->id > 2)
			$this->btns .= '<a href="?delid='.$this->id.'" class="btn btn-danger conf-del">Delete</a>';
		if ($_SESSION['EDITORTYPE'] == 3)
			$this->btns .= '<a id="showrevs" href="#" class="btn btn-secondary">Revisions <sup>'.$this->revs['cnt'].'</sup></a>';
	
	}
	
	// Function to fetch the revisions
	private function getRevisions() {
	
		foreach ($this->query("SELECT git_pages.id,git_pages.page_id,users.username,git_pages.createdon
				FROM git_pages LEFT JOIN users ON git_pages.createdby = users.id
				WHERE git_pages.page_id = ".$this->id." ORDER BY git_pages.id DESC") as $entry) {
	
			$this->revs['opt'] .= '<option value="'.$entry['id'].'">#'.
				$this->revs['cnt'].' '.$entry['createdon'].' ('.$entry['username'].')</option>';
			
			$this->revs['log'] .= '<tr>
				<td>'.$this->revs['cnt'].'</td>
				<td>'.$entry['username'].'</td>
				<td>'.$entry['createdon'].'</td>
			  	<td data-rev-id="'.$entry['id'].'">
				<a href="#">Fetch</a> &nbsp;|&nbsp; 
				<a href="#">Diff</a> &nbsp;|&nbsp;
				<a href="?purgeRev='.$entry['id'].'">Purge</a>	
				</td></tr>';

			$this->revs['jsn'][$entry['id']] = $entry;

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
				if  ( ($entry['id'] != 2) && ($entry['id'] != $this->id) ){
					if ($this->page['parentid'] == $entry['id']) $isSel = 'selected';
					$this->ddOptions .= '<option value="' . $entry['id'] . '" '.$isSel.'>'.
						str_repeat(' - ',$nestCount - 1) . $entry['title'].'</option>';				
				}
				$this->buildTree($entry['id']);
				$this->treehtml .= '</li>';
			}
			$this->treehtml .= '</ul>';
			$nestCount -= 1;
		}

	}
	
	// Function to Update the Controller
	private function update() {

		// Check permissions
		if (!$this->usr['editpage']) {
			header("Location: ?flg=noperms&id=".$this->id);
			exit;
		}
		
		// array to hold the data
		$data = array();
		
		// get the required post varables 
		$txtFlds = array(
			'pagename', 'title', 'keywords', 'description', 			
			'maincontent', 'headercontent', 'sidecontent', 
			'sidercontent', 'sidercontent', 'footercontent',
			'head', 'layout' );
		if ( ($this->id != 1) && ($this->id != 2) )
			array_push($txtFlds, 'parentid', 'url');
		$this->fetchPOSTData($txtFlds, $data);

		// get the required post checkboxes 
		$cksFlds = array(
			'published', 'useheader', 'useside',
			'usesider', 'usefooter', 'nositemap');
		$this->fetchPOSTCheck($cksFlds, $data);
		$data['createdby'] = $_SESSION['EZUSERID'];
		
		// Validate here ...
		if (isset($data['parentid'])) 
			if ($this->id == $data['parentid']) 
				die('Parent cannot be same page');

		if ($this->id == 'new') {
			// add new
			$newID = $this->add( 'pages' , $data);
			if ($newID) {
				header("Location: ?id=".$newID."&flg=added");	// added
				exit; 
			} 
		} else {
		
			// Test if nothing has changed 
			$isChanged = false;
			foreach (array_merge($txtFlds, $cksFlds) as $fld)
				if ($data[$fld] != $this->page[$fld]) $isChanged = true;
			if (!$isChanged) {
				header("Location: ?flg=nochange&id=".$this->id);
				exit;
			}			

			// Create a revision			
			if (!$this->query("INSERT INTO `git_pages` ( 
				  `page_id`, `pagename`, `title`, `keywords`, `description`, `maincontent`,
				  `useheader` , `headercontent` , `usefooter` , `footercontent` , `useside` ,
				   `sidecontent` , `published` , `parentid` , `url` ,
				   `sidercontent` , `usesider` ,`head` , `layout` , `nositemap` , `createdby` )
				SELECT 
				  `id` AS page_id, `pagename`, `title`, `keywords`, `description`, `maincontent`,
				  `useheader` , `headercontent` , `usefooter` , `footercontent` ,
				  `useside` , `sidecontent` , `published` , `parentid` , `url` ,
				  `sidercontent` , `usesider` ,`head` , `layout` , `nositemap` , 
				  '".$_SESSION['EZUSERID']."' as `createdby`  FROM `pages` WHERE `id` = ".$this->id)) {
				header("Location: ?flg=revfailed&id=".$this->id);
				exit;
			}
		
			// update
			if ($this->edit( 'pages' , $this->id , $data )) {
				header("Location: ?id=".$this->id."&flg=saved");	// added
				exit; 
			}		
		}

		// Update sitemap 
		
		// reindex pages ...
		
		
	}
	
	// Function to Set the Display Message
	private function getMessage() {

		// Set the HTML to display for this flag
		switch ($this->flg) {
		
		}

	}

}
?>