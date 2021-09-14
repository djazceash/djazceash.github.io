<?php
defined('_VALID_MOS') or die('Restricted access');
	/*
	 *  >>>
	 *  global $option, $mosConfig_absolute_path;
	 *	require_once("$mosConfig_absolute_path/administrator/components/com_comment/plugin/com_content/josc_com_content.php");
	 *  <<<
	 * 
	 * $row->text will be changed
	 * if $joscplugintext is set, $row->text won't be changed and  $joscplugintext will contain joomlacomment result
	 */
	if (isset($row) && $row!=null && isset($params) && $params!=null 
		&& !isset($row->module) && isset($row->sectionid)) {
	 	/*
	 	 * exclude content module ->  ["module"] is defined
	 	 */

    	global $option, $mosConfig_absolute_path;
		require_once("$mosConfig_absolute_path/components/com_comment/joscomment/utils.php");

		$comObject	= JOSC_utils::ComPluginObject('', $row);

		$JOSC_manual = method_exists($comObject, 'getBotParam') ?  $comObject->getBotParam('manual',0) : 0;
		/*
		 * if manual 
		 * 		if visual -> replace joscomment by text
		 * 		else replace by ''  and execute standard for readon
		 * else execute
		 */
		$JOSC_execute = true;
		if ($JOSC_manual) {
			$JOSC_tagVisual =  "{joscommentenable}";
			$JOSC_execute 	= !(strpos($row->introtext, $JOSC_tagVisual)===false);
			if (!$JOSC_execute)
				if (defined('_JOSC_J15'))
					$JOSC_execute 	= !(strpos($row->fulltext, $JOSC_tagVisual)===false);
				else
					$JOSC_execute 	= !(strpos($row->text, $JOSC_tagVisual)===false);

			if ($JOSC_execute) {
				$row->text 		= str_replace($JOSC_tagVisual, '', $row->text );
			}
		}
		if ($JOSC_execute) {
			/* add at the end */
			if (isset($joscplugintext))
				$joscplugintext = JOSC_utils::execJoomlaCommentPlugin($comObject, $row, $params);
			else
				$row->text .= JOSC_utils::execJoomlaCommentPlugin($comObject, $row, $params);
		}
		unset($comObject);
	}
    
?>
