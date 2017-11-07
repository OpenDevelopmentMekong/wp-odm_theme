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
	public function print_category_linked_to_topic($category, $current_page_slug ="", $hide_empty_terms = false) {
		$post_type =  get_post_type( get_the_ID() );
		$get_post_id = get_post_or_page_id_by_title($category->name);
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

		if ($hide_empty_terms && !$get_post_id):
			echo $category->name;
		endif;

		if ($get_post_id):
			echo "</a></h5>";
		endif;

		echo "</span>";
	}

		/**
	 * Outputs HTML containing a string of the category name linked to the
	 * category page related to the topic <strong>
	 *
	 * @param category $category a category object to display
	 */
	public function print_category_linked_to_category( $category, $current_page_slug ="", $hide_empty_terms = false) {
		$category_has_contents = (get_category($category->term_id)->category_count > 0)? true:false;

		echo "<span class='nochildimage-".odm_country_manager()->get_current_country()." ".$category->slug."'>";

		// add link if contetns categorized by this topic exist
		if ($category_has_contents):
			echo '<h5><a href="/category/' . $category->slug . '">';
    endif;

		if ($hide_empty_terms && !$category_has_contents):
			echo $category->name;
		endif;

		if ($category_has_contents):
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
	public function walk_child_category( $children, $topic_or_category, $hide_empty_terms = false) {
		$current_page = get_post();
		$current_page_slug = $current_page->post_name;
		foreach($children as $child){

			// Get immediate children of current category
			$cat_children = get_categories( array('parent' => $child->term_id, 'hide_empty' => 0, 'orderby' => 'name', ) );
			echo "<li>";
			// Display current category
			if ($topic_or_category == 'topic'):
				$this->print_category_linked_to_topic($child, $current_page_slug, $hide_empty_terms);
			else:
				$this->print_category_linked_to_category($child, $current_page_slug, $hide_empty_terms);
			endif;

			// if current category has children
			if ( !empty($cat_children) ) {
				// add a sublevel
				echo "<ul>";
				// display the children
				$this->walk_child_category( $cat_children,  $topic_or_category, $hide_empty_terms);
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
		$current_page = get_post();
		if (isset($current_page)):
			$current_page_slug = $current_page->post_name;
		else:
			global $wp_query;
			$term = $wp_query->queried_object;
			$current_page_slug = $term->slug;
		endif;
	?>
	<script type="text/javascript">
    jQuery(document).ready(function($) {
		$('.odm_taxonomy_widget_ul > li.topic_nav_item').each(function(){
			if( $(this).has('ul') ){
		    $('ul', this).siblings('span').removeClass("nochildimage-<?php echo odm_country_manager()->get_current_country();?>");
		    $('ul', this).siblings('span').addClass("plusimage-<?php echo odm_country_manager()->get_current_country();?>");
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
		$('.odm_taxonomy_widget_ul > li.topic_nav_item span').click(function(event) {
			if($(event.target).parent("li").find('ul').length){
				$(event.target).parent("li").find('ul:first').slideToggle('fast');
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

		$cat_included_id_arr = !empty($instance['od_include']) ? explode(",", $instance['od_include']) : array();
		$cat_excluded_id_arr = !empty($instance['od_exclude']) ? explode(",", $instance['od_exclude']) : array();
		$topic_or_category = isset( $instance['topic_or_category']) ? $instance['topic_or_category'] : 'topic';
		$hide_empty_terms = isset($instance['hide_empty_terms']) ? $instance['hide_empty_terms'] : false;

		echo "<div>";
		$args = array(
		  'orderby' => 'name',
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
			if ($topic_or_category == 'topic'):
				$this->print_category_linked_to_topic($category, $current_page_slug, false);
			else:
				$this->print_category_linked_to_category($category, $current_page_slug, false);
			endif;

			if ( !empty($children) ) {
				echo '<ul>';

				$this->walk_child_category( $children, $topic_or_category, $hide_empty_terms);

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
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Topic areas';
		$od_include = isset($instance['od_include']) ? $instance['od_include'] : '';
		$od_exclude = isset($instance['od_exclude']) ? $instance['od_exclude'] : '';
		$topic_or_category = isset($instance['topic_or_category']) ? $instance['topic_or_category'] : 'topic';
		$hide_empty_terms = isset($instance['hide_empty_terms']) ? $instance['hide_empty_terms'] : false; ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php _e( $title , 'odm' ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'od_include' ); ?>"><?php _e( 'Include Category by IDs (separated by commas):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'od_include' ); ?>" name="<?php echo $this->get_field_name( 'od_include' ); ?>" type="text" value="<?php echo esc_attr( $od_include ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'od_exclude' ); ?>"><?php _e( 'Exclude Category by IDs (separated by commas):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'od_exclude' ); ?>" name="<?php echo $this->get_field_name( 'od_exclude' ); ?>" type="text" value="<?php echo esc_attr( $od_exclude ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'topic_or_category' ); ?>"><?php _e( 'Links should take user to:' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'topic_or_category' ); ?>" name="<?php echo $this->get_field_name( 'topic_or_category' ); ?>" type="text">
				<option <?php if ($topic_or_category == 'topic') { echo " selected"; } ?> value="topic">Topic</option>
				<option <?php if ($topic_or_category == 'category') { echo " selected"; } ?> value="category">Category</option>
			</select>
		</p>
		<p class="<?php echo $this->get_field_id('hide_empty_terms'); ?>" id="hide_empty_terms">
			<label for="<?php echo $this->get_field_id( 'hide_empty_terms' ); ?>"><?php _e( 'Hide empty terms:' ); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name('hide_empty_terms'); ?>" id="<?php echo $this->get_field_id('hide_empty_terms'); ?>" <?php if ($hide_empty_terms)  echo 'checked="true"'; ?>/>
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
		$instance['topic_or_category'] = isset($new_instance['topic_or_category']) ? $new_instance['topic_or_category'] : 'topic';
		$instance['hide_empty_terms'] = (!empty( $new_instance['hide_empty_terms'])) ? $new_instance['hide_empty_terms'] : false;

		return $instance;
	}
}

add_action( 'widgets_init', create_function('', 'register_widget("Odm_Taxonomy_Widget");'));
