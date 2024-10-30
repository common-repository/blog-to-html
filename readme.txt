=== Blog To HTML ===
Contributors: Rick Hellewell
Donate link: http://cellarweb.com/wordpress-plugins/
Tags: export, html, ebook
Requires at least: 4.6
PHP Version: 5.3
Tested up to: 5.3
Version: 1.91
Stable Tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Export all posts in your blog to a HTML file for ebook creation. 

== Description ==

Blog to HTML allows you to export your blog into an HTML document that can be easily converted into an ebook. All blog posts and pictures are exported in oldest-to-newest date order. Only post content is exported; pages, widget areas, headers, footers, etc., are not exported. You can optionally select multiple categories to export.

When the file is generated, you can view it with your browser, or save it to your local computer for later use. Or you can specify an email address and the generated file will be sent via email as a compressed attachment. Each file has a unique name, so you can have multiple versions of the output. HTML elements get a unique CSS class to allow you to further style the output. Any HTML elements embedded in your post output is retained. All posts are exported; there are no watermarks or limited features.

This plugin's main purpose is to easily output your blog (with pictures) in a format to easily convert to an ebook. For instance, Amazon's Kindle Direct Publishing will accept an HTML file, to which you add additional meta (cover image, etc). Each post has an H1 for the title, and H2 for the publish date, so those tags can be used for a table of contents. You may need to add any additional CSS rules for your site's unique formatting.

You can import the HTML file into Calibre or any HTML editor to further format and create your ebook. Or submit the HTML file directly to your ebook publisher.

The generated HTML file includes some CSS for various HTML tags, and are defined at the top of the HTML file.

