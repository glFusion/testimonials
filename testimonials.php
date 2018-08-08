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

global $_DB_table_prefix, $_TABLES;

// Plugin info

$_TST_CONF['pi_name']            = 'testimonials';
$_TST_CONF['pi_display_name']    = 'Testimonials';
$_TST_CONF['pi_version']         = '1.0.4';
$_TST_CONF['gl_version']         = '1.7.0';
$_TST_CONF['pi_url']             = 'https://www.glfusion.org/';

$_TABLES['testimonials']        = $_DB_table_prefix . 'testimonials';
?>