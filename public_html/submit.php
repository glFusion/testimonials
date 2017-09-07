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

function submitEntry( $A = array(), $errors = array() )
{
    global $_CONF, $_TST_CONF, $_USER, $_TABLES, $LANG_TSTM01;

    if ( SEC_hasRights('testimonials.admin')) COM_refresh($_CONF['site_admin_url'].'/plugins/testimonials/index.php?edit=x');

    $retval = '';
    $display = '';

    COM_clearSpeedlimit ($_TST_CONF['speedlimit'], 'testimonials');
    $last = 0;
    $last = COM_checkSpeedlimit ('testimonials');

    if ($last > 0 && count($A) == 0 ) {
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
        'lang_email'        => $LANG_TSTM01['email'],
        'lang_email_help'   => $LANG_TSTM01['email_help'],
        'lang_text_help'    => $LANG_TSTM01['text_full_help'],
    ));

    if ( COM_isAnonUser() || $_USER['email'] == '' ) $T->set_var('anonymous_user',true);

    if ( count($A) == 0 ) {
        $A['testid'] = '';
        $A['clientname'] = '';
        $A['company'] = '';
        $A['homepage'] = '';
        $A['tst_date'] = '';
        $A['text_full']= '';
        $A['email'] = '';
        $A['owner_id'] = 1;
    }

    $T->set_var(array(
        'row_client'    => $A['clientname'],
        'row_company'   => $A['company'],
        'row_testurl'   => $A['homepage'],
        'row_tstdate'   => $A['tst_date'],
        'row_text_full' => $A['text_full'],
        'row_email'     => $A['email'],
    ));

    if ( COM_isAnonUser() && function_exists('plugin_templatesetvars_captcha') ) {
        $captcha = plugin_templatesetvars_captcha('general', $T);
        $T->set_var('captcha',$captcha);
    } else {
        $T->set_var ('captcha','');
    }

    $errorMessage = '';
    if ( count($errors) > 0 ) {
        $errorMessage = implode("<br>",$errors);
        $T->set_var('errors',$errorMessage);
    }
    $T->parse('output', 'form');
    $retval .= $T->finish($T->get_var('output'));
    return $retval;
}

function saveSubmission()
{
    global $_CONF, $_TST_CONF, $_USER, $_TABLES, $LANG_TSTM01, $LANG_TST_ERRORS,$REMOTE_ADDR;

    $errors = array();

    if (!COM_isAnonUser() ) {
        $A['owner_id'] = $_USER['uid'];
        $A['email'] = $_USER['email'];
    } else {
        $A['owner_id'] = 1;
    }

    if ( COM_isAnonUser() || $_USER['email'] == "" ) {
        $A['email'] = COM_applyFilter($_POST['email']);
    }

    $A['clientname']  = $_POST['clientname'];
    $A['company']     = $_POST['company'];
    $A['homepage']    = COM_applyFilter($_POST['testurl']);
    $A['tst_date']    = date('Y-m-d');
    $A['text_full']   = $_POST['text_full'];

    $filter = new sanitizer();

    $filter->setPostmode('text');
    $A['text_full']     = $filter->filterText($filter->censor($A['text_full']));
    $A['clientname']    = $filter->filterText($filter->censor($A['clientname']));
    $A['company']       = $filter->filterText($filter->censor($A['company']));
    $A['homepage']      = $filter->sanitizeUrl($A['homepage']);

    if ( utf8_strlen(trim($A['clientname'])) === 0 ) {
        $errors[] = $LANG_TST_ERRORS['invalid_name'];
    }

    if ( !COM_isEmail( $A['email'])  ) {
        $errors[] = $LANG_TST_ERRORS['invalid_email'];
    }

    if ( utf8_strlen(trim($A['text_full'])) === 0 ) {
        $errors[] = $LANG_TST_ERRORS['invalid_testimonial'];
    }

    if ( COM_isAnonUser() ) {
        if ( function_exists('plugin_itemPreSave_captcha') && count($errors) == 0 ) {
            if ( !isset($_POST['captcha']) ) {
                $_POST['captcha'] = '';
            }
            $msg = plugin_itemPreSave_captcha('general',$_POST['captcha']);
            if ( $msg != '' ) {
                $errors[] = $msg;
            }
        }
    }
    // Let plugins have a chance to check for spam
    $spamCheckText = $filter->Linkify($A['text_full']);
    $spamcheck = '<h1>' . $A['company'] . '</h1><p>' . $spamCheckText . '</p>';
    $result = PLG_checkforSpam ($spamcheck, $_CONF['spamx']);
    // Now check the result and display message if spam action was taken
    if ($result > 0) {
        $errors[] = $LANG_TSTM01['spam_identified'];
    }
    if ( COM_isAnonUser() && function_exists('plugin_itemPreSave_spamx')) {
       $spamCheckData = array(
            'email'     => $A['email'],
            'ip'        => $REMOTE_ADDR);

        $msg = plugin_itemPreSave_spamx('testimonials',$spamCheckData);
        if ( $msg ) {
            $errors[] = $msg;
        }
    }

    if ( count($errors) > 0 ) {
        return submitEntry($A,$errors);
    }

    if ( $_TST_CONF['queue_submissions'] == true ) {
        $queue = 1;
    } else {
        $queue = 0;
    }

    $sql = "INSERT INTO {$_TABLES['testimonials']} (text_full,clientname,company,homepage,tst_date,queued,owner_id,email) "
           ." VALUES ("
           ."'".$filter->prepareForDB($A['text_full'])."',"
           ."'".$filter->prepareForDB($A['clientname'])."',"
           ."'".$filter->prepareForDB($A['company'])."',"
           ."'".$filter->prepareForDB($A['homepage'])."',"
           ."'".$filter->prepareForDB($A['tst_date'])."',"
           ."'".$queue."',"
           . $filter->prepareForDB($A['owner_id']).","
           . "'".$filter->prepareForDB($A['email'])."'"
           .");";
    $result = DB_query($sql);

    $testid = DB_insertId($result);

    if ( $_TST_CONF['queue_submissions'] != true) {
        PLG_itemSaved($testid,'testimonials');
    }

    COM_updateSpeedlimit ('testimonials');

    CACHE_remove_instance('menu');

    if ( $queue ) {
        COM_setMsg( $LANG_TSTM01['testimonial_submitted'], 'warning' );
    } else {
        COM_setMsg( $LANG_TSTM01['saved_success'],'warning');
    }

    if ( $queue ) {
        TST_sendNotification($testid);
    }

    COM_refresh($_CONF['site_url'].'/testimonials/index.php');
}

