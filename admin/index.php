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

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';

// Only let admin users access this page
if (!SEC_hasRights('testimonials.admin')) {
    // Someone is trying to illegally access this page
    COM_errorLog("Someone has tried to access the testimonials Admin page.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: $REMOTE_ADDR",1);
    $display = COM_siteHeader();
    $display .= COM_startBlock($LANG_TSTM01['access_denied']);
    $display .= $LANG_TSTM01['access_denied_msg'];
    $display .= COM_endBlock();
    $display .= COM_siteFooter(true);
    echo $display;
    exit;
}

USES_lib_admin();

/*
 * Display admin list of all testimonials
*/
function listEntries()
{
    global $_CONF, $_TABLES, $LANG_ADMIN, $LANG_TSTM01, $_IMAGE_TYPE;

    $retval = "";

    $header_arr = array(      # display 'text' and use table field 'field'
            array('text' => $LANG_TSTM01['client'], 'field' => 'clientname', 'sort' => true, 'align' => 'left'),
            array('text' => $LANG_TSTM01['company'], 'field' => 'company', 'sort' => true, 'align' => 'left'),
            array('text' => $LANG_TSTM01['tstdate'], 'field' => 'tst_date', 'sort' => true, 'align' => 'left'),
            array('text' => $LANG_TSTM01['edit'],   'field' => 'testid', 'sort' => false, 'align' => 'center'),
    );
    $defsort_arr = array('field'     => 'tst_date',
                         'direction' => 'DESC');
    $text_arr = array(
            'form_url'      => $_CONF['site_admin_url'] . '/plugins/testimonials/index.php',
            'help_url'      => '',
            'has_search'    => true,
            'has_limit'     => true,
            'has_paging'    => true,
            'no_data'       => 'No testimonials have been submitted',
    );

    $sql = "SELECT testid AS id1,testid,clientname,company,tst_date,text_short,views "
            . "FROM {$_TABLES['testimonials']} ";

    $query_arr = array('table' => 'testimonials',
                        'sql' => $sql,
                        'query_fields' => array('clientname','company'),
                        'default_filter' => " WHERE 1=1 ",
                        'group_by' => "");

    $filter = "";

    $actions = '<input name="delsel" type="image" src="'
            . $_CONF['layout_url'] . '/images/admin/delete.' . $_IMAGE_TYPE
            . '" style="vertical-align:bottom;" title="' . 'Delete Checked'
            . '" onclick="return confirm(\'' . 'Delete Confirm Text' . '\');"'
            . ' value="x" '
            . '/>&nbsp;' . 'Delete Selected Entries';

    $option_arr = array('chkselect' => true,
            'chkfield' => 'id1',
            'chkname' => 'testids',
            'chkminimum' => 0,
            'chkall' => true,
            'chkactions' => $actions
    );

    $formfields = '
            <input name="action" type="hidden" value="delete">
    ';

    $form_arr = array('top' => $formfields);

    $retval .= ADMIN_list('taglist', 'TST_getListField', $header_arr,
    $text_arr, $query_arr, $defsort_arr, $filter, "", $option_arr, $form_arr);

    return $retval;
}

function TST_getListField($fieldname, $fieldvalue, $A, $icon_arr, $token = "")
{
    global $_CONF, $_USER, $_TABLES, $LANG_ADMIN, $LANG04, $LANG28, $_IMAGE_TYPE;

    $retval = '';

    switch ($fieldname) {
        case 'company' :
            $retval = '<a class="'.COM_getToolTipStyle().'" title="' . htmlspecialchars($A['text_short']) . '" >'.$fieldvalue.'</a>';
            break;

        case 'testid' :
            $url = $_CONF['site_admin_url'].'/plugins/testimonials/index.php?edit=x&testid='.$A['testid'];
            $retval = '<a href="'.$url.'"><i class="uk-icon uk-icon-pencil"></i></a>';
            break;

        default:
            $retval = $fieldvalue;
            break;
    }

    return $retval;
}

function delEntry()
{
    global $_CONF, $_TABLES;

    $del_ids = $_POST['testids'];
    if ( is_array($del_ids) && count($del_ids) > 0 ) {
        foreach ($del_ids AS  $id ) {
            $delete_id = (int) COM_applyFilter($id,true);
            if ( $delete_id > 0 )
                DB_query ("DELETE FROM {$_TABLES['testimonials']} where testid = " . (int) $delete_id );
        }
    }
    return;
}

