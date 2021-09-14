<?php
defined('_VALID_MOS') or die('Restricted access');

/*
 * if you need it :  
 * 	defined('_JOSC_J15') will be true if joomla release >= 1.5
 */
 
class JOSC_com_REPLACEnewplugin extends JOSC_component {
//	var $_component='';	 /* JOSC_component */
//	var	$_id=0;			 /* JOSC_component */
	/* specific properties of REPLACEnewplugin if needed */
//	var _specific_data;

	function JOSC_com_REPLACEnewplugin($component,&$row,&$list)
	{
		$id	= isset($row->REPLACEid) ? $row->REPLACEid : 0; /* document id */

		$this->setRowDatas($row); /* get specific properties */
					
		$this->JOSC_component($component,0,$id);
	}

	/*
	 * Set specific properties
	 * will be called also in admin.html.php manage comments during row loop
	 */
	function setRowDatas(&$row)
	{
		/* for optimization reason, do not save row. save just needed parameters */

		//$this->_specific_data	= isset($row->specific) ? $row->specific : '';
	}
	    
	/*
	 * This function is executed to check 
	 * if section/category of the row are authorized or not (exclude/include from the setting)
	 * return : true for authorized / false for excluded
	 */    
	function checkSectionCategory(&$row, $include, $sections=array(), $catids=array(), $contentids=array())
	{
			/* doc id excluded ? */
       		if (in_array((($row->REPLACEid == 0) ? -1 : $row->REPLACEid), $contentids))
       			return false; 		

			/* category included or excluded ? */
        	$result = in_array((($row->REPLACEcatid == 0) ? -1 : $row->REPLACEcatid), $sections);
        	if (($include && !$result) || (!$include && $result))  
        		return false; /* include and not found OR exclude and found */
		
			return true;
	}

    /*
     * Condition to active or not the display of the post and input form
     * If the return is false, show readon will be executed.
     */
	function checkVisual($contentId=0)
    {	
    	global $option, $task;
    	
    	return  (		$option == 'com_REPLACEnewplugin' 
    				&& 	$task == 'REPLACEdoc_details' 
    			);	
    }
	
	/*
	 * This function will active or deactivate the show readon display 
	 */	
	function setShowReadon( &$row, &$params, &$config )
	{
        $show_readon 	= $config->_show_readon;

        return $show_readon;  
	}
	
    /*
     * construct the link to the content item  
     * (and also direct to the comment if commentId set)
     */
    function linkToContent($contentId, $commentId='', $joscclean=false, $admin=false)
    {
    	global $mosConfig_live_site, $mainframe, $Itemid;
		$add = ( $joscclean ? "&joscclean=1" : "" ) . ( $commentId ? "&comment_id=$commentId#josc$commentId" : "" ) ;
		$menuid = $this->getItemid();
		
		$url	= ($admin ? "/" : "" )
				. "index.php?option=com_REPLACEnewplugin&task=REPLACEdoc_details&REPLACEgid=$contentId" 
				. ( $menuid ? "&Itemid=$menuid" : "" );
				;
		$url 	.= $add;
		if ($admin) 
			$url = $mosConfig_live_site.$url;

		$url	= function_exists('sefRelToAbs') ? sefRelToAbs($url) : $url; /* does not exist in admin part */

    	return ($url);
    }

    /*
     * clean the cache of the component when comments are inserted/modified...
     * (if cache is active) 
     */
    function cleanComponentCache() {
            mosCache::cleanCache( 'com_REPLACEnewplugin' );
    }

	/*
	 *  getItemid
	 */
	function getItemid( $component='com_REPLACEnewplugin') {
    	static $ids;
        if( !isset($ids) ) {
        	$ids = array();
        }
        if( !isset($ids[$component]) ) {
        	global $database;
            $query = "SELECT id FROM #__menu"
                    ."\n WHERE link LIKE '%option=$component%'"
                    ."\n AND type = 'component'"
                    ."\n AND published = 1 LIMIT 1";
            $database->setQuery($query);
            $ids[$component] = $database->loadResult();
        }
        return $ids[$component];
    }
    
	/*----------------------------------------------------------------------------------
	 *  F U N C T I O N S   F O R   A D M I N   P A R T 
	 *----------------------------------------------------------------------------------
	 */
	 
    /*
     * section option list used to display the include/exclude section list in setting 
     * must return an array of objects (id,title)
     */
    function getSectionsIdOption() 
    {	
    	global $database;
    	
    	$sectoptions = array();
		$query 	= "SELECT id, title"
				. "\n FROM #__categories"
				. "\n WHERE published = 1"
				. "\n AND section = 'com_REPLACEnewplugin' "
//				. "\n AND access >= 0"    
				. "\n ORDER BY ordering"
				;
		$database->setQuery( $query );
		$sectoptions = $database->loadObjectList();
		
		return $sectoptions;
    }

