<?php

/*
 * Open Development
 * announcement
 */

class OpenDev_Announcement {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Announcements', 'post type general name', 'opendev' ),
			'singular_name'      => _x( 'Announcement', 'post type singular name', 'opendev' ),
			'menu_name'          => _x( 'Announcements', 'admin menu', 'opendev' ),
			'name_admin_bar'     => _x( 'Announcement', 'add new on admin bar', 'opendev' ),
			'add_new'            => _x( 'Add new', 'announcement', 'opendev' ),
			'add_new_item'       => __( 'Add new announcement', 'opendev' ),
			'new_item'           => __( 'New announcement', 'opendev' ),
			'edit_item'          => __( 'Edit announcement', 'opendev' ),
			'view_item'          => __( 'View announcement', 'opendev' ),
			'all_items'          => __( 'All announcements', 'opendev' ),
			'search_items'       => __( 'Search announcements', 'opendev' ),
			'parent_item_colon'  => __( 'Parent announcements:', 'opendev' ),
			'not_found'          => __( 'No announcements found.', 'opendev' ),
			'not_found_in_trash' => __( 'No announcements found in trash.', 'opendev' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'announcement' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'supports'           => array( 'title', 'editor', 'excerpt' )
		);

		register_post_type( 'announcement', $args );

	}

}

$GLOBALS['opendev_announcement'] = new OpenDev_Announcement();