<?php
defined('_VALID_MOS') or die('Restricted access');

class JOSC_com_content extends JOSC_component {
//	var $_component='';	 /* JOSC_component */
//	var	$_sectionid=0;	 /* JOSC_component */
//	var	$_id=0;			 /* JOSC_component */
	/* specific properties of content plugin */
	var $_usecatid=false;
	var	$_route_sectionid=0;  	/* 1.5 */
	var	$_route_catid=0;  		/* 1.5 */
	var $_route_slug=0;			/* 1.5 */

	function JOSC_com_content($component,&$row,&$list)
	{
 		$this->_usecatid = $this->getUseCatid();
		if ($this->_usecatid)
			$sectionid = isset($row->catid) ? $row->catid : $list['sectionid'];
		else
			$sectionid = isset($row->sectionid) ? $row->sectionid : $list['sectionid'];

		$id	= isset($row->id) ? $row->id : 0; /* content item id */

		$this->setRowDatas($row); /* get specific properties */
					
		$this->JOSC_component($component,$sectionid,$id);		
 		
	}

	/*
	 * Set specific properties
	 * will be called also in admin.html.php manage comments during row loop
	 */
	function setRowDatas(&$row)
	{
		/* for optimization reason, do not save row. save just needed parameters */

		if (defined('_JOSC_J15')) {
			$this->_route_sectionid 	= isset($row->sectionid) ? $row->sectionid : 0;
//			$this->_route_catid 		= isset($row->catid) ? $row->catid : 0;
			$this->_route_catid 		= isset($row->catslug) ? $row->catslug : (isset($row->catid) ? $row->catid : 0) ;
			$this->_route_slug			= isset($row->slug) ? $row->slug  : (isset($row->id) ? $row->id : 0);  
		}
	}

	/*
	 * This function is executed to check 
	 * if section/category of the row are authorized or not (exclude/include from the setting)
	 * return : true for authorized / false for excluded
	 * 
	 * obj :
	 * - _include_sc
	 * - _exclude_sections
	 * - _exclude_categories
	 * - _exclude_contentitems 
	 * 
	 */
	function checkSectionCategory(&$row, &$obj)
	{	
		$include		= $obj->_include_sc;
		$sections		= $obj->_exclude_sections;
		$catids			= $obj->_exclude_categories;
		$contentids 	= $obj->_exclude_contentids;
		$contentitems 	= $obj->_exclude_contentitems;
		
		/* content item excluded ? DO NOT USE ANYMORE */
   		if (in_array((($row->id == 0) ? -1 : $row->id), $contentids))
   			return false; 		

		/* 
		 * Include =  include only : selected Ids OR selected Sections OR Selected Categories)
		 * Exclude =  exclude selected Ids OR selected Sections OR Selected Categories
		 */

		/* content ids */
		if (count($contentitems)>0) {
       		$result = in_array((($row->id == 0) ? -1 : $row->id), $contentitems);
       		if ($include && $result) 	return true; 	/* include and selected */
			if (!$include && $result) 	return false; 	/* exclude and selected */ 
		}

		/* sections */
    	$result = in_array((($row->sectionid == 0) ? -1 : $row->sectionid), $sections);
   		if ($include && $result) 	return true; 	/* include and selected */
		if (!$include && $result) 	return false; 	/* exclude and selected */ 

		/* categories */
       	$result = in_array((($row->catid == 0) ? -1 : $row->catid) , $catids);
   		if ($include && $result) 	return true; 	/* include and selected */
		if (!$include && $result) 	return false; 	/* exclude and selected */ 
	
		if ($include) 	return false; /* was not included */
		if (!$include)	return true; /* was not excluded */
	}
	       
    /*
     * Condition to active or not the display of the post.
     * If the return is false, show readon will be executed.
     */
    function checkVisual($contentId=0)
    {	
    	if (defined('_JOSC_J15')) {
			$option	= JRequest::getVar('option', '');
			$task 	= (JRequest::getVar('view', '') == 'article') ? 'view' : '';
			
    	} else {
			global $option, $task;
    	}
	    	
    	return  (		$option == 'com_content' 
    				&& 	$task == 'view'
    				&& 	$contentId == intval(JOSC_utils::decodeData('id'))  
    			);	
    }

