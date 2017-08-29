<?php
// +--------------------------------------------------------------------------+
// | Testimonials Plugin for glFusion                                         |
// +--------------------------------------------------------------------------+
// | functions.inc                                                            |
// |                                                                          |
// | glFusion integration APIs                                                |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2016-2017 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
// | Based on the Testimonials Plugin                                         |
// | Copyright (C) 2006 by the following authors:                             |
// |                                                                          |
// | Authors: Jodi Diehl        - scripts AT sunfrogservices DOTcom           |
// +--------------------------------------------------------------------------+
// |                                                                          |
// | This program is free software; you can redistribute it and/or            |
// | modify it under the terms of the GNU General Public License              |
// | as published by the Free Software Foundation; either version 2           |
// | of the License, or (at your option) any later version.                   |
// |                                                                          |
// | This program is distributed in the hope that it will be useful,          |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
// | GNU General Public License for more details.                             |
// |                                                                          |
// | You should have received a copy of the GNU General Public License        |
// | along with this program; if not, write to the Free Software Foundation,  |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.          |
// |                                                                          |
// +--------------------------------------------------------------------------+

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

/**
* Language file Include
*/
$langfile = $_CONF['path'] . 'plugins/testimonials/language/' . $_CONF['language'] . '.php';

if (file_exists ($langfile)) {
    include_once $langfile;
} else {
    include_once $_CONF['path'] . 'plugins/testimonials/language/english_utf-8.php';
}

/*
* Include testimonials config file
*/
require_once $_CONF['path'] . 'plugins/testimonials/testimonials.php';

/**
* Returns the items for this plugin that should appear on the main menu
*
* NOTE: this MUST return the url/value pairs in the following format
* $<arrayname>[<label>] = <url>
*
* Universal Plugin:  Uncomment and fill in variables;
*
* @return    array   Associative array of menu items to be added to menu
*
*/
function plugin_getmenuitems_testimonials()
{
    global $_CONF;

    $menulabel = "Testimonials";
    $menurul = $_CONF['site_url'] . "/testimonials/index.php";

    $menuitems = array();
	if (SEC_hasRights('testimonials.view')) {
    	$menuitems["$menulabel"] = $menurul;
    }
    return $menuitems;

}

/**
* Check to see if this Plugin uses comments
*
* glFusion is checking to see if this plugin supports comments, tell it no!
* NOTE: to support comments you must use the same date/time based ID for your
* widget.  In other words, to make primary keys for your plugin you should call
* makesid().  Comments are keyed off of that...it is a limitation on how glFusion
* does comments.
*
* @return   boolean true or false
*
*/
function plugin_commentsupport_testimonials()
{
    return false;
}

/**
* This will put an option for the plugin in the command and control block on moderation.php
*
* Add the plugin name, icon and link to the command and control block in moderation.php
*
* @return   array   Array containing (plugin name, admin url, url of plugin icon)
*
*/
function plugin_cclabel_testimonials()
{
    global $_CONF;

    return array('Testimonials',$_CONF['site_admin_url'] . '/plugins/testimonials/index.php',$_CONF['site_url'] . '/testimonials/images/testimonials.gif');
}

/**
* Returns the administrative option for this plugin
*
* Adds the plugin to the Admin menu
*
* Universal Plugin:  Modify/Uncomment use it
*
* @return   array   Array containing (plugin name, plugin admin url, # of items in plugin or '')
*
*/
function plugin_getadminoption_testimonials()
{
    global $_CONF, $_TABLES;

    if (SEC_hasRights('testimonials.admin')) {
        return array('Testimonials', $_CONF['site_admin_url'] . '/plugins/testimonials/index.php', DB_count($_TABLES['testimonials']));
    }
}

