<?php
/**
* glFusion CMS
*
* Testimonials - Testimonials Plugin for glFusion
*
* SQL Table Schema
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

$_SQL['testimonials'] = "CREATE TABLE {$_TABLES['testimonials']} (
    testid int(15) NOT NULL auto_increment,
    text_full text,
    clientname text,
    company text,
    homepage text,
    tst_date date DEFAULT NULL,
    views int(25) NOT NULL default '0',
    queued tinyint(3) NOT NULL default '0',
    owner_id mediumint(8) unsigned NOT NULL default '1',
    PRIMARY KEY  (testid)
) ENGINE=MyISAM
";

?>