=== WP Booklet ===
Contributors: binarystash01
Donate link: http://binarystash.blogspot.com/
Tags: flip book, flipbook, booklet
Requires at least: 3.5
Tested up to: 3.8
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows creation of flip books using the jQuery Booklet plugin

== Description ==

Flip books are useful for creating photo albums, brochures, and other promotional materials. WP Booklet is a Wordpress plugin that allows you to create them using jQuery Booklet.

This Wordpress plugin provides a friendly user interface for configuring your flip books and displaying them anywhere on your Wordpress site using a shortcode. Unlike other plugins, WP Booklet works on desktop and mobile devices.

Highlights

*   PDF uploads
*   Bulk image uploads
*   Page thumbnails
*   Compatible with mobile devices

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Create your flip books under 'Booklets'.
4. Use the provided shortcode.

== Frequently Asked Questions ==

= How do I change the style of my flip book? =

You can override the style in your theme's style.css

= Do you provide flip book templates? = 

Not yet.

= How do I upload PDFs? =

Use the "Upload PDF" button beside the "Add pages" button. After upload, your PDF will be processed automatically.

= Why can't I see the "Upload PDF" button? =

WP Booklet relies on Ghostscript and Imagemagick for PDF conversion. If you can't see the button, then at least one of them is missing or misconfigured. If they are configured properly, you should see their versions under Booklet->Settings.

You also need to ensure that your uploads folder is writable by the web server. Check this status under Booklet->Settings.

If problems persist despite having proper configurations for the items above, please contact your web administrator or hosting provider.

= Why can't I convert more than 10 pages of my PDF? =

By default, WP Booklet only converts the first 10 pages of PDF files because PDF conversion can be extremely resource-intensive. You can disable this limit under WP Booklet->Settings.

= How do I upload images in bulk? =

On the media gallery popup, hold "CTRL" on your keyboard while selecting images.

== Screenshots ==

1. Administration screen
2. Sample flip book

== Changelog ==

= 1.0 =
* Stable version

= 1.0.4 =
* Fixed a bug on themes with multiple jQuery versions

= 1.0.5 = 
* UI enhancements
* Added ability to add page links

= 1.0.6 = 
* UI enhancements
* Added ability to show thumbnailed previews
* Added ability to upload PDFs
* Added ability to close booklets
* Added ability to upload images in bulk

= 1.0.7 = 
* UI enhancements
* Fixed padding reverting to 10px when set to 0
* Added test for uploads folder's writability

= 1.0.8 = 
* Fixed empty pages when PDF has less than 10 pages and PDF limit is on

= 1.0.9 = 
* PDF testing now involves creating a PDF file and checking for its existence.

= 1.1.0 = 
* Fixed shortcode

== Upgrade Notice ==

= 1.0 =
Stable version

= 1.0.4 =
* Fixed a bug on themes with multiple jQuery versions

= 1.0.5 = 
* The ability to add page links was added.

= 1.0.6 = 
* UI enhancements
* Added ability to show thumbnailed previews
* Added ability to upload PDFs
* Added ability to close booklets
* Added ability to upload images in bulk

= 1.0.7 = 
* UI enhancements
* Fixed padding reverting to 10px when set to 0
* Added test for uploads folder's writability

= 1.0.8 = 
* Fixed empty pages when PDF has less than 10 pages and PDF limit is on

= 1.0.9 = 
* PDF testing now involves creating a PDF file and checking for its existence.

= 1.1.0 = 
* Fixed shortcode

== Image credits ==

* image 1 - http://www.publicdomainpictures.net/view-image.php?image=63350&picture=agapanthus-buds
* image 2 - http://www.publicdomainpictures.net/view-image.php?image=63355&picture=alyssum
* image 3 - http://www.publicdomainpictures.net/view-image.php?image=63356&picture=stylized-background-fabric-31
* image 4 - http://www.publicdomainpictures.net/view-image.php?image=63352&picture=surface-of-rock
