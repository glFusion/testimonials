## Testimonials Plugin for glFusion

For the latest, and more detailed, documentation, please see the [Testimonials Plugin Wiki Page](https://www.glfusion.org/wiki/glfusion:plugins:testimonials:start)

### OVERVIEW

This is a plugin for glFusion that will allow you to showcase your testimonials.
Site administrators can manually enter testimonials received via email or other
methods. Users can also submit testimonials that optionally can be queued for
review prior to publishing.

A Testimonial Block is included that will pull random testimonials to display
on your site.

### SYSTEM REQUIREMENTS

The Testimonials Plugin has the following system requirements:

* PHP 5.3.3 and higher.
* glFusion v1.6.0 or newer

### INSTALLATION

The Testimonials Plugin uses the glFusion automated plugin installer. Simply upload the distribution using the glFusion plugin installer located in the Plugin Administration page.

### UPGRADING

The upgrade process is identical to the installation process, simply upload the distribution from the Plugin Administration page.

### CONFIGURATION

**Display Blocks**

Which glFusion blocks to display when viewing Testimonials. For example, left, right, none, both...

**Disable Testimonial Submissions**

If this is set to TRUE, all user submissions will be disabled. Only the Testimonial Admin can enter testimonials through the admin interface.

**Allow Anonymous Users to Submit Testimonials**

If this is set to Yes, anonymous (non-logged-in users) will be able to submit testimonials. See Moderation Queue Below.

**User Moderation Queue for User Submitted Testimonials**

If set to TRUE (Recommended Settings), user submitted testimonials will be placed in a moderation queue for review and approval. Testimonials will not be available for public view until they have been approved by a Testimonials Administrator.

**Time User must wait between testimonial submissions**

The amount of time (in seconds) that a user must wait before submitting a new testimonial. Generally this value should be very high as most users do not submit more than one testimonial.

**Number of Testimonials Per Page**

The number of testimonials to display per page.

### LICENSE

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 2 of the License, or (at your option) any later
version.

This plugin was originally developed by Jodi Diehl - scripts AT sunfrogservices DOT com
