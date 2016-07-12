<?php

class Odm_Custom_Posts_Widget extends WP_Widget {

	private $templates;
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'odm_custom_posts_widget',
			__('ODM Custom Posts', 'odm'),
			array('description' => __('Display entries of the spefied custom post type', 'odm'))
		);

		$this->templates = array(
			"grid-4-cols" => "post-grid-single-4-cols",
			"list-4-cols" => "post-list-single-4-cols",
			"list-1-cols" => "post-list-single-1-cols"
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		$selected_custom_post_id = isset($instance['post_type']) ? $instance['post_type'] : null;
		$limit = isset($instance['limit']) ? $instance['limit'] : -1;
		$layout_type = isset($instance['layout_type']) ? $instance['layout_type'] : 'grid-4-cols';

		$query = array(
				'posts_per_page'   => $limit,
				'order'            => 'DESC',
				'post_type'        => $selected_custom_post_id,
				'post_status'      => 'publish'
			);
		$posts = get_posts( $query );

		echo $args['before_widget']; ?>

		<div class="container">
			<?php
				if (!empty($instance['title'])):
					 echo $args['before_title'].apply_filters('widget_title', __($instance['title'], 'odm')).$args['after_title'];
				endif; ?>

			<?php

				foreach($posts as $post):
					$template = $this->templates[$layout_type];
					odm_get_template($template,array(
						"post" => $post
					),true);
				endforeach; ?>

			<?php echo $args['after_widget']; ?>
		</div>

	<?php
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		$selected_custom_post_id = isset($instance['post_type']) ? $instance['post_type'] : null;
		$layout_type = isset($instance['layout_type']) ? $instance['layout_type'] : 'grid-4-cols';

		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);

		$output = 'objects';
		$operator = 'and';
		$post_types = get_post_types( $args, $output, $operator );

		$title = !empty($instance['title']) ? __($instance['title'], 'odm') : __('Custom posts', 'odm'); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:');?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($title);?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Select custom post type:' ); ?></label>
			<select class='widefat post_type' id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>" type="text">
				<?php foreach ( $post_types  as $post_type ): ?>
					<option <?php if ($selected_custom_post_id == $post_type->name) { echo " selected"; } ?> value="<?php echo $post_type->name ?>"><?php echo $post_type->labels->name ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'layout_type' ); ?>"><?php _e( 'Select layout:' ); ?></label>
			<select class='widefat' id="<?php echo $this->get_field_id('layout_type'); ?>" name="<?php echo $this->get_field_name('layout_type'); ?>" type="text">
				<?php foreach ( $this->templates  as $key => $value ): ?>
					<option <?php if ($layout_type == $key) { echo " selected"; } ?> value="<?php echo $key ?>"><?php echo $key ?></option>
				<?php endforeach; ?>
			</select>
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
		$instance['post_type'] = (!empty( $new_instance['post_type'])) ? $new_instance['post_type'] : '';
		$instance['layout_type'] = (!empty( $new_instance['layout_type'])) ? $new_instance['layout_type'] : 'grid-4-cols';

		return $instance;
	}
}

add_action( 'widgets_init', create_function('', 'register_widget("Odm_Custom_Posts_Widget");'));
