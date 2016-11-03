<?php

class Odm_Contents_Same_Category_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'odm_contents_same_category_widget',
			__('ODM Contents same category', 'odm'),
			array('description' => __('Display other content from other post types tagged with the same categories as the one where the widget is integrated.', 'odm'))
		);

		$this->templates = array(
			"grid-4-cols" => "post-grid-single-4-cols",
			"grid-2-cols" => "post-grid-single-2-cols",
			"grid-1-cols" => "post-grid-single-1-cols",
			"list-4-cols" => "post-list-single-4-cols",
			"list-2-cols" => "post-list-single-2-cols",
			"list-1-cols" => "post-list-single-1-cols"
		);

		$this->order_options = array(
			"date" => "Created date",
			"modified" => "Modified date"
		);

		$this->more_location = array(
			"top" => "Top",
			"bottom" => "Bottom"
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
		$layout_type = isset($instance['layout_type']) ? $instance['layout_type'] : 'grid-4-cols';
		$categories = wp_get_post_categories($post->ID);
		$posts = array();
		$show_meta = isset($instance['show_meta']) ? $instance['show_meta'] : false;
		$show_source_meta = isset($instance['show_source_meta']) ? $instance['show_source_meta'] : false;
		$show_excerpt = isset($instance['show_excerpt']) ? $instance['show_excerpt'] : false;
		$show_thumbnail = isset($instance['show_thumbnail']) ? $instance['show_thumbnail'] : true;
		$order = isset($instance['order']) ? $instance['order'] : 'date';
		$more_location = isset($instance['more_location']) ? $instance['more_location'] : 'bottom';
		$supported_post_types = array();
		$post_types = available_custom_post_types();
		foreach ($post_types as $post_type):
			if (isset($instance[$post_type->name]) && $instance[$post_type->name]):
				array_push($supported_post_types,$post_type->name);
			endif;
		endforeach;

		if (!empty($categories)):
			$query = array(
					'post_type'        => $supported_post_types,
					'posts_per_page'   => $limit,
					'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field' => 'id',
										'terms' => $categories
									)
							)
				);
			  $posts = query_posts($query);
		endif;

		echo $args['before_widget']; ?>

		<?php
			if (!empty($instance['title'])):
				 echo $args['before_title'].apply_filters('widget_title', $instance['title']).$args['after_title'];
			endif; ?>

			<?php if ($more_location == 'top'): ?>
				<div class="eight columns align-right">
					<a href="/<?php echo $post_type_slug?>"> <?php _e('More...', 'odm');?> </a>
				</div>
			<?php endif; ?>

			<div class="sixteen columns">
				<?php
					$index = 1;
					foreach($posts as $post):
						if (should_open_row($layout_type,$index)): ?>
							<div class="row">
						<?php endif; ?>
						<?php
						$template = $this->templates[$layout_type];
						odm_get_template($template,array(
							"post" => $post,
							"show_meta" => $show_meta,
							"show_source_meta" => $show_source_meta,
							"show_excerpt" => $show_excerpt,
							"show_thumbnail" => $show_thumbnail,
							"order" => $order
						),true);
						if (should_close_row($layout_type,$index)): ?>
							</div>
						<?php endif;
						$index++;
					endforeach;
				?>
			</div>

			<?php if ($more_location == 'bottom'): ?>
				<div class="sixteen columns align-right">
					<a href="/<?php echo $post_type_slug?>"> <?php _e('More...','odm') ?></a>
				</div>
			<?php endif; ?>

		<?php echo $args['after_widget'];
		wp_reset_query();
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

	  $post_types = available_custom_post_types();

		$title = !empty($instance['title']) ? $instance['title'] : 'Custom posts';
		$layout_type = isset($instance['layout_type']) ? $instance['layout_type'] : 'grid-4-cols';
		$show_meta = isset($instance['show_meta']) ? $instance['show_meta'] : false;
		$show_source_meta = isset($instance['show_source_meta']) ? $instance['show_source_meta'] : false;
		$show_excerpt = isset($instance['show_excerpt']) ? $instance['show_excerpt'] : false;
		$show_thumbnail = isset($instance['show_thumbnail']) ? $instance['show_thumbnail'] : false;
		$order = isset($instance['order']) ? $instance['order'] : 'date';
		$more_location = isset($instance['more_location']) ? $instance['more_location'] : 'bottom'; ?>

		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:');?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php _e($title, 'odm');?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Select custom post type:' ); ?></label>
			<?php foreach ( $post_types  as $post_type ): ?>
				<p>
					<input type="checkbox" <?php if (isset($instance[$post_type->name]) && $instance[$post_type->name]) { echo " checked"; } ?> name="<?php echo $this->get_field_name($post_type->name)?>" value="<?php echo $post_type->name ?>"><?php echo $post_type->labels->name ?></input>
				</p>
			<?php endforeach; ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'layout_type' ); ?>"><?php _e( 'Select layout:' ); ?></label>
			<select class='widefat layout_type' id="<?php echo $this->get_field_id('layout_type'); ?>" name="<?php echo $this->get_field_name('layout_type'); ?>" type="text">
				<?php foreach ( $this->templates  as $key => $value ): ?>
					<option <?php if ($layout_type == $key) { echo " selected"; } ?> value="<?php echo $key ?>"><?php echo $key ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_excerpt' ); ?>"><?php _e( 'Show post excerpt:' ); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name('show_excerpt'); ?>" id="<?php echo $this->get_field_id('show_excerpt'); ?>" <?php if ($show_excerpt)  echo 'checked="true"'; ?>/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"><?php _e( 'Show post thumbnail:' ); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name('show_thumbnail'); ?>" id="<?php echo $this->get_field_id('show_thumbnail'); ?>" <?php if ($show_thumbnail)  echo 'checked="true"'; ?>/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_meta' ); ?>"><?php _e( 'Show post meta:' ); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name('show_meta'); ?>" id="<?php echo $this->get_field_id('show_meta'); ?>" <?php if ($show_meta)  echo 'checked="true"'; ?>/>
		</p>
		<p class="<?php echo $this->get_field_id('show_source_meta'); ?>" id="show_source_meta" style="<?php if ( !in_array($layout_type, array("list-1-cols", "list-2-cols"))): echo "display: none"; endif; ?>">
			<label for="<?php echo $this->get_field_id( 'show_source_meta' ); ?>"><?php _e( 'Show source meta:' ); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name('show_source_meta'); ?>" id="<?php echo $this->get_field_id('show_source_meta'); ?>" <?php if ($show_source_meta)  echo 'checked="true"'; ?>/>
		</p>
		<?php $limit = !empty($instance['limit']) ? $instance['limit'] : 5 //-1 ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Select max number of posts to list (-1 to show all):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('limit');?>" name="<?php echo $this->get_field_name('limit');?>" type="number" value="<?php echo $limit;?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order by:' ); ?></label>
			<select class='widefat' id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" type="text">
				<?php foreach ( $this->order_options  as $key => $value ): ?>
					<option <?php if ($order == $key) { echo " selected"; } ?> value="<?php echo $key ?>"><?php echo $key ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'more_location' ); ?>"><?php _e( "Select location of 'More...':" ); ?></label>
			<select class='widefat' id="<?php echo $this->get_field_id('more_location'); ?>" name="<?php echo $this->get_field_name('more_location'); ?>" type="text">
				<?php foreach ( $this->more_location as $key => $value ): ?>
					<option <?php if ($more_location == $key) { echo " selected"; } ?> value="<?php echo $key ?>"><?php echo $key ?></option>
				<?php endforeach; ?>
			</select>
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
		$instance['title'] = (!empty($new_instance['title'])) ? $new_instance['title'] : '';
		$instance['limit'] = (!empty($new_instance['limit'])) ? $new_instance['limit'] : -1;
		$instance['layout_type'] = (!empty( $new_instance['layout_type'])) ? $new_instance['layout_type'] : 'grid-4-cols';
		$instance['show_meta'] = (!empty( $new_instance['show_meta'])) ? $new_instance['show_meta'] : false;
		$instance['show_source_meta'] = (!empty( $new_instance['show_source_meta'])) ? $new_instance['show_source_meta'] : false;
		$instance['show_excerpt'] = (!empty( $new_instance['show_excerpt'])) ? $new_instance['show_excerpt'] : false;
		$instance['show_thumbnail'] = (!empty( $new_instance['show_thumbnail'])) ? $new_instance['show_thumbnail'] : false;
		$instance['order'] = (!empty( $new_instance['order'])) ? $new_instance['order'] : '';
		$instance['more_location'] = (!empty( $new_instance['more_location'])) ? $new_instance['more_location'] : '';

		$post_types = available_custom_post_types();
		foreach ($post_types as $post_type):
			$instance[$post_type->name] = (!empty($new_instance[$post_type->name])) ? true : false;
		endforeach;
		return $instance;
	}

}

add_action( 'widgets_init', create_function('', 'register_widget("Odm_Contents_Same_Category_Widget");'));
