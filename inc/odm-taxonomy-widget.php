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

		/**
	 * Outputs HTML containing a string of the category name as a link
	 * and if the current post is in the category, to make it <strong>
	 *
	 * @param category $category a category object to display
	 */
	public function print_category( $category ) {
			$post_type =  get_post_type( get_the_ID() );
			//echo '<a href="' . get_category_link( $category->term_id ) . '">';
			$get_post_id = $this->wp_exist_post_by_title($category->name);
			//$page_slug = strtolower(preg_replace('/\s+/', '-', $category->name));
			//$topical_page_exist = get_page_by_path($page_slug, OBJECT, $post_type ); 
			if ($get_post_id){                       
				$highlight_cat =  " class='".COUNTRY_NAME."-color'";   
                 // $highlight_cat = "";
				echo $get_post_title->ID.'<a'.$highlight_cat.' href="' . get_permalink( $get_post_id ) . '">';
			}else{
                  $highlight_cat = "";
                  //echo '<a'.$highlight_cat.' href="' . get_permalink( $get_post_title->ID) . '">';
             }                                  
				$in_category = in_category( $category->term_id );
				if ($in_category){
					 echo "<strong class='".COUNTRY_NAME."-color'>";
					 $highlight_cat =  " class='".COUNTRY_NAME."-color'";
				}else {
                    $highlight_cat = "";
                }
				//echo "<div".$highlight_cat.">".$category->name."</div>"; 
			     echo $category->name;
				if ($in_category){
					 echo "</strong>";
				}
		//	if ($get_post_title)
				echo "</a><br/>";
			

	}
	/**
	 * Outputs Object containing post information if check the Topic page exist based on the name of category name
	 *
	 * @param  $title_str a category's name to find the topic page
	 */
     public function wp_exist_post_by_title($title_str) { 
        global $wpdb; 
         $get_post = $wpdb->get_results( $wpdb->prepare(
        	"SELECT ID, post_title FROM $wpdb->posts WHERE post_type = %s
                AND post_status = %s
        	",
        	"topic",
        	"publish"
            )
        );  
        foreach ( $get_post as $page_topic ) {   
            $lang_tag = "[:".qtranxf_getLanguage()."]";
            $lang_tag_finder = "/".$lang_tag ."/";
             
            if(qtranxf_getLanguage()!="en"){
                // if Kh                                                     
                if (strpos($page_topic->post_title, '[:kh]') !== false) {                      
                    $page_title = explode($lang_tag, $page_topic->post_title);
                    $pagetitle = trim(str_replace("[:]", "", $page_title[1])) ; 
                }else if (strpos($page_topic->post_title, '<!--:--><!--:kh-->') !== false) {
                        $page_title = explode("<!--:--><!--:kh-->", $page_topic->post_title);
                        $page_title = trim(str_replace("<!--:".qtranxf_getLanguage()."-->", "" , $page_title[1])); 
                        $pagetitle = trim(str_replace("<!--:-->", "" , $page_title)); 
                    //echo " <pre>1111111 ".$pagetitle."</pre>";  
                }else if (strpos($page_topic->post_title, '<!--:-->')){ 
                    $page_title = explode("<!--:-->", $page_topic->post_title); 
                    $pagetitle = trim(str_replace("<!--:en-->", "" , $page_title[0]));   
                }
           }else {             
                //if (preg_match("/[:kh]/" ,$page_topic->post_title)){  
                if (strpos($page_topic->post_title, '[:kh]') !== false) { 
                    $page_title = explode("[:kh]", $page_topic->post_title);           
                    $pagetitle = trim(str_replace("[:en]", "" , $page_title[0])); 
                }elseif (strpos($page_topic->post_title, '[:vi]') !== false) {
                    $page_title = explode("[:vi]", $page_topic->post_title);
                    $pagetitle = trim(str_replace("[:en]", "" , $page_title[0]));  
                }else if (strpos($page_topic->post_title, '[:]')){
                    $page_title = explode("[:]", $page_topic->post_title);
                    $pagetitle = trim(str_replace("[:en]", "" , $page_title[0])); 
                } else if (strpos($page_topic->post_title, '<!--:--><!--:kh-->')){ 
                    $page_title = explode("<!--:--><!--:kh-->", $page_topic->post_title); 
                    $pagetitle = trim(str_replace("<!--:en-->", "" , $page_title[0]));    
                }else if (strpos($page_topic->post_title, '<!--:--><!--:vi-->')){ 
                    $page_title = explode("<!--:--><!--:vi-->", $page_topic->post_title);
                    $pagetitle = trim(str_replace("<!--:en-->", "" , $page_title[0]));    
                }else if (strpos($page_topic->post_title, '<!--:-->')){ 
                    $page_title = explode("<!--:-->", $page_topic->post_title); 
                    $pagetitle = trim(str_replace("<!--:en-->", "" , $page_title[0]));   
                }
                
            }     
            echo "<div style='display:none'>***".trim($title_str) ."== ".$pagetitle."</div>";
            if (trim(strtolower($title_str)) == strtolower($pagetitle)){   
                $page_id = $page_topic->ID;   
            }                   
        } 
        return $page_id ;
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
			echo $args['before_title'] . apply_filters( 'widget_title', __( $instance['od_title'], 'opendev' ) ). $args['after_title'];
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
		$title = ! empty( $instance['od_title'] ) ? __( $instance['od_title'], 'opendev' ) : __( 'Topic areas', 'opendev' );
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
