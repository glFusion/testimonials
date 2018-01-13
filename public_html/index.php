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

require_once '../lib-common.php';


/*
* Main Function
*/

$query  = '';
$tid    = 0;
$page   = 1;

COM_setArgNames( array('id') );
$tid = (int) COM_applyFilter(COM_getArgument( 'id' ),true);

if (isset ($_GET['query'])) {
    $query = trim(COM_applyFilter ($_GET['query']));
}

$limit = $_TST_CONF['per_page'];

if (isset ($_GET['page'])) {
    $page = (int) COM_applyFilter ($_GET['page'], true);
    if ($page == 0) {
        $page = 1;
    }
}
$offset = intval(($page - 1) * $limit);

$where = '';
if ( $tid != 0 ) {
    $where = " AND testid=".(int) $tid. " ";
}

$data = DB_query ("SELECT COUNT(*) AS count FROM {$_TABLES['testimonials']} WHERE queued=0" . $where);
$D = DB_fetchArray ($data);
$num_pages = ceil ($D['count'] / $limit);

$filter = new \sanitizer();

$T = new Template ($_CONF['path'] . 'plugins/testimonials/templates');

$T->set_file (array (
    'page' => 'testimonial_list.thtml',
));

$T->set_var ('header', $LANG_TSTM01['header']);

$sql = "SELECT testid,clientname,company,text_full,homepage "
       ."FROM {$_TABLES['testimonials']} WHERE queued=0 "
       . $where
       ."ORDER BY tst_date, testid DESC "
       ."LIMIT ".$offset.",".$limit;

$result = DB_query ($sql);
$num = DB_numRows ($result);

$pagination = COM_printPageNavigation ($_CONF['site_url'].'/testimonials/index.php', $page, $num_pages);

if ( $num === 0 ) {
    $T->set_var('no_testimonials',true);
    $T->set_var('lang_no_testimonials',$LANG_TSTM01['no_testimonials']);
}

$T->set_var(array(
    'lang_customers_saying' => $LANG_TSTM01['customers_saying'],
    'lang_more' => $LANG_TSTM01['more'],
    'lang_less' => $LANG_TSTM01['less'],
    'lang_view_all' => $LANG_TSTM01['view_all'],
    'pagination' => $pagination,
));

if ( $_TST_CONF['disable_submissions'] == false && (!COM_isAnonUser() || $_TST_CONF['anonymous_submit'] == true ) ) {
    $T->set_var('lang_submit_testimonial',$LANG_TSTM01['submit_testimonial']);
}

$T->set_block('page','testimonials','tm');

for ($i = 0; $i < $num; $i++) {
    $A = DB_fetchArray ($result);

    if ($tid == 0 ) {
        $truncated = TST_truncate($A['text_full'], 500,'');
        $remaining = utf8_substr($A['text_full'],utf8_strlen($truncated));
    } else {
        $truncated = $A['text_full'];
        $remaining = "";
        $T->set_var('single_testimonial',true);
    }
    $T->set_var(array(
        'testid'            => $A['testid'],
        'client'            => COM_highlightQuery($filter->censor($A['clientname']),$query),
        'text_full'         => COM_highlightQuery(nl2br(trim($filter->censor($A['text_full']))),$query),
        'text_truncated'    => COM_highlightQuery(nl2br($filter->censor($truncated)),$query),
        'company_name'      => COM_highlightQuery($filter->censor($A['company']),$query),
    ));
    if ( utf8_strlen($A['text_full']) > 300) {
        $T->set_var('text_remaining',COM_highlightQuery(nl2br($filter->censor($remaining)),$query));
    } else {
        $T->unset_var('text_remaining');
    }
    if ( $A['homepage'] != '' ) {
        $T->set_var('company_url',$A['homepage']);
    } else {
        $T->unset_var('company_url');
    }
    $T->parse('tm','testimonials',true);
}

$T->parse('output', 'page');
$page = $T->finish($T->get_var('output'));

$display = COM_siteHeader($_TST_CONF['menu'],$LANG_TSTM01['plugin_name']);
$display .= $page;
$display .= COM_siteFooter();

echo $display;
?>