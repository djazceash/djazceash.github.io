<?php defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

/*
 * Copyright Copyright (C) 2007 Alain Georgette. All rights reserved.
 * Copyright Copyright (C) 2006 Frantisek Hliva. All rights reserved.
 * License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * !JoomlaComment is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * !JoomlaComment is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA  02110-1301, USA.
 */

if (!defined('_JOSC_J10') && !defined('_JOSC_J15')) {
	if (version_compare($GLOBALS['_VERSION']->RELEASE, '1.5', '>='))
		DEFINE('_JOSC_J15', true);
	else
		DEFINE('_JOSC_J10', true);
}

global $mosConfig_absolute_path;
require_once("$mosConfig_absolute_path/administrator/components/com_comment/class.config.comment.php");
//require_once($mainframe->getPath('admin_html'));

function com_uninstall()
{
    global $database, $mosConfig_absolute_path;

	if (defined('_JOSC_J15'))
		$mambots = 'plugins';
	else
		$mambots = 'mambots';
		
	$null=null;
	$config = new JOSC_config(1,$null);
    $config->load();

	echo "!Joomlacomment component uninstalled.<br />";
    
    if ($config->_complete_uninstall) {
		$database->setQuery("DROP TABLE IF EXISTS `#__comment`");
    	$database->query();    	
		$database->setQuery("DROP TABLE IF EXISTS `#__comment_utf8`");
    	$database->query();    	
		$database->setQuery("DROP TABLE IF EXISTS `#__comment_importsetting`");
    	$database->query();    	
		$database->setQuery("DROP TABLE IF EXISTS `#__comment_setting`");
    	$database->query();    	
		$database->setQuery("DROP TABLE IF EXISTS `#__comment_voting`");
    	$database->query();    	
		$database->setQuery("DROP TABLE IF EXISTS `#__comment_captcha`");
    	$database->query();    	
    	echo "\"Uninstall complete mode\" parameter has value Yes : !JoomlaComment tables have been deleted.<br />";
    } else {
    	echo "\"Uninstall complete mode\" parameter has value No : !JoomlaComment tables NOT deleted.<br />";
    }
    $database->setQuery("DELETE FROM `#__$mambots` WHERE `element`= 'joscomment';");
    $database->query();
    @unlink("$mosConfig_absolute_path/$mambots/content/joscomment.php");
    @unlink("$mosConfig_absolute_path/$mambots/content/joscomment.xml");
    $database->setQuery("DELETE FROM `#__$mambots` WHERE `element`= 'josccleancache';");
    $database->query();
    @unlink("$mosConfig_absolute_path/$mambots/system/josccleancache.php");
    @unlink("$mosConfig_absolute_path/$mambots/system/josccleancache.xml");
	echo "joscomment and josccleancache bots uninstalled.<br />";
    echo "<b>!JoomlaComment was successfully uninstalled.</b><br />";
}

?>
