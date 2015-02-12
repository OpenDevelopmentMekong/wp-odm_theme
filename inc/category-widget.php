<?php

class OpenDev_Category_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'opendev_category_widget',
			__('OD Post Taxonomy', 'opendev'),
			array('description' => __('Display post taxonomies hierarchically.', 'opendev'))
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		echo $args['before_widget'];

		global $post;

		$taxonomies = $instance['taxonomies'];
		foreach($taxonomies as $taxonomy) {
			$tax = get_taxonomy($taxonomy);
			$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
			if ( !empty( $post_terms ) && !is_wp_error( $post_terms ) ) {
				$tax_class = '';
				if(!$tax->hierarchical)
					$tax_class .= ' nonhierarchical';
				else
					$tax_class .= ' hierarchical';
				echo '<div class="od-tax-widget-tax-item tax-' . $taxonomy . $tax_class .'">';
				echo $args['before_title'] . $tax->labels->name . $args['after_title'];
				echo '<ul class="od-tax-widget-term-list">';
				$term_ids = implode( ',' , $post_terms );
				echo wp_list_categories( 'title_li=&echo=0&taxonomy=' . $taxonomy . '&include=' . $term_ids );
				echo '</ul>';
				echo '</div>';
			}
		}
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$taxonomies = get_taxonomies(array('public' => true));
		$selected_taxonomies = ! empty( $instance['taxonomies'] ) ? $instance['taxonomies'] : array('category');
		?>
		<div>
			<label for="<?php echo $this->get_field_id( 'taxonomies' ); ?>"><?php _e( 'Taxonomies to display:' ); ?></label>
			<?php foreach($taxonomies as $tax) { ?>
				<p>
					<input type="checkbox" id="<?php echo $this->get_field_id( 'taxonomies' ) ; ?>_<?php echo $tax; ?>" name="<?php echo $this->get_field_name( 'taxonomies' ); ?>[]" value="<?php echo $tax; ?>" <?php if(in_array($tax, $selected_taxonomies)) echo 'checked'; ?> />
					<label for="<?php echo $this->get_field_id( 'taxonomies' ) ; ?>_<?php echo $tax; ?>"><?php echo $tax; ?></label>
				</p>
			<?php } ?>
		</div>

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
		$instance['taxonomies'] = ( ! empty( $new_instance['taxonomies'] ) ) ? $new_instance['taxonomies'] : array();

		return $instance;
	}
}

add_action( 'widgets_init', create_function('', 'register_widget("OpenDev_Category_Widget");'));