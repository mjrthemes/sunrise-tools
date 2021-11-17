<?php

/* Profile Widget */

add_action ( 'widgets_init', 'mjr_pu_media_upload_widget_init' );
function mjr_pu_media_upload_widget_init() {
return register_widget('mjr_pu_media_upload_widget');
}

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
		* @param array	An array of standard parameters for widgets in this theme
		* @param array	An array of settings for this widget instance
		* @return void Echoes it's output
		**/
	 public function widget( $args, $instance ) {

	 		extract( $args );
			if(isset($instance['title'])) {
				$name = $instance['title'];
			}
			if(isset($instance['info'])) {
				$content = $instance['info'];
			}
			if(isset($instance['image'])) {
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
		* @param array	An array of new settings as submitted by the admin
		* @param array	An array of the previous settings
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
		* @param array	An array of the current settings for this widget
		* @return void
		**/
	 public function form( $instance ) {
			$title = __('Name', 'mjr');
			$info = '';
			if(isset($instance['title']))	{
				$title = $instance['title'];
			}
			if(isset($instance['info'])) {
				$info = $instance['info'];
			}

			$image = '';
			if(isset($instance['image'])) {
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
				<input name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" class="widefat mjr_profile_avatar" type="text" size="36"	value="<?php echo esc_url( $image ); ?>" />
				<a class="mjr_upload_image_button button-secondary" style="margin-top: 10px;">Select or Upload image</a>
			</p>
	 <?php
	 }
}
