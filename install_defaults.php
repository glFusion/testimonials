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

/** Utility plugin configuration data
*   @global array */
global $_TST_CONF;
if (!isset($_TST_CONF) || empty($_TST_CONF)) {
    $_TST_CONF = array();
    require_once dirname(__FILE__) . '/testimonials.php';
}

/**
*   Initialize Testimonials plugin configuration
*
*   @return boolean             true: success; false: an error occurred
*/
function plugin_initconfig_testimonials()
{
    global $_CONF;

    $c = config::get_instance();

    if (!$c->group_exists('testimonials')) {
        require_once $_CONF['path'].'plugins/testimonials/sql/testimonials_config_data.php';

        foreach ( $testimonialsConfigData AS $cfgItem ) {
            $c->add(
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
     return true;
}
?>
