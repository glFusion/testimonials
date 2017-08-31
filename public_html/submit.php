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
    global $_CONF, $_TST_CONF, $_TABLES, $LANG_TSTM01;

    $retval = '';
    $display = '';

    COM_clearSpeedlimit ($_TST_CONF['speedlimit'], 'testimonials');
    $last = 0;
    $last = COM_checkSpeedlimit ('testimonials');
    if ($last > 0) {
        $slMsg = sprintf($LANG_TSTM01['speedlimit_msg'], (int) ($_TST_CONF['speedlimit'] / 60));
        COM_setMsg($slMsg,'error' );
        COM_refresh($_CONF['site_url'].'/testimonials/index.php');
    }


    $T = new Template ($_CONF['path'] . 'plugins/testimonials/templates');
    $T->set_file ('form','submit_entry.thtml');

    $T->set_var(array(
        'client_text'       => $LANG_TSTM01['client'],
        'client_help'       => $LANG_TSTM01['client_help'],
        'company_text'      => $LANG_TSTM01['company'],
        'company_help'      => $LANG_TSTM01['company_help'],
        'testa_text_full'   => $LANG_TSTM01['text_full'],
        'text_full_help'    => $LANG_TSTM01['text_full_help'],
        'testdate_text'     => $LANG_TSTM01['tstdate'],
        'url_text'          => $LANG_TSTM01['homepage'],
        'url_help'          => $LANG_TSTM01['website_help'],
        'date_help'         => 'yyyy-mm-dd format',
        'lang_save'         => $LANG_TSTM01['save'],
        'lang_cancel'       => $LANG_TSTM01['cancel'],
        'sec_token'         => SEC_createToken(),
        'sec_token_name'    => CSRF_TOKEN,
        'lang_submit_title' => $LANG_TSTM01['submit_title'],
        'lang_your_name'    => $LANG_TSTM01['your_name'],
        'lang_company_name' => $LANG_TSTM01['company_name'],
        'lang_company_website' => $LANG_TSTM01['company_website'],
        'lang_website_help' => $LANG_TSTM01['website_help'],
        'lang_submit_help'  => $LANG_TSTM01['submit_help'],
        'lang_word_count'   => $LANG_TSTM01['word_count'],
    ));

    $A['testid'] = '';
    $A['clientname'] = '';
    $A['company'] = '';
    $A['homepage'] = '';
    $A['tst_date'] = '';
    $A['text_full']= '';

    $T->set_var(array(
        'row_testid'    => $A['testid'],
        'row_client'    => $A['clientname'],
        'row_company'   => $A['company'],
        'row_testurl'   => $A['homepage'],
        'row_tstdate'   => $A['tst_date'],
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
    $tst_full    = $_POST['text_full'];

    $filter = new sanitizer();

    $filter->setPostmode('text');
    $text_full      = $filter->filterText($filter->censor($tst_full));
    $client_name    = $filter->filterText($filter->censor($clientName));
    $company_name   = $filter->filterText($filter->censor($company));
    $company_url    = $filter->sanitizeUrl($company_url);

    if ( $_TST_CONF['queue_submissions'] == true ) {
        $queue = 1;
    } else {
        $queue = 0;
    }

    $sql = "INSERT INTO {$_TABLES['testimonials']} (text_full,clientname,company,homepage,tst_date,queued) "
           ." VALUES ("
           ."'".$filter->prepareForDB($text_full)."',"
           ."'".$filter->prepareForDB($client_name)."',"
           ."'".$filter->prepareForDB($company_name)."',"
           ."'".$filter->prepareForDB($company_url)."',"
           ."'".$filter->prepareForDB($tst_date)."',"
           ."'".$queue."'"
           .");";
    $result = DB_query($sql);

    if ( $_TST_CONF['queue_submissions'] != true) {
        $testid = DB_insertId($result);
        PLG_itemSaved($testid,'testimonials');
    }

    COM_updateSpeedlimit ('testimonials');

    CACHE_remove_instance('menu');

    if ( $queue ) {
        COM_setMsg( $LANG_TSTM01['testimonial_submitted'], 'warning' );
    } else {
        COM_setMsg( $LANG_TSTM01['saved_success'],'warning');
    }

    COM_refresh($_CONF['site_url'].'/testimonials/index.php');
}

if ( $_TST_CONF['disable_submissions'] == true ) {
    COM_refresh($_CONF['site_url'].'/testimonials/index.php');
}

if ( COM_isAnonUser() && $_TST_CONF['anonymous_submit'] == false ) {
    $page = SEC_loginRequiredForm();
} else {
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
            if (SEC_checkToken()) {
                $page = saveSubmission();
            }

        default :
            COM_refresh($_CONF['site_url'].'/testimonials/index.php');
            break;
    }
}
$display = COM_siteHeader($_TST_CONF['menu'],$LANG_TSTM01['plugin_name']);
$display .= $page;
$display .= COM_siteFooter();
echo $display;
?>