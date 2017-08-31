<?php
/**
* glFusion CMS
*
* Testimonials - Testimonials Plugin for glFusion
*
* @license GNU General Public License version 2 or later
*     http://www.opensource.org/licenses/gpl-license.php
*
*  Copyright (C) 2016-2017 by the following authors:
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
            DB_query("ALTER TABLE {$_TABLES['testimonials'] ADD email VARCHAR(96) NULL DEFAULT NULL AFTER owner_id",1);

        default:
            DB_query("UPDATE {$_TABLES['plugins']} SET pi_version='".$_TST_CONF['pi_version']."',pi_gl_version='".$_TST_CONF['gl_version']."' WHERE pi_name='testimonials' LIMIT 1");
            break;
    }

    CTL_clearCache();

    if ( DB_getItem($_TABLES['plugins'],'pi_version',"pi_name='testimonials'") == $_TST_CONF['pi_version']) {
        return true;
    } else {
        return false;
    }
}
?>