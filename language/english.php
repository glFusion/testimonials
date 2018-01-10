<?php
/**
* glFusion CMS
*
* Testimonials - Testimonials Plugin for glFusion
*
* English Language
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
    'admin_help'        => 'Testimonial administration. Allows you to create, edit and delete testimonials. You can also move testimonials to and from the submission queue.',
    'cancel'			=> 'Cancel',
    'client'			=> 'Client',
    'client_help'		=> 'Person giving testimonial',
    'company'			=> 'Company',
    'company_help'		=> '',
    'company_name'      => 'Company Name',
    'company_website'   => 'Company Website',
    'create_new'		=> 'Add New',
    'customers_saying'  => 'What Our Customers Are Saying',
    'delete'			=> 'Delete',
    'delete_checked'    => 'Delete Selected Entries',
    'delete_confirm'    => 'Are you sure you want to delete the selected testimonials?',
    'edit'				=> 'Edit',
    'email'             => 'Email',
    'email_help'        => 'Email will never be displayed. Required in case we need to contact you about your submission.',
    'fs_submitter'      => 'Submitter Information',
    'fs_testimonial'    => 'Testimonial Information',
    'full_review'       => 'Full Review',
    'header'            => "{$_CONF['site_name']} Testimonials",
    'help_file'			=> 'Documentation',
    'homepage'			=> 'Client Website',
    'in_queue'          => 'In Submission Queue',
    'less'              => 'Less',
    'mail_body'         => 'A new Testimonial has been submitted for review.',
    'mail_mod_link'     => 'Please view the <a href="%s">Moderation Queue</a> to approve or delete the submission.',
    'mail_subject'      => 'A New Testimonial has been Submitted',
    'more'              => 'More',
    'no_testimonials'   => 'No Testimonials have been submitted yet...',
    'owner_id'          => 'Owner / Submitter',
    'published'         => 'Published',
    'read_full_testimonial'  => 'Read Full Testimonial',
    'save'				=> 'Save',
    'saved_success'     => 'Testimonial successfully saved.',
    'spam_identified'   => 'Your testimonial failed our spam checks. This could be caused by too many URLs in the testimonial, or if you email or IP address appear on our Spam Block Blacklist. If the problem persists, please contact the site administrator for assistance.',
    'speedlimit_msg'    => 'You must wait %d minutes between testimonial submissions.',
    'submission_mod_approved' => 'Testimonial Submission has been approved',
    'submission_approved' => 'Thank you for your testimonial submitted at %s. Your submission has been approved. We appreciate your feedback!',
    'submission_approved_subject' => 'Testimonial Submission Approved',
    'submissions'       => 'Testimonial Submissions',
    'submit_help'       => 'Provide your testimonial below. You must include your name and the testimonal. The other fields, Company and Website are optional but always appreciated if you include them. If you provide a Company Website, there will be a link from the testimonial to your site.',
    'submit_testimonial'=> 'Submit A Testimonial',
    'submit_title'      => 'Submit Testimonial',
    'summary'           => 'Summary',
    'testament'			=> 'Testimonial',
    'testimonial'       => 'Testimonial',
    'testimonial_submitted' => 'Testimonial Successfully Submitted. Once your testimonial is reviewed and approved by our moderators, it will be availabe on the Testimonials Page. Thank you!',
    'text_full'			=> 'Full Testimonial',
    'text_full_help'	=> 'Please limit length to around 150 words.',
    'tstdate'			=> 'Date',
    'view_all'          => 'View All Testimonials',
    'views'				=> 'Views',
    'website_help'      => '(include http:// or https://)',
    'word_count'        => 'Word Count',
    'your_name'         => 'Your Name',
);

$LANG_TST_ERRORS = array(
    'invalid_email'     => 'Please enter a valid email address',
    'invalid_name'      => 'You must enter your name in the name field',
    'invalid_testimonial' => 'You must enter a testimonial in the testimonial field',
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
    'disable_submissions'   => 'Disable Testimonial Submissions',
    'centerblock_where'     => 'Enable Testimonials Centerblock',
    'centerblock_rotate'    => 'Centerblock rotates through testimonials',

);

$LANG_configsubgroups['testimonials'] = array(
    'sg_main' => 'Main Settings',
);

$LANG_fs['testimonials'] = array(
    'fs_main' => 'Main Settings',
);

$LANG_configselects['testimonials'] = array(
    0  => array('True' => 1, 'False' => 0 ),
    1  => array('Left Blocks' => 0, 'Right Blocks' => 1, 'All Blocks' => 2, 'No Blocks' => 3),
    2  => array('Yes' => 1, 'No' => 0 ),
    3  => array('No Centerblock' => -1, 'Top of Page' => 1, 'After Featured Story' => 2, 'Bottom of Page' => 3),
);

?>