	/*
	 * This function will active or deactivate the show readon display 
	 */	
	function setShowReadon( &$row, &$params, &$config )
	{
		//var_dump($params->get( 'intro_only' ));
		//var_dump($config->_intro_only);

		if ($params!=null) {
    		if (defined('_JOSC_J15')) {
    			$readmore 		= $params->get( 'show_readmore' );
    			$link_titles 	= $params->get( 'link_titles' );
    			$intro_only		= true; //$params->get( 'show_intro' );    		
    		} else {
    			$readmore 		= $params->get( 'readmore' );
    			$link_titles 	= $params->get( 'link_titles' );
    			$intro_only		= true; //$params->get( 'intro_only' );
    		}
		}			
        $show_readon 	= $config->_show_readon;
        if ($config->_menu_readon 	&& $params!=null && !$readmore && !$link_titles)
        	$show_readon = false;
//        if ($config->_intro_only 	&& $params!=null && $readmore && $intro_only &&  isset($row->link_text) && $row->link_text ) // && !$row->fulltext )
//        	/* no link if already readmore link */
//        	$show_readon = false;

        if ($config->_intro_only 	&& $params!=null && $readmore && $intro_only 
        							&& isset($row->readmore) && (bool)$row->readmore ) 
        	/* no link if already readmore link */
        	$show_readon = false;
        return $show_readon;  
	}
	
    /*
     * construct the link to the content item  
     * (and also direct link to the comment if commentId set)
     */
    function linkToContent($contentId, $commentId='', $joscclean=false, $admin=false)
    {
    	global $mosConfig_absolute_path, $mosConfig_live_site, $mainframe, $option;

		$Itemid = '';
		
		if (defined('_JOSC_J15')) {
		} else {
			$Itemid = $mainframe->getItemid( $contentId, 0, 0 );
		} 
   	    if (!$Itemid) $Itemid = '99999999';

		$add = ( $joscclean ? "&joscclean=1" : "" ) . ( $commentId ? "&comment_id=$commentId#josc$commentId" : "" ) ;
		
		if (defined('_JOSC_J15')) 
		{
			/*
			 * what a complicated bullshit !
			 * I hope someone would find how to create a simple link
			 * understandable for
			 * - no SEF
			 * - SEF
			 * and root directory or not
			 * and administrator part ! also 
			 * 
			 */							
			/* IMPORT CLASS IF NOT EXIST */
//			jimport('joomla.application.application');
			if (!class_exists('JSite'))
				JLoader::import('includes.application',$mosConfig_absolute_path);
			if (!class_exists('ContentHelperRoute'))
				JLoader::import('components.com_content.helpers.route',$mosConfig_absolute_path);
			/* CONSTRUCT URL */
//			$url	= JRoute::_(ContentHelperRoute::getArticleRoute($contentId, $this->_route_catid, $this->_route_sectionid)
//								. $add);
			$url	= JRoute::_(ContentHelperRoute::getArticleRoute( ($this->_route_slug ? $this->_route_slug:$contentId) , $this->_route_catid, $this->_route_sectionid)
								. $add);

			/* for backend link */
			if (substr(ltrim($url),0,1)!='/')  		/* backend properties */	
				$url = $mosConfig_live_site.'/'.$url;

			/* for notification email links and not root directory - ! */
			if (substr(ltrim($url),0,7)!='http://')
			{
				$uri   =& JURI::getInstance();
				$base  = $uri->toString( array('scheme', 'host', 'port'));
				$url = $base.$url;
			}			
	
 		} else {
    		$url 	= $mosConfig_live_site."/index.php?option=com_content&task=view&id=$contentId" 
    				. ( $Itemid ? "&Itemid=$Itemid" : "" ) . $add;
	 		$url	= function_exists('sefRelToAbs') ? sefRelToAbs($url) : $url; /* does not exist in admin part */							
		}  

    	return ($url);
    }

    /*
     * clean the cache of the component when comments are inserted/modified...
     * (if cache is active) 
     */
    function cleanComponentCache() {
            mosCache::cleanCache( 'com_content' );
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
		$query 	= "SELECT s.id, s.title"
				. "\n FROM #__sections AS s "
				. "\n WHERE s.published = 1"
				. "\n AND s.scope = 'content'"
//					. "\n AND access >= 0"    
				. "\n ORDER BY s.ordering"
				;
		$database->setQuery( $query );
		$sectoptions = $database->loadObjectList();
		// add "Static Content" value
		array_unshift( $sectoptions, mosHTML::makeOption( '-1', 'Static Content', 'id', 'title' ) );
		
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
		$query 	= "SELECT c.id, CONCAT( s.title, ' | ', c.title ) AS title "
				. "\n FROM #__sections AS s "
				. "\n INNER JOIN #__categories AS c ON s.id = c.section "
				. "\n WHERE s.published = 1 "
				. "\n   AND c.published = 1 "
				. "\n   AND s.scope = 'content' "
//					. "\n   AND access >= 0"    
				. "\n ORDER BY s.ordering, c.ordering "
				;    		
		$database->setQuery( $query );
		$catoptions = $database->loadObjectList();
		
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
		$query 	= "SELECT id AS id, title AS title"
				. "\n FROM #__content "
				. ($id ? "\n WHERE id = $id":"")
//					. "\n AND access >= 0"    
				. "\n ORDER BY ordering"
				;
		$database->setQuery( $query );
		$content = $database->loadObjectList();
		if (!$id && $select && count($content)>0) { 
			array_unshift( $content, mosHTML::makeOption( '0', '-- Select Content Item --', 'id', 'title' ) );
		}

		return $content;		
	}
	function getViewTitleField()
	{ 
       $title = 'title';
       return($title);
	}
	function getViewJoinQuery($alias, $contentid)
	{
		$leftjoin	= "\n LEFT JOIN #__content  AS $alias ON $alias.id = $contentid ";
		return $leftjoin;
	}

