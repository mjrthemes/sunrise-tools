<?php
/*
Plugin Name: Sunrise Tools
Plugin URI: https://major-themes.com/plugins/shortcodes
Description: Bunch of useful shortcodes
Version: 1.0.0
Author: MajorThemes
Author URI: https://major-themes.com
*/

function mjr_add_options_submenu_page() {
	  add_submenu_page(
			 'options-general.php',          // admin page slug
			 __( 'Custom CSS', 'mjr' ), // page title
			 __( 'Custom CSS', 'mjr' ), // menu title
			 'manage_options',               // capability required to see the page
			 'mjr_options_css',                // admin page slug, e.g. options-general.php?page=mjr_options
			 'mjr_options_page_css'            // callback function to display the options page
	  );
}
add_action( 'admin_menu', 'mjr_add_options_submenu_page' );
 
/**
 * Register the settings
 */
function mjr_register_settings() {
	  register_setting(
			 'mjr_options_css',  // settings section
			 'mjr_css' // setting name
	  );
}
add_action( 'admin_init', 'mjr_register_settings' );
 
/**
 * Build the options page
 */
function mjr_options_page_css() {
	  if ( ! isset( $_REQUEST['settings-updated'] ) )
			 $_REQUEST['settings-updated'] = false; ?>
 
	  <div class="wrap">
			  
			 <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			  
			 <div id="poststuff">
					<div id="post-body">
						  <div id="post-body-content">
								 <form method="post" action="options.php">
										<?php settings_fields( 'mjr_options_css' ); ?>
										<?php $mjr_css = get_option( 'mjr_css' ); ?>
										<label for="mjr_css"><h3>Custom CSS styles</h3></label>
										<textarea name="mjr_css" id="mjr_css" style="width: 100%; height: 350px;"><?php echo $mjr_css; ?></textarea>
										<?php submit_button(); ?>
								 </form>
						  </div> <!-- end post-body-content -->
					</div> <!-- end post-body -->
			 </div> <!-- end poststuff -->
	  </div>
<?php }

// Getting values

function mjr_analytics() {
	$analytics = get_option( 'mjr_analytics', '' );
	return $analytics;
}

function mjr_custom_styles() {
	$mjr_custom_css = get_option( 'mjr_css' ); 
	if($mjr_custom_css) {
		$mjr_custom_css = '
		<style type="text/css">
			'.$mjr_custom_css.'
		</style>
		';
	}
	return $mjr_custom_css;
}

/* Enqueue Admin scripts */

function mjr_shortcodes_script_enqueuer() {
	wp_register_script( 'mjr_shortcodes_script', plugin_dir_url( __FILE__ ).'js/mjr-shortcodes.js', array( 'jquery', 'jqueryui' ), '1.0.0', true );
	wp_enqueue_script( 'mjr_shortcodes_script' );

	$version_jqueryui = '1.11.4';

	wp_register_script( 'jqueryui', '//ajax.googleapis.com/ajax/libs/jqueryui/'.$version_jqueryui.'/jquery-ui.min.js', 'jquery', $version_jqueryui, true );
	wp_enqueue_script( 'jqueryui' );
}
add_action( 'wp_enqueue_scripts', 'mjr_shortcodes_script_enqueuer' );

/* Enqueue Admin section styles */

function mjr_admin_styles() {
	wp_register_style( 'mjr_admin_styles', plugin_dir_url( __FILE__ )."css/admin.css");
	wp_enqueue_style( 'mjr_admin_styles' );
	wp_register_style( 'font-awesome', plugin_dir_url( __FILE__ )."css/font-awesome.min.css");
	wp_enqueue_style( 'font-awesome' );
}
add_action('admin_print_styles', 'mjr_admin_styles'); 

/* Enable shortcodes in Text Widget */

add_filter('widget_text', 'do_shortcode');

/* No wpautop for properly working tabs shortcode */
function mjr_no_wpautop($content) { 
	$content = do_shortcode( shortcode_unautop($content) ); 
	$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );
	return $content;
}

/* TinyMCE */

