=== WP-ImageFlow2 ===
Author: Bev Stofko
Contributors: Bev Stofko
Donate link: http://stofko.ca/wp-imageflow2-wordpress-plugin/
Tested up to: 3.0
Version: 1.6.3
Requires at least: 2.8.4
Tags: picture, pictures, gallery, galleries, imageflow, coverflow, flow, image, images, flow, lightbox, carousel, autorotate, automatic, rotate, media, tages

ImageFlow style picture gallery with Lightbox popups. Uses either the built-in Wordpress gallery or an uploaded directory 
of images. This is a very light script that uses basic JQuery. Displays simple thumbnail list if Javascript is disabled.


== Description ==

Display nice looking ImageFlow galleries within posts and pages.  Link each image to either a Lightbox preview or an external URL. The Lightbox pop-up supports
cycling through all the photos - left/right arrows appear when hovering over the photos. Supports multiple instances of the galleries on a single page.

This is a very light script that uses the basic JQuery library;

There are two ways to insert a WP-ImageFlow2 gallery:

1. Use the built-in Wordpress media library use the shortcode [wp-imageflow2]
2. Upload your pictures to a subfolder and use the shortcode [wp-imageflow2 dir=SUBFOLDER]

You can configure the background color, text color, container width and choose black or white for the scrollbar. Auto-rotation of the images is now supported.

When using the built in Wordpress library, the photo title will be displayed below each image. When using a subfolder gallery, the image name will 
be displayed below each image.

For a built-in gallery, the image may link to either the large size image or an external url.

= Auto Rotation =

When auto rotation is enabled, the images will automatically rotate through the carousel. You may configure the pause time between rotations. Once the end
of the gallery is reached it flows back to the beginning and starts again. The rotation will pause when the mouse hovers over the bounding div. Once an image
is clicked and expanded into the Lightbox display the auto rotation is suspended.

