<?php defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

/**
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

require_once("$mosConfig_absolute_path/components/com_comment/joscomment/utils.php");
require_once("$mosConfig_absolute_path/administrator/components/com_comment/class.config.comment.php");

function existsAkoTable()
{
    global $database;
    $database->setQuery('');
}

class JOSCmosMenubar extends mosMenuBar {
    
}

class menucomment {
    
    function MENU_DEFAULT($component='')
    {
    	
        JOSCmosMenuBar::startTable();

	    $config = new JOSC_defaultconfig($component);
    	$config->load();
        if (strpos(strtoupper($config->_local_charset), "UTF-8")===false) {
          		JOSCmosMenuBar::custom('convertlcharset', 'extensions_f2.png', 'extensions_f2.png', "Convert to $config->_local_charset", true);       	
        }
        unset($config);

        JOSCmosMenuBar::publishList();
        JOSCmosMenuBar::unpublishList();
        JOSCmosMenuBar::addNew();
        JOSCmosMenuBar::editList();
        JOSCmosMenuBar::deleteList(" Users will not be notified. To notify users use line delete button !");
        JOSCmosMenuBar::spacer();
        JOSCmosMenuBar::endTable();
    }
    
    function SETTINGS_MENU()
    {
    	global $mosConfig_live_site;
    	
        JOSCmosMenuBar::startTable();

//        JOSCmosMenuBar::publishList();
//        JOSCmosMenuBar::unpublishList();

		/* expert mode is not still available - only used for joomlacomment DEMO */		
   		if (!(strpos($mosConfig_live_site, 'acgeorgette.net')===false)) JOSCmosMenuBar::custom('settingsexpert', 'extensions_f2.png', 'extensions_f2.png', "Expert Mode", false);       	
        JOSCmosMenuBar::addNew('settingsnew');
        JOSCmosMenuBar::editList('settingsedit'); /* $task='edit', $alt='Edit' */
        JOSCmosMenuBar::deleteList('', 'settingsremove'); /* $msg='', $task='remove', $alt='Delete' */
        JOSCmosMenuBar::spacer();
        JOSCmosMenuBar::endTable();
    }

    function SETTINGSEXPERT_MENU()
    {
    	
        JOSCmosMenuBar::startTable();

//        JOSCmosMenuBar::publishList();
//        JOSCmosMenuBar::unpublishList();
        JOSCmosMenuBar::addNew('settingsnewexpert');
        JOSCmosMenuBar::editList('settingseditexpert'); /* $task='edit', $alt='Edit' */
        JOSCmosMenuBar::deleteList('', 'settingsremove'); /* $msg='', $task='remove', $alt='Delete' */
        JOSCmosMenuBar::spacer();
        JOSCmosMenuBar::endTable();
    }

    function CONFIG_MENU()
    {
        JOSCmosMenuBar::startTable();
        JOSCmosMenuBar::save('savesettings');
        JOSCmosMenuBar::apply('applysettings');
        JOSCmosMenuBar::cancel('settings');
        JOSCmosMenuBar::spacer();
        JOSCmosMenuBar::endTable();
    }

    function CONFIGEXPERT_MENU()
    {
        JOSCmosMenuBar::startTable();
        JOSCmosMenuBar::save('savesettingsexpert');
        JOSCmosMenuBar::apply('applysettingsexpert');
        JOSCmosMenuBar::cancel('settingsexpert');
        JOSCmosMenuBar::spacer();
        JOSCmosMenuBar::endTable();
    }

    function CONFIGSIMPLE_MENU()
    {
        JOSCmosMenuBar::startTable();
        JOSCmosMenuBar::save('savesettingssimple');
        JOSCmosMenuBar::apply('applysettingssimple');
//        JOSCmosMenuBar::cancel('');  /* -> view comments */
        JOSCmosMenuBar::spacer();
        JOSCmosMenuBar::endTable();
    }

    function IMPORT_MENU($josctask)
    {
        JOSCmosMenuBar::startTable();
        JOSCmosMenuBar::apply($josctask['apply'], 'Preview');
		//mosMenuBar::custom($josctask['import'], 'move.png', 'move_f2.png', 'Import selected' );
      	JOSCmosMenuBar::custom($josctask['importall'], 'move.png', 'move_f2.png', 'Import ALL', false);
      	JOSCmosMenuBar::spacer();
        JOSCmosMenuBar::endTable();
    }

    function FILE_MENU()
    {
        JOSCmosMenuBar::startTable();
        JOSCmosMenuBar::save();
        JOSCmosMenuBar::cancel();
        JOSCmosMenuBar::spacer();
        JOSCmosMenuBar::endTable();
    }
    function ABOUT_MENU()
    {
        JOSCmosMenuBar::startTable();
        JOSCmosMenuBar::back();
        JOSCmosMenuBar::spacer();
        JOSCmosMenuBar::endTable();
    }

    function JUST_BACK()
    {
        JOSCmosMenuBar::startTable();
        JOSCmosMenuBar::back();
        JOSCmosMenuBar::spacer();
        JOSCmosMenuBar::endTable();
    }

}

?>