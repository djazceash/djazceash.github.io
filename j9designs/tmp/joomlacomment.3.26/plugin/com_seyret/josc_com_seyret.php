<?php
defined('_VALID_MOS') or die('Restricted access');
/*
 * include the following instructions :

<!-- START joomlacomment INSERT -->
<div class="" style="">
<?php
	global $option, $mosConfig_absolute_path;
	require_once("$mosConfig_absolute_path/administrator/components/com_comment/plugin/$option/josc_com_seyret.php");
?>
</div>
<!-- END OF joomlacomment INSERT -->

 * 	
 *	
 *
 *  HTML part of the code can be changed ! according to the theme...
 */
	global $option, $mosConfig_absolute_path;
	require_once("$mosConfig_absolute_path/components/com_comment/joscomment/utils.php");

	global $database;
	$database->setQuery( "SELECT * FROM #__seyret_items WHERE id = '$id' LIMIT 1" );
	$row = $database->loadObjectList();
	$comObject = JOSC_utils::ComPluginObject($option,$row[0]);
	$params=null;
	$comments=JOSC_utils::execJoomlaCommentPlugin($comObject, $row[0], $params, true);
	unset($comObject);
?>
