<?php
/**
* glFusion CMS
*
* Testimonials - Testimonials Plugin for glFusion
*
* English Language - UTF-8
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

$LANG_TSTM01 = array (
    'plugin'            => 'Testimonials',
    'plugin_name'       => 'Testimonials',
    'plugin_admin'		=> 'Testimonials Admin',
    'access_denied'     => 'Access Denied',
    'access_denied_msg' => 'You are not authorized to view this Page.  Your user name and IP have been recorded.',
    'admin'		        => 'Testimonials Admin',
    'install_header'	=> 'Install/Uninstall Plugin',
    'installed'         => 'The Plugin is Installed',
    'uninstalled'       => 'The Plugin is Not Installed',
    'install_success'	=> 'Installation Successful',
    'install_failed'	=> 'Installation Failed -- See your error log to find out why.',
    'uninstall_msg'		=> 'Plugin Successfully Uninstalled',
    'install'           => 'Install',
    'uninstall'         => 'UnInstall',
    'warning'           => 'Warning! Plugin is still Enabled',
    'enabled'           => 'Disable plugin before uninstalling.',
    'readme'            => 'STOP! Before you press install please read the ',
    'installdoc'        => 'Install Document.',
    'edit'				=> 'Edit',
    'client'			=> 'Client',
    'client_help'		=> 'Person giving testimonial',
    'company'			=> 'Company',
    'company_help'		=> '',
    'tstdate'			=> 'Date',
    'create_new'		=> 'Add New',
    'views'				=> 'Views',
    'testament'			=> 'Testimonial',
    'text_full'			=> 'Full Testimonial',
    'text_full_help'	=> 'Complete text for testimonials page',
    'homepage'			=> 'Client Website',
    'website_help'      => '(include http:// or https://)',
    'delete'			=> 'Delete',
    'save'				=> 'Save',
    'cancel'			=> 'Cancel',
    'header'            => "{$_CONF['site_name']} Testimonials",
    'help_file'			=> 'Documentation',
    'customers_saying'  => 'What Our Customers Are Saying',
    'submit_testimonial' => 'Submit A Testimonial',
    'more'              => 'More',
    'less'              => 'Less',
    'no_testimonials'   => 'No Testimonials have been submitted yet...',
    'submit_title'      => 'Submit Testimonial',
    'your_name'         => 'Your Name',
    'company_name'      => 'Company Name',
    'company_website'   => 'Company Website',
    'submit_help'       => 'Provide your testimonial below. You must include your name and the testimonal. The other fields, Company and Website are optional but always appreciated if you include them. If you provide a Company Website, there will be a link from the testimonial to your site.',
    'submission_approved' => 'Testimonial Submission has been approved',
    'testimonial'       => 'Testimonial',
    'submissions'       => 'Testimonial Submissions',
    'summary'           => 'Summary',
    'full_review'       => 'Full Review',
    'saved_success'     => 'Testimonial successfully saved.',
    'admin_help'        => 'Admin screen help text for testimonial plugin',
    'word_count'        => 'Word Count',
    'testimonial_submitted' => 'Testimonial Successfully Submitted. Once your testimonial is reviewed and approved by our moderators, it will be availabe on the Testimonials Page. Thank you!',
);

$LANG_configsections['testimonials'] = array(
    'label' => 'Testimonials',
    'title' => 'Testimonials Plugin Configuration',
);

$LANG_confignames['testimonials'] = array(
    'displayblocks'         => 'Display Blocks',
    'anonymous_submit'      => 'Allow Anonymous Users to Submit Testimonials',
    'queue_submissions'     => 'Use Moderation Queue for User Submitted Testimonials',
    'speedlimit'            => 'Time user must wait between testimonial submissions (seconds)',
    'per_page'              => 'Number of Testimonails per page',
);

$LANG_configsubgroups['Testimonials'] = array(
    'sg_main' => 'Main Settings',
);

$LANG_fs['testimonials'] = array(
    'fs_main' => 'Main Settings',
);

$LANG_configselects['testimonials'] = array(
    0  => array('True' => 1, 'False' => 0 ),
    1  => array('Left Blocks' => 0, 'Right Blocks' => 1, 'All Blocks' => 2, 'No Blocks' => 3),
    2  => array('Yes' => 1, 'No' => 0 ),
);

?>