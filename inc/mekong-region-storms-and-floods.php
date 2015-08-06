<?php

/*
 * Open Development
 * mekong_region_storms_and_floods
 */

class OpenDev_Mekong_region_storms_and_floods {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Mekong region storms and floods', 'post type general name', 'opendev' ),
			'singular_name'      => _x( 'Mekong region storm and flood', 'post type singular name', 'opendev' ),
			'menu_name'          => _x( 'Mekong region storms and floods', 'admin menu', 'opendev' ),
			'name_admin_bar'     => _x( 'Mekong region storms and floods', 'add new on admin bar', 'opendev' ),
			'add_new'            => _x( 'Add new', 'mekong region storm and flood', 'opendev' ),
			'add_new_item'       => __( 'Add new mekong region storm and flood', 'opendev' ),
			'new_item'           => __( 'New mekong region storms and floods', 'opendev' ),
			'edit_item'          => __( 'Edit mekong region storms and floods', 'opendev' ),
			'view_item'          => __( 'View mekong region storms and floods', 'opendev' ),
			'all_items'          => __( 'All mekong region storms and floods', 'opendev' ),
			'search_items'       => __( 'Search mekong region storms and floods', 'opendev' ),
			'parent_item_colon'  => __( 'Parent mekong region storms and floods:', 'opendev' ),
			'not_found'          => __( 'No mekong region storms and floods found.', 'opendev' ),
			'not_found_in_trash' => __( 'No mekong region storms and floods found in trash.', 'opendev' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'mekong-region-storms-and-floods' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' )
		);

		register_post_type( 'mekong-storm-flood', $args );

	}

}

$GLOBALS['opendev_mekong_region_storms_and_floods'] = new OpenDev_Mekong_region_storms_and_floods();
