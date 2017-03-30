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
			'name'               => _x( 'Announcements', 'post type general name', 'odi' ),
			'singular_name'      => _x( 'Announcement', 'post type singular name', 'odi' ),
			'menu_name'          => _x( 'Announcements', 'admin menu', 'odi' ),
			'name_admin_bar'     => _x( 'Announcement', 'add new on admin bar', 'odi' ),
			'add_new'            => _x( 'Add new', 'announcement', 'odi' ),
			'add_new_item'       => __( 'Add new announcement', 'odi' ),
			'new_item'           => __( 'New announcement', 'odi' ),
			'edit_item'          => __( 'Edit announcement', 'odi' ),
			'view_item'          => __( 'View announcement', 'odi' ),
			'all_items'          => __( 'All announcements', 'odi' ),
			'search_items'       => __( 'Search announcements', 'odi' ),
			'parent_item_colon'  => __( 'Parent announcements:', 'odi' ),
			'not_found'          => __( 'No announcements found.', 'odi' ),
			'not_found_in_trash' => __( 'No announcements found in trash.', 'odi' )
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
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields')
		);

		register_post_type( 'announcement', $args );

	}

}

$GLOBALS['odm_announcement'] = new Odm_Announcement();
