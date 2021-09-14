<?php
defined('_VALID_MOS') or die('Restricted access');
/*
 * include the following instructions :

<!-- START joomlacomment INSERT -->
<div style="float:left;" align="left">
<?php
	global $option, $mosConfig_absolute_path;
	require_once("$mosConfig_absolute_path/administrator/components/com_comment/plugin/$option/josc_com_docman_doc.php");
?>
</div>
<div class="clr"></div>
<!-- END OF joomlacomment INSERT -->

 * 	at the end of the following file :
 *	components/com_docman/themes/.../templates/documents/document.tpl.php
 *
 * HTML part of the code can be changed ! according to the theme...
 */

    global $option, $mosConfig_absolute_path;
	require_once("$mosConfig_absolute_path/components/com_comment/joscomment/utils.php");

	$comObject = JOSC_utils::ComPluginObject($option,$this->data);
	echo JOSC_utils::execJoomlaCommentPlugin($comObject, $this->data, $this->data->params, true);
	unset($comObject);
?>
