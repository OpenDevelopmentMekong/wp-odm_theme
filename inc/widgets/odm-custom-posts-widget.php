<?php

class Odm_Custom_Posts_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'odm_custom_posts_widget',
			__('ODM Custom Posts', 'opendev'),
			array('description' => __('Display entries of the spefied custom post type', 'opendev'))
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		$selected_custom_post_id = isset($instance['odm_custom_post_types_option']) ? $instance['odm_custom_post_types_option'] : null;
		$query = array(
				'posts_per_page'   => 4,
				'order'            => 'DESC',
				'post_type'        => $selected_custom_post_id,
				'post_status'      => 'publish'
			);
		$posts = get_posts( $query );

		echo $args['before_widget']; ?>

		<div class="container">
			<div class="row">
					<?php foreach($posts as $post): ?>
						<div class="three columns custom-post-item">
							<a href="<?php echo($post->guid); ?>"><?php echo($post->post_title); ?></a>
							<p><?php echo($post->post_date); ?></p>
							<?php
								$thumb_src = opendev_get_thumbnail($post->ID);
								if (isset($thumb_src)):
									echo $thumb_src;
								endif; ?>
						</div>
					<?php endforeach; ?>
			</div>
		</div>

		<?php echo $args['after_widget'];

	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		$selected_custom_post_id = isset($instance['odm_custom_post_types_option']) ? $instance['odm_custom_post_types_option'] : null;

		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);

		$output = 'objects';
		$operator = 'and';

		$post_types = get_post_types( $args, $output, $operator ); ?>
		<label for="<?php echo $this->get_field_id( 'odm_custom_post_types_option' ); ?>"><?php _e( 'Select custom post type:' ); ?></label>

	  <select class='widefat odm_custom_post_types_option' id="<?php echo $this->get_field_id('odm_custom_post_types_option'); ?>" name="<?php echo $this->get_field_name('odm_custom_post_types_option'); ?>" type="text">
			<?php foreach ( $post_types  as $post_type ): ?>
				<option <?php if ($selected_custom_post_id == $post_type->name) { echo " selected"; } ?> value="<?php echo $post_type->name ?>"><?php echo $post_type->labels->name ?></option>
			<?php endforeach; ?>
		</select>

		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['odm_custom_post_types_option'] = ( ! empty( $new_instance['odm_custom_post_types_option'] ) ) ? $new_instance['odm_custom_post_types_option'] : '';

		return $instance;
	}
}

add_action( 'widgets_init', create_function('', 'register_widget("Odm_Custom_Posts_Widget");'));