/**
* Send an email notification for a new submission.
*
* @param    int     $testid Testimonial ID
*
*/
function TST_sendNotification($testid)
{
    global $_CONF, $_TST_CONF, $_USER, $_TABLES, $LANG_TSTM01;

    $result = DB_query("SELECT * FROM {$_TABLES['testimonials']} WHERE testid=". (int) $testid . ' AND queued=1');
    if ( DB_numRows($result) > 0 ) {
        $row = DB_fetchArray($result);

        $mailbody  = $LANG_TSTM01['mail_body'].'<br><br>';
        $mailbody .= sprintf($LANG_TSTM01['mail_mod_link'],$_CONF['site_admin_url'].'/moderation.php');
        $mailbody .= '<br>';

        $testimonials_grp_id = DB_getItem($_TABLES['groups'],'grp_id','grp_name="testimonials Admin"');
        if ( $testimonials_grp_id === NULL ) return;
        $groups = TST_getGroupList($testimonials_grp_id);
        $groupList = implode(',',$groups);
	    $sql = "SELECT DISTINCT {$_TABLES['users']}.uid,username,fullname,email "
	          ."FROM {$_TABLES['group_assignments']},{$_TABLES['users']} "
	          ."WHERE {$_TABLES['users']}.uid > 1 "
	          ."AND {$_TABLES['users']}.uid = {$_TABLES['group_assignments']}.ug_uid "
	          ."AND ({$_TABLES['group_assignments']}.ug_main_grp_id IN (".$groupList."))";
        $result = DB_query($sql);
        $nRows = DB_numRows($result);
        $toCount = 0;
        $to = array();
        $msgData = array();
        for ($i=0;$i < $nRows; $i++ ) {
            $row = DB_fetchArray($result);
            if ( $row['email'] != '' ) {
                $toCount++;
                $to[] = array('email' => $row['email'], 'name' => $row['username']);
            }
        }
        if ( $toCount > 0 ) {
            $msgData['htmlmessage'] = $mailbody;
            $msgData['textmessage'] = $mailbody;
            $msgData['subject'] = $LANG_TSTM01['mail_subject'];
            $msgData['from']['email'] = $_CONF['site_mail'];
            $msgData['from']['name'] = $_CONF['site_name'];
            $msgData['to'] = $to;
            COM_emailNotification( $msgData );
    	} else {
        	COM_errorLog("Testimonials: Error - Did not find any moderators to email");
    	}
    }
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
            break;
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