function mjr_custom_mce_buttons() {
	if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') ) {
		add_filter('mce_buttons', 'mjr_add_mce_button');
		add_filter('mce_external_plugins', 'mjr_add_mce_plugin');
	}
}
add_action('init', 'mjr_custom_mce_buttons');

function mjr_add_mce_button($buttons) {
	array_push($buttons, 'mjrdesign');
	return $buttons;
}

function mjr_add_mce_plugin($plugin_array) {
	$plugin_array['blist'] = plugin_dir_url( __FILE__ ).'js/customcodes.js';
	return $plugin_array;
}

/* 
	Shortcodes
*/

/* Slideshow */

function mjr_slideshow($atts, $content = null) {
	global $post;
	shortcode_atts( array(
		'ids' => '',
		'speed' => '10000',
		'animation' => 'slide',
		'direction' => 'horizontal'
	), $atts );

	$mjr_gallery = "<div class='mjr-slider' data-slideshowspeed='".$atts['speed']."' data-animationspeed='600' data-animation='".$atts['animation']."' data-direction='".$atts['direction']."' data-autostart='true'>
	<ul class='slides'>";

	$images = explode(',', $atts['ids']);
	$i = 0;
	foreach($images as $image) {
		$i++;
		$attimg = wp_get_attachment_image_src($image, 'mjr-main');
		$attdata = get_post($image);
		$mjr_gallery .= "<li data-image='".$attimg[0]."' style='background-image: url(".$attimg[0].");' title='".$attdata->post_title."' ><h3>".$attdata->post_title."</h3>";
		$mjr_gallery .= "</li>";
	}
	$mjr_gallery .= "</ul></div>";

	return $mjr_gallery;
}
add_shortcode('slideshow', 'mjr_slideshow');

/* Button */

function mjr_button($atts, $content = null) {
	extract( shortcode_atts( array(
		'link' => '',
		'icon' => '',
		'color' => '',
		'size' => 'medium'
	), $atts ) );

	if($size == 'large') {
		$size = 'default-button-large';
	}
	if($size == 'small') {
		$size = 'default-button-small';
	}

	$returned_button = '';
	$returned_button .= '<a href="'.$link.'" class="default-button '.$size.'"';
	if($color) {
		$returned_button .= 'style="background-color: '.$color.';"';
	}
	$returned_button .= '>';
	if($icon) {
		$returned_button .= ' <i class="fa fa-'.$icon.'"></i>';
	}
	$returned_button .= $content.'</a>';

	return $returned_button;
}
add_shortcode('button', 'mjr_button');

/* Profile */

function mjr_profile($atts, $content = null) {
	extract( shortcode_atts( array(
		'name' => '',
		'bg' => '',
		'avatar' => '',
		'location' => ''
	), $atts ) );

	$returned_button = '';
	$returned_button .= "<div class='mjr-profile'>";
	if($bg) {
		$returned_button .= "<div class='profile-bg' style='background-image: url(".$bg.")'></div>";
	}
	if($avatar) {
		$returned_button .= "<div class='profile-avatar' style='background-image: url(".$avatar.")'></div>";
	}
	$returned_button .= "<div class='profile-name'>".$name."</div>";
	if(!empty($loaction)) {
		$returned_button .= "<div class='profile-location'><i class='fa fa-map-marker'></i> ".$location."</div>";
	}
	$returned_button .= "<div class='profile-text'>".$content."</div>";
	if(function_exists('sunrise_social_images')) {
		$returned_button .= sunrise_social_images();
	}
	$returned_button .= "</div><!-- .mjr-profile -->";

	return $returned_button;
}
add_shortcode('profile', 'mjr_profile');

/* Postbox */

