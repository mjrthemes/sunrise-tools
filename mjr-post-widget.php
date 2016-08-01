<?php

/* Profile Widget */

add_action( 'widgets_init', create_function( '', 'register_widget("mjr_featured_post_widget");' ) );

class mjr_featured_post_widget extends WP_Widget {
	 /**
		* Constructor
		**/
	 public function __construct() {
			$widget_ops = array(
				'classname' => 'mjr_featured_post_widget',
				'description' => 'Display featured post box'
			);

			parent::__construct( 'mjr_featured_post_widget', 'Featured Post', $widget_ops );

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
				$id = $instance['title'];
			}
			if(isset($instance['info'])) {
				$content = '<p>'.$instance['info'].'</p>';
			}
			
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
				$returned_button .= "<a href='".$link."'>";
			}
			$returned_button .= "<div class='postbox-text'>";
			$returned_button .= "<h2>".$title."</h2>";
			$returned_button .= $content;
			$returned_button .= '<p>'.__("Read Article &rarr;", 'mjr').'</p>';
			$returned_button .= '</div>';
			if(!empty($link)) {
				$returned_button .= "</a>";
			}
			$returned_button .= "</div><!-- .mjr-postbox -->";

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
			$title = '';
			$info = '';
			if(isset($instance['title'])) {
				$title = $instance['title'];
			}
			if(isset($instance['info'])) {
				$info = $instance['info'];
			}

			?>
			<p>
				<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Post ID:', 'mjr' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_name( 'info' ); ?>"><?php _e( 'Content:', 'mjr' ); ?></label>
				<textarea class="widefat" id="<?php echo $this->get_field_id( 'info' ); ?>" name="<?php echo $this->get_field_name( 'info' ); ?>"><?php echo esc_attr( $info ); ?></textarea>
			</p>

	 <?php
	 }
}