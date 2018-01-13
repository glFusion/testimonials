<?php
/**
* glFusion CMS
*
* Testimonials Plugin Configuration
*
* @license GNU General Public License version 2 or later
*     http://www.opensource.org/licenses/gpl-license.php
*
*  Copyright (C) 2016-2018 by the following authors:
*   Mark R. Evans   mark AT glfusion DOT org
*
*/

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

$testimonialsConfigData = array(
    array(
        'name' => 'fs_main',
        'default_value' => NULL,
        'type' => 'fieldset',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => NULL,
        'sort' => 160,
        'set' => TRUE,
        'group' => 'testimonials'
    ),

    array(
        'name' => 'sg_main',
        'default_value' => NULL,
        'type' => 'subgroup',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => NULL,
        'sort' => 160,
        'set' => TRUE,
        'group' => 'testimonials'
    ),

    array(
        'name' => 'displayblocks',
        'default_value' => 0,
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 1,
        'sort' => 170,
        'set' => TRUE,
        'group' => 'testimonials'
    ),

    array(
        'name' => 'disable_submissions',
        'default_value' => 0,
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 0,
        'sort' => 180,
        'set' => TRUE,
        'group' => 'testimonials'
    ),

    array(
        'name' => 'anonymous_submit',
        'default_value' => false,
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 2,
        'sort' => 190,
        'set' => TRUE,
        'group' => 'testimonials'
    ),

    array(
        'name' => 'queue_submissions',
        'default_value' => true,
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 0,
        'sort' => 200,
        'set' => TRUE,
        'group' => 'testimonials'
    ),

    array(
        'name' => 'speedlimit',
        'default_value' => 300,
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => NULL,
        'sort' => 210,
        'set' => TRUE,
        'group' => 'testimonials'
    ),

    array(
        'name' => 'per_page',
        'default_value' => 15,
        'type' => 'text',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => NULL,
        'sort' => 220,
        'set' => TRUE,
        'group' => 'testimonials'
    ),

    array(
        'name' => 'centerblock_where',
        'default_value' => -1,
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 3,
        'sort' => 230,
        'set' => TRUE,
        'group' => 'testimonials'
    ),

    array(
        'name' => 'centerblock_rotate',
        'default_value' => false,
        'type' => 'select',
        'subgroup' => 0,
        'fieldset' => 0,
        'selection_array' => 0,
        'sort' => 240,
        'set' => TRUE,
        'group' => 'testimonials'
    ),
);
?>