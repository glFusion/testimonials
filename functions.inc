<?php
/**
* glFusion CMS
*
* Testimonials - Testimonials Plugin for glFusion
*
* glFusion Interface functions.inc
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

/**
* Language file Include
*/
$langfile = $_CONF['path'] . 'plugins/testimonials/language/' . $_CONF['language'] . '.php';

if (file_exists ($langfile)) {
    include_once $langfile;
} else {
    include_once $_CONF['path'] . 'plugins/testimonials/language/english_utf-8.php';
}

/*
* Include testimonials config file
*/
require_once $_CONF['path'] . 'plugins/testimonials/testimonials.php';

// temporary configuration settings
$_TST_CONF['show_blocks'] = 0;
$_TST_CONF['anonymous_submit'] = 1;
$_TST_CONF['queue_submissions'] = 1;


/**
* Returns the items for this plugin that should appear on the main menu
*
* NOTE: this MUST return the url/value pairs in the following format
* $<arrayname>[<label>] = <url>
*
* Universal Plugin:  Uncomment and fill in variables;
*
* @return    array   Associative array of menu items to be added to menu
*
*/
function plugin_getmenuitems_testimonials()
{
    global $_CONF, $_TABLES;

    $menulabel = "Testimonials";
    $menurul = $_CONF['site_url'] . "/testimonials/index.php";

    $menuitems = array();
  	$menuitems["$menulabel"] = $menurul;

    return $menuitems;

}

/**
* Check to see if this Plugin uses comments
*
* glFusion is checking to see if this plugin supports comments, tell it no!
* NOTE: to support comments you must use the same date/time based ID for your
* widget.  In other words, to make primary keys for your plugin you should call
* makesid().  Comments are keyed off of that...it is a limitation on how glFusion
* does comments.
*
* @return   boolean true or false
*
*/
function plugin_commentsupport_testimonials()
{
    return false;
}

/**
* This will put an option for the plugin in the command and control block on moderation.php
*
* Add the plugin name, icon and link to the command and control block in moderation.php
*
* @return   array   Array containing (plugin name, admin url, url of plugin icon)
*
*/
function plugin_cclabel_testimonials()
{
    global $_CONF;

    return array(
        'Testimonials',
        $_CONF['site_admin_url'] . '/plugins/testimonials/index.php',
        $_CONF['site_url'] . '/testimonials/images/testimonials.png'
    );
}

/**
* Returns the administrative option for this plugin
*
* Adds the plugin to the Admin menu
*
* Universal Plugin:  Modify/Uncomment use it
*
* @return   array   Array containing (plugin name, plugin admin url, # of items in plugin or '')
*
*/
function plugin_getadminoption_testimonials()
{
    global $_CONF, $_TABLES;

    if (SEC_hasRights('testimonials.admin')) {
        return array(
            'Testimonials',
            $_CONF['site_admin_url'] . '/plugins/testimonials/index.php',
            DB_count($_TABLES['testimonials'],'queued',0)
        );
    }
}

/**
* Counts the items that are submitted
*
* @return   int     number of items in submission queue
*
*/
function plugin_submissioncount_testimonials()
{
    global $_TABLES;

    $retval = 0;

    $retval = DB_count ($_TABLES['testimonials'],'queued',1);

    return $retval;
}


/**
* returns list of moderation values
*
* The array returned contains (in order): the row 'id' label, main plugin
* table, moderation fields (comma seperated), and plugin submission table
*
* @return       array        Returns array of useful moderation values
*
*/
function plugin_moderationvalues_testimonials()
{
    global $_TABLES;

    return array (
        'testid',
        $_TABLES['testimonials'],
        "testid,queued",
        ''
    );
}

/**
* Performs plugin exclusive work for items approved by moderation
*
* While moderation.php handles the actual move from mediagallery submission
* to mediagallery tables, within the function we handle all other approval
* relate tasks
*
* @param      string       $id      Identifying string
* @return     string       Any wanted HTML output
*
*/
function plugin_moderationapprove_testimonials($id)
{
    global $_CONF, $_TABLES;

    if ( (int) $id <= 0 ) return '';

    $sql = "UPDATE {$_TABLES['testimonials']} SET queued=0 WHERE testid=".(int) $id;
    DB_query($sql);
//@TODO translation
    COM_setMsg('Testimonial Submission has been approved','warning');
    return '';
}

/**
* Performs plugin exclusive work for items deleted by moderation
*
* While moderation.php handles the actual removal from mediagallery submission
* table, within this function we handle all other deletion
* relate tasks
*
* @param      string       $id      Identifying string
* @return     string       Any wanted HTML output
*
*/
function plugin_moderationdelete_testimonials($id)
{
    global $_CONF, $_TABLES;

    if ( (int) $id <= 0 ) return '';

    $sql = "DELETE FROM {$_TABLES['testimonials']} WHERE testid=".(int) $id . " AND queued=1";
    DB_query($sql);
    return;
}