	/*----------------------------------------------------------------------------------
	 *  F U N C T I O N S   F O R   MOD_COMMENTS   M O D U L E 
	 *----------------------------------------------------------------------------------
	 */
	function mod_commentsGetMostCommentedQuery($secids, $catids, $maxlines)
	{
		global $database, $gid;

		$component = $this->_component;
		
		$limit = $maxlines>=0 ? " limit $maxlines " : "";

		$where = array();
		if ($catids)
			$where[] = "cat.id IN ($catids)";
		if ($secids)
			$where[] = "cat.section IN ($secids)";

	    /* 
	     * Count comment id group by contentid 
   	 	 * TODO: restrict according content item user rights, dates and category/secitons published and dates...
	     */
	    $query 	=  	"SELECT COUNT(c.id) AS countid, ct.id, ct.title "
				.	"\n FROM `#__comment`    AS c "
				.  	"\n INNER JOIN `#__content`    AS ct  ON ct.id = c.contentid "
	       		.  	"\n INNER JOIN `#__categories` AS cat ON cat.id = ct.catid "
	    		.   "\n INNER JOIN `#__sections`   AS sec ON sec.id = cat.section "
				.  	"\n WHERE c.published='1' "
				.	"\n   AND c.component='$component' "
				.	"\n   AND sec.access <= " . (int) $gid
				.  	"\n   AND sec.published='1' "
				.	"\n   AND cat.access <= " . (int) $gid
				.  	"\n   AND cat.published='1' "
				.   "\n   AND ct.access <= " . (int) $gid
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
		global $database, $gid;

		$component = $this->_component;
		
		$limit = $maxlines>=0 ? " limit $maxlines " : "";

		$where = array();
		if ($catids)
			$where[] = "cat.id IN ($catids)";
		if ($secids)
			$where[] = "cat.section IN ($secids)";

		if ($orderby=='mostrated') {
			$mostrated =  ", (c.voting_yes-c.voting_no)/2 AS mostrated";
			$where[]  = "(c.voting_yes > 0 OR c.voting_no > 0)";
		} else {
			$mostrated = "";
			$orderby = "c.$orderby";
		}

		$query 	=  "SELECT c.*, ct.title AS ctitle $mostrated "
				.  "\n FROM `#__comment`    AS c "
				.  "\n INNER JOIN `#__content`    AS ct  ON ct.id = c.contentid "
        		.  "\n INNER JOIN `#__categories` AS cat ON cat.id = ct.catid "
	    		.  "\n INNER JOIN `#__sections`   AS sec ON sec.id = cat.section "
				.  "\n WHERE c.published='1' "
				.	"\n   AND c.component='$component' "
				.	"\n   AND sec.access <= " . (int) $gid
				.  	"\n   AND sec.published='1' "
				.	"\n   AND cat.access <= " . (int) $gid
				.  	"\n   AND cat.published='1' "
				.   "\n   AND ct.access <= " . (int) $gid
				.  "\n ". (count($where) ? (" AND ".implode(" AND ", $where)) : "")
				.  "\n ORDER BY $orderby DESC "
				.  "\n $limit"
				;
		$database->SetQuery($query);
		$rows = $database->loadAssocList();
		
		return $rows;
	}
		
/*
 * O P T I O N N A L
 * F O R   E X P E R T   M O D E  O N L Y
 * not yet available.
 * do not report theses functions !  
 */
    /*
     * section option list for the admin setting
     */
    function getExpertSectionIdOption($sections, $include) 
    {	
    	global $database;
    	
    	$where = "";
    	if ($sections) {
    		if ($include)
    			$where = "\n AND ".($this->_usecatid ? "c.":"s.")."id IN ($sections) ";
    		else
	    		$where = "\n AND ".($this->_usecatid ? "c.":"s.")."id NOT IN ($sections) ";
    	}    			
    	$sectoptions = array();
    	if ($this->_usecatid) {
			$query 	= "SELECT c.id, CONCAT( s.title, ' | ', c.title ) AS title "
					. "\n FROM #__sections AS s "
					. "\n INNER JOIN #__categories AS c ON s.id = c.section "
					. "\n WHERE s.published = 1 "
					. "\n   AND c.published = 1 "
					. "\n   AND s.scope = 'content' "
					. $where
//					. "\n   AND access >= 0"    
					. "\n ORDER BY s.ordering, c.ordering "
					;    		
    	} else {
			$query 	= "SELECT s.id, s.title"
					. "\n FROM #__sections AS s "
					. "\n WHERE s.published = 1"
					. "\n AND s.scope = 'content'"
					. $where
//					. "\n AND access >= 0"    
					. "\n ORDER BY s.ordering"
					;
    	}
		$database->setQuery( $query );
		$sectoptions = $database->loadObjectList();
		// add "All sections" and "Static Content" value
		if ($sections && !(strpos('-1', $sections)===false))
			$static = true;
	    else
			$static = false;

		if (($include && $static) || (!$include && !$static)) {
				array_unshift( $sectoptions, mosHTML::makeOption( '-1', 'Static Content', 'id', 'title' ) );
		}
		array_unshift( $sectoptions, mosHTML::makeOption( '0', '-- All --', 'id', 'title' ) );
		
		return $sectoptions;
    }
    
