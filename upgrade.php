<?php
/**
* glFusion CMS
*
* Testimonials - Testimonials Plugin for glFusion
*
* @license GNU General Public License version 2 or later
*     http://www.opensource.org/licenses/gpl-license.php
*
*  Copyright (C) 2016-2018 by the following authors:
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

// this function is called by lib-plugin whenever the 'Upgrade' option is
// selected in the Plugin Administration screen for this plugin

function testimonials_upgrade()
{
    global $_TABLES, $_CONF, $_TST_CONF, $_DB_table_prefix;

    $currentVersion = DB_getItem($_TABLES['plugins'],'pi_version',"pi_name='testimonials'");

    switch ($currentVersion) {
        case '0.2.0' :
            $c = config::get_instance();
            $c->add('disable_submissions', 0,'select', 0, 0, 0, 7, true, 'testimonials');

        case '0.3.0' :
            DB_query("ALTER TABLE {$_TABLES['testimonials']} ADD email VARCHAR(96) NULL DEFAULT NULL AFTER owner_id",1);

        case '0.4.0' :
        case '0.4.5' :
        case '0.5.0' :
            // no changes in DB / config structure
        case '0.6.0' :
            $c = config::get_instance();
            $c->add('centerblock_where', -1,'select', 0, 0, 3, 40, true, 'testimonials');

        case '0.7.0' :
            // no changes

        case '1.0.0' :
            $c = config::get_instance();
            $c->add('centerblock_rotate', false,'select', 0, 0, 0, 45, true, 'testimonials');

        case '1.0.1' :
            // no changes

        case '1.0.2' :
            // no changes

        case '1.0.3' :
            // no changes

        case '1.0.4' :
            // no changes

        case '1.0.5' :
            // no changes

        case '1.0.6' :
            // no changes

        default:
            DB_query("UPDATE {$_TABLES['plugins']} SET pi_version='".$_TST_CONF['pi_version']."',pi_gl_version='".$_TST_CONF['gl_version']."' WHERE pi_name='testimonials' LIMIT 1");
            break;
    }

    testimonials_update_config();

    CTL_clearCache();

    if ( DB_getItem($_TABLES['plugins'],'pi_version',"pi_name='testimonials'") == $_TST_CONF['pi_version']) {
        return true;
    } else {
        return false;
    }
}

function testimonials_update_config()
{
    global $_CONF, $_TST_CONF, $_TABLES;

    $c = config::get_instance();

    require_once $_CONF['path'].'plugins/testimonials/sql/testimonials_config_data.php';

    // remove stray items
    $result = DB_query("SELECT * FROM {$_TABLES['conf_values']} WHERE group_name='testimonials'");
    while ( $row = DB_fetchArray($result) ) {
        $item = $row['name'];
        if ( ($key = _searchForIdKey($item,$testimonialsConfigData)) === NULL ) {
            DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE name='".DB_escapeString($item)."' AND group_name='testimonials'");
        } else {
            $testimonialsConfigData[$key]['indb'] = 1;
        }
    }
    // add any missing items
    foreach ($testimonialsConfigData AS $cfgItem ) {
        if (!isset($cfgItem['indb']) ) {
            _addConfigItem( $cfgItem );
        }
    }
    $c = config::get_instance();
    $c->initConfig();
    $tcnf = $c->get_config('testimonials');
    // sync up sequence, etc.
    foreach ( $testimonialsConfigData AS $cfgItem ) {
        $c->sync(
            $cfgItem['name'],
            $cfgItem['default_value'],
            $cfgItem['type'],
            $cfgItem['subgroup'],
            $cfgItem['fieldset'],
            $cfgItem['selection_array'],
            $cfgItem['sort'],
            $cfgItem['set'],
            $cfgItem['group']
        );
    }
}

if ( !function_exists('_searchForId')) {
    function _searchForId($id, $array) {
       foreach ($array as $key => $val) {
           if ($val['name'] === $id) {
               return $array[$key];
           }
       }
       return null;
    }
}

if ( !function_exists('_searchForIdKey')) {
    function _searchForIdKey($id, $array) {
       foreach ($array as $key => $val) {
           if ($val['name'] === $id) {
               return $key;
           }
       }
       return null;
    }
}

if ( !function_exists('_addConfigItem')) {
    function _addConfigItem($data = array() )
    {
        global $_TABLES;

        $Qargs = array(
                       $data['name'],
                       $data['set'] ? serialize($data['default_value']) : 'unset',
                       $data['type'],
                       $data['subgroup'],
                       $data['group'],
                       $data['fieldset'],
                       ($data['selection_array'] === null) ?
                        -1 : $data['selection_array'],
                       $data['sort'],
                       $data['set'],
                       serialize($data['default_value']));
        $Qargs = array_map('DB_escapeString', $Qargs);

        $sql = "INSERT INTO {$_TABLES['conf_values']} (name, value, type, " .
            "subgroup, group_name, selectionArray, sort_order,".
            " fieldset, default_value) VALUES ("
            ."'{$Qargs[0]}',"   // name
            ."'{$Qargs[1]}',"   // value
            ."'{$Qargs[2]}',"   // type
            ."{$Qargs[3]},"     // subgroup
            ."'{$Qargs[4]}',"   // groupname
            ."{$Qargs[6]},"     // selection array
            ."{$Qargs[7]},"     // sort order
            ."{$Qargs[5]},"     // fieldset
            ."'{$Qargs[9]}')";  // default value

        DB_query($sql);
    }
}
?>