<?php
// Extend parent theme class
 class Extended_JEO_Markers extends JEO_Markers {
    function __construct() {
    }

    function query() {
  		global $wp_query;

  		$marker_query = apply_filters('jeo_marker_base_query', $wp_query);
  		$query = $marker_query->query_vars;

  		if(isset($query['suppress_filters']))
  			unset($query['suppress_filters']);

  		if(is_singular(array('map', 'map-group', 'profiles'))) {
  			global $post;
  			$marker_query = apply_filters('jeo_marker_base_query', new WP_Query());
  			$marker_query->parse_query();
  			$query = $marker_query->query_vars;
  			$query['map_id'] = $post->ID;
  			unset($query['page_id']);
  		}

  		if($wp_query->get('map_id') && !$wp_query->get('p')) {
  			$query['map_id'] = $wp_query->get('map_id');
  		}

  		if(!isset($query['post_type'])):
  			$query['post_type'] = jeo_get_mapped_post_types();
			endif;	
  		$query['post_status'] = 'publish';

  		$markers_limit = parent::get_limit();
  		$query['posts_per_page'] = $markers_limit;
  		if($markers_limit != -1) {
  			$amount = $marker_query->found_posts;
  			if($markers_limit > $amount) {
  				$markers_limit = $amount;
  			} else {
  				$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
  				$offset = get_query_var('posts_per_page') * ($page - 1);
  				if($offset <= ($amount - $markers_limit)) {
  					if($offset !== 0) $offset = $offset - 1;
  					$query['offset'] = $offset;
  				} else {
  					$query['offset'] = $amount - $markers_limit;
  				}
  			}
  		}

  		// add search
  		if(isset($_GET['s']))
  			$query['s'] = $_GET['s'];

  		$query['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;

  		return apply_filters('jeo_marker_query', $query);
  	}//end function
} //class
// Init Child class

new Extended_JEO_Markers();

$GLOBALS['extended_jeo_markers'] = new Extended_JEO_Markers();

function extended_jeo_markers_query() {
    return $GLOBALS['extended_jeo_markers']->query();
}
?>
