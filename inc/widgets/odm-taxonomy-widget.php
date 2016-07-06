<?php
class Odm_Taxonomy_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'odm_taxonomy_widget', // Base ID
			__( 'ODM Content Taxonomy Widget', 'odm' ), // Name
			array( 'description' => __( 'Display ODM taxonomy for content', 'odm' ), ) // Args
		);
	}

	/**
	 * Checks to see if post is a descendent of given categories
	 * from: https://codex.wordpress.org/Function_Reference/in_category
	 * @param mixed $categories
	 * @param mixed $_post
	 */
	function post_is_in_descendant_category( $cats, $_post = null ) {
		foreach ( (array) $cats as $cat ) {
			// get_term_children() accepts integer ID only
			$descendants = get_term_children( (int) $cat, 'category' );
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
	 * @param category $category a category object to display
	 */

		/**
	 * Outputs HTML containing a string of the category name as a link
	 * and if the current post is in the category, to make it <strong>
	 *
	 * @param category $category a category object to display
	 */
	public function print_category( $category, $current_page_slug ="") {
			$site_name = strtolower(str_replace('Open Development ', '', get_bloginfo('name')));
			$post_type =  get_post_type( get_the_ID() );
			$get_post_id = get_post_or_page_id_by_title($category->name);
			if ($get_post_id){ // if page of the topic exists
				$topic_page = get_post($get_post_id);
				$topic_slug = $topic_page->post_name;
				if ($topic_slug == $current_page_slug){
					 $current_page = " ".$current_page_slug;
				}else {
					 $current_page = "";
				}
			}

			echo "<span class='nochildimage-".$site_name.$current_page."'>";

			if ($get_post_id){ // if page of the topic exists
				$hyperlink_color =  " class='".COUNTRY_NAME."-color'";
				echo '<a'.$hyperlink_color.' href="' . get_permalink( $get_post_id ) . '">';
			}else{
                $hyperlink_color = "";
            }

			$in_category = in_category( $category->term_id );
			if ($in_category){
				 echo "<strong class='".COUNTRY_NAME."-color'>";
				 $hyperlink_color =  " class='".COUNTRY_NAME."-color'";
			}else {
				$hyperlink_color = "";
			}
				echo $category->name;

			if ($in_category){
				 echo "</strong>";
			}

			if ($get_post_id)
				echo "</a>";

			echo "</span>";

	}
	/**
	 * Walks through a list of categories and prints all children descendant
	 * in a hierarchy.
	 *
	 * @param array $children an array of categories to display
	 */
	public function walk_child_category( $children ) {
		$current_page = get_post();
		$current_page_slug = $current_page->post_name;
		foreach($children as $child){
			// Get immediate children of current category
			$cat_children = get_categories( array('parent' => $child->term_id, 'hide_empty' => 1, 'orderby' => 'term_id', ) );
			echo "<li>";
			// Display current category
			$this -> print_category($child, $current_page_slug);
			// if current category has children
			if ( !empty($cat_children) ) {
				// add a sublevel
				echo "<ul>";
				// display the children
				$this->walk_child_category( $cat_children );
				echo "</ul>";
			}
			echo "</li>";
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
	$site_name = strtolower(str_replace('Open Development ', '', get_bloginfo('name')));
	$current_page = get_post();
	$current_page_slug = $current_page->post_name;
	?>
	<script type="text/javascript">
    jQuery(document).ready(function($) {
		$('.odm_taxonomy_widget_ul > li.topic_nav_item').each(function(){
			if($('.odm_taxonomy_widget_ul > li.topic_nav_item:has(ul)')){
				$('.odm_taxonomy_widget_ul > li.topic_nav_item ul').siblings('span').removeClass("nochildimage-<?php echo $site_name;?>");
				$('.odm_taxonomy_widget_ul > li.topic_nav_item ul').siblings('span').addClass("plusimage-<?php echo $site_name;?>");
			}
			//if parent is showed, child need to expend
			$('span.<?php echo $current_page_slug; ?>').siblings("ul").show();
			$('span.<?php echo $current_page_slug; ?>').toggleClass('minusimage-<?php echo $site_name;?>');
			$('span.<?php echo $current_page_slug; ?>').toggleClass('plusimage-<?php echo $site_name;?>');

			//if child is showed, parent expended
			$('span.<?php echo $current_page_slug; ?>').parents("li").parents("ul").show();
			$('span.<?php echo $current_page_slug; ?>').parents("li").parents("ul").siblings('span').toggleClass('minusimage-<?php echo $site_name;?>');
			$('span.<?php echo $current_page_slug; ?>').parents("li").parents("ul").siblings('span').toggleClass('plusimage-<?php echo $site_name;?>');
		});
		$('.odm_taxonomy_widget_ul > li.topic_nav_item span').click(function(event) {
			if($(event.target).parent("li").find('ul').length){
				$(event.target).parent("li").find('ul:first').slideToggle();
				$(event.target).toggleClass("plusimage-<?php echo $site_name;?>");
				$(event.target).toggleClass('minusimage-<?php echo $site_name;?>');
			}
		});
    });
   </script>
	<?php
		echo $args['before_widget'];
		if ( ! empty( $instance['od_title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', __( $instance['od_title'], 'odm' ) ). $args['after_title'];
		}
		if ( ! empty( $instance['od_include'] ) ) {
			$cat_included_id_arr = explode(",", $instance['od_include']);
		}
		if ( ! empty( $instance['od_exclude'] ) ) {
			$cat_excluded_id_arr = explode(",", $instance['od_exclude']);
		//	$cat_excluded_id_arr = str_split($instance['od_exclude']);
		}
		echo "<div>";
		$args = array(
		  'orderby' => 'term_id',
		  'exclude' => $cat_excluded_id_arr,
		  'include' => $cat_included_id_arr,
		  'parent' => 0
		  );

		$categories = get_categories( $args );

		echo "<ul class='odm_taxonomy_widget_ul'>";
		foreach($categories as $category){
			$jackpot = false;
			$children = array();

			if ( in_category( $category->term_id ) || $this->post_is_in_descendant_category( $category->term_id ) )
			{
				$jackpot = true;
				$children = get_categories( array('parent' => $category->term_id, 'hide_empty' => 0, 'orderby' => 'term_id', ) );

			}

			echo "<li class='topic_nav_item'>";
					$this -> print_category($category, $current_page_slug);

			if ( !empty($children) ) {
				echo '<ul>';

				$this->walk_child_category( $children );

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
		$title = ! empty( $instance['od_title'] ) ? __( $instance['od_title'], 'odm' ) : __( 'Topic areas', 'odm' );
		$od_include = $instance['od_include'];
		$od_exclude = $instance['od_exclude'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'od_title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'od_title' ); ?>" name="<?php echo $this->get_field_name( 'od_title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'od_include' ); ?>"><?php _e( 'Include Category by IDs (separated by commas):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'od_include' ); ?>" name="<?php echo $this->get_field_name( 'od_include' ); ?>" type="text" value="<?php echo esc_attr( $od_include ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'od_exclude' ); ?>"><?php _e( 'Exclude Category by IDs (separated by commas):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'od_exclude' ); ?>" name="<?php echo $this->get_field_name( 'od_exclude' ); ?>" type="text" value="<?php echo esc_attr( $od_exclude ); ?>">
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
		$instance['od_title'] = ( ! empty( $new_instance['od_title'] ) ) ? strip_tags( $new_instance['od_title'] ) : '';

		$instance['od_include'] = $new_instance['od_include'] ;
		$instance['od_exclude'] = $new_instance['od_exclude'] ;

		return $instance;
	}
}

add_action( 'widgets_init', create_function('', 'register_widget("Odm_Taxonomy_Widget");'));
