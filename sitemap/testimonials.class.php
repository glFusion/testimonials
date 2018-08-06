<?php
/**
* glFusion CMS
*
* Testimonials - Testimonials Plugin for glFusion
*
* SiteMap Integration
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

class sitemap_testimonials extends sitemap_base
{
    protected $name = 'testimonials';

    public function getDisplayName()
    {
        global $LANG_TSTM01;
        return $LANG_TSTM01['plugin_name'];
    }

    /**
    *   @param  mixed   $tid    Topic or Category ID, not used
    *   @return array of (
    *   'id'        => $id (string),
    *   'title'     => $title (string),
    *   'uri'       => $uri (string),
    *   'date'      => $date (int: Unix timestamp),
    *   'image_uri' => $image_uri (string)
    * )
    */
    public function getItems($tid = false)
    {
        global $_CONF, $_TST_CONF, $_TABLES;

        $retval = array();

        $sql = "SELECT testid,clientname,company,homepage,text_full,UNIX_TIMESTAMP(tst_date) AS tst_date
    					FROM {$_TABLES['testimonials']} WHERE queued=0 ORDER BY tst_date ASC";

        $result = DB_query($sql);
        if (DB_error()) {
            COM_errorLog("sitemap_testimonials::getItems error: $sql");
            return $retval;
        }

        while ($A = DB_fetchArray($result, false)) {
            $retval[] = array(
                'id'        => $A['testid'],
                'title'     => $A['clientname'] . ' ' . $A['company'],
                'uri'       => COM_buildUrl($_CONF['site_url'] . '/testimonials/index.php?id='.(int) $A['testid']),
                'date'      => $A['tst_date'],
                'imageurl'  => false,
            );
        }
        return $retval;
    }

}

?>
