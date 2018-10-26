# Testimonials Plugin ChangeLog

## v1.0.7 (Unreleased)

### Fixed
  - Fixed issue where getItemInfo returned queued items in master list
  - View all Testimonials prompt was always displaying - should only display when viewing a single testimonial

## v1.0.6 (September 17, 2018)

### Fixed
  - Fixed typo in search that caused crash

## v1.0.5 (September 17, 2018)

### Added
  - New configuration option to control if the testimonial index will truncate long testimonials

### Changed
  - Form standardization - consistent use of buttons, colors, etc.

### Fixed
  - Fixed issue where search results were not filtered by owner id when searching by author
  - Fixed SpamX integration

## v1.0.4 (August 8, 2018)

### Added
  - Added Sitemap driver to allow testimonials to appear in online and XML sitemaps

## v1.0.3 (July 13, 2018)

### Fixed
  - Fixed typo preventing installation
  - Fixed error where Testimonials Admin link would display to anyone with Command and Control access

## v1.0.2 (June 5, 2018)

### Added
  - Implement new method to manage config items

### Changed
  - Changed how testimonials are censored - now censor on display instead of save
  - Improved integration with glFusion v1.7.2+ Spam checking

### Fixed
  - Fixed issue with returning the wrong status (queued / published) in getItemInfo
  - Fixed typo in language file that prevented one configuration heading to not display correctly

## v1.0.1 (October 22, 2017)

### Added
  - Added option to have both centerblock and random testimonial block pull up to 20 random testimonials and rotate through them

### Fixed
  - Fixed uninstall did not remove testimonials block
  - Enabling / Disabling Testimonials plugin now toggles the testimonials block accordingly

## v1.0.0 (October 6, 2017)

### Added
  - CAPTCHA Support
  - Configuration options
  - Added pagination to testimonals list view
  - Added speedlimit checking
  - Added moderation queue support
  - Ported / Updated to glFusion CMS standards.
