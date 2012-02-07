<?php
/*
Plugin Name: WP-ImageFlow2
Plugin URI: http://www.stofko.ca/wp-imageflow2-wordpress-plugin/
Description: WordPress implementation of the picture gallery ImageFlow with Lightbox. 
Version: 1.6.3
Author: Bev Stofko
Author URI: http://www.stofko.ca

Originally based on the discontinued plugin by Sven Kubiak http://www.svenkubiak.de/wp-imageflow2, but has now grown substantially.

Copyright 2010 Bev Stofko

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
global $wp_version;
define('WPIMAGEFLOW2VERSION', version_compare($wp_version, '2.8.4', '>='));

if(!defined("PHP_EOL")){define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");}

if (!class_exists("WPImageFlow2")) {
Class WPImageFlow2
{
	var $isrss = false;
	var $adminOptionsName = 'wpimageflow2_options';

	/* html div ids */
	var $imageflow2div = 'wpif2_imageflow';
	var $loadingdiv   = 'wpif2_loading';
	var $imagesdiv    = 'wpif2_images';
	var $captionsdiv  = 'wpif2_captions';
	var $sliderdiv    = 'wpif2_slider';
	var $scrollbardiv = 'wpif2_scrollbar';
	var $noscriptdiv  = 'wpif2_imageflow_noscript';

	var $wpif2_instance = 0;

	function wpimageflow2()
	{
		if (!WPIMAGEFLOW2VERSION)
		{
			add_action ('admin_notices',__('WP-ImageFlow2 requires at least WordPress 2.8.4','wp-imageflow2'));
			return;
		}	
		
		add_action('init', array(&$this, 'isRssFeed'));

		if ($this->isrss == true)
			return;

			
		register_activation_hook( __FILE__, array(&$this, 'activate'));
		register_deactivation_hook( __FILE__, array(&$this, 'deactivate'));
		add_action('init', array(&$this, 'addScripts'));	
		add_action('admin_menu', array(&$this, 'wpImageFlow2AdminMenu'));
		add_shortcode('wp-imageflow2', array(&$this, 'flow_func'));	
	}
	
	function activate()
	{
		/*
		** Nothing needs to be done for now
		*/
	}
	
	function deactivate()
	{
		/*
		** Nothing needs to be done for now
		*/
	}			
	
	function flow_func($attr) {
		/*
		** ImageFlow2 gallery shortcode handler
		*/
		$this->wpif2_instance ++;

		/* First produce the Javascript for this instance */
		$options = $this->getAdminOptions();

		// start the javascript output
		$js  = "\n".'<script type="text/javascript">'."\n";
		$js .= 'jQuery(document).ready(function() { '."\n".'var imageflow2_' . $this->wpif2_instance . ' = new imageflowplus('.$this->wpif2_instance.');'."\n";
		$js .= 'imageflow2_' . $this->wpif2_instance . '.init( {conf_autorotate: "';
		if ( !isset ($attr['rotate']) ) {
			$js .= $options['autorotate'];
		} else {
			$js .= $attr['rotate'];
		}
		$js .= '", conf_autorotatepause: ' . $options['pause'];
		if ( !isset ($attr['startimg']) ) {
			$js .= ', conf_startimg: 1';
		} else {
			$js .= ', conf_startimg: ' . $attr['startimg'];
		}
		$js .= '} );'."\n";
		$js .= '});'."\n";
		$js .= "</script>\n\n";

		/* Now produce the gallery html */
	 	if ( !isset ($attr['dir']) ) {
			return $js . $this->galleryBuiltin($attr);
		} else {
			return $js . $this->galleryFromDir($attr);
	  	}
	}

	function galleryBuiltin($attr) {
		/*
		** Use the built-in gallery images
		*/
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
				unset( $attr['orderby'] );
		}

		/*
		** Standard gallery shortcode defaults that we support here	
		*/
		global $post;
		extract(shortcode_atts(array(
				'order'      => 'ASC',
				'orderby'    => 'menu_order ID',
				'id'         => $post->ID,
				'size'       => 'thumbnail',
				'include'    => '',
				'exclude'    => '',
				'mediatag'	 => '',	// corresponds to Media Tags plugin by Paul Menard
		  ), $attr));
	
		$id = intval($id);
		if ( 'RAND' == $order )
			$orderby = 'none';

		if ( !empty($mediatag) ) {
			$mediaList = get_attachments_by_media_tags("media_tags=$mediatag&orderby=$orderby&order=$order");
			$attachments = array();
			foreach ($mediaList as $key => $val) {
				$attachments[$val->ID] = $mediaList[$key];
			}
		} elseif ( !empty($include) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( !empty($exclude) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}

		if ( empty($attachments) )
			return '';

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}

		/*
		** Get options for ImageFlow2 gallery display
		*/
		$options = $this->getAdminOptions();
		$bgcolor = $options['bgcolor'];
		$txcolor = $options['txcolor'];
		$slcolor = $options['slcolor'];
		$width   = $options['width'];
		$link    = $options['link'];
		$reflect = $options['reflect'];
		$strict  = $options['strict'];

		$plugin_url = get_option('siteurl') . "/" . PLUGINDIR . "/" . plugin_basename(dirname(__FILE__)); 			

		/**
		* Start output
		*/
		$noscript = '<noscript><div id="' . $this->noscriptdiv . '_' . $this->wpif2_instance . '" class="' . $this->noscriptdiv . '">';	
		$output  = '<div id="' . $this->imageflow2div . '_' . $this->wpif2_instance . '" class="' . $this->imageflow2div . '" style="background-color: ' . $bgcolor . '; color: ' . $txcolor . '; width: ' . $width . '">' . PHP_EOL; 
		$output .= '<div id="' . $this->loadingdiv . '_' . $this->wpif2_instance . '" class="' . $this->loadingdiv . '" style="color: ' . $txcolor . ';">' . PHP_EOL;
		$output .= '<b>';
		$output .= __('Loading Images','wp-imageflow2');
		$output .= '</b><br/>' . PHP_EOL;
		$output .= '<img src="' . $plugin_url . '/img/loading.gif" width="208" height="13" alt="' . $loadingdiv . '" />' . PHP_EOL;
		$output .= '</div>' . PHP_EOL;
		$output .= '<div id="' . $this->imagesdiv . '_' . $this->wpif2_instance . '" class="' . $this->imagesdiv . '">' . PHP_EOL;	

		/**
		* Add images
		*/
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$image 		= wp_get_attachment_image_src($id, "medium");
			$image_large 	= wp_get_attachment_image_src($id, "large");
			if ($strict == 'true') {
				$dir_array = parse_url($image[0]);
				$url_path = $dir_array['path'];
				$pic_reflected 	= $plugin_url.'/php/reflect.php?img='. urlencode($url_path) . '&bgc=' . urlencode($bgcolor);
			} else {
				$pic_reflected 	= $plugin_url.'/php/reflect.php?img='. urlencode($image[0]) . '&bgc=' . urlencode($bgcolor);
			}
			$pic_original 	= $image[0];
			$pic_large		= $image_large[0];
			$linkurl 		= '';
			$rel 			= '';

			/* If the media description contains an url and the link option is enabled, use the media description as the linkurl */
			if (($link == 'true') && (substr($attachment->post_content,0,7) == 'http://')) $linkurl = $attachment->post_content;

			if ($linkurl === '') {
				/* We are linking to the popup - use the title and description as the alt text */
				$linkurl = $pic_large;
				$rel = ' rel="wpif2_lightbox"';
				$alt = ' alt="'.$attachment->post_title."++".$attachment->post_content.'"';
			} else {
				/* We are linking to an external url - use the title as the alt text */
				$alt = ' alt="'.$attachment->post_title.'"';
			}

			/* Note that IE gets confused if we put newlines after each image, so we don't */
			if ($reflect == 'true') {
				$output .= '<img src="'.$pic_reflected.'" longdesc="'.$linkurl.'"'. $rel . $alt . ' />';
			} else {
				$output .= '<img src="'.$pic_original.'" longdesc="'.$linkurl.'"'. $rel . $alt . ' />';
			}
			/* build separate thumbnail list for users with scripts disabled */
			$noscript .= '<a href="' . $linkurl . '"><img src="' . $pic_original .'" width="100px"></a>';
			$i++;
		}
					
		
		$output .= '</div>' . PHP_EOL;
		$output .= '<div id="' . $this->captionsdiv . '_' . $this->wpif2_instance . '" class="' . $this->captionsdiv . '"></div>' . PHP_EOL;
		$output .= '<div id="' . $this->scrollbardiv . '_' . $this->wpif2_instance . '" class="' . $this->scrollbardiv;
		if ($slcolor == "black") {
			$output .= ' black';
		}
		$output .= '"><div id="' . $this->sliderdiv . '_' . $this->wpif2_instance . '" class="' . $this->sliderdiv . '">' . PHP_EOL;
		$output .= '</div>';
		$output .= '</div>' . PHP_EOL;
		$output .= $noscript . '</div></noscript></div>';	

		return $output;
	}

	function galleryFromDir($attr)
	{
		/*
		** ImageFlow2 gallery with images taken from a directory
		*/
		$replace = '';
		$options = $this->getAdminOptions();
		$bgcolor = $options['bgcolor'];
		$txcolor = $options['txcolor'];
		$slcolor = $options['slcolor'];
		$width   = $options['width'];
		$reflect = $options['reflect'];
		$strict  = $options['strict'];

		$galleries_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->get_path($options['gallery_url']);
		if (!file_exists($galleries_path))
			return '';

		/*
		** Gallery directory is ok - replace the shortcode with the image gallery
		*/
		$plugin_url = get_option('siteurl') . "/" . PLUGINDIR . "/" . plugin_basename(dirname(__FILE__)); 			
			
		$gallerypath = $galleries_path . $attr['dir'];
					
		if (file_exists($gallerypath))
		{	
			$noscript = '<noscript><div id="' . $this->noscriptdiv .'" class="' . $this->noscriptdiv . '">';	
			$replace  = '<div id="' . $this->imageflow2div . '_' . $this->wpif2_instance . '" class="' . $this->imageflow2div . '" style="background-color: ' . $bgcolor . '; color: ' . $txcolor . '; width: ' . $width .'">' . PHP_EOL; 
			$replace .= '<div id="' . $this->loadingdiv . '_' . $this->wpif2_instance . '" class="' . $this->loadingdiv . '" style="color: ' . $txcolor . ';">' . PHP_EOL;
			$replace .= '<b>';
			$replace .= __('Loading Images','wp-imageflow2');
			$replace .= '</b><br/>' . PHP_EOL;
			$replace .= '<img src="'.$plugin_url.'/img/loading.gif" width="208" height="13" alt="' . $loadingdiv . '" />' . PHP_EOL;
			$replace .= '</div>' . PHP_EOL;
			$replace .= '<div id="' . $this->imagesdiv . '_' . $this->wpif2_instance . '" class="' . $this->imagesdiv . '">' . PHP_EOL;	
					
			$handle = opendir($gallerypath);
			while ($image=readdir($handle))
			{
			    if (filetype($gallerypath."/".$image) != "dir" && !eregi('refl_',$image))
			    {
				   $pageURL = 'http';
				   if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
				   $pageURL .= "://";
				   if ($_SERVER["SERVER_PORT"] != "80") {
				    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
				   } else {
				    $pageURL .= $_SERVER["SERVER_NAME"];
				   }
				  $imagepath = $pageURL . '/' . $this->get_path($options['gallery_url']) . $attr['dir'] . '/' . $image;

				  $pic_original 	= $imagepath;
				  if ($strict == 'true') {
					  $dir_array = parse_url($pic_original);
					  $url_path = $dir_array['path'];
					  $pic_reflected 	= $plugin_url.'/php/reflect.php?img=' . urlencode($url_path) . '&bgc=' . urlencode($bgcolor);
				  } else {
					  $pic_reflected 	= $plugin_url.'/php/reflect.php?img=' . urlencode($pic_original) . '&bgc=' . urlencode($bgcolor);
				  }

				  /* Code the image with or without reflection */
				  /* Note that IE gets confused if we put newlines after each image, so we don't */
				  if ($reflect == 'true') {	
					$replace .= '<img src="' . $pic_reflected . '" longdesc="' . $pic_original . '" alt="' . $image . '" rel="wpif2_lightbox"/>';
				  } else {
					$replace .= '<img src="' . $pic_original . '" longdesc="' . $pic_original . '" alt="' . $image . '" rel="wpif2_lightbox"/>';
				  }
				  /* build separate list for users with scripts disabled */
				  $noscript .= '<a href="' . $pic_original . '"><img src="' . $pic_original .'" width="100px"></a>';
			    }				
			}			
			closedir($handle);
			
			$replace .= '</div>' . PHP_EOL;
			$replace .= '<div id="' . $this->captionsdiv . '_' . $this->wpif2_instance . '" class="' . $this->captionsdiv . '"></div>' . PHP_EOL;
			$replace .= '<div id="' . $this->scrollbardiv . '_' . $this->wpif2_instance . '" class="' . $this->scrollbardiv;
			if ($slcolor == "black") {
				$replace .= ' black';
			}
			$replace .= '"><div id="' . $this->sliderdiv . '_' . $this->wpif2_instance . '" class="' . $this->sliderdiv . '">';
			$replace .= '</div>';
			$replace .= '</div>' . PHP_EOL;
			$replace .= $noscript . '</div></noscript></div>';	
		}
		return $replace;	
	}

	function getAdminOptions() {
		/*
		** Merge default options with the saved values
		*/
		$use_options = array(	'gallery_url' => '0', 	// Path to gallery folders when not using built in gallery shortcode
						'bgcolor' => '#000000', // Background color defaults to black
						'txcolor' => '#ffffff', // Text color defaults to white
						'slcolor' => 'white',	// Slider color defaults to white
						'link'    => 'false',	// Don't link to image description
						'width'   => '520px',	// Width of containing div
						'reflect' => 'true',	// True to reflect images
						'strict'  => 'false',	// True for strict servers that don't allow http:// in script args
						'autorotate' => 'off',	// True to enable auto rotation
						'pause' =>	'3000'	// Time to pause between auto rotations
					);
		$saved_options = get_option($this->adminOptionsName);
		if (!empty($saved_options)) {
			foreach ($saved_options as $key => $option)
				$use_options[$key] = $option;
		}

		return $use_options;
	}

	function get_path($gallery_url) {
		/*
		** Determine the path to prepend with DOCUMENT_ROOT
		*/
		if (substr($gallery_url, 0, 7) != "http://") return $gallery_url;

		$dir_array = parse_url($gallery_url);
		return $dir_array['path'];
	}

	function addScripts()
	{
		$plugin_url = get_option('siteurl') . "/" . PLUGINDIR . "/" . plugin_basename(dirname(__FILE__)); 			
		if (!is_admin()) {
			wp_enqueue_style( 'wpimageflow2css', $plugin_url.'/css/screen.css');
			wp_enqueue_script('wpif2_imageflow2', $plugin_url.'/js/imageflowplus.js', array('jquery'));
		} else {
			wp_enqueue_script('colorcode_validate', $plugin_url.'/js/colorcode_validate.js');
		}
	}	
	
	function isRssFeed()
	{
		switch (basename($_SERVER['PHP_SELF']))
		{
			case 'wp-rss.php':
				$this->isrss = true;
			break;
			case 'wp-rss2.php':
				$this->isrss = true;
			break;
			case 'wp-atom.php':
				$this->isrss = true;
			break;
			case 'wp-rdf.php':
				$this->isrss = true;
			break;
			default:
				$this->isrss = false;	
		}		
	}
	
	function wpImageFlow2AdminMenu()
	{
		add_options_page('WP-ImageFlow2', 'WP-ImageFlow2', 8, 'wpImageFlow2', array(&$this, 'wpImageFlow2OptionPage'));	
	}
	
	function wpImageFlow2OptionPage()
	{		
		if (!current_user_can('manage_options'))
			wp_die(__('Sorry, but you have no permission to change settings.','wp-imageflow2'));	
			
		$options = $this->getAdminOptions();
		if (($_POST['save_wpimageflow2'] == 'true') && check_admin_referer('wpimageflow2_options'))
		{
			echo "<div id='message' id='updated fade'>";	

			/*
			** Validate the background colour
			*/
			if ((preg_match('/^#[a-f0-9]{6}$/i', $_POST['wpimageflow2_bgc'])) || ($_POST['wpimageflow2_bgc'] == 'transparent')) {
				$options['bgcolor'] = $_POST['wpimageflow2_bgc'];
			} else {
			echo "<p><b style='color:red;'>".__('Invalid background color, not saved.','wp-imageflow2'). " - " . $_POST['wpimageflow2_bgc'] ."</b></p>";	
			}

			/*
			** Validate the text colour
			*/
			if (preg_match('/^#[a-f0-9]{6}$/i', $_POST['wpimageflow2_txc'])) {
				$options['txcolor'] = $_POST['wpimageflow2_txc'];
			} else {
			echo "<p><b style='color:red;'>".__('Invalid text color, not saved.','wp-imageflow2'). " - " . $_POST['wpimageflow2_txc'] ."</b></p>";	
			}

			/*
			** Validate the slider color
			*/
			if (($_POST['wpimageflow2_slc'] == 'black') || ($_POST['wpimageflow2_slc'] == 'white')) {
				$options['slcolor'] = $_POST['wpimageflow2_slc'];
			} else {
			echo "<p><b style='color:red;'>".__('Invalid slider color, not saved.','wp-imageflow2'). " - " . $_POST['wpimageflow2_slc'] ."</b></p>";	
			}

			/*
			** Accept the container width
			*/
			$options['width'] = $_POST['wpimageflow2_width'];

			/* 
			** Look for link to description option
			*/
			if (isset ($_POST['wpimageflow2_link']) && ($_POST['wpimageflow2_link'] == 'link')) {
				$options['link'] = 'true';
			} else {
				$options['link'] = 'false';
			}

			/* 
			** Look for reflect option
			*/
			if (isset ($_POST['wpimageflow2_reflect']) && ($_POST['wpimageflow2_reflect'] == 'reflect')) {
				$options['reflect'] = 'true';
			} else {
				$options['reflect'] = 'false';
			}

			/* 
			** Look for strict option
			*/
			if (isset ($_POST['wpimageflow2_strict']) && ($_POST['wpimageflow2_strict'] == 'strict')) {
				$options['strict'] = 'true';
			} else {
				$options['strict'] = 'false';
			}

			/* 
			** Look for auto rotate option
			*/
			if (isset ($_POST['wpimageflow2_autorotate']) && ($_POST['wpimageflow2_autorotate'] == 'autorotate')) {
				$options['autorotate'] = 'on';
			} else {
				$options['autorotate'] = 'off';
			}

			/*
			** Accept the pause value
			*/
			$options['pause'] = $_POST['wpimageflow2_pause'];

			/*
			** Done validation, update whatever was accepted
			*/
			$options['gallery_url'] = trim($_POST['wpimageflow2_path']);
			update_option($this->adminOptionsName, $options);
			echo "<p>".__('Settings were saved.','wp-imageflow2')."</p></div>";	
		}
			
		?>
					
		<div class="wrap">
			<h2>WP-ImageFlow2</h2>
			<form action="options-general.php?page=wpImageFlow2" method="post">
	    		<h3><?php echo __('Settings','wp-imageflow2'); ?></h3>
	    		<table class="form-table">
				<tr>
					<th scope="row" valign="top">
					<?php echo __('Background color', 'wp-imageflow2'); ?>
					</th>
					<td>
					<input type="text" name="wpimageflow2_bgc" onkeyup="colorcode_validate(this, this.value);" value="<?php echo $options['bgcolor']; ?>"> 
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
					<?php echo __('Text color', 'wp-imageflow2'); ?>
					</th>
					<td>
					<input type="text" name="wpimageflow2_txc" onkeyup="colorcode_validate(this, this.value);" value="<?php echo $options['txcolor']; ?>"> 
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
					<?php echo __('Slider color', 'wp-imageflow2'); ?>
					</th>
					<td>
					<select name="wpimageflow2_slc">
					<option value="white"<?php if ($options['slcolor'] == 'white') echo ' SELECTED'; echo __('>White', 'wp-imageflow2'); ?></option>
					<option value="black"<?php if ($options['slcolor'] == 'black') echo ' SELECTED'; echo __('>Black', 'wp-imageflow2'); ?></option>
					</select>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
					<?php echo __('Container width CSS', 'wp-imageflow2'); ?>
					</th>
					<td>
					<input type="text" name="wpimageflow2_width" value="<?php echo $options['width']; ?>"> 
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
					<?php echo __('Check this box to have the built in gallery use the description field as an external link from the image thumbnail.', 'wp-imageflow2'); ?>
					</th>
					<td>
					<input type="checkbox" name="wpimageflow2_link" value="link" <?php if ($options['link'] == 'true') echo ' CHECKED'; ?> />
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
					<?php echo __('Check this box to have image reflections (requires gd).', 'wp-imageflow2'); ?>
					</th>
					<td>
					<input type="checkbox" name="wpimageflow2_reflect" value="reflect" <?php if ($options['reflect'] == 'true') echo ' CHECKED'; ?> />
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
					<?php echo __('Check this box if your server is strict and serves a 404 error on reflected images.', 'wp-imageflow2'); ?>
					</th>
					<td>
					<input type="checkbox" name="wpimageflow2_strict" value="strict" <?php if ($options['strict'] == 'true') echo ' CHECKED'; ?> />
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
					<?php echo __('Enable auto rotation.', 'wp-imageflow2'); ?>
					</th>
					<td>
					<input type="checkbox" name="wpimageflow2_autorotate" value="autorotate" <?php if ($options['autorotate'] == 'on') echo ' CHECKED'; ?> />
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
					<?php echo __('Auto rotation pause between images', 'wp-imageflow2'); ?>
					</th>
					<td>
					<input type="text" name="wpimageflow2_pause" value="<?php echo $options['pause']; ?>"> 
					</td>
				</tr>
	    			<tr>
					<th scope="row" valign="top">
					<?php echo __('Enter a value here if you wish to upload images to a directory.','wp-imageflow2'); ?>	
					</th>
					<td>
					<?php echo __('Path to galleries from homepage root path or full url.','wp-imageflow2'); ?>
					<br /><input type="text" size="35" name="wpimageflow2_path" value="<?php echo $options['gallery_url']; ?>">
					<br /><?php echo __('e.g.','wp-imageflow2'); ?> wp-content/galleries/
					<br /><?php echo __('Ending slash, but NO starting slash','wp-imageflow2'); ?>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">&nbsp;</th>
					<td>
					<input type="hidden" value="true" name="save_wpimageflow2">
					<?php
					if ( function_exists('wp_nonce_field') )
						wp_nonce_field('wpimageflow2_options');
					?>
					<input name="submit" value="<?php echo __('Save','wp-imageflow2'); ?>" type="submit" />			
					</td>
				</tr>				
			</table>
			</form>				
	    		<table class="form-table">
	    			<tr>
					<th scope="row" valign="top">
					<?php echo __('Subdirectory galleries found:','wp-imageflow2'); ?>	
					</th>
					<td>
					<?php
						$galleries_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->get_path($options['gallery_url']);
						if (file_exists($galleries_path)) {
							$handle	= opendir($galleries_path);
							while ($dir=readdir($handle))
							{
								if ($dir != "." && $dir != "..")
								{									
									echo "[wp-imageflow2 dir=".$dir."]";
									echo "<br />";
								}
							}
							closedir($handle);								
						} else {
							echo "Gallery path doesn't exist";
						}					
					?>
					</td>
				</tr>
			</table>		
		</div>
		
		<?php			
	}		
}

}

if (class_exists("WPImageFlow2")) {
	$wpimageflow2 = new WPImageFlow2();
}
?>