    /*
     * return the id,title section object
     */
    function getExpertSectionTitle($sectionid)
    {
    	global $database;
    	
    	if ($sectionid==-1) 
    		return '(-1) Static Content';
    		
    	if ($sectionid==0) 
    		return '(0) All ';

		if ($this->_usecatid) {
    		$query 	= "SELECT c.id, CONCAT( s.title, ' | ', c.title ) AS title "
					. "\n FROM #__sections AS s "
					. "\n INNER JOIN #__categories AS c ON s.id = c.section "
					. "\n WHERE c.id=$sectionid"
					;    	
		} else {
    		$query 	= "SELECT id, title"
					. "\n FROM #__sections"
					. "\n WHERE id=$sectionid"
					;
		}    	
		$database->setQuery( $query );
		$row = $database->loadObjectList();
		return "($sectionid) ".$row[0]->title;
    }

/*
 * S P E C I F I C   T O   C O N T E N T  P L U G I N
 */
	function getUseCatid()
	{
		global $database, $_MAMBOTS;
	
		/* 
		 * usecatid ?
		 */
		if (defined('_JOSC_J15'))
			$mambots = 'plugins';
		else
			$mambots = 'mambots';     

		$mambot=null;
		// check if param query has previously been processed
		if ( !isset($_MAMBOTS) || !isset($_MAMBOTS->_content_mambot_params['joscomment']) ) {
			// load mambot params info
			$query = "SELECT params"
			. "\n FROM #__$mambots"
			. "\n WHERE element LIKE 'joscomment%'"
			. "\n AND folder = 'content'"
			. "\n AND published = 1 "
			;
			$database->setQuery( $query );
			$database->loadObject($mambot);			
		}
		if (isset($_MAMBOTS)) {
			if (!isset($_MAMBOTS->_content_mambot_params['joscomment'])) {	
				// save query to class variable
				$_MAMBOTS->_content_mambot_params['joscomment'] = $mambot;
			}
			// pull query data from class variable
			$mambot = $_MAMBOTS->_content_mambot_params['joscomment'];
		}
 		$botParams = new mosParameters( $mambot->params );
 		return $botParams->get( 'usecatid', 0 );		
	}        

	function getBotParam($name, $default=0)
	{
		global $database, $_MAMBOTS;
	
		/* 
		 * usecatid ?
		 */
		if (defined('_JOSC_J15'))
			$mambots = 'plugins';
		else
			$mambots = 'mambots';     

		$mambot=null;
		// check if param query has previously been processed
		if ( !isset($_MAMBOTS) || !isset($_MAMBOTS->_content_mambot_params['joscomment']) ) {
			// load mambot params info
			$query = "SELECT params"
			. "\n FROM #__$mambots"
			. "\n WHERE element LIKE 'joscomment%'"
			. "\n AND folder = 'content'"
			. "\n AND published = 1 "
			;
			$database->setQuery( $query );
			$database->loadObject($mambot);			
		}
		if (isset($_MAMBOTS)) {
			if (!isset($_MAMBOTS->_content_mambot_params['joscomment'])) {	
				// save query to class variable
				$_MAMBOTS->_content_mambot_params['joscomment'] = $mambot;
			}
			// pull query data from class variable
			$mambot = $_MAMBOTS->_content_mambot_params['joscomment'];
		}
 		$botParams = new mosParameters( $mambot->params );
 		return $botParams->get( $name, $default );		
	}   
	    
}

?>