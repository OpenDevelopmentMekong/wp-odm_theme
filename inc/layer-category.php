<?php

/*
 * Open Development
 * Map categories
 */

class OpenDev_Map_Category {

	function __construct() {

		add_action('init', array($this, 'register_taxonomy'));
		add_action('admin_menu', array($this, 'admin_menu'));

	}

	function admin_menu() {
		add_submenu_page('edit.php?post_type=map', __('Layer categories', 'jeo'), __('Layer categories', 'jeo'), 'edit_posts', 'edit-tags.php?taxonomy=layer-category');
	}

	function register_taxonomy() {
		$labels = array(
			'name'              => _x( 'Layer categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Layer category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Layer categories' ),
			'all_items'         => __( 'All Layer categories' ),
			'parent_item'       => __( 'Parent Layer category' ),
			'parent_item_colon' => __( 'Parent Layer category:' ),
			'edit_item'         => __( 'Edit Layer category' ),
			'update_item'       => __( 'Update Layer category' ),
			'add_new_item'      => __( 'Add New Layer category' ),
			'new_item_name'     => __( 'New Layer category Name' ),
			'menu_name'         => __( 'Layer categories' ),
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'layer-category' ),
		);
		register_taxonomy('layer-category', array( 'map-layer' ), $args );
	}

}

$GLOBALS['opendev_map_category'] = new OpenDev_Map_Category();