<?php
class Odm_Custom_Taxonomy_With_Topic_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'odm_taxonomy_link_to_topic_widget', // Base ID
			__( "Custom ODM Topic Area Widget: able to custom taxonomy's url link to Topic page insted", 'odm' ), // Name
			array( 'description' => __( 'This widget will display custom taxonomy list that its link can like to the term or link to topic.  * Please ensure that that term name and topic name are exactly the same.', 'odm' ), ) // Args
		);
	}

	/**
	 * Checks to see if post is a descendent of given categories
	 * from: https://codex.wordpress.org/Function_Reference/in_category
	 * @param mixed $custom_categories
	 * @param mixed $_post
	 */
	function post_is_in_descendant_custom_taxonomy( $cats, $cus_taxonomy='category', $_post = null ) {
		foreach ( (array) $cats as $cat ) {
			// get_term_children() accepts integer ID only
			$descendants = get_term_children( (int) $cat, $cus_taxonomy );
			//if ( $descendants && in_category( $descendants, $_post ) )
			if ( $descendants)
				return true;
		}
		return false;
	}

	/**
	 * Outputs HTML containing a string of the category name as a link
	 * and if the current post is in the category, to make it <strong>
	 *
	 * @param custom_tax $custom_tax a category object to display
	 */
	public function print_category_linked_to_topic($custom_tax, $current_page_slug ="") {
		$get_post_id = get_post_or_page_id_by_title($custom_tax->name);
		$current_page = "";
		if ($get_post_id){ // if page of the topic exists
			$topic_page = get_post($get_post_id);
			$topic_slug = $topic_page->post_name;
			if ($topic_slug == $current_page_slug){
				 $current_page = " ".$current_page_slug;
			}
		}
		echo "<span class='nochildimage-".odm_country_manager()->get_current_country()." ".$current_page."'>";
		if ($get_post_id): // if page of the topic exists
			echo '<h5><a href="' . get_permalink( $get_post_id ) . '">';
		endif;

		echo $custom_tax->name;

		if ($get_post_id):
			echo "</a></h5>";
		endif;

		echo "</span>";
	}

		/**
	 * Outputs HTML containing a string of the category name linked to the
	 * category page related to the topic <strong>
	 *
	 * @param category $custom_tax a category object to display
	 */
	public function print_category_linked_to_custom_taxonomy( $custom_tax, $current_page_slug ="") {
		$custom_tax_has_contents = ($custom_tax->count > 0)? true:false;
		echo "<span class='nochildimage-".odm_country_manager()->get_current_country()." ".$custom_tax->slug."'>";

		// add link if contetns categorized by this topic exist
		if ($custom_tax_has_contents):
			echo '<h5><a href="/'.$custom_tax->taxonomy.'/' . $custom_tax->slug . '">';
    endif;

		echo $custom_tax->name;

		if ($custom_tax_has_contents):
			echo "</a></h5>";
		endif;

		echo "</span>";

	}
	/**
	 * Walks through a list of categories and prints all children descendant
	 * in a hierarchy.
	 *
	 * @param array $children an array of categories to display
	 */
	public function walk_child_customTax( $children, $topic_or_category, $cus_taxonomy, $hide_empty_taxonomy ) {
		$current_page = get_post();
		$current_page_slug = $current_page->post_name;
		foreach($children as $child){
			// Get immediate children of current category
				$cat_children = get_terms( $cus_taxonomy, array('parent' => $child->term_id, 'hide_empty' => $hide_empty_taxonomy, 'orderby' => 'name', ) );
			 if($hide_empty_taxonomy && ($topic_or_category=="topic")){
				 $posts = get_posts( array(
				    'post_type' => 'topic',
				    'fields' => 'ids',
				    'tax_query' => array(
				        array(
				            'taxonomy' => $cus_taxonomy,
				            'field'    => 'term_id',
				            'terms'    => array( $child->term_id )
				        ),
				    ),
				) );

				if ( count($posts) ) {
						echo "<li>";
						$this->print_category_linked_to_topic($child, $current_page_slug);
						// if current category has children
						if ( !empty($cat_children) ) {
							// add a sublevel
							echo "<ul>";
							// display the children
							$this->walk_child_customTax( $cat_children,  $topic_or_category, $cus_taxonomy, $hide_empty_taxonomy);
							echo "</ul>";
						}
						echo "</li>";
				}
			}else{
				echo "<li>";
				// Display current category
				if ($topic_or_category == 'topic'):
					$this->print_category_linked_to_topic($child, $current_page_slug);
				else:
					$this->print_category_linked_to_custom_taxonomy($child, $current_page_slug);
				endif;

				// if current category has children
				if ( !empty($cat_children) ) {
					// add a sublevel
					echo "<ul>";
					// display the children
					$this->walk_child_customTax( $cat_children,  $topic_or_category, $cus_taxonomy, $hide_empty_taxonomy);
					echo "</ul>";
				}
				echo "</li>";
			}
		}
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$current_page = get_post();
		if (isset($current_page)):
			$current_page_slug = $current_page->post_name;
		else:
			global $wp_query;
			$term = $wp_query->queried_object;
			$current_page_slug = $term->slug;
		endif;

		$cat_included_id_arr = !empty($instance['od_include']) ? explode(",", $instance['od_include']) : array();
		$cat_excluded_id_arr = !empty($instance['od_exclude']) ? explode(",", $instance['od_exclude']) : array();
		$topic_or_category = isset( $instance['topic_or_category']) ? $instance['topic_or_category'] : 'topic';
		$registed_custom_tax = isset( $instance['registed_custom_tax']) ? $instance['registed_custom_tax'] : 'category';
		$hide_empty_taxonomy = (!empty($instance['hide_empty_taxonomy'])) ? true : false;
		$is_expended = (!empty($instance['is_expended'])) ? true : false;
		$cus_taxonomy = $registed_custom_tax;
	?>
	<script type="text/javascript">
    jQuery(document).ready(function($) {
			$('.odm_<?php echo $cus_taxonomy;?>_taxonomy_widget_ul > li.topic_nav_item').each(function(){
				if( $(this).has('ul') ){
					<?php if($is_expended) {?>
						$('ul', this).show();
			    	$('ul', this).siblings('span').removeClass("nochildimage-<?php echo odm_country_manager()->get_current_country();?>");
			    	$('ul', this).siblings('span').addClass("minusimage-<?php echo odm_country_manager()->get_current_country();?>");
					<?php } else{ ?>
			    	$('ul', this).siblings('span').removeClass("nochildimage-<?php echo odm_country_manager()->get_current_country();?>");
			    	$('ul', this).siblings('span').addClass("plusimage-<?php echo odm_country_manager()->get_current_country();?>");
					<?php } ?>
			  }

				//if parent is showed, child need to expend
			  if( $('ul li', this).children("span").hasClass('<?php echo $current_page_slug; ?>') ){
					$('span.<?php echo $current_page_slug; ?>', this).siblings("ul").show();
					$('span.<?php echo $current_page_slug; ?>', this).toggleClass('minusimage-<?php echo odm_country_manager()->get_current_country();?>');
					$('span.<?php echo $current_page_slug; ?>', this).toggleClass('plusimage-<?php echo odm_country_manager()->get_current_country();?>');

					//if child is showed, parent expended
					$('span.<?php echo $current_page_slug; ?>', this).parents("li").parents("ul").show();
					$('span.<?php echo $current_page_slug; ?>', this).parents("li").parents("ul").siblings('span').toggleClass('minusimage-<?php echo odm_country_manager()->get_current_country();?>');
					$('span.<?php echo $current_page_slug; ?>', this).parents("li").parents("ul").siblings('span').toggleClass('plusimage-<?php echo odm_country_manager()->get_current_country();?>');
				}
			});
			$('.odm_<?php echo $cus_taxonomy;?>_taxonomy_widget_ul > li.topic_nav_item span').click(function(event) {
				if($(event.target).parent("li").find('ul').length){
					$(event.target).parent(".odm_<?php echo $cus_taxonomy;?>_taxonomy_widget_ul li").find('ul:first').slideToggle('fast');
					$(event.target).toggleClass("plusimage-<?php echo odm_country_manager()->get_current_country();?>");
					$(event.target).toggleClass('minusimage-<?php echo odm_country_manager()->get_current_country();?>');
				}
			});
    });
   </script>
	<?php
		echo $args['before_widget'];
		if (!empty($instance['title'])):
			echo $args['before_title'].apply_filters('widget_title', $instance['title']).$args['after_title'];
		endif;

		echo "<div>";
		$args = array(
		  'orderby' => 'name',
		  'exclude' => $cat_excluded_id_arr,
		  'include' => $cat_included_id_arr,
			'hide_empty' => $hide_empty_taxonomy,
		  'parent' => 0
		  );
		$custom_categories = get_terms( $cus_taxonomy, $args );
		echo "<ul class='odm_custom_taxonomy_widget_ul odm_".$cus_taxonomy."_taxonomy_widget_ul'>";
		foreach($custom_categories as $custom_tax){
			$jackpot = false;
			$children = array();

			if ( has_term( $custom_tax->term_id, $cus_taxonomy ) || $this->post_is_in_descendant_custom_taxonomy( $custom_tax->term_id, $cus_taxonomy ) )
			{
				$jackpot = true;
				$children = get_terms( $cus_taxonomy, array('parent' => $custom_tax->term_id, 'hide_empty' => $hide_empty_taxonomy, 'orderby' => 'term_id', ) );

			}

			echo "<li class='topic_nav_item'>";
			if ($topic_or_category == 'topic'):
				$this->print_category_linked_to_topic($custom_tax, $current_page_slug);
			else:
				$this->print_category_linked_to_custom_taxonomy($custom_tax, $current_page_slug);
			endif;

			if ( !empty($children) ) {
				echo '<ul>';

				$this->walk_child_customTax( $children, $topic_or_category, $cus_taxonomy, $hide_empty_taxonomy );

				echo '</ul>';
			}

			echo "</li>";

		}
		echo "</ul>";
		echo "</div>";

		if (array_key_exists('after_widget',$args))
			echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		// outputs the options form on
		$od_include = isset($instance['od_include']) ? $instance['od_include'] : '';
		$od_exclude = isset($instance['od_exclude']) ? $instance['od_exclude'] : '';
		$topic_or_category = isset($instance['topic_or_category']) ? $instance['topic_or_category'] : 'topic';
		$registed_custom_tax = isset($instance['registed_custom_tax']) ? $instance['registed_custom_tax'] : '';
		$hide_empty_taxonomy = isset($instance['hide_empty_taxonomy']) ? true : false;
		$is_expended = isset($instance['is_expended']) ? true : false;

		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Custom topic areas'.$is_expended;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php _e( $title , 'odm' ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'registed_custom_tax' ); ?>"><?php _e( 'Select Taxonomy:' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'registed_custom_tax' ); ?>" name="<?php echo $this->get_field_name( 'registed_custom_tax' ); ?>">
				<option value="" <?php if (!$registed_custom_tax) { echo " selected"; } ?> >Select</option>
				<?php $registed_tax = get_taxonomies(array('public'   => true, '_builtin' => false )); ?>
				<option <?php if ($registed_custom_tax == "category" ) { echo " selected"; } ?> value="<?php echo "category"  ?>"><?php echo "category";  ?></option>
				<?php foreach ( $registed_tax as $taxonomy ) { ?>
				<option <?php if ($registed_custom_tax == $taxonomy ) { echo " selected"; } ?> value="<?php echo $taxonomy  ?>"><?php echo $taxonomy;  ?></option>
				<?php }?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'od_include' ); ?>"><?php _e( 'Include Term by IDs (separated by commas):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'od_include' ); ?>" name="<?php echo $this->get_field_name( 'od_include' ); ?>" type="text" value="<?php echo esc_attr( $od_include ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'od_exclude' ); ?>"><?php _e( 'Exclude Term by IDs (separated by commas):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'od_exclude' ); ?>" name="<?php echo $this->get_field_name( 'od_exclude' ); ?>" type="text" value="<?php echo esc_attr( $od_exclude ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'topic_or_category' ); ?>"><?php _e( 'Links should take user to:' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'topic_or_category' ); ?>" name="<?php echo $this->get_field_name( 'topic_or_category' ); ?>">
				<option <?php if ($topic_or_category == 'topic') { echo " selected"; } ?> value="topic">Topic</option>
				<option <?php if ($topic_or_category == 'category') { echo " selected"; } ?> value="category">Term</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'hide_empty_taxonomy' ); ?>"><?php _e( 'Hide empty taxonomy:' ); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name('hide_empty_taxonomy'); ?>" id="<?php echo $this->get_field_id('hide_empty_taxonomy'); ?>" <?php if ($hide_empty_taxonomy)  echo 'checked="true"'; ?>/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'is_expended' ); ?>"><?php _e( 'Expend the taxonomy:' ); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name('is_expended'); ?>" id="<?php echo $this->get_field_id('is_expended'); ?>" <?php if ($is_expended)  echo 'checked="true"'; ?>/>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['od_include'] = $new_instance['od_include'] ;
		$instance['od_exclude'] = $new_instance['od_exclude'] ;
		$instance['topic_or_category'] = isset($new_instance['topic_or_category']) ? $new_instance['topic_or_category'] : '';
		$instance['registed_custom_tax'] = isset($new_instance['registed_custom_tax']) ? $new_instance['registed_custom_tax'] : '';
		$instance['hide_empty_taxonomy'] = $new_instance['hide_empty_taxonomy'];
		$instance['is_expended'] = $new_instance['is_expended'];

		return $instance;
	}
}

add_action( 'widgets_init', function() {register_widget("Odm_Custom_Taxonomy_With_Topic_Widget");});
