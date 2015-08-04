<?php

/*
 * Open Development
 * mekong_floods
 */

class OpenDev_Mekong_Floods {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Mekong Floods', 'post type general name', 'opendev' ),
			'singular_name'      => _x( 'Mekong Flood', 'post type singular name', 'opendev' ),
			'menu_name'          => _x( 'Mekong Floods', 'admin menu', 'opendev' ),
			'name_admin_bar'     => _x( 'Mekong Flood', 'add new on admin bar', 'opendev' ),
			'add_new'            => _x( 'Add new', 'mekong floods', 'opendev' ),
			'add_new_item'       => __( 'Add new mekong floods', 'opendev' ),
			'new_item'           => __( 'New mekong floods', 'opendev' ),
			'edit_item'          => __( 'Edit mekong floods', 'opendev' ),
			'view_item'          => __( 'View mekong floods', 'opendev' ),
			'all_items'          => __( 'All mekong floods', 'opendev' ),
			'search_items'       => __( 'Search mekong floods', 'opendev' ),
			'parent_item_colon'  => __( 'Parent mekong floods:', 'opendev' ),
			'not_found'          => __( 'No mekong floods found.', 'opendev' ),
			'not_found_in_trash' => __( 'No mekong floods found in trash.', 'opendev' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'mekong_floods' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' )
		);

		register_post_type( 'mekong_floods', $args );

	}

}

$GLOBALS['opendev_mekong_floods'] = new OpenDev_Mekong_Floods();
