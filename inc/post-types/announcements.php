<?php

/*
 * Open Development
 * announcement
 */

class Odm_Announcement {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Announcements', 'post type general name', 'odm' ),
			'singular_name'      => _x( 'Announcement', 'post type singular name', 'odm' ),
			'menu_name'          => _x( 'Announcements', 'admin menu', 'odm' ),
			'name_admin_bar'     => _x( 'Announcement', 'add new on admin bar', 'odm' ),
			'add_new'            => _x( 'Add new', 'announcement', 'odm' ),
			'add_new_item'       => __( 'Add new announcement', 'odm' ),
			'new_item'           => __( 'New announcement', 'odm' ),
			'edit_item'          => __( 'Edit announcement', 'odm' ),
			'view_item'          => __( 'View announcement', 'odm' ),
			'all_items'          => __( 'All announcements', 'odm' ),
			'search_items'       => __( 'Search announcements', 'odm' ),
			'parent_item_colon'  => __( 'Parent announcements:', 'odm' ),
			'not_found'          => __( 'No announcements found.', 'odm' ),
			'not_found_in_trash' => __( 'No announcements found in trash.', 'odm' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon' 				 => '',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'announcements' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'taxonomies'         => array( 'category', 'post_tag'),
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
		);

		register_post_type( 'announcement', $args );

	}

}

$GLOBALS['odm_announcement'] = new Odm_Announcement();