function saveEntry()
{
    global $_CONF, $_TABLES, $LANG_TSTM01;

    // we need to do some error checking here - make sure everything
    // is set and in proper format (such as date).

    $testid      = (int) COM_applyFilter($_POST['testid'],true);
    $clientName  = $_POST['clientname'];
    $company     = $_POST['company'];
    $company_url = COM_applyFilter($_POST['testurl']);
    $tst_date    = COM_applyFilter($_POST['tstdate']);
    $tst_short   = $_POST['text_short'];
    $tst_full    = $_POST['text_full'];

    $filter = new sanitizer();

    $filter->setPostmode('html');
    $text_full = $filter->filterHTML($filter->censor($tst_full));
    $text_short = $filter->filterHTML($filter->censor($tst_short));

    $filter->setPostmode('text');
    $client_name = $filter->filterText($filter->censor($clientName));
    $company_name = $filter->filterText($filter->censor($company));

    $company_url = $filter->sanitizeUrl($company_url);

    if ( $testid == 0 ) {
        $sql = "INSERT INTO {$_TABLES['testimonials']} (text_short,text_full,clientname,company,homepage,tst_date) "
               ." VALUES ('". $filter->prepareForDB($text_short)."',"
               ."'".$filter->prepareForDB($text_full)."',"
               ."'".$filter->prepareForDB($client_name)."',"
               ."'".$filter->prepareForDB($company_name)."',"
               ."'".$filter->prepareForDB($company_url)."',"
               ."'".$filter->prepareForDB($tst_date)."'"
               .");";
        $result = DB_query($sql);
    } else {
        $sql = "UPDATE {$_TABLES['testimonials']} SET "
               ."text_short='". $filter->prepareForDB($text_short)."',"
               ."text_full='".$filter->prepareForDB($text_full)."',"
               ."clientname='".$filter->prepareForDB($client_name)."',"
               ."company='".$filter->prepareForDB($company_name)."',"
               ."homepage='".$filter->prepareForDB($company_url)."',"
               ."tst_date='".$filter->prepareForDB($tst_date)."'"
               ." WHERE testid=".(int) $testid;
        $result = DB_query($sql);
    }
    COM_setMsg( 'Testimonial Successfully Saved.', 'warning' );

    return listEntries();
}

function editEntry($mode,$testid='')
{
    global $_CONF, $_TABLES, $LANG_TSTM01;

    $retval = '';
    $display = '';

    $T = new Template ($_CONF['path'] . 'plugins/testimonials/templates');
    $T->set_file ('form','newentry.thtml');

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
    ));

    if ($mode == 'edit') {
        $result = DB_query ("SELECT * FROM {$_TABLES['testimonials']} WHERE testid = ".(int) $testid);
        $A = DB_fetchArray($result);
    } else {
        $A['testid'] = '';
        $A['clientname'] = '';
        $A['company'] = '';
        $A['homepage'] = '';
        $A['tst_date'] = '';
        $A['text_short']= '';
        $A['text_full']= '';
    }
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

function tst_admin_menu($action)
{
    global $_CONF, $_TST_CONF, $LANG_ADMIN,$LANG_TSTM01;

    $retval = '';

    $menu_arr = array(
        array( 'url' => $_CONF['site_admin_url'].'/plugins/testimonials/index.php?list=x','text' => 'Testimonials','active' => ($action == 'list' ? true : false)),
        array( 'url' => $_CONF['site_admin_url'].'/plugins/testimonials/index.php?edit=x','text'=> $LANG_TSTM01['create_new'], 'active'=> ($action == 'edit' ? true : false)),
        array( 'url' => $_CONF['site_admin_url'], 'text' => $LANG_ADMIN['admin_home'])
    );

    $retval = '<h2>Testimonials</h2>';

    $retval .= ADMIN_createMenu(
        $menu_arr,
        'Need some help text here',
        $_CONF['site_url'] . '/testimonials/images/testimonials.gif'
    );

    return $retval;
}
//var_dump($_POST);exit;
$page = '';
$display = '';
$cmd ='list';

$expectedActions = array('list','edit','delete','save','delsel_x');
foreach ( $expectedActions AS $action ) {
    if ( isset($_POST[$action])) {
        $cmd = $action;
    } elseif ( isset($_GET[$action])) {
        $cmd = $action;
    }
}
if ( isset($_POST['cancel'])) $cmd = 'list';

switch ( $cmd ) {
    case 'edit' :
        if (empty ($_GET['testid'])) {
            $page = editEntry ($cmd);
        } else {
            $page = editEntry ($cmd, (int) COM_applyFilter ($_GET['testid']));
        }
        break;
    case 'save' :
        $page = saveEntry();
        break;

    case  'delsel_x':
        delEntry();
        $page = listEntries();
        break;

    case 'list' :
    default :
        $page = listEntries();
        break;
}

$display  = COM_siteHeader ('menu', $LANG_TSTM01['admin']);
$display .= tst_admin_menu($cmd);
$display .= $page;
$display .= COM_siteFooter (false);
echo $display;

?>