function mjr_postbox($atts, $content = null) {
	extract( shortcode_atts( array(
		'id' => '',
		'superlink' => ''
	), $atts ) );

	$categories = get_the_category($id);

	if (!empty($categories)) {
		$category = esc_html($categories[0]->name);
		$category_link = esc_url(get_category_link($categories[0]->term_id));
	}

	$link = get_permalink($id);
	$title = get_the_title($id);
	$bg = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'large');
	$bg = $bg[0];
	$returned_button = '';
	if(!empty($bg)) {
		$returned_button .= "<div class='mjr-postbox' style='background-image: url(".$bg.")'>";
	} else {
		$returned_button .= "<div class='mjr-postbox no-bg'>";
	}
	if(!empty($category)) {
		$returned_button .= "<div class='postbox-cat'><a href='".$category_link."'>".$category."</a></div>";
	}
	if(!empty($link)) {
		if($superlink) {
			$link = $superlink;
		}
		$returned_button .= "<a href='".$link."'>";
	}
	$returned_button .= "<div class='postbox-text'><h2>".$title."</h2>".$content."</div>";
	if(!empty($link)) {
		$returned_button .= "</a>";
	}
	$returned_button .= "</div>";

	return $returned_button;
}
add_shortcode('postbox', 'mjr_postbox');

/* Notice */

function mjr_notice($atts, $content = null) {
	extract( shortcode_atts( array(
		'icon' => '',
		'color' => '',
		'size' => 'medium'
	), $atts ) );

	$returned_button = '';
	$returned_button .= '<div class="mjr-notice '.$size.'"';
	if($color) {
		$returned_button .= 'style="background-color: '.$color.';"';
	}
	$returned_button .= '>';
	if($icon) {
		$returned_button .= ' <i class="fa fa-'.$icon.'"></i>';
	}
	$returned_button .= $content.'</div>';

	return $returned_button;
}
add_shortcode('notice', 'mjr_notice');

/* Icon */

function mjr_icon($atts, $content = null) {
	extract( shortcode_atts( array(
					'name'  => 'wrench',
					'size'  => 'inherit'
				), $atts ) );
	return '<i class="fa fa-'.$name.'" style="font-size: '.$size.'">'.$content.'</i>';
}
add_shortcode('icon', 'mjr_icon');

/* Columns */

function grid($atts, $content = null) {
	return '<div class="grid">' . do_shortcode($content) . '</div>';
}
add_shortcode('grid', 'grid'); 

function col($atts, $content = null) {
	return '<div class="grid-cell">' . do_shortcode($content) . '</div>';
}
add_shortcode('col', 'col');

/* Toggle */

function mjr_toggle($atts, $content = null) {
	extract(shortcode_atts(array("caption" => "Toggle", "collapsable" => "yes"), $atts));
	$html = ''; 
	if ($collapsable == "yes") {
		$html .= '<div class="trigger-button"><span>' . $caption . '</span></div> <div class="accordion">';
		$html .= mjr_no_wpautop($content);
		$html .= '</div>';
	}
	else {
		$html .= '<div class="toggle-wrap">';
		$html .= '<span class="trigger"><a href="#">' . $caption . '</a></span><div class="toggle-container">';
		$html .= mjr_no_wpautop($content);
		$html .= '</div></div>';
	}
	return $html;
}
add_shortcode('toggle', 'mjr_toggle');

/* Tabs */

function mjr_tab( $atts, $content = null ) {

	extract(shortcode_atts(array(
		'title' => 'Tab'
	), $atts ));
										
	return '<div id="tab-'. preg_replace('/[^A-Za-z0-9-]/' ,'', sanitize_title($title) ) .'">'. do_shortcode($content) .'</div>';
}
add_shortcode('tab', 'mjr_tab');

function mjr_tabs( $atts, $content = null ) {

	if( preg_match_all( '/\[tab[^\]]*title="([^\"]+)"[^\]]*\]/i', $content, $mjr_tabs ) ) {

		$titles = '';
		
		foreach($mjr_tabs[1] as $mjr_single_tab) {
			$titles.='<li><a href="#tab-'. preg_replace('/[^A-Za-z0-9-]/' ,'', sanitize_title($mjr_single_tab)) .'">'.$mjr_single_tab.'</a></li>';
		}
	}

	$tabs = '
	<div class="mjr-tabs">
		<ul class="tabs-control">'.$titles.'</ul>
		'.do_shortcode($content).'
	</div>';
			
	return $tabs;
}
add_shortcode('tabs', 'mjr_tabs');

