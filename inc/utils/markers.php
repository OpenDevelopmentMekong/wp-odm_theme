<?php
// Extend parent theme class
 class Extended_JEO_Markers extends JEO_Markers {

	var $directory = '';

	var $directory_uri = '';

	var $options = array();

	var $use_clustering = false;

	var $geocode_service = '';

	var $geocode_type = 'default';

	var $gmaps_api_key = false;

	var $use_extent = false;

	var $extent_default_zoom = false;

	function __construct() {
		$this->setup_directories();
		add_action('jeo_init', array($this, 'setup'), 100);

		//add_action('wp_enqueue_scripts', array($this, 'setup'), 100);
	}

	/*
	 * Settings
	 */

	function setup() {
		$this->setup_scripts();
		$this->setup_post_map();
		$this->setup_ajax();
		$this->setup_cache_flush();
		$this->geocode_setup();
		$this->get_options();
		$this->use_clustering();
		$this->geocode_type();
		$this->geocode_service();
		$this->gmaps_api_key();
		$this->use_extent();
	}

	function get_options() {
		$this->options = jeo_get_options();
		return $this->options;
	}

	function use_clustering() {
		if($this->options && isset($this->options['map']))
			$clustering = $this->options['map']['enable_clustering'];
		else
			$clustering = false;

		$this->use_clustering = apply_filters('jeo_enable_clustering', $clustering);
		return $this->use_clustering;
	}

	function use_transient() {
		return apply_filters('jeo_markers_enable_transient', true);
	}

	function use_browser_caching() {
		return apply_filters('jeo_markers_enable_browser_caching', false);
	}

	function geocode_type() {
		if($this->options && isset($this->options['geocode']))
			$type = $this->options['geocode']['type'];
		else
			$type = 'default';
		$this->geocode_type = apply_filters('jeo_geocode_type', $type);
		return $this->geocode_type;
	}

	function geocode_service() {
		if($this->options && isset($this->options['geocode']))
			$service = $this->options['geocode']['service'];
		else
			$service = 'osm';
		$this->geocode_service = apply_filters('jeo_geocode_service', $service);
		return $this->geocode_service;
	}

	function gmaps_api_key() {
		if($this->options && isset($this->options['geocode']))
			$key = $this->options['geocode']['gmaps_api_key'];
		else
			$key = false;
		$this->gmaps_api_key = apply_filters('jeo_gmaps_api_key', $key);
		return $this->gmaps_api_key;
	}

	function use_extent() {
		$this->use_extent = true;
		if(is_front_page() || is_singular(array('map', 'map-group')))
			$this->use_extent = false;

		return apply_filters('jeo_use_marker_extent', $this->use_extent);
	}

	function extent_default_zoom() {
		$this->extent_default_zoom = false;
		return apply_filters('jeo_marker_extent_default_zoom', $this->extent_default_zoom);
	}

	function setup_directories() {
		$this->directory = apply_filters('jeo_directory', get_template_directory() . '/inc');
		$this->directory_uri = apply_filters('jeo_directory_uri', get_template_directory_uri() . '/inc');
	}

	function setup_scripts() {
		add_action('jeo_enqueue_scripts', array($this, 'register_scripts'));
		add_action('wp_footer', array($this, 'enqueue_scripts'));
	}

	function enqueue_scripts() {
		if(wp_script_is('jeo.markers', 'registered')) {
				wp_deregister_script('jeo.markers');
				wp_dequeue_script('jeo.markers');
		}
		if(wp_script_is('opendev.markers', 'registered')) {
			wp_enqueue_script('opendev.markers');

			wp_localize_script('opendev.markers', 'opendev_markers', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'query' => $this->query(),
				'markerextent' => $this->use_extent(),
				'markerextent_defaultzoom' => $this->extent_default_zoom(),
				'enable_clustering' => $this->use_clustering() ? true : false,
				'stories_label' => __('stories', 'odm'),
		    'home' => (is_home() && !is_paged() && !isset($_REQUEST['opendev_filter_'])),
		    'copy_embed_label' => __('Copy the embed code', 'odm'),
		    'share_label' => __('Share', 'odm'),
		    'print_label' => __('Print', 'odm'),
		    'embed_base_url' => home_url('/embed/'),
		    'share_base_url' => home_url('/share/'),
				'site_url' => home_url('/'),
				'read_more_label' => __('Read more', 'odm'),
				'lightbox_label' => array(
				'slideshow' => __('Open slideshow', 'odm'),
				'videos' => __('Watch video gallery', 'odm'),
				'video' => __('Watch video', 'odm'),
				'images' => __('View image gallery', 'odm'),
				'image' => __('View fullscreen image', 'odm'),
				'infographic' => __('View infographic', 'odm'),
				'infographics' => __('View infographics', 'odm'),
				)
			));

			do_action('jeo_markers_enqueue_scripts');
		}
	}

	function register_scripts() {
		wp_register_script('opendev.markers', get_stylesheet_directory_uri().'/inc/jeo-scripts/markers.js', array('jeo', 'underscore', 'twttr'), '0.3.19', true);
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
	}

	function setup_ajax() {
		add_action('wp_ajax_nopriv_od_markers_geojson', array($this, 'get_data'));
		add_action('wp_ajax_od_markers_geojson', array($this, 'get_data'));
	}

	function get_data($query = false) {
		$query = $query ? $query : $_REQUEST['query'];

		if(!isset($query['singular_map']) || $query['singular_map'] !== true) {
			$query['posts_per_page'] = $this->get_limit();
			$query['nopaging'] = false;
			$query['paged'] = 0;
		}

		$query['is_marker_query'] = 1;

		$cache_key = 'mp_';

		if(function_exists('qtrans_getLanguage'))
			$cache_key .= qtrans_getLanguage() . '_';

		$query_id = md5(serialize($query));
		$cache_key .= $query_id;

		$cache_key = apply_filters('jeo_markers_cache_key', $cache_key, $query);

		$data = false;

		if($this->use_transient())
			$data = get_transient($cache_key, 'jeo_markers_query');

		if($data === false) {
			$data = array();

			$markers_query = new WP_Query($query);

			$data['type'] = 'FeatureCollection';
			$data['features'] = array();

			if($markers_query->have_posts()) {
				$i = 0;
				while($markers_query->have_posts()) {

					$markers_query->the_post();

					$geojson = $this->get_geojson();

					if($geojson) {
						$data['features'][$i] = $this->get_geojson();
						$i++;
					}
				}
			}
			wp_reset_postdata();
			$data = apply_filters('jeo_markers_data', $data, $markers_query);
			$data = json_encode($data);

			if($this->use_transient())
				set_transient($cache_key, $data, 60*10); // 10 minutes transient
		}

		$content_type = apply_filters('jeo_geojson_content_type', 'application/json');

		header('Content-Type: ' . $content_type . ';charset=UTF-8');

		if($this->use_browser_caching()) {
			/* Browser caching */
			$expires = 60 * 10; // 10 minutes of browser cache
			header('Pragma: public');
			header('Cache-Control: maxage=' . $expires);
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
			/* --------------- */
		}

		do_action('jeo_markers_before_print');

		echo apply_filters('jeo_markers_geojson', $data);

		do_action('jeo_markers_after_print');
		exit;
	}

	function get_limit() {
		return apply_filters('jeo_markers_limit', 200);
	}

	function get_bubble($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		ob_start();
		get_template_part('content', 'marker-bubble');
		$bubble = ob_get_contents();
		ob_end_clean();
		return apply_filters('jeo_marker_bubble', $bubble, $post);
	}

	function get_icon() {
		global $post;
		if($this->has_location()) {
			$marker = array(
				'iconUrl' => get_stylesheet_directory_uri().'/img/marker_active_'.$site_name.'.png',
				'iconSize' => array(26, 30),
				'iconAnchor' => array(13, 30),
				'popupAnchor' => array(0, -40),
				'markerId' => 'none'
			);
			return apply_filters('jeo_marker_icon', $marker, $post);
		}
		return null;
	}

	function get_class() {
		global $post;
		$class = get_post_class();
		return apply_filters('jeo_marker_class', $class, $post);
	}

	function get_properties() {
		global $post;
    $get_post = get_page($post->ID);
    $get_title = qtrans_use(odm_language_manager()->get_current_language(), $get_post->post_content,false);
		$properties = array();
		$properties['id'] = 'post-' . $post->ID;
		$properties['postID'] = $post->ID;
		$properties['title'] = $get_title;
		$properties['date'] = get_the_date(_x('m/d/Y', 'reduced date format', 'jeo'));
		$properties['url'] = apply_filters('the_permalink', get_permalink());
		$properties['bubble'] = $this->get_bubble();
		$properties['marker'] = $this->get_icon();
		$properties['class'] = implode(' ', $this->get_class());
    $properties['permalink'] = add_query_arg(array('lang' => odm_language_manager()->get_current_language()), get_permalink());
    $properties['thumbnail'] = odm_get_thumbnail();
    if (get_post_meta($post->ID, 'geocode_zoom', true)) {
        $properties['zoom'] = get_post_meta($post->ID, 'geocode_zoom', true);
    }

		return apply_filters('jeo_marker_data', $properties, $post);
	}

	function get_geometry() {
		global $post;
		$coordinates = $this->get_coordinates();
		if(!$coordinates) {
			$geometry = false;
		} else {
			$geometry = array();
			$geometry['type'] = 'Point';
			$geometry['coordinates'] = $coordinates;
		}
		return apply_filters('jeo_marker_geometry', $geometry, $post);
	}


	function get_coordinates($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		$lat = get_post_meta($post_id, 'geocode_latitude', true);
		$lon = get_post_meta($post_id, 'geocode_longitude', true);

		if($lat && is_numeric($lat) && $lon && is_numeric($lon))
			$coordinates = array(floatval($lon), floatval($lat));
		else
			$coordinates = false;

		return apply_filters('jeo_marker_coordinates', $coordinates, $post);
	}

	function has_location($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		return ($this->get_coordinates($post_id));
	}

	function get_geojson($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		$geojson = get_post_meta($post_id, $this->get_geojson_key(), true);
		if(!$geojson)
			return $this->update_geojson($post_id);

      return $this->update_geojson($post_id);
		//return $geojson;
	}

	function update_geojson($post_id = false) {
		if(!$post_id)
			return false;

		global $post;
		setup_postdata(get_post($post_id));

		$geometry = $this->get_geometry();

		$geojson = array();

    $geojson['MMM'] = 'Feature';
		$geojson['type'] = 'Feature';

		// marker geometry
		if($geometry) {
			$geojson['geometry'] = $geometry;
		}

		// marker properties
		$geojson['properties'] = $this->get_properties();

		update_post_meta($post_id, $this->get_geojson_key(), $geojson);

		wp_reset_postdata();

		return $geojson;
	}
}
// Init Child class

$extended_jeo_markers = new Extended_JEO_Markers();

?>
