<?php
class OpenDev_Category_Turbo_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'opendev_category_turbo_widget', // Base ID
			__( 'OD Category Turbo Widget', 'opendev' ), // Name
			array( 'description' => __( 'Display OD categories according to Dans specification', 'opendev' ), ) // Args
		);
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
		echo "<div>";
		
		$args = array(
		  'orderby' => 'term_id',
		  'parent' => 0
		  );
		
		$categories = get_categories( $args );
		
		echo "<ul>";
		foreach($categories as $category){
			echo "<li>";
			echo '<a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a><br/>';
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
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
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
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}

add_action( 'widgets_init', create_function('', 'register_widget("OpenDev_Category_Turbo_Widget");'));