[Demo](http://www.stofko.ca/wp-imageflow2-wordpress-plugin/)

== Installation ==

1. Unzip to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the gallery in Settings -> WP-ImageFlow2.

= Using the built-in Wordpress library: =

1. Upload images using the Wordpress image uploader on your post or page or into the media library. Enter a title to display, and optionally enter a description that may be used as an external link.
2. Use the shortcode [wp-imageflow2] anywhere in the post or page
3. If you want the image to link to an external url, enter the url in the description field of the image (as http://www.website.com) and enable the 
checkbox in the options. If the description field does not contain an url, the image will link to the full size popup image with the description (if any) displayed as text
below the image.

These standard gallery options may be used:

* id
* order (default is ASC)
* orderby (default is menu_order ID)
* include
* exclude
* size (applies to RSS feed only)

These additional WP-Imageflow2 specific options may be used:

* mediatag  - Corresponds to Media Tags plugin by Paul Menard. This option will pull matching media out of your media library and include it in the gallery.
* startimg  - Gives the starting slide number to center in the gallery, the default is 1.
* rotate    - Turns on/off auto-rotation for this instance (overrides the setting from the admin panel). Values are 'on' or 'off'.

= For galleries based on a subfolder: =

1. Create a folder for your galleries within your WordPress installation, wherever you want. The location has to be accessible from the internet - for example you could use wp-content/galleries.
2. Upload your image galleries to a subfolder of this folder, for example you might upload your images under "wp-content/galleries/subfolder".
3. Set the "Path to galleries from homepage root path" in the settings admin page for WP-ImageFlow2. Enter the path with trailing slash like: "wp-content/galleries/". NEW - alternatively you may also enter the full path like "http://www.mywebsite.com/wp-content/galleries/". Note that the gallery must reside on the same server as the blog. If you have entered the gallery path correctly you will see a list of the sub-directories on the settings page.
4. Insert a gallery on a page by specifying the shortcode [wp-imageflow2 dir=subfolder] on your post or page.

This gallery style will display the image names as the captions, and will link to the full size image.

These additional WP-Imageflow2 specific options may be used:

* startimg  - Gives the starting slide number to center in the gallery, the default is 1.
* rotate    - Turns on/off auto-rotation for this instance (overrides the setting from the admin panel). Values are 'on' or 'off'.

= Notes =

If your reflected images don't show up, you might have a strict server that generates 404 errors on the reflected images. In this case select the option in the settings for strict servers.

IF YOU ARE UPGRADING FROM 1.3.1 OR PRIOR AND YOU USED CUSTOM STYLING ON YOUR WP-IMAGEFLOW2 DIVS, YOU MUST UPDATE YOUR CUSTOM STYLES:

* The main WP-ImageFlow2 divs are now CLASSes instead of IDs in order to support multiple instances, so any custom styling must be changed from #wpif2... to .wpif2...

IF YOU ARE UPGRADING FROM 1.2.6 OR PRIOR, YOU MUST EDIT YOUR GALLERY SHORTCODES:

* Use [wp-imageflow2] instead of [gallery]
* Use [wp-imageflow2 dir=SUBFOLDER] instead of [wp-imageflow2=SUBFOLDER]

== Screenshots ==

1. WP-ImageFlow 2
2. Choose the options you need. 

== Changelog ==

Version 1.6.3 (September 9, 2010)

* Fix display of caption for galleries based on a directory

Version 1.6.2 (May 17, 2010)

* Fix bug when gallery has only one image

Version 1.6.1 (May 14, 2010)

* Support directory paths specified as URLs to provide support to more server configurations

Version 1.6.0 (May 13, 2010)

* NEW FEATURE - Provide an option to start at a specific slide number
* NEW FEATURE - Provide an option to turn on/off rotate for each instance of a gallery
* NEW FEATURE - Support full text description in the popup window of a built-in gallery
* Handle files with special characters in the name

Version 1.5.4 (May 7, 2010)

* Fix potential conflict 

Version 1.5.3 (May 7, 2010)

* Fix dragging the scrollbar on galleries beyond the first on a page
* Update overlay div creation

Version 1.5.2 (May 4, 2010)

* Fix potential rotation problem with IE

Version 1.5.1 (May 4, 2010)

* Fix black slider on built-in galleries

Version 1.5.0 (May 3, 2010)

* Support gallery based on Media Tags (plugin by Paul Menard)
* Support auto-rotation (default is disabled, enable using the settings page)

Version 1.4.9 (April 17, 2010)

* Fix the slider styling added in 1.4.8 - it caused other problems. 

Version 1.4.8 (April 16, 2010)

* Use stronger styling on slider to override some theme styles
* New option - to be used on servers with more secure settings to prevent reflected images generating 404 errors
* Fix Lightbox when last image in gallery has an external link

Version 1.4.7 (April 14, 2010)

* Drop Scriptaculous library since it clashes with MooTools, now only uses the basic jquery library
* Support transparency as a background colour. In this case the image reflections will be black over a transparent div.

Version 1.4.6 (April 13, 2010)

* Define PHP_EOL if not found
* Fix black scrollbar

Version 1.4.5 (April 13, 2010)

* Fix dragging scroll bar (don't know how I missed that one!)
* Hide dashed outline of prev/next links in Lightbox on Firefox

Version 1.4.4 (April 11, 2010)

* Admin menu - fix possible missing text

Version 1.4.3 (April 11, 2010)

* Fix class on outer div (this matters to those who use custom styling)

Version 1.4.2 (April 9, 2010)

* Improve image path construction for galleries based on a subdirectory, to hopefully work on all servers

Version 1.4.1 (April 8, 2010)

* Fix captions when cycling through the Lightbox view 

Version 1.4 (April 8, 2010)

* Support multiple instances of wp-imageflow2 galleries on a single page. You must update your custom styles when updating from a previous version (see Installation notes).
* Lightbox pop-up now supports cycling through the images directly with left/right arrows appearing when hovering over the photos.
* Fix color-code check in settings page (broken on version 1.2)
* Style changes in the method used to display the flow gallery - should be compatible with more themes

Version 1.3.1 (March 26, 2010)

* Fix potential loading issue in IE

Version 1.3.0 (March 25, 2010)

* New shortcode method: [wp-imageflow2] for the built-in gallery and [wp-imageflow2 dir=subdir] for a subdirectory. YOU MUST UPDATE YOUR SHORTCODES WHEN UPGRADING FROM A PREVIOUS VERSION.
* Dropped support for prior shortcode method
* Organize code into a class to prevent potential collisions with other plugins
* General code clean-up

Version 1.2.6 (March 10, 2010)

* Fix issues on legacy version of Internet explorer

Version 1.2.5 (March 7, 2010)

* Fix overlay size and position on scrolled screens

Version 1.2.4 (March 5, 2010)

* Fix problem with include/exclude built-in gallery options

Version 1.2.3 (March 4, 2010)

* Use a different method to extract image info so it works on servers with url access disabled

Version 1.2.2 (March 2, 2010)

* Remove the need for PHP 5
* Add option to turn off reflections (if your server doesn't support GD or you just don't want them)

Version 1.2.1 (February 18, 2010)

* Add a "close" link to the overlay div of the image Lightbox in case the full size image never loads

Version 1.2 (February 16, 2010)

* Use a Lightbox effect for the large size image display rather than opening a new window
* Don't load scripts on admin pages
* Trim spaces from the galleries url entered on the settings page
* Display simple thumbnail gallery on browsers with Javascript disabled

Version 1.1 (February 8, 2010)

* Fix problem with image paths on some servers

Version 1.0.1 (February 3, 2010)

* Fix typo in readme.txt

Version 1.0 (January 29, 2010)

* Initial version
