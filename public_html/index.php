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

require_once '../lib-common.php';

/*
* Main Function
*/

$T = new Template ($_CONF['path'] . 'plugins/testimonials/templates');

$T->set_file (array (
    'page' => 'testimonial_list.thtml',
));

$T->set_var ('header', $LANG_TSTM01['header']);

$result = DB_query ("SELECT clientname,company,text_full,homepage FROM " . $_TABLES['testimonials'] . " ORDER BY tst_date DESC");
$num = DB_numRows ($result);

$T->set_block('page','testimonials','tm');

for ($i = 0; $i < $num; $i++) {
    $A = DB_fetchArray ($result);

    $T->set_var(array(
        'client'    => $A['clientname'],
        'text_full' => nl2br(trim($A['text_full'])),
        'company_name' => $A['company'],
    ));
    if ( $A['homepage'] != '' ) {
        $T->set_var('company_url',$A['homepage']);
    } else {
        $T->unset_var('company_url');
    }
    $T->parse('tm','testimonials',true);
}

$T->parse('output', 'page');
$page = $T->finish($T->get_var('output'));

$display = COM_siteHeader();
$display .= $page;
$display .= COM_siteFooter();

echo $display;
?>