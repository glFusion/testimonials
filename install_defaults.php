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

/** Utility plugin configuration data
*   @global array */
global $_TST_CONF;
if (!isset($_TST_CONF) || empty($_TST_CONF)) {
    $_TST_CONF = array();
    require_once dirname(__FILE__) . '/testimonials.php';
}

/** Utility plugin default configurations
*   @global array */
global $_TST_DEFAULTS;
$_TST_DEFAULTS = array(
    'displayblocks'         => 0,
    'anonymous_submit'      => false,
    'queue_submissions'     => true,
    'speedlimit'            => 300,
    'per_page'              => 15,
);

/**
*   Initialize Searcher plugin configuration
*
*   @return boolean             true: success; false: an error occurred
*/
function plugin_initconfig_testimonials()
{
    global $_CONF, $_TST_CONF, $_TST_DEFAULTS;

    $c = config::get_instance();

    if (!$c->group_exists('testimonials')) {

        $c->add('sg_main', NULL, 'subgroup', 0, 0, NULL, 0, true,'testimonials');
        $c->add('fs_main', NULL, 'fieldset', 0, 0, NULL, 0, true,'testimonials');

        $c->add('displayblocks', $_TST_DEFAULTS['displayblocks'],'select', 0, 0, 1, 5, true, 'testimonials');
        $c->add('anonymous_submit', $_TST_DEFAULTS['anonymous_submit'],'select', 0, 0, 0, 10, true, 'testimonials');
        $c->add('queue_submissions', $_TST_DEFAULTS['queue_submissions'],'select', 0, 0, 0, 15, true, 'testimonials');
        $c->add('speedlimit', $_TST_DEFAULTS['speedlimit'],'text', 0, 0, NULL, 20, true, 'testimonials');
        $c->add('per_page', $_TST_DEFAULTS['per_page'],'text', 0, 0, NULL, 25, true, 'testimonials');
     }
     return true;
}

?>
