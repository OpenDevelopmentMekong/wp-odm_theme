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
			array('description' => __('Display other content from other post types tagged with the same categories as the one where the widget is integrated.', 'opendev'))
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		global $post;

		$limit = isset($instance['limit']) ? $instance['limit'] : -1;
		$categories = wp_get_post_categories($post->ID);
		$related_posts = array();

		$supported_post_types = array();
		$post_types = available_custom_post_types();
		foreach ($post_types as $post_type):
			if (isset($instance[$post_type->name]) && $instance[$post_type->name]):
				array_push($supported_post_types,$post_type->name);
			endif;
		endforeach;

		if (!empty($categories)):

			//TODO: OPtimize this query to filter out categories directly
			// and ensuring $limit is precise.

			$query = array(
					'posts_per_page'   => $limit,
					'order'            => 'DESC',
					'post_type'        => $supported_post_types,
					'post_status'      => 'publish'
				);
			$related_posts = get_posts( $query );

		endif;

		echo $args['before_widget']; ?>

		<?php
			if (!empty($instance['title'])):
				 echo $args['before_title'].apply_filters('widget_title', __($instance['title'], 'opendev')).$args['after_title'];
			endif; ?>

		<ul>

			<?php

				//TODO: After optimizing query above, this check would not be necessary

				foreach($related_posts as $related_post):
					$related_categories = wp_get_post_categories($related_post->ID);
					if (array_intersect($categories,$related_categories)): ?>

						<li>
							<a href="<?php echo get_permalink($related_post->ID);?>"><?php echo $related_post->post_title;?></a>
						</li>

			<?php
					endif;
				endforeach; ?>

		</ul>

		<?php echo $args['after_widget'];

	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

	  $post_types = available_custom_post_types();

		$title = !empty($instance['title']) ? __($instance['title'], 'opendev') : __('Custom posts', 'opendev'); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:');?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($title);?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Select custom post type:' ); ?></label>
			<?php foreach ( $post_types  as $post_type ): ?>
				<p>
					<input type="checkbox" <?php if (isset($instance[$post_type->name]) && $instance[$post_type->name]) { echo " checked"; } ?> name="<?php echo $this->get_field_name($post_type->name)?>" value="<?php echo $post_type->name ?>"><?php echo $post_type->labels->name ?></input>
				</p>
			<?php endforeach; ?>
		</p>

		<?php $limit = !empty($instance['limit']) ? $instance['limit'] : -1 ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Select max number of posts to list (-1 to show all):' ); ?></label>
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

		$post_types = available_custom_post_types();
		foreach ($post_types as $post_type):
			$instance[$post_type->name] = (!empty($new_instance[$post_type->name])) ? true : false;
		endforeach;
		return $instance;
	}



}

add_action( 'widgets_init', create_function('', 'register_widget("Odm_Contents_Same_Category_Widget");'));
