<?php
/**
* glFusion CMS
*
* Testimonials - Testimonials Plugin for glFusion
*
* Auto Installer
*
* @license GNU General Public License version 2 or later
*     http://www.opensource.org/licenses/gpl-license.php
*
*  Copyright (C) 2016-2021 by the following authors:
*   Mark R. Evans   mark AT glfusion DOT org
*
*  Based on the Testimonials Plugin
*  Copyright (C) 2006 by the following authors:
*  Authors: Jodi Diehl - scripts AT sunfrogservices DOTcom
*
*/

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

global $_DB_dbms;

require_once $_CONF['path'].'plugins/testimonials/functions.inc';
require_once $_CONF['path'].'plugins/testimonials/testimonials.php';
require_once $_CONF['path'].'plugins/testimonials/sql/mysql_install.php';

// +--------------------------------------------------------------------------+
// | Plugin installation options                                              |
// +--------------------------------------------------------------------------+

$INSTALL_plugin['testimonials'] = array(

    'installer' => array('type' => 'installer', 'version' => '1', 'mode' => 'install'),

    'plugin' => array('type' => 'plugin', 'name' => $_TST_CONF['pi_name'],
        'ver' => $_TST_CONF['pi_version'], 'gl_ver' => $_TST_CONF['gl_version'],
        'url' => $_TST_CONF['pi_url'], 'display' => $_TST_CONF['pi_display_name']),

    array('type' => 'table', 'table' => $_TABLES['testimonials'], 'sql' => $_SQL['testimonials']),

    array('type' => 'group', 'group' => 'testimonials Admin', 'desc' => 'Users in this group can administer the Testimonials plugin',
        'variable' => 'admin_group_id', 'addroot' => true, 'admin' => true),

    array('type' => 'feature', 'feature' => 'testimonials.admin', 'desc' => 'Ability to administer the Testimonials plugin',
            'variable' => 'admin_feature_id'),

    array('type' => 'mapping', 'group' => 'admin_group_id', 'feature' => 'admin_feature_id',
            'log' => 'Adding testimonials.admin feature to the Testimonials admin group'),

    array('type' => 'block', 'name' => 'block_testimonials', 'title' => 'Testimonials',
          'phpblockfn' => 'phpblock_testimonials', 'block_type' => 'phpblock',
          'group_id' => 'admin_group_id' , 'onleft' => true),
);


/**
* Puts the datastructures for this plugin into the glFusion database
*
* Note: Corresponding uninstall routine is in functions.inc
*
* @return   boolean True if successful False otherwise
*
*/
function plugin_install_testimonials()
{
    global $INSTALL_plugin, $_TST_CONF;

    $pi_name            = $_TST_CONF['pi_name'];
    $pi_display_name    = $_TST_CONF['pi_display_name'];
    $pi_version         = $_TST_CONF['pi_version'];

    COM_errorLog("Attempting to install the $pi_display_name plugin", 1);

    $ret = INSTALLER_install($INSTALL_plugin[$pi_name]);
    if ($ret > 0) {
        return false;
    }

    return true;
}

/**
*   Loads the configuration records for the Online Config Manager.
*
*   @return boolean     True = proceed, False = an error occured
*/
function plugin_load_configuration_testimonials()
{
    require_once dirname(__FILE__) . '/install_defaults.php';
    return plugin_initconfig_testimonials();
}


/**
* Automatic uninstall function for plugins
*
* @return   array
*
* This code is automatically uninstalling the plugin.
* It passes an array to the core code function that removes
* tables, groups, features and php blocks from the tables.
* Additionally, this code can perform special actions that cannot be
* foreseen by the core code (interactions with other plugins for example)
*
*/
function plugin_autouninstall_testimonials ()
{
    $out = array (
        /* give the name of the tables, without $_TABLES[] */
        'tables' => array('testimonials'),
        /* give the full name of the group, as in the db */
        'groups' => array('testimonials Admin'),
        /* give the full name of the feature, as in the db */
        'features' => array('testimonials.admin'),
        /* give the full name of the block, including 'phpblock_', etc */
        'php_blocks' => array('block_testimonials'),
        /* give all vars with their name */
        'vars'=> array()
    );
    return $out;
}
?>