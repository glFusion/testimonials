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
	Truncated text to the nearest word based on a character count - substr()
	http://www.beliefmedia.com/php-truncate-functions
	preg-match()
	http://php.net/manual/en/function.preg-match.php
*/
function TST_truncate($string, $length, $trimmarker = '') {
  $strlen = strlen($string);
  /* mb_substr forces a break at $length if no word (space) boundary */
  $string = trim(utf8_substr($string, 0, $strlen));
  if ($strlen > $length) {
   preg_match('/^.{1,' . ($length - strlen($trimmarker)) . '}\b/su', $string, $match);
   $string = trim($match['0']) . $trimmarker;
    } else {
   $string = trim($string);
  }
 return $string;
}

/*
* Main Function
*/

$limit = $_TST_CONF['per_page'];

$page = 1;
if (isset ($_GET['page'])) {
    $page = (int) COM_applyFilter ($_GET['page'], true);
    if ($page == 0) {
        $page = 1;
    }
}
$offset = intval(($page - 1) * $limit);

$data = DB_query ("SELECT COUNT(*) AS count FROM {$_TABLES['testimonials']} WHERE queued=0");
$D = DB_fetchArray ($data);
$num_pages = ceil ($D['count'] / $limit);

$T = new Template ($_CONF['path'] . 'plugins/testimonials/templates');

$T->set_file (array (
    'page' => 'testimonial_list.thtml',
));

$T->set_var ('header', $LANG_TSTM01['header']);

$sql = "SELECT testid,clientname,company,text_full,text_short,homepage "
       ."FROM {$_TABLES['testimonials']} WHERE queued=0 ORDER BY tst_date DESC "
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
    'pagination' => $pagination,
));

if ( !COM_isAnonUser() || $_TST_CONF['anonymous_submit'] == true ) {
    $T->set_var('lang_submit_testimonial',$LANG_TSTM01['submit_testimonial']);
}

$T->set_block('page','testimonials','tm');

for ($i = 0; $i < $num; $i++) {
    $A = DB_fetchArray ($result);

    if ( $A['text_full'] == "" ) $A['text_full'] = $A['text_short'];

    $truncated = TST_truncate($A['text_full'], 500,'');
    $remaining = utf8_substr($A['text_full'],utf8_strlen($truncated));

    $T->set_var(array(
        'testid'            => $A['testid'],
        'client'            => $A['clientname'],
        'text_full'         => nl2br(trim($A['text_full'])),
        'text_truncated'    => nl2br($truncated),
        'company_name'      => $A['company'],
    ));
    if ( utf8_strlen($A['text_full']) > 300) {
        $T->set_var('text_remaining',nl2br($remaining));
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