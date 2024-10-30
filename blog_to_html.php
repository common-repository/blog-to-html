<?php

/*
Plugin Name: Blog to HTML
Plugin URI: http://cellarweb.com/wordpress-plugins/
Description: Export all post content, including pictures, to an HTML file, which can be used as a basis for an ebook, or a static site.
Version: 1.91
Tested up to: 4.9
Requires at least: 4.6
PHP Version: 5.3
Author: Rick Hellewell - CellarWeb.com
Author URI: http://CellarWeb.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

*/

/*
Copyright (c) 2016-2017 by Rick Hellewell and CellarWeb.com
All Rights Reserved


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA02110-1301USA

*/
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// increase time allowed for process to run; more time required for large site
set_time_limit (300);	// 5 minutes
// increase allowable memory, for large sites, especially with email
ini_set('memory_limit','256M');

// ----------------------------------------------------------------
// Let's get started!
global $blogtohtml_version ;
global $atts ; // used for the shortcode parameters
$blog2html_submit_html = "Create / Send the HTML File";

$blogtohtml_version = "1.91  (23 Feb 2018)" ;
// check for required versions of WP and PHP
if ( !blogtohtml_is_requirements_met())
{
	add_action('admin_init', 'blogtohtml_disable_plugin') ;
	add_action('admin_notices', 'blogtohtml_show_notice') ;
	add_action('network_admin_init', 'blogtohtml_disable_plugin') ;
	add_action('network_admin_notices', 'blogtohtml_show_notice') ;
	blogtohtml_deregister() ;
	return ;
}
// Check whether the button has been pressed 
if ( isset ($_POST['blogtohtml_button']))
{
	// Sanitize all POST variables
	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING) ;
	$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING) ;
	// check which button was pressed
	$create_type = "HTML";	// default is HTML button
	// the button has been pressed AND we've passed the security check
	add_action('admin_init', "blogtohtml_site_show_posts") ;
	// re-define the gallery shortcode only on a button press so it 
	// 		doesn't affect the site
	remove_shortcode("gallery") ;
	add_shortcode("gallery", "blogtohtml_output_empty") ;

}
// ----------------------------------------------------------------
function blogtohtml_output_empty()
{
	return ;
}


// remove the 'thank you' and WP version number from the admin footer,
// because it sometimes overwriting the settings page after the HTML is created
function blogtohtml_remove_admin_footer()
{
	return '' ;
}

add_filter('admin_footer_text', 'blogtohtml_remove_admin_footer', 999) ;
add_filter('update_footer', 'blogtohtml_remove_admin_footer', 999) ;

// Add settings link on plugin page
function blogtohtml_settings_link($links)
{
	$settings_link = '<a href="options-general.php?page=blogtohtml_settings" title="Blog to HTML">Blog to HTML Info/Usage</a>' ;
	array_unshift($links, $settings_link) ;
	return $links ;
}

$plugin = plugin_basename(__FILE__) ;
add_filter("plugin_action_links_$plugin", 'blogtohtml_settings_link') ;

//	build the class for all of this
class blogtohtml_Settings_Page
{
// start your engines!

	public function __construct()
	{
		add_action( 'admin_menu', array($this, 'blogtohtml_add_plugin_page')) ;
	}
	// add options page

	public function blogtohtml_add_plugin_page()
	{
	// This page will be under "Settings"
		add_options_page( 'Blog to HTML Info/Usage', 'Blog to HTML Info/Usage', 'manage_options', 'blogtohtml_settings', array($this, 'blogtohtml_create_admin_page')) ;
	}
	// options page callback

	public function blogtohtml_create_admin_page()
	{
	// Set class property
		$this->options = get_option('blogtohtml_options') ;
		echo '<div class="wrap">' ;
		blogtohtml_info_top() ;
		blogtohtml_info_bottom() ; 		// display bottom info stuff
		echo '</div>' ;
	}
	// print the Section text