add_action( 'widgets_init', create_function( '', 'register_widget("mjr_pu_media_upload_widget");' ) );

class mjr_pu_media_upload_widget extends WP_Widget {
	 /**
	  * Constructor
	  **/
	 public function __construct() {
		  $widget_ops = array(
				'classname' => 'mjr_pu_media_upload',
				'description' => 'Set your fancy profile in sidebar or footer'
		  );

		  parent::__construct( 'mjr_pu_media_upload', 'Profile', $widget_ops );

		  add_action('admin_enqueue_scripts', array($this, 'upload_scripts'));
	 }

	 /**
	  * Upload the Javascripts for the media uploader
	  */
	 public function upload_scripts() {
		  wp_enqueue_script('media-upload');
		  wp_enqueue_script('thickbox');
		  wp_enqueue_script('mjr_upload_media_widget', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'));

		  wp_enqueue_style('thickbox');
	 }

	 /**
	  * Outputs the HTML for this widget.
	  *
	  * @param array  An array of standard parameters for widgets in this theme
	  * @param array  An array of settings for this widget instance
	  * @return void Echoes it's output
	  **/
	 public function widget( $args, $instance ) {

	 	  extract( $args );
		  if(isset($instance['title']))
		  {
				$name = $instance['title'];
		  }
		  if(isset($instance['info']))
		  {
				$content = $instance['info'];
		  }
		  if(isset($instance['image']))
		  {
				$avatar = $instance['image'];
		  }

			$returned_button = '';
			$returned_button .= "<div class='mjr-profile'>";
			if($avatar) {
				$returned_button .= "<div class='profile-avatar' style='background-image: url(".$avatar.")'></div>";
			}
			$returned_button .= "<div class='profile-name'>".$name."</div>";
			$returned_button .= "<div class='profile-text'>".$content."</div>";
			if(function_exists('sunrise_social_images')) {
				$returned_button .= sunrise_social_images();
			}
			$returned_button .= "</div><!-- .mjr-profile -->";

			echo $before_widget;
			echo $returned_button;
			echo $after_widget;
	 }

	 /**
	  * Deals with the settings when they are saved by the admin. Here is
	  * where any validation should be dealt with.
	  *
	  * @param array  An array of new settings as submitted by the admin
	  * @param array  An array of the previous settings
	  * @return array The validated and (if necessary) amended settings
	  **/
	 public function update( $new_instance, $old_instance ) {

		  // update logic goes here
		  $updated_instance = $new_instance;
		  return $updated_instance;
	 }

	 /**
	  * Displays the form for this widget on the Widgets page of the WP Admin area.
	  *
	  * @param array  An array of the current settings for this widget
	  * @return void
	  **/
	 public function form( $instance ) {
		  $title = __('Name', 'mjr');
		  $info = '';
		  if(isset($instance['title']))
		  {
				$title = $instance['title'];
		  }
		  if(isset($instance['info']))
		  {
				$info = $instance['info'];
		  }

		  $image = '';
		  if(isset($instance['image']))
		  {
				$image = $instance['image'];
		  }
		  ?>
		  <p>
				<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Name:', 'mjr' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		  </p>

		  <p>
				<label for="<?php echo $this->get_field_name( 'info' ); ?>"><?php _e( 'Info:', 'mjr' ); ?></label>
				<textarea class="widefat" id="<?php echo $this->get_field_id( 'info' ); ?>" name="<?php echo $this->get_field_name( 'info' ); ?>"><?php echo esc_attr( $info ); ?></textarea>
		  </p>

		  <p>
				<label for="<?php echo $this->get_field_name( 'image' ); ?>"><?php _e( 'Profile image:', 'mjr' ); ?></label>
				<input name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" class="widefat mjr_profile_avatar" type="text" size="36"  value="<?php echo esc_url( $image ); ?>" />
				<p><button class="mjr_upload_image_button">Select or Upload image</button></p>
		  </p>
	 <?php
	 }
}