/**
* Returns data for the random
*
* block for the Testimonials plugin
*
*/
function phpblock_testimonials()
{
    global $_CONF, $_TABLES;

    $retval = '';

    $sql = "SELECT testid,clientname,company,homepage,text_short
					FROM {$_TABLES['testimonials']}
					order by views ASC, tst_date
					LIMIT 1";

    $result = DB_query($sql);
    if (DB_numRows($result) == 1 ) {
        $A = DB_fetchArray($result);
        $addhit = DB_query("update {$_TABLES['testimonials']} set views = views + 1 where testid = $A[testid]");

    	$retval .= '<p>'.nl2br($A['text_short']).'</p>';
    	$retval .= '<p align="right">'.$A['clientname'].'<br>';
    	$retval .= '<a href="'.$A['homepage'].'" target="_blank">'.$A['company'].'</a></p>';
	}
    return $retval;
}

/**
* Removes the datastructures for this plugin from the glFusion database.
* This routine will get called from the Plugin install program if user select De-Install or if Delete is used in the Plugin Editor.
* The Plugin Installer will also call this routine upon and install error to remove anything it has created.
* The Plugin installer will pass the optional parameter which will then double check that plugin has first been disabled.
*
* For this plugin, this routine will also remove the Block definition.
*
* Returns True if all Plugin related data is removed without error
*
* @param    string   $installCheck     Defult is blank but if set, check if plugin is disabled first
*
* @return   boolean True if successful false otherwise
*
*/
function plugin_uninstall_testimonials($installCheck='')
{
    global $_TABLES, $LANG_TSTM01;

    // The testimonials need to be changed to the name of your plugin
    //
    $pi_name='testimonials';
    $FEATURES = array ( 'testimonials.admin', 'testimonials.view');
    $TABLES = array ('testimonials');

    // Check and see if plugin is still enabled - if so display warning and exit
    if ($installCheck != '' && DB_getItem($_TABLES['plugins'],'pi_enabled', 'pi_name = "' .$pi_name. '"')) {
	    COM_errorLog("Plugin is installed and enabled. Disable first if you want to de-install it",1);
		$display .= COM_startBlock($LANG_TSTM01['warning']);
		$display .= $LANG_TSTM01['enabled'];
		$display .= COM_endBlock();
		echo $display;
		return false;
		exit;
	}

	// Ok to proceed and delete plugin

    // Unregister the plugin with glFusion
    COM_errorLog('Attempting to unregister the testimonials plugin from glFusion',1);
    DB_query("DELETE FROM {$_TABLES['plugins']} WHERE pi_name = 'testimonials'",1);

    // Drop testimonials tables
    foreach($TABLES as $table) {
        $t = $_TABLES["$table"];
        COM_errorLog("Removing Table $t",1);
        DB_query("DROP TABLE $t",1);
    }

    // Remove Security for this plugin
    $grp_id = DB_getItem($_TABLES['vars'], 'value', "name = '{$pi_name}_gid'");

    COM_errorLog("Removing $pi_name Admin Group", 1);
    DB_query("DELETE FROM {$_TABLES['groups']} WHERE grp_id = $grp_id",1);
    DB_query("DELETE FROM {$_TABLES['vars']} WHERE name = '{$pi_name}_gid'");
    COM_errorLog("Removing root users from admin of $pi_name");
    DB_query("DELETE FROM {$_TABLES['group_assignments']} WHERE ug_main_grp_id = $grp_id",1);

	// remove from blocks
	DB_query("DELETE FROM {$_TABLES['blocks']} WHERE name = 'testimonials'");

    // Remove all the associated features -- access rights
    foreach ($FEATURES as $feature) {
        COM_errorLog("Removing $feature feature and rights to it",1);
        $feat_id = DB_getItem($_TABLES['features'], 'ft_id', "ft_name = '$feature'");
        DB_query("DELETE FROM {$_TABLES['access']} WHERE acc_ft_id = $feat_id",1);
        DB_query("DELETE FROM {$_TABLES['features']} WHERE ft_id = $feat_id",1);
    }

    COM_errorLog('...success',1);
    return true;
}

?>