	public function blogtohtml_print_section_info()
	{
		print '<h3><strong>Information about Blog to HTML from CellarWeb.com</strong></h3>' ;
	}

}
// end of the class stuff

if ( is_admin())
{
	$my_settings_page = new blogtohtml_Settings_Page() ;


	// ----------------------------------------------------------------------------
	// supporting functions
	// ----------------------------------------------------------------------------
	//	display the top info part of the page
	// ----------------------------------------------------------------------------
	function blogtohtml_info_top()
	{
		global $blogtohtml_version ;
		
		?>

<div class="wrap" >
	<h2></h2>
	<!-- placeholder for any WP admin status messages -->
	<hr  />
	<div style="background-color:#9FE8FF;padding-left:15px;padding-bottom:10px;margin-bottom:15px;"> <br />
		<h1 align="center" style="font-size:300%"><strong>Blog to HTML</strong></h1>
		<h3 align="center">Export All Posts and Pictures to a HTML File to Easily Create an eBook</h3>
		<p>Version <?php echo $blogtohtml_version ;?></p>
	</div>
	<hr />
	<div style="border:thin solid #000; padding:  5px 15px  5px 15px;margin-left:15px;max-width:800px;">
		<p><strong>Blog to HTML allows you to export your blog into an HTML document that can be easily converted into an ebook.</strong> All blog posts and pictures are exported in oldest-to-newest date order. Only post content is exported; pages, widget areas, headers, footers, etc., are not exported. All posts are exported; there are no watermarks or limited features.</p>
		<p>When the file is generated, you can view it with your browser, or save it to your local computer for later use. Or you can email it. Each file has a unique name, so you can have multiple versions of the output. HTML elements get a unique CSS class to allow you to further style the output. Any HTML elements embedded in your post output is retained.</p>
		<p><strong>This plugin's main purpose is to easily output your blog (with pictures) in a format to easily convert to an ebook.</strong> For instance, Amazon's Kindle Direct Publishing will accept an HTML file, to which you add additional meta (cover image, etc). Each post has an H1 for the title, and H2 for the publish date, so those tags can be used for a table of contents. You may need to add any additional CSS rules for your site's unique formatting.</p>
		<p>You can import the HTML file into Calibre or any HTML editor to further format and create your ebook. Or submit the HTML file directly to your ebook publisher, although we suspect that you'd want to do additional formatting of the HTML file.</p>
	</div>
	<?php blogtohtml_button_admin_page() ;?>
	<div style="border:thin solid #000; padding:  5px 15px  5px 15px;margin-left:15px;max-width:800px;">
		<p>The generated HTML file includes some CSS for various HTML tags, and are defined at the top of the HTML file. You can add your CSS for these CSS classes to help format your HTML file/ebook.</p>
		<ul style="margin-left:20px; list-style:disc">
			<li><strong>body</strong> {color:#000 !important;background-color:#fff !important; } /* to make sure the text is visible */</li>
			<li><strong>.blog2html_h1 {}</strong> /* H1 class */ </li>
			<li><strong>.blog2html_h2 {}</strong> /* H2 class */</li>
			<li><strong>.blog2html_div {}</strong> /* DIV class */</li>
			<li><strong>.blog2html_p {}</strong> /* P class */</li>
			<li><strong>.blog2html_img {}</strong> /* IMG class */</li>
			<li><strong>.blog2html_caption {}</strong> /* CAPTION class */</li>
			<li><strong>.blog2html_image_div {}</strong> /* DIV around IMG  */ </li>
			<li><strong>.blog2html_a {} </strong> /* A class */
			<li><strong>hr {} </strong> /* in case you want to add a display:none */</li>
			<li><strong>figure.blog2html_figure {}</strong>	/* for new figure tag in HTML5 */
		<ul><li><em>This CSS is included for this class, to correctly align image, regardless of content height </em></li>
		<li>/* To horizontally center images and caption */</li>
		<li>vertical-align: top;</li>
		<li>display: inline-block;</li>
		<li>text-align: center;</li>
		<li>width: 300px; /* The width of the container also implies margin around the images. */</li>
		</ul>
		<li><strong>.blog2html_figure_caption</strong> {display: block;} /* for formatting the figure caption */</strong>

		</ul>
		<p>You can set those CSS values according to your needs. Images are set to the 'medium' size (WP default of that size is max width/height of 300px; look in Settings, Media to see your image sizes). </p>
		<hr />
		<p><strong>Suggestions?</strong></p>
		<p>The plugin is not very fancy, but will do what it says: you can use the generated HTML to create an ebook. There are many ways to get to the final ebook product, so this is not a one-step process. But it will get you started.</p>
		<p>That said, what new features would make sense? How could we improve the plugin to make it easier to create an ebook? Or is this 'good enough'? Let us know via the Support section of the plugin's page.</p>
	</div>
	<hr />
	<div style="border:thin solid #000; padding:  5px 15px  5px 15px;margin-left:15px;max-width:800px;">
		<p>Tell us how the Blog to HTML plugin works for you - leave a <a href="https://wordpress.org/support/plugin/blog-to-html/reviews/" title="Blog to HTML" target="_blank" > review or rating or suggestions</a> on our plugin page.&nbsp;&nbsp;&nbsp; <a href="https://wordpress.org/support/plugin/blog-to-html" title="Blog to HTML Support" target="_blank" >Get Help or Ask Questions here</a>. </p>
	</div>
	<hr />
	<div style="background-color:#9FE8FF;padding:3px 8px 3px 8px;">
		<p><strong>Interested in a plugin that will automatically add your Amazon Affiliate code to any Amazon links?&nbsp;&nbsp;&nbsp;Check out our nifty <a href="https://wordpress.org/plugins/amazolinkenator/" target="_blank">AmazoLinkenator</a>!&nbsp;&nbsp;It will probably increase your Amazon Affiliate revenue!</strong></p>
		<p>If you are struggling with comment or contact form spam, check out our <a href="https://wordpress.org/plugins/formspammertrap-for-comments/" target="_blank"><strong>FormSpammerTrap for Comments</strong></a> plugin, or the <a href='https://wordpress.org/plugins/formspammertrap-for-contact-form-7/' target="_blank"><strong>FormSpammerTrap for Contact Form 7</strong></a> plugin. They will get rid of those spambots!</p>
		<p>How about <strong><a href="https://wordpress.org/plugins/multisite-comment-display/" target="_blank">Multisite Comment Display</a></strong> to show all comments from all subsites? Or <strong><a href="https://wordpress.org/plugins/multisite-post-reader/" target="_blank">Multisite Post Reader</a></strong> to show all posts from all subsites? Or our <strong>U<a href="https://wordpress.org/plugins/url-smasher/" target="_blank">RL Smasher</a></strong> which automatically shortens URLs in pages/posts/comments? Just search for them in the Add Plugins screen - <strong>they are all free and fully featured!</strong></p>
	</div>
</div>
		<?php

	}

	// ----------------------------------------------------------------------------
	// display the copyright info part of the adminpage
	// ----------------------------------------------------------------------------
	function blogtohtml_info_bottom()
	{
	// print copyright with current year, never needs updating
		$xstartyear = "2016" ;
		$xname = "Rick Hellewell" ;
		$xcompanylink1 = ' <a href="http://CellarWeb.com" title="CellarWeb" >CellarWeb.com</a>' ;
		echo '<hr><div style="background-color:#9FE8FF;padding-left:15px;padding:10px 0 10px 0;margin:15px 0 15px 0;">
	<p align="center"><strong>Copyright &copy; ' . $xstartyear . '- ' . date("Y") . ' by ' . $xname . ' and ' . $xcompanylink1 ;
		echo ' , All Rights Reserved. Released under GPL2 license. <a href="http://cellarweb.com/contact-us/" target="_blank" title="Contact Us">Contact us page</a>.</strong></p></div><hr>' ;
		return ;
	}

	// ----------------------------------------------------------------------------
	function blogtohtml_button_admin_page()
	{
	// This function creates the output for the admin page.
	// It also checks the value of the $_POST variable to see whether
	// there has been a form submission.
	// The check_admin_referer is a WordPress function that does some security
	// checking and is recommended good practice.
	global $blog2html_submit_html;	// text for html button
	// General check for user permissions.
		if ( !current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient privileges to access this page.')) ;
		}
		// Start building the page
		echo '<div class="wrap">' ;
		//echo '<h2>Click the button to create the file</h2>';
		echo '<hr>' ;
		echo '<div style="border:thin solid #000; padding: 5px 15px  5px 15px;margin-left: 15px;max-width:800px;list-style:none !important;">' ;
		echo '<form action="" method="post">' ;
		// this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
		//wp_nonce_field('blogtohtml_button_clicked','blogtohtml_button_clicked') ;
		echo "<p><strong>Select specific categories to output. If none are selected, all categories will be output.</strong></p>" ;
		echo '<div id="checkboxes" style="padding-left:18px;">' ;
		wp_category_checklist() ;
		echo "</div><hr  style=\"border-top: 3px solid #2ebc57;\">" ;
		
		?>
<p><strong>Fill in email addresses if you want the generated file to be emailed. Leave both fields blank and the generated file will cause an Open/Save As dialog.</strong></p>
<p><strong>Email to: </strong>
	<input type="email" name="email" value=""  size="50" maxlength="50" />
	<br />
	Enter the email address to send output to your email as an attachment.  <br />
<p><strong>Email From: </strong>
	<input type="text" name="email_from" value="" size="50" maxlength="50">
	<br>
	Enter a valid email on your site's domain. Should be the site's domain so that the sent mail won't be caught as spam. The email account should exist, or your server may not send the mail, or the mail may end up in your spam folder. Note that exports of large sites might cause some delay in receiving the email; this is due to your server's configuration and not something we can control.</p>
</p>
<hr  style= "border-top: 3px solid #2ebc57; "/>
		<?php

		echo '<p><strong>Click this button to create the HTML file of all of the posts, or to email the file (if you filled in the email addresses above).</strong> The posts will be in date order (oldest first). The HTML file has minimal formatting. You will be able to open/view or save the file to your computer.</p>' ;
		echo '<input type="hidden" value="true" name="blogtohtml_button"  />' ;
		echo '<div align="center">';
	submit_button($blog2html_submit_html) ;
	echo "</div>";
		echo "<hr style=\"border-top: 3px solid #2ebc57;\" />";
		echo "<p><strong>Files are not saved on the server.</strong> You will have the option of opening or saving the file after it is generated. Email is sent using the mail() function, which should work OK (although you might need to look in your spam folder, depending on your server configuration.</p>" ;
		?>
<p><strong>For large sites</strong>, we recommend saving the file, rather than opening it in your browser. Or you may need to email the file. Large files will take a while to generate and show the Save As/Open dialog, and will take a while to open in a browser. The program has a 5-minute execution time limit, which should be enough for most sites. We've tested it with a 1300-post site, and it takes about a minute to finish.</p>
<p>Note that if you email the file, it may take a while for the file to be received. And your server or mail client may get irritated and not send or receive the file. Nothing we can control about that, though.</p>
<?php 
		echo '</form></div>' ;
		echo '<hr>' ;
	}
	// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
	// ``end of admin area
	//here's the closing bracket for the is_admin thing

}
// ----------------------------------------------------------------------------
// register/deregister/uninstall hooks
register_activation_hook(__FILE__, 'blogtohtml_register') ;
register_deactivation_hook(__FILE__, 'blogtohtml_deregister') ;
register_uninstall_hook(__FILE__, 'blogtohtml_uninstall') ;

// register/deregister/uninstall options (even though there aren't options)
function blogtohtml_register()
{
	return ;
}

function blogtohtml_deregister()
{
	return ;
}

function blogtohtml_uninstall()
{
	return ;
}

//----------------------------------------------------------------------------
// delete old generated files
function blogtohtml_delete_old_files()
{
	return true ;
}

// --------------------------------------------------------------------------------
// replace caption shortcode
// from https://github.com/chrisguitarguy/WPSE-Plugins/blob/master/data-attr-caption.php
add_filter('img_caption_shortcode', 'blogtohtml_caption_fix', 10, 3) ;


/**
* The `img_caption_shortcode` callback. Actually generates the caption and
* image to be inserted.  This is mostly copied from WP's core image shortcode
* callback with some modifications to suit our needs.
*
* @param   string $ns An emptry string, not used
* @param   array $args The shortcode args
* @param   string|null $content The content passed to the caption shortcode.
* @return  string
*/
function blogtohtml_caption_fix($x = null, $atts = null, $content = null)
{
	extract( shortcode_atts( array('id' => '', 'align' => 'alignnone', 'width' => '', 'caption' => ''), $atts )) ;
	if ( 1 > (int) $width || empty ($caption))
	{
		return $content ;
	}
	// add the data attribute
	$res = str_replace( '<img', '<img data-caption="' . esc_attr($caption) . '"', $content ) ;
	// the next bit is more tricky: we need to append our align class to the
	// already exists classes on the image.
	$class = 'class=' ;
	$cls_pos = stripos( $res, $class ) ;
	if ($cls_pos === false)
	{
		$res = str_replace( '<img', '<img class="blog2html_img ' . esc_attr($align) . '"', $res ) ;
	}
	else
	{
		$res = substr_replace( $res, esc_attr($align) . ' ', $cls_pos + strlen( $class ) + 1, 0 ) ;
	}
	$res .= '<p class="blog2html_p ">' . esc_attr($caption) . '</p>' ;
	return $res ;
}
// --------------------------------------------------------------------------------
// display posts on all sites
// the figure styles and tags are from https://stackoverflow.com/questions/22592064/how-to-align-text-below-an-image-in-css 

function blogtohtml_site_show_posts()
{
	global $create_type;	// specifies type of file to create
	$post_output = "" ;
	$post_output .= '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
	<!-- These styles available in the HTML file
		All Content styles are not changed; you may need to add those
		-->
	<style type="text/css">
	body {color:#000 !important;background-color:#fff !important; }
	/* H1 class */
	.blog2html_h1 {}	
	/* H2 class */
	.blog2html_h2 {}	
	/* DIV class */
	.blog2html_div {}	
	/* P class */
	.blog2html_p {}		
	/* IMG class */
	.blog2html_img {}	
	/* A class */
	.blog2html_a {} 
	/* HR in case you want to display:none */
	hr {}		
	figure.blog2html_figure {
		/* To correctly align image, regardless of content height: */
		vertical-align: top;
		display: inline-block;
		/* To horizontally center images and caption */
		text-align: center;
		/* The width of the container also implies margin around the images. */
		width: 300px;
	}
	.blog2html_figure_caption {
		/* Make the caption a block so it occupies its own line. */
		display: block;
	}	
	
	</style>';

$post_output .= '
</head>
<body>
<div id="pagecontent">' ;
	global $post ;
	$args = array('post_type' => 'post', 'post_status' => 'any', 'order' => 'asc', 'order_by' => 'date', 'nopaging' => true, 'posts_per_page' => - 1,) ;
	if ($_POST[post_category])
	{
		$post_categories = $_POST[post_category];
		$args['category__in'] = $post_categories ;
	}
	$query = new WP_Query($args) ;
	//	// uncomment this if you want to see the SQL statement
	//		 $post_output .= "<strong>Debug:<br> SQL = </strong>" . $query->request . "<br>";
	//		 $query->store_result();
	//		 $records_found = $query->post_count;
	//		 $post_output .= "<strong>Found:</strong> " . $records_found . " records<br>";
	//		 $post_output .= "<strong>WP Version: </strong>" . get_bloginfo('version') . "<br>";
	//		 $post_output .= "<strong>PHP Version: </strong>" . PHP_VERSION . "<br>";
	//		 $post_output .= "<strong>Debug Info End</strong><hr>";
	//	// end uncomment area
	while ( $query->have_posts())
	{
		$query->the_post() ;
		$post = get_post() ;
		$post_output .= "<h1 class='blog2html_h1'>" . ucfirst( get_the_title()) . "</h1>" ;
		$thepostdate = get_the_date('j M Y') ;
		$post_output .= "<h2 class='blog2html_h2'>" . $thepostdate . "</h2>" ;
		$post_output .= apply_filters('the_content',$post->post_content) ;
		$media = get_attached_media('');
		if ($media) {
			$post_output .= blogtohtml_output_media($media);
}
		$post_output .= "<br>" ;
	}
	$post_output .= "</div>";
	$post_output .= '</body></html>' ;
	// 	need to add the blog2html_img class to all images that aren't galleries
	$post_output = blogtohtml_fix_img_attr($post_output);
		
	if ($_POST['email'])
	{
		$email_to = $_POST['email'];
		$email_from = $_POST['email_from'];
		blogtohtml_send_the_mail_2($email_to, $email_from, $post_output) ;
	}
	else
	{
		blogtohtml_output_file($post_output) ;
	}
	$post_output = "" ;
	wp_reset_postdata() ;
	return true ;
}

// ----------------------------------------------------------------------------
function blogtohtml_output_media($media) { // note $media is an object, not an array
	$x = "";
	foreach ($media as $picture) {
		$pixsrc = wp_get_attachment_image_src($picture->ID,'medium');
		$x .= "<figure class='blog2html_figure'><img src = '" . $pixsrc[0] . "'";
		$x .= " title = '" . $picture->post_title . "' ";
		$x .= " caption = '" . $picture->post_excerpt . "' ";
		$x .= " alt = '" . $picture->post_excerpt . "' ";
		$x .= "  class='blog2html_img' />";
		$x .= "</figure><figcaption class=\"blog2html_figure_caption\">" ;
		$x .= $picture->post_excerpt . "</figcaption>";
		
		
	}
	return $x; }
// ----------------------------------------------------------------------------
// set the proper class for all tags used by the inserted styles for the output
// from https://stackoverflow.com/questions/3820666/grabbing-the-href-attribute-of-an-a-element

function blogtohtml_fix_img_attr($html) {
	$dom = new DOMDocument;
	libxml_use_internal_errors(false); // supress errors
	$dom->loadHTML($html, LIBXML_NOERROR);	// supress errors
	// img = blog2html_img
	foreach ($dom->getElementsByTagName('img') as $node) {
		$node->setattribute('class','blog2html_img');
		
		$dom->saveHtml($node) ;
	}	
	// h1 = blog2html_h1
	foreach ($dom->getElementsByTagName('h1') as $node) {
		$node->setattribute('class','blog2html_h1');
		
		$dom->saveHtml($node) ;
	}	
	// h2 = blog2html_h2
	foreach ($dom->getElementsByTagName('h2') as $node) {
		$node->setattribute('class','blog2html_h2');
		
		$dom->saveHtml($node) ;
	}	
	// p = blog2html_p
	foreach ($dom->getElementsByTagName('p') as $node) {
		$node->setattribute('class','blog2html_p');
		
		$dom->saveHtml($node) ;
	}	
	// p = blog2html_p
	foreach ($dom->getElementsByTagName('a') as $node) {
		$node->setattribute('class','blog2html_a');
		
		$dom->saveHtml($node) ;
	}	
	foreach ($dom->getElementsByTagName('a') as $node) {
		$node->removeAttribute('href');;
		
		$dom->saveHtml($node) ;
	}	
	// img = blog2html_img
	foreach ($dom->getElementsByTagName('img') as $node) {
		$node->setattribute('class','blog2html_img');
		$caption = $node->getattribute('caption');
		if (! $caption) {$caption = $node->getAttribute('alt');}
		if (! $caption) {$caption = $node->getAttribute('data-caption');}
		if (! $caption) {$caption = " "; }
		$node->setattribute('alt',$caption);
		$node->setattribute('name',$caption);
		$node->setattribute('data-caption',$caption);
		
		$dom->saveHtml($node) ;
	}	
	$html = $dom->saveHTML();	// saves the object (all of the html) so we can return it
	
	return $html;
}

// --------------------------------------------------------------------------------
// output the file with a prompt to save
function blogtohtml_output_file($post_output = "nothing found")
{
	global $create_type;	// specifies type of file to create
		$thefile = 'blogexport_' . date('m-d-Y_his') . '.html' ;
	if ( headers_sent())
	{
		throw new Exception('Uh-oh...headers already sent. :( ') ;
	}
	$size = strlen($post_output) ;
	header('Content-Description: File Transfer') ;
	header('Content-Type: application/octet-stream') ;
	header('Content-Disposition: attachment; filename=' . $thefile) ;
	header('Content-Transfer-Encoding: binary') ;
	header('Connection: Keep-Alive') ;
	header('Expires: 0') ;
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0') ;
	header('Pragma: public') ;
	header('Content-Length: ' . $size) ;
	ob_clean() ;
	flush() ;
	echo $post_output ;
	exit ;
	return ;
}

// --------------------------------------------------------------------------------
function blogtohtml_objectToArray($object)
{ // convert object to array, required for get_sites() loop
	if ( !is_object($object) && !is_array($object))
		return $object ;
	return array_map( 'blogtohtml_objectToArray', (array) $object ) ;
}

// ----------------------------------------------------------------------------
// debugging function to show array values nicely formatted; not used
function blogtohtml_show_array( $xarray = array())
{
	echo "<pre>" ;
	print_r($xarray) ;
	echo "</pre>" ;
	$x = "<pre>" . print_r($xarray) . "</pre>" ;
	return $x ;
}

// ----------------------------------------------------------------------------
// check if at least WP 4.6 and PHP version at least 5.3
// based on https://www.sitepoint.com/preventing-wordpress-plugin-incompatibilities/
function blogtohtml_is_requirements_met()
{
	$min_wp = '4.6' ;
	$min_php = '5.3' ;
	// Check for WordPress version
	if ( version_compare( get_bloginfo('version'), $min_wp, '<' ))
	{
		return false ;
	}
	// Check the PHP version
	if ( version_compare(PHP_VERSION, $min_php, '<'))
	{
		return false ;
	}
	return true ;
}

// ----------------------------------------------------------------------------
// disable plugin if WP/PHP versions are not enough
function blogtohtml_disable_plugin()
{
	if ( is_plugin_active( plugin_basename(__FILE__)))
	{
		deactivate_plugins( plugin_basename(__FILE__)) ;
		// Hide the default "Plugin activated" notice
		if ( isset ($_GET['activate']))
		{
			unset ($_GET['activate']) ;
		}
	}
}

// ----------------------------------------------------------------------------
// show notice that plugin was deactivated because WP/PHP versions not enough
function blogtohtml_show_notice()
{
	echo '<div class="notice notice-error is-dismissible"><h3><strong>Blog to HTML</strong></h3><p> cannot be activated - requires at least WordPress 4.6 and PHP 5.3.&nbsp;&nbsp;&nbsp;Plugin automatically deactivated.</p></div>' ;
	return ;
}

// ----------------------------------------------------------------------------
// get stuff inside the caption shortcode
//remove_shortcode('caption'); 	// removes default caption shortcode processor
//add_shortcode('caption', 'blogtohtml_get_caption_text',10,2);	// our caption shortcode process
function blogtohtml_get_caption_text($atts, $content = null)
{
//$content = "<p>11" . blogtohtml_show_array($content) . "22</p>";
	$content = "<p class='blog2html_p '>" . $content . "22</p>" ;
	return $content ;
}

// ----------------------------------------------------------------------------
// admin notice if something failed
function blogtohtml_admin_notice( $theerrors = array('Something did not work correctly!'))
{
	foreach ($the_errors as $the_error)
	{
		echo "<div class='notice notice-error is-dismissible'><h3><strong>Blog to HTML</strong></h3><p> - Error: $the_error .</p></div>" ;
	}
	return ;
}

// ----------------------------------------------------------------------------
// admin message if mail sent OK
function blogtohtml_mail_sent()
{
	global $mailto ;
	echo '<div class="notice notice-success is-dismissible"><h3><strong>Blog to HTML</strong> - Mail was sent OK.</strong></h3><p> This means that the message and attachment were sent to your server\'s mail system. There is no information available to verify that the message was actually sent or will be received by the recipient. The mail server on a site with a large amount of exported content might not be mailed due to the mail server limitations. See information below the mail fields for possible problems.</p><p>The attachment has been compressed, just uncompress to view the HTML file.</p></div>' ;
	return ;
}

// ----------------------------------------------------------------------------
// admin message if mail send failed
function blogtohtml_mail_fail()
{
	echo '<div class="notice notice-error is-dismissible"><h3><strong>Blog to HTML</strong> - Problem sending the mail message. Might be caused by a non-existent "from" email address, or server problems. Your server logs might help determine the error.</h3></div>' ;
	return ;
}

// ----------------------------------------------------------------------------
// admin message if bad email addresses
function blogtohtml_show_bad_email()
{
	echo '<div class="notice notice-error is-dismissible"><h3><strong>Blog to HTML</strong> - The email addresses you entered were not valid.</h3></div>' ;
	return ;
}

// ----------------------------------------------------------------------------
// send the email with the generated HTML as an attachment
function blogtohtml_send_the_mail_2($mailto = "", $from_mail, $content = "No content found")
{
	$size = strlen($content);
	$content = gzencode($content);


	$subject = "Generated HTML from Blog To HTML plugin" ;
	$message = "This is the generated HTML file created by Blog To HTML plugin. Size is $size ." ;
	// based on the second answer to https://stackoverflow.com/questions/12301358/send-attachments-with-php-mail
	$content = chunk_split( base64_encode($content)) ;
	$uid = md5( uniqid( time())) ;
	// do the following instead of this   $name = basename($file);
	$filename = 'blogexport_' . date('m-d-Y_his') . '.html.gz' ;
	// header
	$header = "From: $from_mail <" . $from_mail . ">\r\n" ;
	$header .= "Reply-To: $from_mail <" . $from_mail . ">\r\n" ;
	$header .= "MIME-Version: 1.0\r\n" ;
	$header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n" ;
	// message & attachment
	$nmessage .= "--" . $uid . "\r\n" ;
	$nmessage .= "Content-type:text/plain; charset=iso-8859-1\r\n" ;
	$nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n" ;
	$nmessage .= $message . "\r\n\r\n" ;
	$nmessage .= "--" . $uid . "\r\n" ;
	$nmessage .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"\r\n" ;
	$nmessage .= "Content-Transfer-Encoding: base64\r\n" ;
	$nmessage .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n" ;
	$nmessage .= $content . "\r\n\r\n" ;
	$nmessage .= "--" . $uid . "--" ;
	if ( mail($mailto, $subject, $nmessage, $header))
	{
		blogtohtml_mail_sent() ;
		return true ; 		// Or do something here
	}
	else
	{
		blogtohtml_mail_fail() ;
		return false ;
	}
	return ;
}
// ===============================================================================
// ALL DONE!!
// ===============================================================================
