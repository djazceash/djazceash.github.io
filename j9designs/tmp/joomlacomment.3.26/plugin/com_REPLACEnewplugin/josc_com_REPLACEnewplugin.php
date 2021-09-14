<?php
defined('_VALID_MOS') or die('Restricted access');
/*
 * include the following instructions :
<!-- START joomlacomment INSERT -->
REPLACE<div class="" style="">
<?php
	global $option, $mosConfig_absolute_path;
	require_once("$mosConfig_absolute_path/administrator/components/com_comment/plugin/$option/josc_com_REPLACEnewplugin.php");
?>
REPLACE</div>
<!-- END OF joomlacomment INSERT -->

 * 	in the following file :
 *	components/com_REPLACEnewplugin/...
 *  at the following place : ....
 */

	global $option, $mosConfig_absolute_path;
	require_once("$mosConfig_absolute_path/components/com_comment/joscomment/utils.php");

	$comObject = JOSC_utils::ComPluginObject($option,$REPLACErow);
	echo JOSC_utils::execJoomlaCommentPlugin($comObject, $REPLACErow, $REPLACEparams, true);
	unset($comObject);
?>
