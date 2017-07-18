<?php

class Odm_Custom_Posts_Widget extends WP_Widget {

	private $templates;
	private $order_options;
	private $more_location;
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
			"grid-2-cols" => "post-grid-single-2-cols",
			"grid-1-cols" => "post-grid-single-1-cols",
			"list-4-cols" => "post-list-single-4-cols",
			"list-2-cols" => "post-list-single-2-cols",
			"list-1-cols" => "post-list-single-1-cols",
			"blog-layout-2-cols" => "post-blog-layout-2-cols"
		);

		$this->order_options = array(
			"metadata_created" => "Creation date",
			"metadata_modified" => "Modification date"
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

		$selected_custom_post_id = isset($instance['post_type']) ? $instance['post_type'] : null;
		$limit = isset($instance['limit']) ? $instance['limit'] : -1;
		$layout_type = isset($instance['layout_type']) ? $instance['layout_type'] : 'grid-4-cols';
		$show_meta = isset($instance['show_meta']) ? $instance['show_meta'] : false;
		$show_source_meta = isset($instance['show_source_meta']) ? $instance['show_source_meta'] : false;
		$show_excerpt = isset($instance['show_excerpt']) ? $instance['show_excerpt'] : false;
		$show_thumbnail = isset($instance['show_thumbnail']) ? $instance['show_thumbnail'] : false;
		$order = isset($instance['order']) ? $instance['order'] : 'metadata_created';
		$more_location = isset($instance['more_location']) ? $instance['more_location'] : 'bottom';
		$override_order = isset($instance['override_order']) && !empty($instance['override_order']) ? $instance['override_order'] : null;

		$post_type = get_post_type_object($selected_custom_post_id);
		$post_type_slug = $post_type->rewrite['slug'];

		$query = array(
				'posts_per_page'   => $limit,
				'order'            => 'DESC',
				'post_type'        => $selected_custom_post_id,
				'post_status'      => 'publish',
				'orderby' 				 => $order
			);

		if (isset($override_order) && !empty($override_order)):
			$override_order = str_replace(" ","",$override_order);
			$post_slugs = explode(",",$override_order);
			$query['post_name__in'] = $post_slugs;
		endif;

		$posts = get_posts( $query );

		echo $args['before_widget']; ?>

		<div class="container">
			<div class="eight columns">
				<?php
					if (!empty($instance['title'])): ?>
						<a href="/<?php echo $post_type_slug?>"><?php echo $args['before_title'].apply_filters('widget_title', __($instance['title'], 'odm')).$args['after_title']; ?></a>
				<?php endif; ?>
			</div>

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
									"order" => $order,
									"index" => $index
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
		</div>

		<?php echo $args['after_widget']; ?>

	<?php
	wp_reset_query();
	}


	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		$selected_custom_post_id = isset($instance['post_type']) ? $instance['post_type'] : null;
		$layout_type = isset($instance['layout_type']) ? $instance['layout_type'] : 'grid-4-cols';
		$show_meta = isset($instance['show_meta']) ? $instance['show_meta'] : false;
		$show_source_meta = isset($instance['show_source_meta']) ? $instance['show_source_meta'] : false;
		$show_excerpt = isset($instance['show_excerpt']) ? $instance['show_excerpt'] : false;
		$show_thumbnail = isset($instance['show_thumbnail']) ? $instance['show_thumbnail'] : false;
		$order = isset($instance['order']) ? $instance['order'] : 'date';
		$more_location = isset($instance['more_location']) ? $instance['more_location'] : 'bottom';
		$override_order = isset($instance['override_order']) ? $instance['override_order'] : null;

		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);

		$output = 'objects';
		$operator = 'and';
		$post_types = get_post_types( $args, $output, $operator );

		$title = !empty($instance['title']) ? $instance['title'] : 'Custom posts'; ?>

		 <script type="text/javascript">
			 jQuery(function($) {
				 $('.layout_type').change(function(){
							var get_select_id = $(this).attr("id");
									widget_id = get_select_id.replace("layout_type", "");
								if( ($(this).val() == "list-1-cols") || ($(this).val() == "list-2-cols") ){
									 $('.'+widget_id+'show_source_meta').show();
								}else {
										$('.'+widget_id+'show_source_meta').hide();
								 }
				 });
			 });
	  </script>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:');?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php _e($title,'odm');?>">
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
			<label for="<?php echo $this->get_field_id( 'override_order' ); ?>"><?php _e( 'Specific list of posts (optional):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('override_order');?>" name="<?php echo $this->get_field_name('override_order');?>" type="text" value="<?php echo $override_order;?>" placeholder="Enter comma-separated list of slugs">
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
		<?php $limit = !empty($instance['limit']) ? $instance['limit'] : -1 ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Select max number of posts to list (-1 to show all):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('limit');?>" name="<?php echo $this->get_field_name('limit');?>" type="number" value="<?php echo $limit;?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order by:' ); ?></label>
			<select class='widefat' id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" type="text">
				<?php foreach ( $this->order_options  as $key => $value ): ?>
					<option <?php if ($order == $key) { echo " selected"; } ?> value="<?php echo $key ?>"><?php echo $value ?></option>
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
		$instance['post_type'] = (!empty( $new_instance['post_type'])) ? $new_instance['post_type'] : '';
		$instance['layout_type'] = (!empty( $new_instance['layout_type'])) ? $new_instance['layout_type'] : 'grid-4-cols';
		$instance['show_meta'] = (!empty( $new_instance['show_meta'])) ? $new_instance['show_meta'] : false;
		$instance['show_source_meta'] = (!empty( $new_instance['show_source_meta'])) ? $new_instance['show_source_meta'] : false;
		$instance['show_excerpt'] = (!empty( $new_instance['show_excerpt'])) ? $new_instance['show_excerpt'] : false;
		$instance['show_thumbnail'] = (!empty( $new_instance['show_thumbnail'])) ? $new_instance['show_thumbnail'] : false;
		$instance['order'] = (!empty( $new_instance['order'])) ? $new_instance['order'] : 'metadata_created';
		$instance['more_location'] = (!empty( $new_instance['more_location'])) ? $new_instance['more_location'] : '';
		$instance['override_order'] = isset($new_instance['override_order']) && !empty($new_instance['override_order']) ? $new_instance['override_order'] : null;

		return $instance;
	}
}

add_action( 'widgets_init', create_function('', 'register_widget("Odm_Custom_Posts_Widget");'));