* body</strong> {color:#000 !important;background-color:#fff !important; } /* to make sure the text is visible */
* .blog2html_h1 {}	/* H1 class */
* .blog2html_h2 {}	/* H2 class */
* .blog2html_div {}	/* DIV class */
* .blog2html_p {}	/* P class */
* .blog2html_img {}	/* IMG class */
* .blog2html_caption {}	 /* CAPTION class */</li>
* .blog2html_image_div {}	 /* DIV around multiple IMG  */ </li>
* .blog2html_a {} 	 /* A class */
* This next block is to help images position next to each other
figure.blog2html_figure {
    /* To correctly align image, regardless of content height: */
    vertical-align: top;
    display: inline-block;
    /* To horizontally center images and caption */
    text-align: center;
    /* The width of the container also implies margin around the images. */
    width: 120px;
}

You can set those CSS values according to your needs. Images are set to a max-width of 400px, although you can override that with custom CSS.


== Installation ==

1. Install via the Add Plugin page. (On multisites, use the Network Admin Plugin page.) Or download the zip file, uncompress, then upload to `/wp-content/plugins/` directory. Or download, then upload/install via the Add Plugin page.

1. Activate the plugin through the 'Plugins' menu in WordPress.

1. Usage information in Settings, 'Blog To HTML Info/Usage'.

== Frequently Asked Questions ==

= Do you have other plugins? = 

Yes! 

* **Multisite Media Display** : shows all media from all subsites. SuperAdmins can click on a picture to edit it. Great for ensuring all media conforms to your site's standards.

* **FormSpammerTrap for Comments** : enhances comment forms so that bots can't spam your comments. Uses a more clever technique than just hidden fields or captchas or other things that don't always work. Also lets you change the text/headings of the comment form. (We also have a free standalone version; take a look at www.FormSpammerTrap.com (that's the page that comment bots will see, but also contains all the info about the 'trap').

* **FormSpammerTrap for Contact Form 7** : adds our FormSpammerTrap technique to Contact Form 7 forms with a simple shortcode.

* **URL Smasher** : automatically shortens URLs on all URLs in pages/posts.

* **AmazoLinkenator** : adds your Amazon Affiliate ID to any Amazon product link in pages/posts/comments. It's your site, so use your Amazon Affiliate ID. 

All plugins are free and full-functioned. No premium features. Just search for them on the Add Plugins page.

== Screenshots ==

None; no options, just an information an usage screen.

== Changelog ==

= Version 1.91(23 Feb 2018) = 
* Tweaked some of the code for img tags to get the caption into the alt/text/caption parameters. This helps a bit with pictures with captions when importing into Word, although Word (2010 and later) doesn't create the caption from the img attributes. A Word macro fixes that (somewhat); that macro is a bit beyond the scope of this plugin.
* Changed the color and width of the HR tag on the Settings/Info screen.
* Moved some unused code.

= Version 1.90 (9 Feb 2018) =

* This version's changes are intended to help tweak images by wrapping them in the new HTML5 'figure' tags. This should allow images to appear side-by-side, rather than vertically. Additional CSS is included for the 'figure' tag, and subtags (like 'figcaption') that are available in HTML5.
* The image caption (if available) is wrapped in the HTML5 'caption' tag.
* The HTML DocType is now set to HTML (for HTML5 type).
* There still seems to be an issue with figure captions not converting well when importing the HTML document into Word (at least on my old Word 2010 version). (Not a big fan of on-line/subscription-based word processors, which is why I still use Word 2010. But have never had good luck using other open-source replacements, so....)
* We're working on a new version 2.0 that might be better at keeping captions with thier pictures, as well as creating a DOCX file directly from the blog output.

= Version 1.82 (26 Jan 2018) = 
* Some sites have some entries that cause some DOM errors, so we now suppress any DOM errors. We figure that you can fix any formatting errors when you edit the HTML document. This prevents the errors from showing on the screen, even though the HTML is created.

= Version 1.81 (26 Jan 2018) = 
* Whoops!  Left an error trapping code segment that caused errors. Gone now. = 

= Version 1.80 (24 Jan 2018) =
* remove any <a href> codes from the document. This is intended to remove links that normally surround <img> tags, as the img links are not needed. The img will still be displayed/output in the generated HTML document. This will have a minor effect of removing any HREF link, but those links will be converted back to links when you import the HTML into the word processing program for further tweaking into a book format.

= Version 1.71 (22 Jan 2018) = 
* removed invalid caption tag (that tag is only used in tables). Picture captions are still output as part of the <img> tag.
* fixed version number displayed date on Settings/Info screen
* removed <br> between pictures (allows pictures to float:left if you add that to the CSS)
* added <hr> to CSS section so you can display:none to remove horizontal line between posts

= Version 1.70 (22 Jan 2018) = 
* changed the way that media is output in the HTML file. Now handles the [gallery] shortcode that doesn't have any media ID parameters. This may cause duplicate pictures if you use gallery and non-gallery images in a post. 
* changed the image sizes from galleries to be the 'medium' size (max width/height of 300px, according to the Settings, Media values).
* removed some unneeded code 

= Version 1.60 (17 Jan 2018) = 
* added code to ensure that all of the HTML tags have the proper CSS class as defined in the style section added to the top of the document. This will allow you to properly style all elements when you add your additional CSS to the default styles. (The default styles are shown in the plugin Info/Usage screen.)

= Version 1.50 (6 Dec 2017) = 
* fixed bug where galleries were not being shown in the site if the plugin was enabled. The plugin redefines the [gallery] shortcode for it's own purposes; prior versions had that shortcode redefinition applied to the entire site. Now the redefinitionly only happens if you press the button to create the html file.


= Version 1.40 (16 Nov 2017) = 
* For sites with many entries, the generated HTML file will be very large. Example: a test site with 1300+ entres resulted in an HTML file of about 25MB. That size of file may not be emailed by your mail server because of file attachment size limitations, which we can't control. So this version will compress the output into a "gz" file that will be attached. You can use your favorite unzipping program to get the HTML file; note that Windows may be able to uncompress the file without the use of additional programs.
* Note that a large HTML file might take a while to be displayed by your browser if you use the "Open" choice when the file is generated. Patience, Grasshoper. You can always regenerate the file and then use the "Save As" option. Or you can email the file, since this version will compress if email is selected.


= Version 1.30 (15 Nov 2017) = 
* fixed problem with deactivation (and maybe updates) that also caused the "Are you sure you want to do this" message on any Post/Page publish). An error message about an array_merge() error in load.php would also show up. (This one was almost as hard as the problem fixed by version 1.20, in case anyone wants to feel sorry for a developer...)

= Version 1.21 (13 Nov 2017) = 
* For very large sites, browser or server timeouts or memory issues may occur. You can determine a browser timeout if the process takes longer than 30 seconds (the screen will remain blank, usually). So increased the timeout from default of 30 seconds to 5 minutes (the process will probably never take that long), and increased the available memory to the process to 256M. 
* The above changes should allow very large sites to complete the export. The program was tested on a site with over 1300 posts. 
* Changed the settings/info screen to suggest using email on very large sites.
* Changed other email info on the settings/info screen.

= Version 1.20 (11 Nov 2017) = 
* fixed bug that would sometimes cause the generated output to appear on the settings page, rather than showing the Open/Save Ad dialog box. (That was a weird one, since the same code worked properly on one site, but not on a nearly-idential site. But the good guys won this one!)
* Added ability to specify email from/to addresses so the generated file is emailed. Note that an invalid or non-existent email address might irritate your server, causing the mail not to be sent.
* Changed function name prefixes for consistency
* Minor code optimizations
* Tested with WP 4.8.3; should work with 4.9 when it is released.
* Attachment via email Content settings will work with later PHP versions.
* Ensure sanitization of POST variables (form field content)

= Version 1.10 (27 Oct 2017) = 
* added capability to select multiple categories of posts.
* some minor code optimizations/cleanup

= Version 1.01 (30 Sep 2017) = 
* version number correction in file and readme

= Version 1.00 (21 Sep 2017) =

* Initial Release
