<?php

class Odm_Contents_Same_Category_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'odm_contents_same_category_widget',
			__('ODM Contents same category', 'opendev'),
			array('description' => __('Display other post types tagged with the same categories as the one where the widget is integrated.', 'opendev'))
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		$selected_custom_post_ids = isset($instance['post_type']) ? $instance['post_type'] : null;
		$limit = isset($instance['limit']) ? $instance['limit'] : -1;
		global $post;
		$categories = wp_get_post_categories($post->ID);

		$query = array(
				'posts_per_page'   => $limit,
				'order'            => 'DESC',
				'post_type'        => $selected_custom_post_ids,
				'post_status'      => 'publish',
				'category'				 => $categories
			);
		$posts = get_posts( $query );

		echo $args['before_widget']; ?>

		<?php
			if (!empty($instance['title'])):
				 echo $args['before_title'].apply_filters('widget_title', __($instance['title'], 'opendev')).$args['after_title'];
			endif; ?>

		<ul>

			<?php foreach($posts as $post):?>
				<li>
					<a href="<?php echo get_permalink($post->ID);?>"><?php echo get_permalink($post->title);?></a>
				</li>
			<?php endforeach; ?>

		</ul>

		<?php echo $args['after_widget'];

	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		$selected_custom_post_id = isset($instance['post_type']) ? $instance['post_type'] : null;

		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);

		$output = 'objects';
		$operator = 'and';
		$post_types = get_post_types( $args, $output, $operator );

		$title = !empty($instance['title']) ? __($instance['title'], 'opendev') : __('Custom posts', 'opendev'); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:');?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($title);?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Select custom post type:' ); ?></label>
			<?php foreach ( $post_types  as $post_type ): ?>
				<input <?php if (in_array($post_type->name,$selected_custom_post_id)) { echo " selected"; } ?> name="<?php echo $this->get_field_name('post_type'); ?>" value="<?php echo $post_type->name ?>"><?php echo $post_type->labels->name ?></input>
			<?php endforeach; ?>
		</p>

		<?php $limit = !empty($instance['limit']) ? $instance['limit'] : -1 ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Select max number of posts to list:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('limit');?>" name="<?php echo $this->get_field_name('limit');?>" type="number" value="<?php echo $limit;?>">
		</p>

		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {


		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['limit'] = (!empty($new_instance['limit'])) ? strip_tags($new_instance['limit']) : -1;
		$instance['post_type'] = (!empty( $new_instance['post_type'])) ? $new_instance['post_type'] : '';

		return $instance;
	}
}

add_action( 'widgets_init', create_function('', 'register_widget("Odm_Contents_Same_Category_Widget");'));
