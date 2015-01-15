<?php

// Theme options
require_once(STYLESHEETPATH . '/inc/theme-options.php');

// Briefings
require_once(STYLESHEETPATH . '/inc/briefings.php');

// Map category
require_once(STYLESHEETPATH . '/inc/map-category.php');

// Live search
require_once(STYLESHEETPATH . '/inc/live-search/live-search.php');

function opendev_styles() {

	$options = get_option('opendev_options');

	$css_base = get_stylesheet_directory_uri() . '/css/';

	//wp_dequeue_style('jeo-main');

	wp_register_style('webfont-droid-serif', 'http://fonts.googleapis.com/css?family=Droid+Serif:400,700');
	wp_register_style('webfont-opendev', get_stylesheet_directory_uri() . '/font/style.css');
	wp_register_style('opendev-base',  $css_base . 'opendev.css', array('webfont-droid-serif', 'webfont-opendev'));
	wp_register_style('opendev-theme_01',  $css_base . 'theme_01.css');
	wp_register_style('opendev-theme_02',  $css_base . 'theme_02.css');
	wp_register_style('opendev-theme_03',  $css_base . 'theme_03.css');

	wp_enqueue_style('opendev-base');
	if($options['style']) {
		wp_enqueue_style('opendev-' . $options['style']);
	}

}
add_action('wp_enqueue_scripts', 'opendev_styles', 15);

function opendev_jeo_scripts() {

	/* Shadowbox */
	wp_register_script('shadowbox', get_stylesheet_directory_uri() . '/lib/shadowbox/shadowbox.js', array('jquery'), '3.0.3');
	wp_register_style('shadowbox', get_stylesheet_directory_uri() . '/lib/shadowbox/shadowbox.css', array(), '3.0.3');

	wp_register_script('twttr', 'http://platform.twitter.com/widgets.js');

	// custom marker system
	global $jeo_markers;
	wp_deregister_script('jeo.markers');
	wp_register_script('jeo.markers', get_stylesheet_directory_uri() . '/js/markers.js', array('jeo', 'underscore', 'shadowbox', 'twttr'), '0.3.17', true);
	wp_localize_script('jeo.markers', 'opendev_markers', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'query' => $jeo_markers->query(),
		'stories_label' => __('stories', 'opendev'),
		'home' => (is_home() && !is_paged() && !$_REQUEST['opendev_filter_']),
		'copy_embed_label' => __('Copy the embed code', 'opendev'),
		'share_label' => __('Share', 'opendev'),
		'print_label' => __('Print', 'opendev'),
		'embed_base_url' => home_url('/' . $lang . '/embed/'),
		'share_base_url' => home_url('/' . $lang . '/share/'),
		'marker_active' => array(
			'iconUrl' => get_stylesheet_directory_uri() . '/img/marker_active.png',
			'iconSize' => array(26, 30),
			'iconAnchor' => array(13, 30),
			'popupAnchor' => array(0, -40),
			'markerId' => 'none'
		),
		'language' => $lang,
		'site_url' => home_url('/'),
		'read_more_label' => __('Read more', 'opendev'),
		'lightbox_label' => array(
			'slideshow' => __('Open slideshow', 'opendev'),
			'videos' => __('Watch video gallery', 'opendev'),
			'video' => __('Watch video', 'opendev'),
			'images' => __('View image gallery', 'opendev'),
			'image' => __('View fullscreen image', 'opendev'),
			'infographic' => __('View infographic', 'opendev'),
			'infographics' => __('View infographics', 'opendev')
		),
		'enable_clustering' => jeo_use_clustering() ? true : false,
		'default_icon' => jeo_formatted_default_marker()
	));

	if(is_home())
		wp_enqueue_script('opendev-sticky', get_stylesheet_directory_uri() . '/js/sticky-posts.js', array('jeo.markers', 'jquery'), '0.1.2');

	//wp_enqueue_script('opendev-interactive-map', get_stylesheet_directory_uri() . '/inc/interactive-map.js', array('jeo'));
}
add_action('wp_enqueue_scripts', 'opendev_jeo_scripts', 100);

// custom marker data
function opendev_marker_data($data, $post) {
	global $post;

	$permalink = $data['url'];

	if(function_exists('qtrans_getLanguage'))
		$permalink = add_query_arg(array('lang' => qtrans_getLanguage()), $permalink);

	$data['permalink'] = $permalink;
	$data['url'] = $permalink;
	$data['content'] = get_the_excerpt();
	if(get_post_meta($post->ID, 'geocode_zoom', true))
		$data['zoom'] = get_post_meta($post->ID, 'geocode_zoom', true);

	// thumbnail
	$data['thumbnail'] = opendev_get_thumbnail();

	return $data;
}
add_filter('jeo_marker_data', 'opendev_marker_data', 10, 2);

function opendev_get_thumbnail($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	$thumb_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post-thumb');
	if($thumb_src)
		return $thumb_src[0];
	else
		return get_post_meta($post->ID, 'picture', true);
}

function opendev_logo() {

	$logo = opendev_get_logo();

	if($logo && !is_multisite()) {
		?>
		<h1 class="with-logo">
			<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
				<?php bloginfo('name'); ?>
				<?php echo $logo; ?>
			</a>
		</h1>
		<?php
	} else {
		?>
		<h1>
			<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
				<?php // bloginfo('name'); ?>
				<span class="icon-od-logo"></span>
				Op<sup>e</sup>nDevelopment
			</a>
		</h1>
		<?php
	}

}