<?php
class OpenDev_Taxonomy_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'opendev_taxonomy_widget', // Base ID
			__( 'OD Content Taxonomy Widget', 'opendev' ), // Name
			array( 'description' => __( 'Display OD taxonomy for content', 'opendev' ), ) // Args
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
			if ( $descendants && in_category( $descendants, $_post ) )
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

	public function print_category( $category ) {		
		//echo '<a href="' . get_category_link( $category->term_id ) . '">';		
			$in_category = in_category( $category->term_id );
			
			if ($in_category){ 
				 echo "<strong>";
			} 
			echo $category->name; 		
			if ($in_category){			
				 echo "</strong>";
			}		
			//echo "</a><br/>";	
	}
	
	/**
	 * Walks through a list of categories and prints all children descendant
	 * in a hierarchy.
	 * 
	 * @param array $children an array of categories to display
	 */
	public function walk_child_category( $children ) {				
		foreach($children as $child){			
			// Get immediate children of current category
			$cat_children = get_categories( array('parent' => $child->term_id, 'hide_empty' => 1, 'orderby' => 'term_id', ) );			
			echo "<li>";			
			// Display current category
			$this -> print_category($child);			
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
		echo $args['before_widget']; 
		if ( ! empty( $instance['od_title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['od_title'] ). $args['after_title'];
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
		
		echo "<ul>";
		foreach($categories as $category){
			
			$jackpot = false;
			$children = array();
			
			if ( in_category( $category->term_id ) || $this->post_is_in_descendant_category( $category->term_id ) )
			{
				$jackpot = true;
				$children = get_categories( array('parent' => $category->term_id, 'hide_empty' => 1, 'orderby' => 'term_id', ) );
				
			}
			
			echo "<li>";
			$this -> print_category($category);

			if ( !empty($children) ) {			
				echo '<ul>';
			
				$this->walk_child_category( $children );
			
				echo '</ul>';
			}
			
			echo "</li>";
			
		}
		echo "</ul>";
		echo "</div>";
		
		
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
		$title = ! empty( $instance['od_title'] ) ? $instance['od_title'] : __( 'Topic areas', 'opendev' ); 
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

add_action( 'widgets_init', create_function('', 'register_widget("OpenDev_Taxonomy_Widget");'));