    /*
     * categories option list used to display the include/exclude category list in setting 
     * must return an array of objects (id,title)
     */
    function getCategoriesIdOption() 
    {	
    	global $database;
    	
    	$catoptions = array();
		
		return $catoptions;
    }

	/*
	 * content items list (or single) for new and edit comment
	 * must return an array of objects (id,title)
	 */
	function getObjectIdOption($id=0, $select=true)
	{
    	global $database;
    	
    	$content = array();
		$query 	= "SELECT REPLACEid AS id, REPLACEtitle AS title"
				. "\n FROM #__REPLACEcontent "
				. ($id ? "\n WHERE REPLACEid = $id":"")
				. "\n ORDER BY REPLACEordering"
				;
		$database->setQuery( $query );
		$content = $database->loadObjectList();
		if (!$id && $select && count($content)>0) { 
			array_unshift( $content, mosHTML::makeOption( '0', '-- Select REPLACEContent Item --', 'id', 'title' ) );
		}

		return $content;		
	}
	function getViewTitleField()
	{ 
       $title = 'REPLACEtitle';
       return($title);
	}
	function getViewJoinQuery($alias, $contentid)
	{
		$leftjoin	= "\n LEFT JOIN #__REPLACEcontent  AS $alias ON $alias.REPLACEid = $contentid ";
		return $leftjoin;
	}

	/*----------------------------------------------------------------------------------
	 *  F U N C T I O N S   F O R   MOD_COMMENTS   M O D U L E 
	 *----------------------------------------------------------------------------------
	 */
	function mod_commentsGetMostCommentedQuery($secids, $catids, $maxlines)
	{
		global $database;

		$component = $this->_component;
		
		$limit = $maxlines>=0 ? " limit $maxlines " : "";

		$where = array();
		if ($secids)
			$where[] = "REPLACEcat.id IN ($secids)";

	    /* 
	     * Count comment id group by contentid 
   	 	 * TODO: restrict according to user rights, dates and category/secitons published and dates...
	     */
	    $query 	=  	"SELECT COUNT(c.id) AS countid, ct.id, ct.REPLACEtitle AS title "
				.	"\n FROM `#__comment`    AS c "
				.  	"\n INNER JOIN `#__REPLACEcontent`  AS ct  ON ct.id = c.contentid "
	       		.  	"REPLACE\n INNER JOIN `#__categories` AS cat ON cat.id = ct.catid "
				.  	"\n WHERE c.published='1' "
				.	"\n   AND c.component='$component' "
				.  	"\n ". (count($where) ? (" AND ".implode(" AND ", $where)) : "")
	    		.	"\n GROUP BY c.contentid"
	    		.	"\n ORDER BY countid DESC"
				.	"\n $limit"
	    		;
		$database->SetQuery($query);
		$rows = $database->loadAssocList();

		return $rows;		
	}
	
	function mod_commentsGetOthersQuery($secids, $catids, $maxlines, $orderby)
	{
		global $database;

		$component = $this->_component;
		
		$limit = $maxlines>=0 ? " limit $maxlines " : "";

		$where = array();
		if ($secids)
			$where[] = "cat.id IN ($secids)";

		if ($orderby=='mostrated') {
			$mostrated =  ", (c.voting_yes-c.voting_no)/2 AS mostrated";
			$where[]  = "(c.voting_yes > 0 OR c.voting_no > 0)";
		} else {
			$mostrated = "";
			$orderby = "c.$orderby";
		}

    	/*
   	 	 * TODO: restrict according to user rights, dates and category/secitons published and dates...
   	 	 */
		$query 	=  "SELECT c.*, ct.REPLACEtitle AS ctitle $mostrated "
				.  "\n FROM `#__comment`    AS c "
				.  "\n INNER JOIN `#__REPLACEcontent`    AS ct  ON ct.id = c.contentid "
        		.  "REPLACE\n INNER JOIN `#__categories` AS cat ON cat.id = ct.catid "
				.  "\n WHERE c.published='1' "
				.	"\n  AND c.component='$component' "
				.  "\n ". (count($where) ? (" AND ".implode(" AND ", $where)) : "")
				.  "\n ORDER BY $orderby DESC "
				.  "\n $limit"
				;
		$database->SetQuery($query);
		$rows = $database->loadAssocList();
		
		return $rows;
	}
        
}
?>