/**
* show files for moderation on submissions page
*
* Uses the Plugin class to return data required by moderation.php to list
* plugin objects that need to be moderated.
*
* @param        string token The
* @return       Plugin       return HTML
*
*/
function plugin_itemlist_testimonials($token)
{
    global $_CONF, $_TABLES, $_USER, $_MG_CONF, $LANG_MG00, $LANG_MG01, $LANG_MG02,$LANG_MG10;
    global $LANG01, $LANG24, $LANG29, $LANG_ADMIN, $_IMAGE_TYPE;

    $retval = '';
    $key='media_id';

    if ( COM_isAnonUser() ) {
        $uid = 1;
    } else {
        $uid = $_USER['uid'];
    }

    $sql = "SELECT * FROM {$_TABLES['testimonials']} WHERE queued = 1";

    $result = DB_query($sql);
    $nrows = DB_numRows($result);

    if ( $nrows == 0 ) return;

    $data_arr = array();
    for ($i = 0; $i < $nrows; $i++) {
        $A = DB_fetchArray($result);
        $A['edit'] = 'edit';
        $A['_type_']  = 'testimonials';
        $A['_key_']   = 'testid';      // name of key/id field
        $data_arr[$i] = $A;     // push row data into array
    }

//@TODO translation
    $header_arr = array(      // display 'text' and use table field 'field'
        array('text' => $LANG_ADMIN['edit'], 'field' => 'testid', 'align' => 'center', 'width' => '25px'),
        array('text' => 'Client Name', 'field' => 'clientname'),
        array('text' => 'Company', 'field' => 'company'),
        array('text' => 'Date', 'field' => 'tst_date', 'align' => 'center', 'width' => '15%'),
        array('text' => 'Testimonial','field'=>'text_full'),
    );

    $text_arr = array('has_menu'    => false,
                      'title'       => 'Testimonial Submissions',
                      'help_url'    => '',
                      'no_data'     => 'No submissions',
                      'form_url'    => "{$_CONF['site_admin_url']}/moderation.php"
    );

    $actions = '<input name="approve" type="image" src="'
        . $_CONF['layout_url'] . '/images/admin/accept.' . $_IMAGE_TYPE
        . '" style="vertical-align:bottom;" title="' . $LANG29[44]
        . '" onclick="return confirm(\'' . $LANG29[45] . '\');"'
        . '/>&nbsp;' . $LANG29[1];
    $actions .= '&nbsp;&nbsp;&nbsp;&nbsp;';
    $actions .= '<input name="delbutton" type="image" src="'
        . $_CONF['layout_url'] . '/images/admin/delete.' . $_IMAGE_TYPE
        . '" style="vertical-align:text-bottom;" title="' . $LANG01[124]
        . '" onclick="return confirm(\'' . $LANG01[125] . '\');"'
        . '/>&nbsp;' . $LANG_ADMIN['delete'];

    $options = array('chkselect' => true,
                     'chkfield' => 'testid',
                     'chkname' => 'selitem',
                     'chkminimum' => 0,
                     'chkall' => true,
                     'chkactions' => $actions,
                     );

    $form_arr['bottom'] = '<input type="hidden" name="type" value="testimonials"/>' . LB
            . '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . $token . '"/>' . LB
            . '<input type="hidden" name="moderation" value="x"/>' . LB
            . '<input type="hidden" name="count" value="' . $nrows . '"/>';

    $retval .= ADMIN_simpleList('_tstm_getListField', $header_arr,
                              $text_arr, $data_arr, $options, $form_arr, $token);
    return $retval;
}

function _tstm_getListField($fieldname, $fieldvalue, $A, $icon_arr, $token = "")
{
    global $_CONF;

    $retval = '';

    switch ( $fieldname ) {
        case 'testid' :
            $url = $_CONF['site_admin_url'].'/plugins/testimonials/index.php?edit=x&src=mod&testid='.$A['testid'];
            $retval = '<a href="'.$url.'"><i class="uk-icon uk-icon-pencil"></i></a>';

            break;
        case 'text_full' :
//@TODO translation
            $retval = '

            <button type="button" class="uk-button uk-button-success" data-uk-modal="{target:\'#tstid'.$A['testid'].'\'}">Testimonial</button>
            <div id="tstid'.$A['testid'].'" class="uk-modal">
                <div class="uk-modal-dialog">
                    <a class="uk-modal-close uk-close"></a>
                    <h3>Summary</h3>
                    '.nl2br($A['text_short']).'
                    <h3>Full Review</h3>
                    '.nl2br($A['text_full']).'
                </div>
            </div>
            ';
            break;

        default :
            $retval = $fieldvalue;
            break;
    }

    return $retval;
}

/**
* Returns data for the random
*
* block for the Testimonials plugin
*
*/
function phpblock_testimonials()
{
    global $_CONF, $_TABLES;

    $retval = '';

    $randomSelect = "RAND(NOW()) ";

    $sql = "SELECT testid,clientname,company,homepage,text_short
					FROM {$_TABLES['testimonials']}
					ORDER BY RAND(NOW()) LIMIT 1";

    $result = DB_query($sql);
    if (DB_numRows($result) == 1 ) {
        $A = DB_fetchArray($result);
        $addhit = DB_query("update {$_TABLES['testimonials']} set views = views + 1 where testid = $A[testid]");

    	$retval .= '<p>'.nl2br($A['text_short']).'</p>';
    	$retval .= '<p align="right">'.$A['clientname'].'<br>';
    	$retval .= '<a href="'.$A['homepage'].'" target="_blank">'.$A['company'].'</a></p>';
	}
    return $retval;
}
?>