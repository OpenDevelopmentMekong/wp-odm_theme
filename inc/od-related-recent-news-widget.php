<?php
class OpenDev_Related_Recent_News_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'opendev_related_recent_news_widget', // Base ID
			__( 'OD Related Recent News Widget', 'opendev' ), // Name
			array( 'description' => __( 'Display OD the related recent news for the topics', 'opendev' ), ) // Args
		);
	}

	/**
	 * Outputs HTML containing a string of the post title as a link
	 *
	 * @param category $category a category object to display
	 */

	public function get_related_news( $category = "") {
        $args=array(
              'post_type' => 'post',
              'post_status' => 'publish',
              'category_name' => $category,
              'numberposts' => 10
              );

        $rel_news_query = get_posts( $args );
        if( !empty($rel_news_query) ) {
            $news = "<ul>";
              foreach( $rel_news_query as  $rel_post ) :
                $news .= "<li>";
                 /* if(has_post_thumbnail()) :
				    $news .= '<a href="'.get_permalink($rel_post->ID).'" title="'.$rel_post->post_title.'">';
                         $news .= get_the_post_thumbnail($rel_post->ID, array(50,50), array('class' => 'align-left'));
                    $news .="</a>";
			     endif; */
			     $related_post_title = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($rel_post->post_title);
                 $news .= '<a href="'.get_permalink($rel_post->ID).'" rel="bookmark" title="Click to view '.$related_post_title.'">'.$related_post_title.'</a></li>';
              endforeach;
             $news .= '</ul>';
            //wp_reset_query();
             return $news;
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
	        global $wpdb;
    		if ( ! empty( $instance['od_related_news_option'] ) ) {
    			$news_option = $instance['od_related_news_option'];
    			if ($news_option == 'Related To The Topics'){
                     if (function_exists(qtranxf_getLanguage) && (qtranxf_getLanguage()!="en")){
                            $page_id = get_the_ID();
                            $post_type = get_post_type( $page_id );
                            $page_results = $wpdb->get_results($wpdb->prepare(
                                        "SELECT `post_title` FROM $wpdb->posts WHERE `post_type` = %s AND `ID` = %d",
                                        $post_type,
                                        $page_id),
                                ARRAY_A );
                            $english_pagetitle = qtrans_use('en', $page_results[0]['post_title'], false);
                     }else {
                            $english_pagetitle = get_the_title();
                     }
            		$category_slug = strtolower(preg_replace('/\s+/', '-', $english_pagetitle));
                }else if ($news_option == 'Show By specific category slug'){
                    if (!empty ($instance['od_related_news_by_cat_slug']))
                        $category_slug = $instance['od_related_news_by_cat_slug'];
                }else{
                    $category_slug = "";
                }
                $news_exist =  $this->get_related_news( $category_slug );
    		}

    	if ( $news_exist){
    	   echo $args['before_widget'];

           if ( ! empty( $instance['od_related_news_title'] ) ) {
    			echo $args['before_title'] . apply_filters( 'widget_title', __($instance['od_related_news_title'], 'opendev') ). $args['after_title'];
    		}

    		echo "<div>";
    		  echo $this->get_related_news( $category_slug );
    		echo "</div>";

    	   echo $args['after_widget'];
    	} //if news avaialable
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
		$title = ! empty( $instance['od_related_news_title'] ) ? $instance['od_related_news_title'] : __( 'Related News', 'opendev' );
		$by_cat_slug = ! empty( $instance['od_related_news_by_cat_slug'] ) ? $instance['od_related_news_by_cat_slug'] : '';
        $news_option = $instance['od_related_news_option'];
		?>
		 <script type="text/javascript">
           jQuery(function($) {
             $('.by_cat_slug').hide();
             var get_select_id ="";
             $('.od_related_news_option').change(function(){
                get_select_id = $(this).attr("id");
                      if($(this).val() == "Show By specific category slug"){
                         $('.'+get_select_id+'_by_cat_slug').show();
                         $('.show_by_cat_slug').show();
                     }else {
                         $('.'+get_select_id+'_by_cat_slug').hide();
                         $('.show_by_cat_slug').hide();
                     }
             });
           });
         </script>
		<p>
			<label for="<?php echo $this->get_field_id( 'od_related_news_title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'od_related_news_title' ); ?>" name="<?php echo $this->get_field_name( 'od_related_news_title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'od_related_news_option' ); ?>"><?php _e( 'Option:' ); ?></label>
			<select class='widefat od_related_news_option' id="<?php echo $this->get_field_id('od_related_news_option'); ?>"
                name="<?php echo $this->get_field_name('od_related_news_option'); ?>" type="text">
                  <option value='Related To The Topics'<?php echo ($news_option=='Related To The Topics')?'selected':''; ?>>
                  Related To The Topics
                  </option>
                  <option value='The Latest News'<?php echo ($news_option=='The Latest News')?'selected':''; ?>>
                  The Latest News
                  </option>
                  <option value='Show By specific category slug'<?php echo ($news_option=='Show By specific category slug')?'selected':''; ?>>
                  Show by specific category slug
                  </option>
            </select>
       </p>
       <p class='<?php if($news_option != "Show By specific category slug"){
                    echo 'by_cat_slug '.$this->get_field_id( 'od_related_news_option_by_cat_slug' );
             } else echo "show_by_cat_slug"; ?>'>
			<label for="<?php echo $this->get_field_id( 'od_related_news_by_cat_slug' ); ?>"><?php _e( 'Category slugs:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'od_related_news_by_cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'od_related_news_by_cat_slug' ); ?>" type="text" value="<?php echo esc_attr( $by_cat_slug ); ?>" />  <br />
            <i>Split the slug by comma (,). eg. agriculture-and-fishing,sugarcane-bagasse</i>

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
		$instance['od_related_news_title'] = ( ! empty( $new_instance['od_related_news_title'] ) ) ? strip_tags( $new_instance['od_related_news_title'] ) : '';

		$instance['od_related_news_option'] = $new_instance['od_related_news_option'] ;
		$instance['od_related_news_by_cat_slug'] = $new_instance['od_related_news_by_cat_slug'] ;

		return $instance;
	}
}

add_action( 'widgets_init', create_function('', 'register_widget("OpenDev_Related_Recent_News_Widget");'));

