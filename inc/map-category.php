<?php

/*
 * Open Development
 * Map categories
 */

class OpenDev_Map_Category {

	function __construct() {

		add_action('init', array($this, 'register_taxonomy'));

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
			'menu_name'         => __( 'Map category' ),
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'Map category' ),
		);
		register_taxonomy('map-category', array( 'map' ), $args );
	}

}

$GLOBALS['opendev_map_category'] = new OpenDev_Map_Category();