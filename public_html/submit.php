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

function submitEntry( $A = array() )
{
    global $_CONF, $_TABLES, $LANG_TSTM01;

    $retval = '';
    $display = '';

    $T = new Template ($_CONF['path'] . 'plugins/testimonials/templates');
    $T->set_file ('form','submit_entry.thtml');

    $T->set_var(array(
        'client_text'       => $LANG_TSTM01['client'],
        'client_help'       => $LANG_TSTM01['client_help'],
        'company_text'      => $LANG_TSTM01['company'],
        'company_help'      => $LANG_TSTM01['company_help'],
        'testa_text_short'  => $LANG_TSTM01['text_short'],
        'text_short_help'   => $LANG_TSTM01['text_short_help'],
        'testa_text_full'   => $LANG_TSTM01['text_full'],
        'text_full_help'    => $LANG_TSTM01['text_full_help'],
        'testdate_text'     => $LANG_TSTM01['tstdate'],
        'url_text'          => $LANG_TSTM01['homepage'],
        'url_help'          => $LANG_TSTM01['homepage_help'],
        'date_help'         => 'yyyy-mm-dd format',
        'lang_save'         => $LANG_TSTM01['save'],
        'lang_cancel'       => $LANG_TSTM01['cancel'],
        'sec_token'         => SEC_createToken(),
        'sec_token_name'    => CSRF_TOKEN,
        'lang_submit_title' => $LANG_TSTM01['submit_title'],
        'lang_your_name'    => $LANG_TSTM01['your_name'],
        'lang_company_name' => $LANG_TSTM01['company_name'],
        'lang_company_website' => $LANG_TSTM01['company_website'],
        'lang_submit_help'  => $LANG_TSTM01['submit_help'],
    ));

    $A['testid'] = '';
    $A['clientname'] = '';
    $A['company'] = '';
    $A['homepage'] = '';
    $A['tst_date'] = '';
    $A['text_short']= '';
    $A['text_full']= '';

    $T->set_var(array(
        'row_testid'    => $A['testid'],
        'row_client'    => $A['clientname'],
        'row_company'   => $A['company'],
        'row_testurl'   => $A['homepage'],
        'row_tstdate'   => $A['tst_date'],
        'row_text_short'=> $A['text_short'],
        'row_text_full' => $A['text_full'],
    ));
    if (!empty($testid) && SEC_hasRights('testimonials.admin')) {
        $T->set_var ('delete_option', '<input type="submit" value="' . $LANG_TSTM01['delete'] . '" name="mode" onClick="return delconfirm()">');
        $T->set_var ('lang_delete',$LANG_TSTM01['delete']);
    }

    if (!empty($testid)) {
        $T->set_var ('cancel_option', '<input type="submit" value="' . $LANG_TSTM01['cancel'] . '" name="mode">');
        $T->set_var('lang_cancel',$LANG_TSTM01['cancel']);
    }

    $T->parse('output', 'form');
    $retval .= $T->finish($T->get_var('output'));
    return $retval;
}

function saveSubmission()
{
    global $_CONF, $_TST_CONF, $_TABLES, $LANG_TSTM01;

    // we need to do some error checking here - make sure everything
    // is set and in proper format (such as date).


    $clientName  = $_POST['clientname'];
    $company     = $_POST['company'];
    $company_url = COM_applyFilter($_POST['testurl']);
    $tst_date    = date('Y-m-d');
    $tst_short   = $_POST['text_short'];
    $tst_full    = $_POST['text_full'];

    $filter = new sanitizer();

    $filter->setPostmode('text');
    $text_full      = $filter->filterText($filter->censor($tst_full));
    $text_short     = $filter->filterText($filter->censor($tst_short));
    $client_name    = $filter->filterText($filter->censor($clientName));
    $company_name   = $filter->filterText($filter->censor($company));
    $company_url    = $filter->sanitizeUrl($company_url);

    if ( $_TST_CONF['queue_submissions'] == true ) {
        $queue = 1;
    } else {
        $queue = 0;
    }

    $sql = "INSERT INTO {$_TABLES['testimonials']} (text_short,text_full,clientname,company,homepage,tst_date,queued) "
           ." VALUES ('". $filter->prepareForDB($text_short)."',"
           ."'".$filter->prepareForDB($text_full)."',"
           ."'".$filter->prepareForDB($client_name)."',"
           ."'".$filter->prepareForDB($company_name)."',"
           ."'".$filter->prepareForDB($company_url)."',"
           ."'".$filter->prepareForDB($tst_date)."',"
           ."'".$queue."'"
           .");";
    $result = DB_query($sql);

    CACHE_remove_instance('menu');

    COM_setMsg( 'Testimonial Successfully Submitted.', 'warning' );

    COM_refresh($_CONF['site_url'].'/testimonials/index.php');
}

$cmd = 'add';
$expectedActions = array('add','save');
foreach ( $expectedActions AS $action ) {
    if ( isset($_POST[$action])) {
        $cmd = $action;
    } elseif ( isset($_GET[$action])) {
        $cmd = $action;
    }
}
if ( isset($_POST['cancel'])) COM_refresh($_CONF['site_url'].'/testimonials/index.php');

switch ( $cmd ) {
    case 'add' :
        $page = submitEntry();
        break;
    case 'save' :
        $page = saveSubmission();
        break;

    default :
        COM_refresh($_CONF['site_url'].'/testimonials/index.php');
        break;
}

$display = COM_siteHeader('menu');
$display .= $page;
$display .= COM_siteFooter();
echo $display;
?>