<?php

class Odm_Category_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'odm_category_widget',
			__('ODM Post Taxonomy', 'odi'),
			array('description' => __('Display post taxonomies hierarchically.', 'odi'))
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
		global $post;
    $current_cat = get_queried_object();
		if (isset($current_cat->slug) && $current_cat->slug) {
				$current_cat_page = $current_cat->slug;
		} elseif (isset($current_cat->post_name)) {
  			$current_cat_page = $current_cat->post_name;
		} else {
				$current_cat_page = "post-type-category";
		}

		$taxonomies = $instance['taxonomies'];
		foreach($taxonomies as $taxonomy) {
			$args_term = array(
			'taxonomy' => $taxonomy,
			'parent' => 0
			);
	    $categories = get_categories( $args_term );
			if (isset($_GET['post_type']))
				$post_type = $_GET['post_type'];
			else
				$post_type =  get_post_type( get_the_ID() );

			$tax = get_taxonomy($taxonomy);

			$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
			if ( !empty( $post_terms ) && !is_wp_error( $post_terms ) ) {
				$tax_class = '';
				if(!$tax->hierarchical)
					$tax_class .= ' nonhierarchical';
				else
					$tax_class .= ' hierarchical';
				echo $args['before_widget'];
					echo '<div class="od-tax-widget-tax-item tax-' . $taxonomy . $tax_class .'">';
								echo $args['before_title'] . $tax->labels->name . $args['after_title'];
								$term_ids = implode( ',' , $post_terms );
								list_category_by_post_type($post_type, $args_term, 0, 0);
					echo '</div>';
				echo $args['after_widget'];
			}
		}
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
			$('.odm_taxonomy_widget_ul > li.cat_item').each(function(){
				if($('.odm_taxonomy_widget_ul > li.cat_item:has(ul)')){
					$('.odm_taxonomy_widget_ul > li.cat_item ul').siblings('span').removeClass("nochildimage-<?php echo odm_country_manager()->get_current_country();?>");
					$('.odm_taxonomy_widget_ul > li.cat_item ul').siblings('span').addClass("plusimage-<?php echo odm_country_manager()->get_current_country();?>");
				}
				//if parent is showed, child need to expend
				if ($('span.<?php echo $current_cat_page; ?>').length){
					$('span.<?php echo $current_cat_page; ?>').siblings("ul").show();
					$('span.<?php echo $current_cat_page; ?>').toggleClass('minusimage-<?php echo odm_country_manager()->get_current_country();?>');
					$('span.<?php echo $current_cat_page; ?>').toggleClass('plusimage-<?php echo odm_country_manager()->get_current_country();?>');

					//if child is showed, parent expended
					$('span.<?php echo $current_cat_page; ?>').parents("li").parents("ul").show();
					$('span.<?php echo $current_cat_page; ?>').parents("li").parents("ul").siblings('span').toggleClass('minusimage-<?php echo odm_country_manager()->get_current_country();?>');
					$('span.<?php echo $current_cat_page; ?>').parents("li").parents("ul").siblings('span').toggleClass('plusimage-<?php echo odm_country_manager()->get_current_country();?>');
				}
			});
			$('.odm_taxonomy_widget_ul > li.cat_item span').click(function(event) {
			//	event.preventDefault();
				var target =  $( event.target );
					if(target.parent("li").find('ul').length){
					  target.parent("li").find('ul:first').slideToggle();
						target.toggleClass("plusimage-<?php echo odm_country_manager()->get_current_country();?>");
						target.toggleClass('minusimage-<?php echo odm_country_manager()->get_current_country();?>');
						}
				});
			});
		 </script>
	<?php
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

add_action( 'widgets_init', create_function('', 'register_widget("Odm_Category_Widget");'));
