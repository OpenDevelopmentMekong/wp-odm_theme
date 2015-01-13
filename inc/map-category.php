<?php

/*
 * Open Development
 * Map categories
 */

class OpenDev_Map_Category {

	function __construct() {

		add_action('init', array($this, 'register_taxonomy'));
		add_filter('jeo_map_data', array($this, 'jeo_map_data'));

	}

	function register_taxonomy() {
		$labels = array(
			'name'              => _x( 'Map categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Map category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Map categories' ),
			'all_items'         => __( 'All Map categories' ),
			'parent_item'       => __( 'Parent Map category' ),
			'parent_item_colon' => __( 'Parent Map category:' ),
			'edit_item'         => __( 'Edit Map category' ),
			'update_item'       => __( 'Update Map category' ),
			'add_new_item'      => __( 'Add New Map category' ),
			'new_item_name'     => __( 'New Map category Name' ),
			'menu_name'         => __( 'Map categories' ),
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'map-category' ),
		);
		register_taxonomy('map-category', array( 'map' ), $args );
	}

	function jeo_map_data($data) {
		global $post;
		$data['categories'] = get_the_terms($post->ID, 'map-category');
		return $data;
	}

}

$GLOBALS['opendev_map_category'] = new OpenDev_Map_Category();